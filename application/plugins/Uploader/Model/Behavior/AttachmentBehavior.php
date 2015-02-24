<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/cakephp/uploader
 */

App::uses('Set', 'Utility');
App::uses('ModelBehavior', 'Model');

use Transit\Transit;
use Transit\File;
use Transit\Exception\ValidationException;
use Transit\Transformer\Image\CropTransformer;
use Transit\Transformer\Image\FlipTransformer;
use Transit\Transformer\Image\ResizeTransformer;
use Transit\Transformer\Image\ScaleTransformer;
use Transit\Transporter\Aws\S3Transporter;
use Transit\Transporter\Aws\GlacierTransporter;

/**
 * A CakePHP Behavior that attaches a file to a model, uploads automatically,
 * and then stores a value in the database.
 */
class AttachmentBehavior extends ModelBehavior {

	/**
	 * Transformation types.
	 */
	const CROP = 'crop';
	const FLIP = 'flip';
	const RESIZE = 'resize';
	const SCALE = 'scale';

	/**
	 * Transportation types.
	 */
	const S3 = 's3';
	const GLACIER = 'glacier';

	/**
	 * Transit instances indexed by model alias.
	 *
	 * @type \Transit\Transit[]
	 */
	protected $_uploads = array();

	/**
	 * Mapping of database columns to attachment fields.
	 *
	 * @type array
	 */
	protected $_columns = array();

	/**
	 * Default attachment settings.
	 *
	 * @type array {
	 * 		@type string $nameCallback	Method to format filename with
	 * 		@type string $append		What to append to the end of the filename
	 * 		@type string $prepend		What to prepend to the beginning of the filename
	 * 		@type string $tempDir		Directory to upload files to temporarily
	 * 		@type string $uploadDir		Directory to move file to after upload to make it publicly accessible
	 * 		@type string $finalPath		The final path to prepend to file names (like a domain)
	 * 		@type string $dbColumn		Database column to write file path to
	 * 		@type array $metaColumns	Database columns to write meta data to
	 * 		@type string $defaultPath	Default image if no file is uploaded
	 * 		@type bool $overwrite		Overwrite a file with the same name if it exists
	 * 		@type bool $stopSave		Stop save() if error exists during upload
	 * 		@type bool $allowEmpty		Allow an empty file upload to continue
	 * 		@type array $transforms		List of transforms to apply to the image
	 * 		@type array $transport		Settings for file transportation
	 * }
	 */
	protected $_defaultSettings = array(
		'nameCallback' => '',
		'append' => '',
		'prepend' => '',
		'tempDir' => TMP,
		'uploadDir' => '',
		'finalPath' => '',
		'dbColumn' => '',
		'metaColumns' => array(),
		'defaultPath' => '',
		'overwrite' => false,
		'stopSave' => true,
		'allowEmpty' => true,
		'transforms' => array(),
		'transport' => array()
	);

	/**
	 * Default transform settings.
	 *
	 * @type array {
	 * 		@type string $method		The transform method
	 * 		@type string $nameCallback	Method to format filename with
	 * 		@type string $append		What to append to the end of the filename
	 * 		@type string $prepend		What to prepend to the beginning of the filename
	 * 		@type string $uploadDir		Directory to move file to after upload to make it publicly accessible
	 * 		@type string $finalPath		The final path to prepend to file names (like a domain)
	 * 		@type string $dbColumn		Database column to write file path to
	 * 		@type bool $overwrite		Overwrite a file with the same name if it exists
	 * 		@type bool $self			Should the transforms apply to the uploaded file instead of creating new images
	 * }
	 */
	protected $_transformSettings = array(
		'method' => '',
		'nameCallback' => '',
		'append' => '',
		'prepend' => '',
		'uploadDir' => '',
		'finalPath' => '',
		'dbColumn' => '',
		'overwrite' => false,
		'self' => false
	);

