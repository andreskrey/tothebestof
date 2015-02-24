<?php
/**
 * Model template file.
 *
 * Used by bake to create new Model files.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.classes
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo "<?php\n";
echo "App::uses('AppModel', 'Model');\n";
?>
/**
 * <?php echo $name ?> Model
 *
<?php
foreach (array('hasOne', 'belongsTo', 'hasMany', 'hasAndBelongsToMany') as $assocType) {
	if (!empty($associations[$assocType])) {
		foreach ($associations[$assocType] as $relation) {
			echo " * @property {$relation['className']} \${$relation['alias']}\n";
		}
	}
}
?>
 */
class <?php echo $name ?> extends <?php echo $plugin; ?>AppModel
{
  /**
	 * Singular name for collection
	 *
	 * @var string
	 */
	public $singularName = '<?php echo $singularName; ?>';

  /**
	 * Plural name for collection
	 *
	 * @var string
	 */
	public $pluralName = '<?php echo $pluralName; ?>';

  /**
   * name mapping for fields
   *
   * @var array
   */
  public $fieldNames = array(
<?php foreach ($fields as $key => $field): ?>
		'<?php echo $key ?>' => '<?php echo $fieldNames[$key] ?>',
<?php endforeach; ?>
  );
<?php

if ($useTable && $useTable !== Inflector::tableize($name)):
	$table = "'$useTable'";
	echo "/**\n * Use table\n *\n * @var mixed False or table name\n */\n";
	echo "\tpublic \$useTable = $table;\n\n";
endif;

if ($primaryKey !== 'id'): ?>
	/**
	 * Primary key field
	 *
	 * @var string
	 */
	public $primaryKey = '<?php echo $primaryKey; ?>';

<?php endif;

if ($displayField): ?>
	/**
	 * Display field
	 *
	 * @var string
	 */
	public $displayField = '<?php echo $displayField; ?>';

<?php endif; ?>

	/**
	 * Behaviors
	 *
	 * @var array
	 */
	public $actsAs = array(
		'Search.Searchable',
	);
<?php
// @todo poner espacio y comments del filterArgs
//filtra solamente los fields con texto y enteros, sin el primary key
$searchFields = array_filter( $fields, function ( $elem ) { return in_array( $elem[ 'type' ], array( 'text', 'string', 'integer' ) ) && Hash::get($elem, 'key') != 'primary'; } );
?>

	/**
	 * Filter Arguments
	 * from the Search Filter, see https://github.com/CakeDC/search for help and examples
	 *
	 * @var array
	 */
	public $filterArgs = array(
		'*' => array( 'type' => 'like', 'field' => array( '<?php echo join("', '", array_keys($searchFields)) ?>' ) ),
<?php foreach ($searchFields as $key => $field): ?>
		'<?php echo $key ?>' => array( 'type' => 'like' ),
<?php endforeach; ?>
	);

<?php
foreach ($associations as $assoc):
	if (!empty($assoc)):
?>
<?php
		break;
	endif;
endforeach;

foreach (array('hasOne', 'belongsTo') as $assocType):
	if (!empty($associations[$assocType])):
		$typeCount = count($associations[$assocType]);
		echo "\n\t/**\n\t * $assocType associations\n\t *\n\t * @var array\n\t */";
		echo "\n\tpublic \$$assocType = array(";
		foreach ($associations[$assocType] as $i => $relation):
			$out = "\n\t\t'{$relation['alias']}' => array(\n";
			$out .= "\t\t\t'className' => '{$relation['className']}',\n";
			$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
			$out .= "\t\t)";
			if ($i + 1 < $typeCount) {
				$out .= ",";
			}
			echo $out;
		endforeach;
		echo "\n\t);\n";
	endif;
endforeach;

if (!empty($associations['hasMany'])):
	$belongsToCount = count($associations['hasMany']);
	echo "\n\t/**\n\t * hasMany associations\n\t *\n\t * @var array\n\t */";
	echo "\n\tpublic \$hasMany = array(";
	foreach ($associations['hasMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'dependent' => false,\n";
		$out .= "\t\t)";
		if ($i + 1 < $belongsToCount) {
			$out .= ",";
		}
		echo $out;
	endforeach;
	echo "\n\t);\n\n";
endif;

if (!empty($associations['hasAndBelongsToMany'])):
	$habtmCount = count($associations['hasAndBelongsToMany']);
	echo "\n\t/**\n\t * hasAndBelongsToMany associations\n\t *\n\t * @var array\n\t */";
	echo "\n\tpublic \$hasAndBelongsToMany = array(";
	foreach ($associations['hasAndBelongsToMany'] as $i => $relation):
		$out = "\n\t\t'{$relation['alias']}' => array(\n";
		$out .= "\t\t\t'className' => '{$relation['className']}',\n";
		$out .= "\t\t\t'joinTable' => '{$relation['joinTable']}',\n";
		$out .= "\t\t\t'foreignKey' => '{$relation['foreignKey']}',\n";
		$out .= "\t\t\t'associationForeignKey' => '{$relation['associationForeignKey']}',\n";
		$out .= "\t\t\t'unique' => 'keepExisting',\n";
		$out .= "\t\t)";
		if ($i + 1 < $habtmCount) {
			$out .= ",";
		}
		echo $out;
	endforeach;
	echo "\n\t);\n\n";
endif;

if (!empty($validate)):
	echo "\t/**\n\t * Validation rules\n\t *\n\t * @var array\n\t */\n";
	echo "\tpublic \$validate = array(\n";
	foreach ($validate as $field => $validations):
		$i = 0;
		echo "\t\t'$field' => array(\n";
		foreach ($validations as $key => $validator):
			echo "\t\t\t'$key' => array(\n";
			echo "\t\t\t\t'rule' => array('$validator'),\n";
      $message = isset($messages[$validator]) ? $messages[$validator] : 'Mensaje custom';
			echo "\t\t\t\t'message' => '{$message}',\n";
			if ($i == 0):
			echo "\t\t\t\t'allowEmpty' => false,\n";
			echo "\t\t\t\t'required' => true,\n";
			endif;
			echo "\t\t\t\t'last' => true, \n";
			echo "\t\t\t\t//'on' => 'create', \n";
			echo "\t\t\t),\n";
			$i++;
		endforeach;
		echo "\t\t),\n";
	endforeach;
	echo "\t);\n";
endif;
?>
}
