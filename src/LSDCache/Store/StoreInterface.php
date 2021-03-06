<?php
namespace LSDCache\Store;

interface StoreInterface {

  /**
   * @param string $key
   * @return mixed Value or FALSE if not exists
   */
  public function get($key);

  /**
   * @param string $key
   * @param mixed $value
   * @param int $ttl
   * @return bool
   */
  public function set($key, $value, $ttl = 0);

  /**
   * @param array $keys
   * @return array Found key-value pairs as an assoc array.
   */
  public function getMulti($keys);

  /**
   * @param array $values
   * @param int $ttl
   * @return bool
   */
  public function setMulti($values, $ttl = 0);

  /**
   * @param string $key
   * @param mixed $value
   * @param int $ttl
   * @return bool Returns false if such key already exist.
   */
  public function add($key, $value, $ttl = 0);

  /**
   * @param string $key
   * @return bool
   */
  public function delete($key);

  /**
   * @param string $key
   * @param int $step
   * @return int New incremented value or FALSE if not exists
   */
  public function inc($key, $step = 1);

  /**
   * @return bool
   */
  public function isSupported();

  /**
   * @return bool
   */
  public function isStoreRunning();
}
