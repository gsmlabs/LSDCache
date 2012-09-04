<?php
namespace LSDCache\Store;

class Memcache implements StoreInterface {

  private $memcache;

  public function __construct(\Memcache $memcache) {
    $this->memcache = $memcache;
  }

  /**
   * Hash key due to memcache key length limit (250 chars).
   * 
   * See ticket #3702 for details.
   * @param string $key
   * @return string
   */
  private function prepareKey($key) {
    return sha1($key);
  }
  
  public function get($key) {
    $key = $this->prepareKey($key);
    return $this->memcache->get($key);
  }

  public function set($key, $value, $ttl = 0) {
    $key = $this->prepareKey($key);
    return $this->memcache->set($key, $value, 0, $ttl);
  }

  public function getMulti($keys) {
    $hashed_keys = $keys;
    foreach ($hashed_keys as &$key) {
      $key = $this->prepareKey($key);
    }
    
    $data = $this->memcache->get($hashed_keys);
    
    $result = array();
    foreach ($keys as $key) {
      $hash = $this->prepareKey($key);
      if (isset($data[$hash])) {
        $result[$key] = $data[$hash];
      }
    }
    return $result;
  }

  public function setMulti($values, $ttl = 0) {
    $result = true;
    foreach($values as $key => $value) {
      $key = $this->prepareKey($key);
      $result &= $this->set($key, $value, $ttl);
    }
    return (bool)$result;
  }

  public function add($key, $value, $ttl = 0) {
    $key = $this->prepareKey($key);
    return $this->memcache->add($key, $value, 0, $ttl);
  }

  public function delete($key) {
    $key = $this->prepareKey($key);
    // workaround for memcache "delete" various issue
    // for example see comments for http://php.net/manual/en/memcache.delete.php
    return $this->memcache->set($key, false, 0, -1);
  }

  public function inc($key, $step=1) {
    $key = $this->prepareKey($key);
    return $this->memcache->increment($key, $step);
  }
}
