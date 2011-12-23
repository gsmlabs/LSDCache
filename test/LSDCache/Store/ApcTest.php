<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreApcTest extends StoreTest {

  public function setUp() {
    apc_clear_cache('user');
    $this->setStore(new Store\Apc());
  }
  
  public function testSetAndGetValue() {
    $this->markTestIncomplete('APC seems not to set value immediately, so it cannot be accessed right after setting');
  }

  public function testAddMethodSetsValue() {
    $this->markTestIncomplete('APC seems not to set value immediately, so it cannot be accessed right after setting');
  }

  public function testAddMethodReturnsFalseOnAlreadyExistingValue() {
    $this->markTestIncomplete('APC seems not to set value immediately, so it cannot be accessed right after setting');
  }

  public function testSetMultiAndGetMultiValues() {
    $this->markTestIncomplete('Functionality not yet implemented');
  }
  
}