	/**
	 * Save attachment settings.
	 *
	 * @param Model $model
	 * @param array $settings
	 */
	public function setup(Model $model, $settings = array()) {
		if ($settings) {
			if (!isset($this->_columns[$model->alias])) {
				$this->_columns[$model->alias] = array();
			}

			foreach ($settings as $field => $attachment) {
				$attachment = Set::merge($this->_defaultSettings, $attachment + array(
					'dbColumn' => $field
				));

				$columns = array($attachment['dbColumn'] => $field);

				// Set defaults if not defined
				if (!$attachment['tempDir']) {
					$attachment['tempDir'] = TMP;
				}

				if (!$attachment['uploadDir']) {
					$attachment['finalPath'] = 'files/uploads/';
					$attachment['uploadDir'] = WWW_ROOT . $attachment['finalPath'];
				}

				// Merge transform settings with defaults
				if ($attachment['transforms']) {
					foreach ($attachment['transforms'] as $dbColumn => $transform) {
						$transform = Set::merge($this->_transformSettings, $transform + array(
							'uploadDir' => $attachment['uploadDir'],
							'finalPath' => $attachment['finalPath'],
							'dbColumn' => $dbColumn
						));

						if ($transform['self']) {
							$transform['dbColumn'] = $attachment['dbColumn'];
						}

						$columns[$transform['dbColumn']] = $field;
						$attachment['transforms'][$dbColumn] = $transform;
					}
				}

				$this->settings[$model->alias][$field] = $attachment;
				$this->_columns[$model->alias] += $columns;
			}
		}
	}

	/**
	 * Cleanup and reset the behavior when its detached.
	 *
	 * @param Model $model
	 * @return void
	 */
	public function cleanup(Model $model) {
		parent::cleanup($model);

		$this->_uploads = array();
		$this->_columns = array();
	}

	/**
	 * Deletes any files that have been attached to this model.
	 *
	 * @param Model $model
	 * @param bool $cascade
	 * @return bool
	 */
	public function beforeDelete(Model $model, $cascade = true) {
		if (empty($model->id)) {
			return false;
		}

		return $this->deleteFiles($model, $model->id);
	}

	/**
	 * Before saving the data, try uploading the file, if successful save to database.
	 *
	 * @uses Transit\Transit
	 *
	 * @param Model $model
	 * @return bool
	 */
	public function beforeSave(Model $model) {
		$alias = $model->alias;
		$cleanup = array();

		if (empty($model->data[$alias])) {
			return true;
		}

		// Loop through the data and upload the file
		foreach ($model->data[$alias] as $field => $file) {
			if (empty($this->settings[$alias][$field])) {
				continue;
			}

			// Gather attachment settings
			$attachment = $this->_settingsCallback($model, $this->settings[$alias][$field]);
			$data = array();

			// Initialize Transit
			$transit = new Transit($file);
			$transit->setDirectory($attachment['tempDir']);

			$this->_uploads[$alias] = $transit;

			// Set transformers and transporter
			$this->_addTransformers($model, $transit, $attachment);
			$this->_setTransporter($model, $transit, $attachment);

			// Attempt upload or import
			try {
				$overwrite = $attachment['overwrite'];

				// File upload
				if (is_array($file)) {
					$response = $transit->upload($overwrite);

				// Remote import
				} else if (preg_match('/^http/i', $file)) {
					$response = $transit->importFromRemote($overwrite);

				// Local import
				} else if (file_exists($file)) {
					$response = $transit->importFromLocal($overwrite);

				// Stream import
				} else {
					$response = $transit->importFromStream($overwrite);
				}

				// Successful upload or import
				if ($response) {
					$dbColumnMap = array($attachment['dbColumn']);

					// Rename and move file
					$data[$attachment['dbColumn']] = $this->_renameAndMove($model, $transit->getOriginalFile(), $attachment);

					// Transform the files and save their path
					if ($attachment['transforms']) {
						$transit->transform();

						$transformedFiles = $transit->getTransformedFiles();
						$count = 0;

						foreach ($attachment['transforms'] as $transform) {
							if ($transform['self']) {
								$tempFile = $transit->getOriginalFile();
								$dbColumnMap[0] = $transform['dbColumn'];

							} else {
								$tempFile = $transformedFiles[$count];
								$dbColumnMap[] = $transform['dbColumn'];
								$count++;
							}

							$data[$transform['dbColumn']] = $this->_renameAndMove($model, $tempFile, $transform);
						}
					}

					$metaData = $transit->getOriginalFile()->toArray();

					// Transport the files and save their remote path
					if ($attachment['transport']) {
						if ($transportedFiles = $transit->transport()) {
							foreach ($transportedFiles as $i => $transportedFile) {
								$data[$dbColumnMap[$i]] = $transportedFile;
							}
						}
					}
				}

			// Trigger form errors if validation fails
			} catch (ValidationException $e) {
				$dbColumns = array_merge(array($attachment['dbColumn']), array_keys($attachment['transforms']));

				foreach ($dbColumns as $dbCol) {
					unset($model->data[$alias][$dbCol]);
				}

				// Allow empty uploads
				if ($attachment['allowEmpty']) {
					if (!empty($attachment['defaultPath']) && !$model->id) {
						$model->data[$alias][$attachment['dbColumn']] = $attachment['defaultPath'];
					}

					continue;
				}

				// Invalidate and stop
				$model->invalidate($field, __d('uploader', $e->getMessage()));

				if ($attachment['stopSave']) {
					return false;
				}

			// Log exceptions that shouldn't be shown to the client
			} catch (Exception $e) {
				$model->invalidate($field, __d('uploader', 'An unknown error has occurred'));

				$this->log($e->getMessage(), LOG_DEBUG);

				// Rollback the files since it threw errors
				$transit->rollback();

				if ($attachment['stopSave']) {
					return false;
				}
			}

			// Save file meta data
			$cleanup = $data;

			if ($attachment['metaColumns'] && $data && !empty($metaData)) {
				foreach ($attachment['metaColumns'] as $method => $column) {
					if (isset($metaData[$method]) && $column) {
						$data[$column] = $metaData[$method];
					}
				}
			}

			// Merge upload data with model data
			if ($data) {
				$model->data[$alias] = $data + $model->data[$alias];
			}
		}

		// If we are doing an update, delete the previous files that are being replaced
		if ($model->id && $cleanup) {
			$this->_cleanupOldFiles($model, $cleanup);
		}

		return true;
	}

