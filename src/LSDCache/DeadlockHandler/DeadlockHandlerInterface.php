<?php
namespace LSDCache\DeadlockHandler;
use LSDCache\Cache;

interface DeadlockHandlerInterface {

  public function handle(Cache $cache, $key);

}
