<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/transit
 */

namespace Transit\Transformer\Image;

use Transit\File;
use \InvalidArgumentException;

/**
 * Scale the image based on a percentage.
 *
 * @package Transit\Transformer\Image
 */
class ScaleTransformer extends AbstractImageTransformer {

	/**
	 * Configuration.
	 *
	 * @type array {
	 * 		@type int $percent	Percent to scale image with
	 * 		@type int $quality	Quality of JPEG image
	 * }
	 */
	protected $_config = array(
		'percent' => .5,
		'quality' => 100
	);

	/**
	 * {@inheritdoc}
	 *
	 * @throws \InvalidArgumentException
	 */
	public function transform(File $file, $self = false) {
		$config = $this->_config;

		if (empty($config['percent']) || !is_numeric($config['percent'])) {
			throw new InvalidArgumentException('Invalid percent for scaling');
		}

		$width = round($file->width() * $config['percent']);
		$height = round($file->height() * $config['percent']);

		return $this->_process($file, array(
			'dest_w'	=> $width,
			'dest_h'	=> $height,
			'quality'	=> $config['quality'],
			'overwrite'	=> $self
		));
	}

}