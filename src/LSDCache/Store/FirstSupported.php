<?php
namespace LSDCache\Store;

/**
 * Aggregates multiple stores (call methods for first supported store).
 */
class FirstSupported implements StoreInterface {

  protected $store;

  public function __construct(array $stores) {
    foreach ($stores as $store) {
      $this->setStore($store);
    }
  }

  /**
   * Set store (if supported or not already set).
   *
   * @return bool
   */
  public function setStore(StoreInterface $store) {
    if ($this->store) {
      return false;
    }

    if (!$store->isSupported() || !$store->isStoreRunning()) {
      return false;
    }

    $this->store = $store;

    return true;
  }

  public function get($key) {
    return $this->store->get($key);
  }

  public function set($key, $value, $ttl = 0) {
    return $this->store->set($key, $value, $ttl);
  }

  public function getMulti($keys) {
    return $this->store->getMulti($keys);
  }

  public function setMulti($values, $ttl = 0) {
    return $this->store->setMulti($values, $ttl);
  }

  public function add($key, $value, $ttl = 0) {
    return $this->store->add($key, $value, $ttl);
  }

  public function delete($key) {
    return $this->store->delete($key);
  }

  public function inc($key, $step = 1) {
    return $this->store->inc($key, $step);
  }

  public function isSupported() {
    return true;
  }

  public function isStoreRunning() {
    return true;
  }
}
