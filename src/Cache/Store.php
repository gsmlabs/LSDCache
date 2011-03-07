<?php
interface Cache_Store {

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
   * @param string $key
   * @param mixed $value
   * @param int $ttl
   * @return bool
   */
  public function add($key, $value, $ttl = 0);

  /**
   * @param string $key
   * @return bool
   */
  public function delete($key);

}
