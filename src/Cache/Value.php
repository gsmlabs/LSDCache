<?php
class Cache_Value {

  private $value;
  private $ttl;
  private $generation_time;
  private $expiration_timestamp;

  public function  __construct($value, $ttl = 0, $generation_time = NULL) {
    $this->value = $value;
    $this->ttl = $ttl;
    $this->generation_time = $generation_time ? $generation_time : $ttl;
    $this->expiration_timestamp = time() + $ttl;
  }

  public function getValue() {
    return $this->value;
  }

  public function getTtl() {
    return $this->ttl;
  }

  public function getGenerationTime() {
    return $this->generation_time;
  }

  public function getExpirationTimestamp() {
    return $this->expiration_timestamp;
  }

  public function isExpired($now_timestamp = NULL) {
    if ($now_timestamp === NULL) {
      $now_timestamp = time();
    }
    return ($now_timestamp > $this->getExpirationTimestamp());
  }

}
