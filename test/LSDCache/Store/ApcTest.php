<?php
namespace LSDCache\Tests;
use LSDCache\Store;

class StoreApcTest extends StoreTest {

  public function setUp() {
    $isEnabled = $this->enableCliStore();
    if (false === $isEnabled) {
      $this->markTestIncomplete('APC not working in CLI mode');
    }

    $this->setStore(new Store\Apc());
  }

  public function tearDown() {
    $this->restoreCliStore();
  }

  protected function enableCliStore() {
    if (false === (bool)\ini_get('apc.enable_cli')) {
      \ini_set('apc.enable_cli', 1);
    }

    return (bool)\ini_get('apc.enable_cli');
  }

  protected function restoreCliStore() {
    \ini_restore('apc.enable_cli');
  }
}
