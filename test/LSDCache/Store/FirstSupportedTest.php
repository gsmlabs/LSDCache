<?php
namespace LSDCache\Tests;
use LSDCache\Store;
use Memcached;

class StoreFirstSupportedTest extends StoreTest {

  public function setUp() {
    $stores = array(
      new Store\PhpArray(),
      new Store\XCache(),
      new Store\Apc()
    );

    $store = new Store\FirstSupported($stores);

    $this->setStore($store);
  }

}
