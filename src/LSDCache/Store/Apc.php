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
    $result = apc_store($values, null, $ttl);
    foreach ($result as $key => $value) {
      if ($value < 0) unset($result[$key]);
    }
    return empty($result);
  }

  public function add($key, $value, $ttl = 0) {
    return apc_add($key, $value, $ttl);
  }

  public function delete($key) {
    return apc_delete($key);
  }

  public function inc($key, $step = 1) {
    return apc_inc($key, $step);
  }

  public function isSupported() {
    return extension_loaded('apc');
  }

  public function isStoreRunning() {
    if (0 === (int)\ini_get('apc.shm_size')) {
      return false;
    }

    if ('cli' === \php_sapi_name()) {
      return (bool)\ini_get('apc.enable_cli');
    }
    return (bool)\ini_get('apc.enabled');
  }
}
