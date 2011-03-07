<?php
require_once dirname(__FILE__).'/StoreTest.php';

class StoreApcTest extends StoreTest {

  public function setUp() {
//    apc_clear_cache('user');
    $this->setStore(new Cache_Store_Apc());
  }

}
