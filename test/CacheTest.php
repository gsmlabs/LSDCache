<?php
require_once 'PHPUnit/Framework.php';
require_once __DIR__.'/../src/Cache.php';
require_once __DIR__.'/../src/Cache/Store.php';
require_once __DIR__.'/../src/Cache/Store/Apc.php';
require_once __DIR__.'/../src/Cache/Store/Array.php';
require_once __DIR__.'/../src/Cache/Store/Memcache.php';

class CacheTest extends PHPUnit_Framework_TestCase {

  private $cache;

  const KEY = 'barcelona';
  const VALUE = 'spain';

//  public function setUpApc() {
//    $this->cache = new Cache(new Cache_Store_Array());
//  }

  public function setUpMemcache() {
    $memcache = new Memcache();
    $memcache->addserver('localhost');
    $memcache->flush();
    $this->cache = new Cache(new Cache_Store_Memcache($memcache));
  }
  
  public function setUp() {
    $this->setUpMemcache();
  }

  public function testSetsValue() {
    $result = $this->cache->set(self::KEY, self::VALUE);
    $this->assertTrue($result);
  }

  public function testGetsValue() {
    $key = 'puyol';
    $value = 'carles';

    $this->cache->set(self::KEY, self::VALUE);
    $this->assertEquals(self::VALUE, $this->cache->get(self::KEY));
  }

  public function testReturnsFalseWhenGettingNotExistingValue() {
    $result = $this->cache->get(microtime().mt_rand());
    $this->assertFalse($result);
  }

  public function testLockIsSetWhenGettingValue() {
    $this->cache->get(self::KEY);
    $this->assertTrue($this->cache->lockStatus(self::KEY));
  }

  public function testLockIsReleasedWhenSettingValue() {
    $this->cache->get(self::KEY);
    $this->cache->set(self::KEY, self::VALUE, 60);
    $this->assertFalse($this->cache->lockStatus(self::KEY));
  }

  public function testLockIsSetAndReleased() {
    $this->cache->set(self::KEY, self::VALUE, -1);
    if ($this->cache->get(self::KEY)) {
      $this->cache->set(self::KEY, self::VALUE, 60);
    }
    $this->assertFalse($this->cache->lockStatus(self::KEY));
  }

  public function testGetsOldValueWhenExpiredAndLockIsSet() {
    $this->markTestIncomplete('hard to test, must to mock up a store');
  }

  public function testWaitsWhenCacheEmptyAndLockIsSet() {
    $this->markTestIncomplete();
  }

}
