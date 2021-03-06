<?php
/**
 * li3_couchbase: A Couchbase datasource for Lithium.
 *
 * @copyright     Copyright 2012, Michael Nitschinger
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_couchbase\tests\cases\extensions\data\source;

use li3_couchbase\extensions\data\source\Couchbase;
use lithium\data\Connections;
use lithium\analysis\Inspector;

/**
 *
 */
class CouchbaseTest extends \lithium\test\Unit {

	/**
	 * Database configuration.
	 */
	protected $_dbConfig = array();

	/**
	 * Connection to the database.
	 */
	public $db = null;

	/**
	 * Skip the test if a Couchbase adapter configuration is unavailable.
	 */
	public function skip() {
		$this->skipIf(!Couchbase::enabled(), 'The Couchbase extension is not loaded!');

		$this->_dbConfig = Connections::get('test-couchbase', array('config' => true));
		$hasDb = (isset($this->_dbConfig['type']) && $this->_dbConfig['type'] == 'Couchbase');
		$message = 'Test database is either unavailable, or not a Couchbase connection!';
		$this->skipIf(!$hasDb, $message);

		$this->db = new Couchbase($this->_dbConfig);
	}

	public function testEnabled() {
		$this->assertTrue(Couchbase::enabled());
		$this->assertFalse(Couchbase::enabled('transactions'));
	}

	public function testDefaults() {
		$expected = array(
			'host'       => 'localhost:8091',
			'login'      => null,
			'password'   => null,
			'database'     => 'default',
			'persistent' => false,
			'autoConnect' => true,
			'init' => false
		);

		$cb = new Couchbase(array('init' => false));
		$reflection = new \ReflectionObject($cb);
		$configProperty = $reflection->getProperty('_config');
		$configProperty->setAccessible(true);
		$result = $configProperty->getValue($cb);

		$this->assertEqual($expected, $result);
	}

	public function testConnect() {
		$result = new Couchbase($this->_dbConfig);
		$this->assertTrue($result->isConnected());

		$this->assertTrue(is_array($result->connection->getVersion()));
		$this->assertTrue($result->connection->getClientVersion());
	}

	public function testDisconnect() {
		$cb = new Couchbase($this->_dbConfig);
		$this->assertTrue($cb->isConnected());
		$this->assertTrue($cb->disconnect());
		$this->assertFalse($cb->isConnected());
	}

	public function testCreate() {

	}

	public function testRead() {

	}

	public function testUpdate() {

	}

	public function testDelete() {

	}
}

?>