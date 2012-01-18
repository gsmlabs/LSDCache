<?php
namespace LSDCache\DeadlockHandler;
use LSDCache\Cache;

class Wait implements DeadlockHandlerInterface {
  private $waiting_seconds, $max_tries;
  private $counters = array();
  
  /**
   * @param type $waiting_seconds Time (seconds) to wait before trying to get value again.
   * @param type $max_tries Max number of tries.
   */
  public function __construct($waiting_seconds, $max_tries) {
    $this->waiting_seconds = $waiting_seconds;
    $this->max_tries = $max_tries;
  }

  public function handle(Cache $cache, $key) {
    usleep($this->waiting_seconds * 1000000);
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
