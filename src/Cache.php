<?php
class Cache {

  private $store;

  private $lock_ttl = 30; // custom!!!
  private $lock_key_suffix = '.lock';
  
  public function  __construct(Cache_Store $store) {
    $this->store = $store;
  }

  public function getStore() {
    return $this->store;
  }

  public function getLockKey($key) {
    return $key.$this->lock_key_suffix;
  }

  public function set($key, $value, $ttl = 0) {    
    $value = array($value, time() + $ttl);
    $result = $this->store->set($key, $value, $ttl * 2);
    $this->unlock($key);
    return $result;
  }

  public function get($key) {
    $result = $this->store->get($key);
    if ($result !== false) {
      list($value, $expiration) = $result;

      if (time() < $expiration) {
        return $value;
      }
    }

    $locked = $this->lock($key);

    if (!$locked) {
      if (isset($value)) {
        return $value;
      }
      else {
        // wait
      }
    }

    return false;
  }

  public function getOrSet($key, $callback, $ttl = 0) {
    $result = $this->get($key);
    if ($result === false) {
      $value = call_user_func($callback);
      $this->cache->set($key, $value, $ttl);
    }
  }

  /**
   * @return bool
   */
  private function lock($key) {
    return $this->store->add($key.$this->lock_key_suffix, true, $this->lock_ttl);
  }

  /**
   * @return bool
   */
  private function unlock($key) {
    // DO NOT DELETE: because it is problematic, set false!
    return $this->store->set($key.$this->lock_key_suffix, false, $this->lock_ttl);
  }

  public function lockStatus($key) {
    return $this->store->get($key.$this->lock_key_suffix);
  }
  
}
