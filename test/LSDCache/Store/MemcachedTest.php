<?php
namespace LSDCache\Tests;
use LSDCache\Store;
use Memcached;

class StoreMemcachedTest extends StoreTest {

  static private $memcached;

  static public function setUpBeforeClass() {
    self::$memcached = new Memcached();
    self::$memcached->addServer('127.0.0.1', 11211);
    self::$memcached->setOption(Memcached::OPT_COMPRESSION, true);
    self::$memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
  }

  public function setUp() {
    self::$memcached->flush();
    $this->setStore(new Store\Memcached(self::$memcached));
  }
}
