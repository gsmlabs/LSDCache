<?php
namespace LSDCache\Tests;
use LSDCache\Store;
use Memcached;

class StoreCompositeTest extends StoreTest {

  private $memcached_store, $array_store;

  public function setUp() {
    $memcached = new Memcached();
    $memcached->addServer('127.0.0.1', 11211);

    $stores = array(
      $this->array_store = new Store\PhpArray(),
      $this->memcached_store = new Store\Memcached($memcached)
    );

    $store = new Store\Composite($stores);
    $this->setStore($store);
  }

  public function testGetSetsValueForPreviousStores() {
    $key = $value = 'mascherano';

    $this->memcached_store->set($key, $value, 60);
    $this->store->get($key);

    $this->assertEquals($value, $this->array_store->get($key));
  }

}
