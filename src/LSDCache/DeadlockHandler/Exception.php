<?php
namespace LSDCache\DeadlockHandler;
use LSDCache\Cache;

class Exception implements DeadlockHandlerInterface {

  public function handle(Cache $cache, $key) {
    throw new Exception('"'.$key.'" locked and no value to return');
  }

}
