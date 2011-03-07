<?php

class Cache_DeadlockHandler_False implements Cache_DeadlockHandler {

  public function handle(Cache_Cache $cache, $key) {
    return false;
  }

}
