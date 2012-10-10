<?php
namespace LSDCache\Store;

class Memcached implements StoreInterface {

  private $memcached;

  public function __construct(\Memcached $memcached) {
    $this->memcached = $memcached;
  }

  // memcached has limit max 250 chars for key
  private function prepareKey($key) {
    return sha1($key);
  }

  public function get($key) {
    return $this->memcached->get($this->prepareKey($key));
  }

  public function set($key, $value, $ttl = 0) {
    return $this->memcached->set($this->prepareKey($key), $value, $ttl);
  }

  public function getMulti($keys) {
    $prepared_keys = array();
    foreach ($keys as $key) {
      $prepared_keys[] = $this->prepareKey($key);
    }
    
    $raw_result = $this->memcached->getMulti($prepared_keys);

    $result = array();

    foreach ($keys as $key) {
      $prepared_key = $this->prepareKey($key);

      if (isset($raw_result[$prepared_key])) {
        $result[$key] = $raw_result[$prepared_key];
      }
    }

    return $result;
  }

  public function setMulti($values, $ttl = 0) {
    $prepared_key_values = array();

    foreach ($values as $key => $value) {
      $prepared_key_values[$this->prepareKey($key)] = $value;
    }

    return $this->memcached->setMulti($prepared_key_values, $ttl);
  }

  public function add($key, $value, $ttl = 0) {
    return $this->memcached->add($this->prepareKey($key), $value, $ttl);
  }

  public function delete($key) {
    return $this->memcached->delete($this->prepareKey($key));
  }

  public function inc($key, $step = 1) {
    return $this->memcached->increment($this->prepareKey($key), $step);
  }

}
