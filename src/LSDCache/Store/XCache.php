<?php
namespace LSDCache\Store;

class XCache implements StoreInterface {

  public function get($key) {
    $value = xcache_get($key);
    if (!$value) {
      return false;
    }
    return unserialize($value);
  }

  public function set($key, $value, $ttl = 0) {
    return xcache_set($key, serialize($value), $ttl);
  }

  public function getMulti($keys) {
    $values = array();

    foreach ($keys as $key) {
      $values[$key] = $this->get($key);
    }

    return $values;
  }

  public function setMulti($values, $ttl = 0) {
    foreach ($values as $key => $value) {
      $this->set($key, $value, $ttl);
    }
  }

  // SMELL: not atomic for xcache!
  public function add($key, $value, $ttl = 0) {
    if (!$this->get($key)) {
      return $this->set($key, $value, $ttl);
    }
    return false;
  }

  public function delete($key) {
    return xcache_unset($key);
  }

  public function inc($key, $step=1) {
    return xcache_inc($key, $step);
  }

  public function isSupported() {
    return extension_loaded('xcache');
  }
}
