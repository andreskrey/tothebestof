<?php
App::uses('Hit', 'Model');

/**
 * Hit Test Case
 *
 */
class HitTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.hit',
		'app.band',
		'app.songid',
		'app.favorite',
		'app.user',
		'app.playlist'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Hit = ClassRegistry::init('Hit');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Hit);

		parent::tearDown();
	}

}
