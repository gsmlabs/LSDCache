<?php
namespace LSDCache\Store;

use LSDCache\Value;

/**
 * Aggregates multiple stores (call methods for all of the stores).
 * Only supported stores are called.
 */
class Composite implements StoreInterface {

  protected $stores = array();

  public function __construct(array $stores) {
    foreach ($stores as $store) {
      $this->addStore($store);
    }
  }

  /**
   * Add store (if supported).
   *
   * @return bool
   */
  public function addStore(StoreInterface $store) {
    if ($store->isSupported()) {
      $this->stores[] = $store;
      return true;
    }
    return false;
  }

  public function get($key) {
    $previous_stores = array();

    foreach ($this->stores as $store) {
      $value = $store->get($key);
      if ($value) {
        foreach ($previous_stores as $store) {
          if ($value instanceof Value) {
            $store->set($key, $value->getValue(), $value->getExpirationTimestamp() - time());
          }
          else {
            $store->set($key, $value);
          }
        }
        return $value;
      }
      $previous_stores[] = $store;
    }

    return false;
  }

  public function set($key, $value, $ttl = 0) {
    foreach ($this->stores as $store) {
      $store->set($key, $value, $ttl);
    }
  }

  public function getMulti($keys) {
    foreach ($this->stores as $store) {
      $values = $store->getMulti($keys);
      if (!empty($values)) {
        return $values;
      }
    }

    return array();
  }

  public function setMulti($values, $ttl = 0) {
    $return_value = false;

    foreach ($this->stores as $store) {
      $result = $store->setMulti($values, $ttl);
      if ($result) {
        $return_value = true;
      }
    }

    return $return_value;
  }

  // at least one set -> true
  public function add($key, $value, $ttl = 0) {
    $return_value = false;

    foreach ($this->stores as $store) {
      $result = $store->add($key, $value, $ttl);
      if ($result) {
        $return_value = true;
      }
    }

    return $return_value;
  }

  public function delete($key) {
    foreach ($this->stores as $store) {
      $store->delete($key);
    }
  }

  public function inc($key, $step = 1) {
    $return_values = array();

    foreach ($this->stores as $store) {
      $return_values[] = $store->inc($key, $step);
    }

    return max($return_values);
  }

  public function isSupported() {
    return true;
  }

  public function isStoreRunning() {
    return true;
  }

}
