<?php
interface Cache_DeadlockHandler {

  public function handle(Cache_Cache $cache, $key);

}