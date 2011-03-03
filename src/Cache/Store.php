<?php
interface Cache_Store {

  public function set($key, $value, $ttl = 0);
  public function get($key);
  public function add($key, $value, $ttl = 0);

}
