<?php
require_once dirname(__FILE__).'/../src/Cache/Cache.php';
require_once dirname(__FILE__).'/../src/Cache/Value.php';
require_once dirname(__FILE__).'/../src/Cache/Store.php';
require_once dirname(__FILE__).'/../src/Cache/Store/Array.php';
require_once dirname(__FILE__).'/../src/Cache/DeadlockHandler.php';
require_once dirname(__FILE__).'/../src/Cache/DeadlockHandler/Exception.php';
require_once dirname(__FILE__).'/../src/Cache/DeadlockHandler/False.php';

class CacheTest extends PHPUnit_Framework_TestCase {

  public function testGetsValue() {
    $key = 'liverpool';
    $value = 'everton';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $store->set($key, new Cache_Value($value, 3600));

    $result = $cache->get($key);
    $this->assertEquals($value, $result);
  }
  
  public function testSetsValue() {
    $key = 'barcelona';
    $value = 'fc barcelona';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);
    
    $result = $cache->set($key, $value, 3600);
    $this->assertTrue($result);

    $vo = $store->get($key);
    $this->assertTrue($vo instanceof Cache_Value, 'Cache should return Cache_Value');
    $this->assertEquals($value, $vo->getValue());
  }

  public function testReturnsFalseWhenGettingNotExistingValue() {
    $key = 'monaco';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $result = $cache->get($key);
    $this->assertFalse($result);
  }

  public function testLockIsSetWhenGettingValue() {
    $key = 'dortmund';
    $value = 'borussia';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $cache->get($key);
    $this->assertTrue($store->get($cache->lockKey($key)));
  }

  public function testLockIsReleasedWhenSettingValue() {
    $key = 'milan';
    $value = 'internazionale';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $store->set($cache->lockKey($key), true);

    $cache->set($key, $value, 3660);
    $this->assertFalse($store->get($cache->lockKey($key)));
  }

  public function testGetsStaleValueWhenExpiredAndLockIsSet() {
    $key = 'madrid';
    $value = 'real';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $cache->set($key, $value, -1, 10); // expired value    
    $store->set($cache->lockKey($key), true); // other process sets a lock
    $this->assertEquals($value, $cache->get($key));
  }

  public function testReturnsFalseWhenLockIsSetAndStaleValueIsNotPresent() {
    $key = 'krakow';
    $value = 'wisla';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $store->set($cache->lockKey($key), true); // other process sets a lock
    
    $this->assertFalse($cache->get($key));
  }

  public function testCanSetCustomDeadlockHandler() {
    $cache = new Cache_Cache(new Cache_Store_Array());

    $deadlock_handler = new Cache_DeadlockHandler_False();
    $cache->setDeadlockHandler($deadlock_handler);
    $this->assertSame($deadlock_handler, $cache->getDeadlockHandler());

    $deadlock_handler = new Cache_DeadlockHandler_Exception();
    $cache->setDeadlockHandler($deadlock_handler);
    $this->assertSame($deadlock_handler, $cache->getDeadlockHandler());
  }

  public function testCanGetStore() {
    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $this->assertSame($store, $cache->getStore());
  }

  public function testCanGetAndSetViaCallback() {
    $key = $value = 'lisboa';

    $store = new Cache_Store_Array();
    $cache = new Cache_Cache($store);

    $cache->getOrSet($key, array($this, 'getOrSetCallback'), 3600);
    $this->assertEquals($value, $cache->get($key));
  }

  public function getOrSetCallback() {
    $key = $value = 'lisboa';
    return $value;
  }

  public function testMultiGetsValue() {
    $this->markTestIncomplete();
  }

  public function testMultiSetsValue() {
    $this->markTestIncomplete();
  }

}
