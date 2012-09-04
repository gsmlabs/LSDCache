<?php
namespace LSDCache\Store;

class Apc implements StoreInterface {

  public function get($key) {
    return apc_fetch($key);
  }

  public function set($key, $value, $ttl = 0) {
    return apc_store($key, $value, $ttl);
  }

  public function getMulti($keys) {
    $result = apc_fetch($keys);
    return empty($result) ? array() : $result;
  }

  public function setMulti($values, $ttl = 0) {
    throw new Exception('Not yet implemented');
    // when setMulti used, apc returns ($key1 => -1, $key2 => -1) array

    // $result = apc_store($values, null, $ttl);
    // foreach ($result as $key => $value) {
    //   if ($value < 0) unset($result[$key]);
    // }
    // return empty($result);
  }

  public function add($key, $value, $ttl = 0) {
    return apc_add($key, $value, $ttl);
  }

  public function delete($key) {
    return apc_delete($key);
  }

  public function inc($key, $step=1) {
    return apc_inc($key, $step);
  }
}
