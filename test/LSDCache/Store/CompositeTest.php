<?php
namespace LSDCache\Tests;
use LSDCache\Store;
use Memcached;

class StoreCompositeTest extends StoreTest {

  public function setUp() {
    $memcached = new Memcached();
    $memcached->addServer('127.0.0.1', 11211);

    $stores = array(
      new Store\PhpArray(),
      new Store\Memcached($memcached)
    );

    $store = new Store\Composite($stores);
    $this->setStore($store);
  }

}
