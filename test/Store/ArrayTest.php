<?php
require_once dirname(__FILE__).'/StoreTest.php';

class StoreArrayTest extends StoreTest {

  public function setUp() {
    $this->setStore(new Cache_Store_Array());
  }

  public function testSetMultiAndGetMultiValues() {
    $this->markTestIncomplete('Functionality not yet implemented');
  }

}
