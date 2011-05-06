<?php
require_once dirname(__FILE__).'/../../src/Cache/Cache.php';
require_once dirname(__FILE__).'/../../src/Cache/Value.php';
require_once dirname(__FILE__).'/../../src/Cache/Store.php';
require_once dirname(__FILE__).'/../../src/Cache/Store/Apc.php';
require_once dirname(__FILE__).'/../../src/Cache/Store/Array.php';
require_once dirname(__FILE__).'/../../src/Cache/Store/Memcache.php';
require_once dirname(__FILE__).'/../../src/Cache/DeadlockHandler.php';
require_once dirname(__FILE__).'/../../src/Cache/DeadlockHandler/Exception.php';

abstract class StoreTest extends PHPUnit_Framework_TestCase {

  private $store;

  /**
   * @return Cache_Store
   */
  public function getStore() {
    return $this->store;
  }

  public function setStore(Cache_Store $store) {
    $this->store = $store;
  }

  public function testSetAndGetValue() {
    $key = 'zabrze';
    $value = 'górnik zabrze';

    $this->getStore()->set($key, $value);
    $this->assertEquals($value, $this->getStore()->get($key));
  }

  public function testSetMultiAndGetMultiValues() {
    $key = 'zabrze';
    $value = 'górnik zabrze';
    $key_not_in_cache = 'poznan';

    $key_value = array($key => $value, $value => $key);
    $this->getStore()->setMulti($key_value);

    $this->assertEquals(array($key => $value), $this->getStore()->getMulti( array($key) ));
    // $this->assertEquals(array(), $this->getStore()->getMulti( array($key_not_in_cache) ));

    // TODO: remember that sometimes key's whitespace is being converted it into underscores
    // ie. array('gornik zabrze' => 'zabrze') results in ('gornik_zabrze' => 'zabrze')

  }

  public function testreturnsFalseOnGettingNonExistingValue() {
    $key = 'gliwice';

    $this->assertFalse($this->getStore()->get($key));
  }

  public function testAddMethodSetsValue() {
    $key = 'bytom';
    $value = 'polonia bytom';

    $this->getStore()->add($key, $value);
    $this->assertEquals($value, $this->getStore()->get($key));
  }

  public function testAddMethodReturnsFalseOnAlreadyExistingValue() {
    $key = 'katowice';
    $value1 = 'gks katowice';
    $value2 = 'rozwój katowice';

    $this->getStore()->set($key, $value1);
    $result = $this->getStore()->add($key, $value2);

    $this->assertFalse($result);
    $this->assertEquals($value1, $this->getStore()->get($key));
  }

  public function testDeleteValue() {
    $key = 'chorzow';
    $value = 'ruch chorzów';

    $this->getStore()->set($key, $value);
    $this->getStore()->delete($key, $value);

    $this->assertFalse($this->getStore()->get($key));
  }

  public function testMultiGetsValue() {
    $this->markTestIncomplete();
  }

  public function testMultiSetsValue() {
    $this->markTestIncomplete();
  }

  public function testValuesExpire() {
    $this->markTestIncomplete('It should be tested whether values really expire after specified ttl');
  }

}
