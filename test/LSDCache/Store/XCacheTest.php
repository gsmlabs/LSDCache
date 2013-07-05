<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreXCacheTest extends StoreTest {

  public function setUp() {
    $this->setStore(new Store\XCache());
  }
  
}
