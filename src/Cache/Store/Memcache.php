<?php
class Cache_Store_Memcache implements Cache_Store {

  private $memcache;

  public function __construct(Memcache $memcache) {
    $this->memcache = $memcache;
  }

  public function get($key) {
    return $this->memcache->get($key);
  }

  public function set($key, $value, $ttl = 0) {
    return $this->memcache->set($key, $value, 0, $ttl);
  }

  public function add($key, $value, $ttl = 0) {
    return $this->memcache->add($key, $value, 0, $ttl);
  }

  public function delete($key) {
    // workaround for memcache "delete" various issue
    // for example see comments for http://php.net/manual/en/memcache.delete.php
    return $this->memcache->set($key, false, 0, 1);
  }

}
