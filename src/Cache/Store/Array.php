<?php
class Cache_Store_Array implements Cache_Store {

  private $values = array();
  private $expiration_timestamps = array();

  public function get($key) {
    if (!isset($this->values[$key]) || !isset($this->expiration_timestamps[$key])) {
      return false;
    }

    if (time() > $this->expiration_timestamps[$key]) {
      unset($this->values[$key]);
      return false;
    }

    return $this->values[$key];
  }

  public function set($key, $value, $ttl = 0) {
    $this->values[$key] = $value;
    if ($ttl == 0) {
      $ttl = 999999999; // stored forever
    }
    $this->expiration_timestamps[$key] = time() + $ttl;
    return true;
  }

  public function getMulti($keys) {
    throw new Exception('Not yet implemented');
  }

  public function setMulti($values, $ttl = 0) {
    throw new Exception('Not yet implemented');
  }

  public function add($key, $value, $ttl = 0) {
    if (!isset($this->values[$key])) {
      return $this->set($key, $value, $ttl);
    }
    return false;
  }

  public function delete($key) {
    if (isset($this->values[$key])) {
      unset($this->values[$key]);
      return true;
    }
    return false;
  }
  
}
