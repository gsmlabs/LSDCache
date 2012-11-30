<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreXCacheTest extends StoreTest {

  public function setUp() {
    $this->markTestIncomplete('XCache not working in CLI mode');

    $this->setStore(new Store\XCache());
  }
  
}
