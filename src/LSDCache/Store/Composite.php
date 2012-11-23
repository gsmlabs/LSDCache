<?php
namespace LSDCache\Store;

class Composite implements StoreInterface {

  protected $stores = array();

  public function __construct(array $stores) {
    foreach ($stores as $store) {
      $this->addStore($store);
    }
  }

  public function addStore(StoreInterface $store) {
    $this->stores[] = $store;
  }

  public function get($key) {
    $previous_stores = array();

    foreach ($this->stores as $store) {
      $value = $store->get($key);
      if ($value) {
        foreach ($previous_stores as $store) {
          $store->set($key, $value->getValue(), $value->getExpirationTimestamp() - time());
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
    foreach ($this->stores as $store) {
      $store->inc($key, $step);
    }
  }

}
