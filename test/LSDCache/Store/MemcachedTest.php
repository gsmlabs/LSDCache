<?php
namespace LSDCache\Tests;
use LSDCache\Store;
use Memcached;

class StoreMemcachedTest extends StoreTest {

  public function setUp() {
    $memcached = new Memcached();
    $memcached->addServer('127.0.0.1', 11211);
    $memcached->setOption(Memcached::OPT_COMPRESSION, true);
    $memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
    $memcached->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
    $memcached->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
    $this->setStore(new Store\Memcached($memcached));
  }

}
