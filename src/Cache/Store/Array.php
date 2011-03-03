<?php
class Cache_Store_Array implements Cache_Store {

  private $array = array();
  private $ttls = array();

  public function add($key, $value, $ttl = 0) {
    if (!isset($this->array[$key])) {
      return $this->set($key, $value, $ttl);
    }
    return false;
  }

  public function set($key, $value, $ttl = 0) {
    $this->array[$key] = array($value, time() + $ttl);
    $this->ttls[$key] = $ttl;
    return true;
  }

  public function get($key) {
    if (!isset($this->array[$key]) || !isset($this->ttls[$key])) {
      return false;
    }

    list($value, $expiration_time) = $this->array[$key];

    if (time() > $this->ttls[$key]) {
      unset($this->array[$key]);
      return false;
    }

    return $value;
  }

}
