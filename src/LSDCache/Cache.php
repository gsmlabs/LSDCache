<?php
namespace LSDCache;
use LSDCache\Value;

class Cache {

  private $store;

  private $active_locks = array();
  private $default_lock_ttl = 30;
  private $lock_key_suffix = '.lock';
  private $deadlock_handler;
  
  public function  __construct(Store\StoreInterface $store) {
    $this->store = $store;
    register_shutdown_function(array($this, 'unlockActiveLocks'));
  }

  public function getStore() {
    return $this->store;
  }

  public function set($key, $value, $ttl = 0, $generation_time = NULL) {
    $vo = new Value($value, $ttl, $generation_time);
    $result = $this->store->set($key, $vo, $vo->getTtl() + $vo->getGenerationTime());
    $this->unlock($key);
    return $result;
  }

  public function get($key) {
    $vo = $this->store->get($key);
    if ($this->isCacheValue($vo) && (!$vo->isExpired())) {
      return $vo->getValue();
    }

    $locked = $this->lock($key, ($this->isCacheValue($vo) ? $vo->getGenerationTime() : $this->default_lock_ttl));

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

  public function delete($key) {
    return $this->getStore()->delete($key);
  }

  public function setDefaultTtl($ttl) {
    $this->default_lock_ttl = (int)$ttl;
  }

  /**
   * @return bool
   */
  private function lock($key, $lock_ttl = NULL, $mark_as_active = true) {
    $locked = $this->store->add($this->lockKey($key), true, ($lock_ttl ? $lock_ttl : $this->default_lock_ttl));
    if ($locked && $mark_as_active) {
      $this->active_locks[$key] = true;
    }
    return $locked;
  }

  /**
   * @return bool
   */
  private function unlock($key) {
    if (isset($this->active_locks[$key])) {
      unset($this->active_locks[$key]);
    }
    return $this->store->delete($this->lockKey($key));
  }

  public function unlockActiveLocks() {
    foreach ($this->active_locks as $key => $v) {
      $this->unlock($key);
    }
  }

  public function lockKey($key) {
    return $key.$this->lock_key_suffix;
  }

  /**
   * @return bool
   */
  private function isCacheValue($vo) {
    return ($vo !== false && $vo instanceof \LSDCache\Value);
  }

  public function getDeadlockHandler() {
    if (!$this->deadlock_handler) {
      $this->deadlock_handler = new DeadlockHandler\FalseValue();
    }
    return $this->deadlock_handler;
  }

  public function setDeadlockHandler(DeadlockHandler\DeadlockHandlerInterface $deadlock_handler) {
    $this->deadlock_handler = $deadlock_handler;
  }

}
