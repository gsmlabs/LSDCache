Cache
=====

Rationale
---------

The library is meant mainly to handle dogpile effect.

If multiple requests come at roughly same time, and the cache is expired,
all the requests will try to recalculate the value, which can bring down
the servers if the operation is time and resources consuming. This is dogpile
effect (aka clobbering updates, stampending requests).

Locking gotchas
---------------

When the data has expired but lock cannot be aquired, the cached data is returned
immediately (this means that outdated data may be returned, but only one request
- the one doing the actual recalculation - will have a delay).

However, if no outdated is present, there's a deadlock. By default exception is
thrown, but it can be changed using Cache_DeadlockHandler.

Make sure that you check the freshness of the data a second time after you
aquired the lock, because otherwise you might recalculate the data multiple
times in the following scenario:
- request A comes in, it aquires the lock to recalculate the data,
- request B comes in, tries to aquire the lock and enters a waiting state,
- request A finishes,
- request B aquires the lock, but now the cache contains fresh data (written
  by request A), thus there is no reason to recalculate it.
It can be done via custom Cache_DeadlockHandler.

Usage
-----

$cache = new Cache_Cache(new Cache_Store_Apc());
if ($cache->get($key)) {
  // regenerate value
  $cache->set($key, $value, $ttl);
}

$cache = new Cache_Cache(new Cache_Store_Apc());
$cache->getOrSet($key, callback, $ttl);


Tests
-----

phpunit --colors test/
phpunit --testdox test/


Possible improvements
---------------------

- Cache versioning may be implemented to improve value replacing.
- Regeneration process may be moved outside of the request-response cycle
  (eg. in cronjob), so data is always served from cache.

ToDo/Issues
-----------

- how to handle consistently ttl=0 (eg. memcache caches it forever)
- key namespaces/prefixes
