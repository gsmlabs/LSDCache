<?php
require_once dirname(__FILE__).'/StoreTest.php';

class StoreMemcacheTest extends StoreTest {

  public function setUp() {
    $memcache = new Memcache();
    $memcache->addServer('127.0.0.1');
    // $memcache->flush();
    $this->setStore(new Cache_Store_Memcache($memcache));
  }

}
