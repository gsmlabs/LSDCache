<?php
class Cache_Value {

  private $value;
  private $expiration_timestamp;

  public function  __construct($value, $expiration_timestamp = NULL) {
    $this->value = $value;
    $this->expiration_timestamp = $expiration_timestamp;
  }

  public function getValue() {
    return $this->value;
  }

  public function getExpirationTimestamp() {
    return $this->expiration_timestamp;
  }

  public function isExpired($now_timestamp = NULL) {
    $now_timestamp = $now_timestamp ?: time();
    return ($now_timestamp > $this->getExpirationTimestamp());
  }

}
