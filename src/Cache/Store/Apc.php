<?php
class Cache_Store_Apc implements Cache_Store {

  public function get($key) {
    return apc_fetch($key);
  }

  public function set($key, $value, $ttl = 0) {
    return apc_store($key, $value, $ttl);
  }

  public function getMulti($keys) {
    throw new Exception('Not yet implemented');

    $result = apc_fetch($keys);
    return empty($result) ? array() : $result;
  }

  public function setMulti($values, $ttl = 0) {
    return apc_store($values, null, $ttl);
  }

  public function add($key, $value, $ttl = 0) {
    return apc_add($key, $value, $ttl);
  }

  public function delete($key) {
    return apc_delete($key);
  }

}
