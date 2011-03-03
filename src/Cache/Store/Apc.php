<?php
class Cache_Store_Apc implements Cache_Store {

  public function set($key, $value, $ttl = 0) {
    return apc_store($key, $value, $ttl);
  }

  public function get($key) {
    return apc_fetch($key);
  }

  public function add($key, $value, $ttl = 0) {
  }

}
