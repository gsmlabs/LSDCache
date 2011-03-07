<?php

class Cache_DeadlockHandler_Exception implements Cache_DeadlockHandler {

  public function handle(Cache_Cache $cache, $key) {
    throw new Exception('"'.$key.'" locked and no value to return');
  }

}
