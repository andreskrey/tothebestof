<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
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

$helpers[]='Admin';
$components[] = 'Search.Prg';
$components[] = 'Admin';

echo "<?php\n";
echo "App::uses( 'AdminAppController', 'Controller' );\n";
?>
/**
 * <?php echo $controllerName; ?> Controller
 *
<?php
$defaultModel = Inflector::singularize($controllerName);
echo " * @property {$defaultModel} \${$defaultModel}\n";
if (!empty($components)) {
	foreach ($components as $component) {
		echo " * @property {$component}Component \${$component}\n";
	}
}
?>
 */
class <?php echo $controllerName; ?>Controller extends AdminAppController
{
  /* inject */
  /**
	 * Paginate
	 *
	 * @var array
	 */
  public $paginate = array(
    '<?php echo $currentModelName ?>' => array(
      'order' => array( '<?php echo $currentModelName ?>.id' => 'asc' ),
      'limit' => 20,
    )
	);

<?php
echo "\t/**\n\t * Helpers\n\t *\n\t * @var array\n\t */\n";
echo "\tpublic \$helpers = array(";
for ($i = 0, $len = count($helpers); $i < $len; $i++):
	if ($i != $len - 1):
		echo "'" . Inflector::camelize($helpers[$i]) . "', ";
	else:
		echo "'" . Inflector::camelize($helpers[$i]) . "'";
	endif;
endfor;
echo ");\n\n";

echo "\t/**\n\t * Components\n\t *\n\t * @var array\n\t */\n";
echo "\tpublic \$components = array(";
for ($i = 0, $len = count($components); $i < $len; $i++):
	if ($i != $len - 1):
		echo "'" . Inflector::camelize($components[$i]) . "', ";
	else:
		echo "'" . Inflector::camelize($components[$i]) . "'";
	endif;
endfor;
echo ");\n\n";
?>
  /**
	 * Cached actions
	 *
	 * @var array
	 */
  //public $cacheAction = array();


	/**
	 * beforeFilter method
	 *
	 * @return void
	 */
	public function beforeFilter()
	{
    $this->breadcrumbControllerNames['<?php echo Inflector::tableize($pluralName) ?>'] = $this-><?php echo $currentModelName; ?>->pluralName;

    parent::beforeFilter();

    if ( $this->admin ) $this->set( 'model', $this-><?php echo $currentModelName ?> );
	}

<?php
echo $actions . "\n";
 ?>
}
