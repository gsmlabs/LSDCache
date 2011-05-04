<?php
class Cache_Cache {

  private $store;

  private $lock_ttl = 30;
  private $lock_key_suffix = '.lock';
  private $deadlock_handler;
  
  public function  __construct(Cache_Store $store) {
    $this->store = $store;
  }

  public function getStore() {
    return $this->store;
  }

  public function set($key, $value, $ttl = 0, $generation_time = NULL) {
    $vo = new Cache_Value($value, $ttl, $generation_time);
    $result = $this->store->set($key, $vo, $vo->getTtl() + $vo->getGenerationTime());
    $this->unlock($key);
    return $result;
  }

  public function get($key) {
    $vo = $this->store->get($key);
    if ($this->isCacheValue($vo) && (!$vo->isExpired())) {
      return $vo->getValue();
    }

    $locked = $this->lock($key);

    if (!$locked) {
      if ($this->isCacheValue($vo)) {
        return $vo->getValue();
      }
      else {
        return $this->getDeadlockHandler()->handle($this, $key, $vo);
      }
    }

    return false;
  }

  public function getOrSet($key, $callback, $ttl = 0) {
    $result = $this->get($key);
    if ($result === false) {
      $value = call_user_func($callback);
      return $this->set($key, $value, $ttl);
    }
  }

  /**
   * @return bool
   */
  private function lock($key) {
    return $this->store->add($this->lockKey($key), true, $this->lock_ttl);
  }

  /**
   * @return bool
   */
  private function unlock($key) {    
    return $this->store->delete($this->lockKey($key));
  }

  public function lockKey($key) {
    return $key.$this->lock_key_suffix;
  }

  /**
   * @return bool
   */
  private function isCacheValue($vo) {
    return ($vo !== false && $vo instanceof Cache_Value);
  }

  public function getDeadlockHandler() {
    if (!$this->deadlock_handler) {
      $this->deadlock_handler = new Cache_DeadlockHandler_False();
    }
    return $this->deadlock_handler;
  }

  public function setDeadlockHandler(Cache_DeadlockHandler $deadlock_handler) {
    $this->deadlock_handler = $deadlock_handler;
  }
  
}
