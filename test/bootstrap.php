<?php
require_once __DIR__.'/../src/LSDCache/Cache.php';
require_once __DIR__.'/../src/LSDCache/Value.php';
require_once __DIR__.'/../src/LSDCache/Store/StoreInterface.php';
require_once __DIR__.'/../src/LSDCache/Store/PhpArray.php';
require_once __DIR__.'/../src/LSDCache/Store/Apc.php';
require_once __DIR__.'/../src/LSDCache/Store/Memcache.php';
require_once __DIR__.'/../src/LSDCache/Store/Memcached.php';
require_once __DIR__.'/../src/LSDCache/DeadlockHandler/DeadlockHandlerInterface.php';
require_once __DIR__.'/../src/LSDCache/DeadlockHandler/Exception.php';
require_once __DIR__.'/../src/LSDCache/DeadlockHandler/FalseValue.php';
require_once __DIR__.'/../src/LSDCache/DeadlockHandler/Wait.php';

require_once __DIR__.'/LSDCache/Store/StoreTest.php';
