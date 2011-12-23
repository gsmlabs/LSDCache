<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class PhpArrayTest extends StoreTest {

  public function setUp() {
    $this->setStore(new Store\PhpArray());
  }

}
