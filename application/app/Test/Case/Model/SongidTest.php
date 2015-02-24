<?php
App::uses('Songid', 'Model');

/**
 * Songid Test Case
 *
 */
class SongidTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.songid',
		'app.band'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Songid = ClassRegistry::init('Songid');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Songid);

		parent::tearDown();
	}

}
