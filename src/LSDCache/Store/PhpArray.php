<?php
namespace LSDCache\Store;

class PhpArray implements StoreInterface {

  private $values = array();
  private $expiration_timestamps = array();

  public function get($key) {
    if (!isset($this->values[$key]) || !isset($this->expiration_timestamps[$key])) {
      return false;
    }

    if (time() > $this->expiration_timestamps[$key]) {
      unset($this->values[$key]);
      return false;
    }

    return $this->values[$key];
  }

  public function set($key, $value, $ttl = 0) {
    $this->values[$key] = $value;
    if ($ttl == 0) {
      $ttl = 999999999; // stored forever
    }
    $this->expiration_timestamps[$key] = time() + $ttl;
    return true;
  }

  public function getMulti($keys) {
    foreach($keys as $key) {
      // If key has no expiration set, skip this step
      if (!isset($this->values[$key]) || !isset($this->expiration_timestamps[$key])) {
        continue;
      }
      // Or unset if data is no longer valid
      if (time() > $this->expiration_timestamps[$key]) {
        unset($this->values[$key]);
      }
    }
    // Transform $keys to $key => false associative array (for comparision purposes)
    $data_to_fetch = array_fill_keys(array_values($keys), false);
    // Compare arrays and get all existing data from cache
    $fetched_data = array_intersect_key($this->values, $data_to_fetch);

    return $fetched_data;
  }

  public function setMulti($values, $ttl = 0) {

     $this->values = array_merge($this->values, $values);
     if ($ttl == 0) {
       $ttl = 999999999; // stored forever
     }
     foreach($values as $key => $value) {
       $this->expiration_timestamps[$key] = time() + $ttl;
     }
     return true;
  }

  public function add($key, $value, $ttl = 0) {
    if (!isset($this->values[$key])) {
      return $this->set($key, $value, $ttl);
    }
    return false;
  }

  public function delete($key) {
    if (isset($this->values[$key])) {
      unset($this->values[$key]);
      return true;
    }
    return false;
  }

  public function inc($key, $step=1) {
    $orgval = $this->get($key);
    if($orgval === false) {
      return false;
    }
    $newval = (int)$orgval + (int)$step;
    $this->set($key, $newval);

    return $newval;
  }
}
