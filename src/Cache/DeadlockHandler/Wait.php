<?php

class Cache_DeadlockHandler_Wait implements Cache_DeadlockHandler {
  private $waiting_time, $max_tries;
  private $counters = array();
  
  /**
   * @param type $waiting_time Time (seconds) to wait before trying to get value again.
   * @param type $max_tries Max number of tries.
   */
  public function __construct($waiting_time, $max_tries) {
    $this->waiting_time = $waiting_time;
    $this->max_tries = $max_tries;
  }

  public function handle(Cache_Cache $cache, $key) {
    sleep($this->waiting_time);
    if (!isset($this->counters[$key])) {
      $this->counters[$key] = 0;
    }

    $this->counters[$key] += 1;

    if ($this->counters[$key] > $this->max_tries) {
      return false;
    }

    return $cache->get($key);
  }

}
