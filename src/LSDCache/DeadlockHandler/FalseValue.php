<?php
namespace LSDCache\DeadlockHandler;
use LSDCache\Cache;

class FalseValue implements DeadlockHandlerInterface {

  public function handle(Cache $cache, $key) {
    return false;
  }

}
