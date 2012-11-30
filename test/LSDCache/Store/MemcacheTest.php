<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreMemcacheTest extends StoreTest {

  static private $memcache;

  static public function setUpBeforeClass() {
    self::$memcache = new \Memcache();
    self::$memcache->addServer('127.0.0.1');
  }

  public function setUp() {
    self::$memcache->flush();
    $this->setStore(new Store\Memcache(self::$memcache));
  }
}
