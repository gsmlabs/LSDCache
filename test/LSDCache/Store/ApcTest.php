<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreApcTest extends StoreTest {

  public function setUp() {
    $this->markTestIncomplete('APC not really working in CLI mode');

    $this->setStore(new Store\Apc());
  }

}