	/**
	 * Delete all files associated with a record but do not delete the record.
	 *
	 * @param Model $model
	 * @param int $id
	 * @param array $filter
	 * @return bool
	 */
	public function deleteFiles(Model $model, $id, array $filter = array()) {
		$columns = $this->_columns[$model->alias];
		$data = $model->find('first', array(
			'conditions' => array($model->alias . '.' . $model->primaryKey => $id),
			'contain' => false,
			'recursive' => -1
		));

		if (empty($data[$model->alias])) {
			return false;
		}

		// Set data in case $this->data is used in callbacks
		$model->set($data);

		$save = array();

		foreach ($data[$model->alias] as $column => $value) {
			if (empty($columns[$column])) {
				continue;
			} else if ($filter && !in_array($column, $filter)) {
				continue;
			}

			if ($this->_deleteFile($model, $columns[$column], $value)) {
				$save[$column] = '';
			}
		}

		// Set the fields to empty
		if ($save) {
			$model->id = $id;
			$model->save($save, array(
				'validate' => false,
				'callbacks' => false,
				'fieldList' => array_keys($save)
			));
		}

		return true;
	}

	/**
	 * Return the uploaded original File object.
	 *
	 * @param Model $model
	 * @return \Transit\File
	 */
	public function getUploadedFile(Model $model) {
		if (isset($this->_uploads[$model->alias])) {
			return $this->_uploads[$model->alias]->getOriginalFile();
		}

		return null;
	}

	/**
	 * Return the transformed File objects.
	 *
	 * @param Model $model
	 * @return \Transit\File[]
	 */
	public function getTransformedFiles(Model $model) {
		if (isset($this->_uploads[$model->alias])) {
			return $this->_uploads[$model->alias]->getTransformedFiles();
		}

		return null;
	}

	/**
	 * Trigger callback methods to modify attachment settings before uploading.
	 *
	 * @param Model $model
	 * @param array $options
	 * @return array
	 */
	protected function _settingsCallback(Model $model, array $options) {
		if (method_exists($model, 'beforeUpload')) {
			$options = $model->beforeUpload($options);
		}

		if ($options['transforms'] && method_exists($model, 'beforeTransform')) {
			foreach ($options['transforms'] as $i => $transform) {
				$options['transforms'][$i] = $model->beforeTransform($transform);
			}
		}

		if ($options['transport'] && method_exists($model, 'beforeTransport')) {
			$options['transport'] = $model->beforeTransport($options['transport']);
		}

		return $options;
	}

	/**
	 * Add Transit Transformers based on the attachment settings.
	 *
	 * @param Model $model
	 * @param \Transit\Transit $transit
	 * @param array $attachment
	 */
	protected function _addTransformers(Model $model, Transit $transit, array $attachment) {
		if (empty($attachment['transforms'])) {
			return;
		}

		foreach ($attachment['transforms'] as $options) {
			$transformer = $this->_getTransformer($options);

			if ($options['self']) {
				$transit->addSelfTransformer($transformer);
			} else {
				$transit->addTransformer($transformer);
			}
		}
	}

