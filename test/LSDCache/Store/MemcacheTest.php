<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreMemcacheTest extends StoreTest {

  public function setUp() {
    $memcache = new \Memcache();
    $memcache->addServer('127.0.0.1');
    // $memcache->flush();
    $this->setStore(new Store\Memcache($memcache));
  }

}
