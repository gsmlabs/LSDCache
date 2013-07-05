<?php
namespace LSDCache\Store;

class Memcache implements StoreInterface {

  private $memcache;

  const MAX_TTL = 2592000;

  public function __construct(\Memcache $memcache) {
    $this->memcache = $memcache;
  }

  /**
   * Hash key due to memcache key length limit (250 chars).
   * 
   * See ticket #3702 for details.
   * @param string $key
   * @return string
   */
  private function prepareKey($key) {
    return sha1($key);
  }
  
  public function get($key) {
    $key = $this->prepareKey($key);
    return $this->memcache->get($key);
  }

  public function set($key, $value, $ttl = 0) {
    $key = $this->prepareKey($key);
    $ttl = $this->prepareTtl($ttl);
    return $this->memcache->set($key, $value, 0, $ttl);
  }

  public function getMulti($keys) {
    $prepared_keys = array();
    foreach ($keys as $key) {
      $prepared_keys[] = $this->prepareKey($key);
    }

    $raw_result = $this->memcache->get($prepared_keys);

    $result = array();

    foreach ($keys as $key) {
      $prepared_key = $this->prepareKey($key);

      if (isset($raw_result[$prepared_key])) {
        $result[$key] = $raw_result[$prepared_key];
      }
    }

    return $result;
  }

  public function setMulti($values, $ttl = 0) {
    $result = 1;
    $ttl = $this->prepareTtl($ttl);
    foreach($values as $key => $value) {
      $result &= $this->set($key, $value, $ttl);
    }
    return (bool)$result;
  }

  public function add($key, $value, $ttl = 0) {
    $key = $this->prepareKey($key);
    $ttl = $this->prepareTtl($ttl);
    return $this->memcache->add($key, $value, 0, $ttl);
  }

  public function delete($key) {
    $key = $this->prepareKey($key);
    // workaround for memcache "delete" various issue
    // for example see comments for http://php.net/manual/en/memcache.delete.php
    return $this->memcache->set($key, false, 0, -1);
  }

  public function inc($key, $step = 1) {
    $key = $this->prepareKey($key);
    return $this->memcache->increment($key, $step);
  }

  public function isSupported() {
    return extension_loaded('memcache');
  }

  /**
   * When TTL exceeds 2592000 (30 days) then ttl must be set in Unix timestamp
   * 
   * See ticket #4022 for details.
   * @param string $key
   * @return string
   */
  public function prepareTtl($ttl) {
    if (self::MAX_TTL < $ttl) {
      return (time() + $ttl);
    }
    return $ttl;
  }

  public function isStoreRunning() {
    foreach ($this->memcache->getExtendedStats() as $serverKey => $serverInfo) {
      $host = substr($serverKey, 0, (strpos($serverKey, ':')));
      $port = substr($serverKey, (strpos($serverKey, ':') + 1));

      $memcache = new \Memcache;
      if ($memcache->connect($host, $port)) {
        $memcache->close();
        return true;
      }
    }
    return false;
  }
}
