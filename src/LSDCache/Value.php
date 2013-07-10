<?php
namespace LSDCache;

class Value {

  private $value;
  private $ttl;
  private $generation_time;
  private $expiration_timestamp;

  public function  __construct($value, $ttl = 0) {
    if (isset($params[2])) {
      \trigger_error("Param 'genaration_time' is not supported anymore", E_DEPRECATED);
    }
    $this->value = $value;
    $this->ttl = $ttl;
    $this->expiration_timestamp = time() + $ttl;
  }

  public function getValue() {
    return $this->value;
  }

  public function getTtl() {
    return $this->ttl;
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
