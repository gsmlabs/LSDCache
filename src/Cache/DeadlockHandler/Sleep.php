<?php

class Cache_DeadlockHandler_Sleep implements Cache_DeadlockHandler {
  private $sleep, $max;
  private $counters = array();
  
  /**
   * @param type $sleep Time (seconds) to wait before trying to get value again.
   * @param type $max Max number of tries.
   */
  public function __construct($sleep, $max) {
    $this->sleep = $sleep;
    $this->max = $max;
  }

  public function handle(Cache_Cache $cache, $key) {
    sleep($this->sleep);
    if (!isset($this->counters[$key])) {
      $this->counters[$key] = 0;
    }
    
    if ($this->counters[$key] > $max) {
      return false;
    }
    
    $counter[$key] += 1;
    
    return $cache->get($key);
  }

}