	/**
	 * Set the Transit Transporter to use based on the attachment settings.
	 *
	 * @param Model $model
	 * @param \Transit\Transit $transit
	 * @param array $attachment
	 */
	protected function _setTransporter(Model $model, Transit $transit, array $attachment) {
		if (empty($attachment['transport'])) {
			return;
		}

		$transit->setTransporter($this->_getTransporter($attachment['transport']));
	}

	/**
	 * Return a Transformer based on the options.
	 *
	 * @uses Transit\Transformer\Image\CropTransformer
	 * @uses Transit\Transformer\Image\FlipTransformer
	 * @uses Transit\Transformer\Image\ResizeTransformer
	 * @uses Transit\Transformer\Image\ScaleTransformer
	 *
	 * @param array $options
	 * @return \Transit\Transformer
	 * @throws \InvalidArgumentException
	 */
	protected function _getTransformer(array $options) {
		switch ($options['method']) {
			case self::CROP:
				return new CropTransformer($options);
			break;
			case self::FLIP:
				return new FlipTransformer($options);
			break;
			case self::RESIZE:
				return new ResizeTransformer($options);
			break;
			case self::SCALE:
				return new ScaleTransformer($options);
			break;
			default:
				throw new InvalidArgumentException(sprintf('Invalid transformation method %s', $options['method']));
			break;
		}
	}

	/**
	 * Return a Transporter based on the options.
	 *
	 * @uses Transit\Transporter\Aws\S3Transporter
	 * @uses Transit\Transporter\Aws\GlacierTransporter
	 *
	 * @param array $options
	 * @return \Transit\Transporter
	 * @throws \InvalidArgumentException
	 */
	protected function _getTransporter(array $options) {
		switch ($options['class']) {
			case self::S3:
				return new S3Transporter($options['accessKey'], $options['secretKey'], $options);
			break;
			case self::GLACIER:
				return new GlacierTransporter($options['accessKey'], $options['secretKey'], $options);
			break;
			default:
				throw new InvalidArgumentException(sprintf('Invalid transport class %s', $options['class']));
			break;
		}
	}

	/**
	 * Rename or move the file and return its relative path.
	 *
	 * @param Model $model
	 * @param \Transit\File $file
	 * @param array $options
	 * @return string
	 */
	protected function _renameAndMove(Model $model, File $file, array $options) {
		$nameCallback = null;

		if ($options['nameCallback'] && method_exists($model, $options['nameCallback'])) {
			$nameCallback = array($model, $options['nameCallback']);
		}

		$file->rename($nameCallback, $options['append'], $options['prepend']);

		if ($options['uploadDir']) {
			$file->move($options['uploadDir'], $options['overwrite']);
		}

		return (string) $options['finalPath'] . $file->basename();
	}

	/**
	 * Attempt to delete a file using the attachment settings.
	 *
	 * @uses Transit\File
	 *
	 * @param Model $model
	 * @param string $field
	 * @param string $path
	 * @return bool
	 */
	protected function _deleteFile(Model $model, $field, $path) {
		if (empty($this->settings[$model->alias][$field])) {
			return false;
		}

		$attachment = $this->_settingsCallback($model, $this->settings[$model->alias][$field]);
		$basePath = $attachment['uploadDir'] ?: $attachment['tempDir'];

		try {
			// Delete remote file
			if ($attachment['transport']) {
				$transporter = $this->_getTransporter($attachment['transport']);

				return $transporter->delete($path);

			// Delete local file
			} else {
				$file = new File($basePath . basename($path));

				return $file->delete();
			}

		} catch (Exception $e) {
			$this->log($e->getMessage(), LOG_DEBUG);
		}

		return false;
	}

	/**
	 * Delete previous files if a record is being overwritten.
	 *
	 * @param Model $model
	 * @param array $fields
	 * @return void
	 */
	protected function _cleanupOldFiles(Model $model, array $fields) {
		$columns = $this->_columns[$model->alias];
		$data = $model->find('first', array(
			'conditions' => array($model->alias . '.' . $model->primaryKey => $model->id),
			'contain' => false,
			'recursive' => -1
		));

		if (empty($data[$model->alias])) {
			return;
		}

		foreach ($fields as $column => $value) {
			if (empty($data[$model->alias][$column])) {
				continue;
			}

			// Delete if previous value doesn't match new value
			$previous = $data[$model->alias][$column];

			if ($previous !== $value) {
				$this->_deleteFile($model, $columns[$column], $previous);
			}
		}
	}

}
