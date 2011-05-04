<?php
require_once dirname(__FILE__).'/../src/Cache/Cache.php';
require_once dirname(__FILE__).'/../src/Cache/Value.php';

class ValueTest extends PHPUnit_Framework_TestCase {

  public function testGetValue() {
    $value = 'lewandowski';
    $vo = new Cache_Value($value);
    $this->assertEquals($value, $vo->getValue());
  }

  public function testGetTtl() {
    $value = 'błaszczykowski';
    $ttl = 3600;
    $vo = new Cache_Value($value, $ttl);
    $this->assertEquals($ttl, $vo->getTtl());
  }

  public function testGetGenerationTime() {
    $value = 'błaszczykowski';
    $ttl = 3600;
    $gtime = 30;
    $vo = new Cache_Value($value, $ttl, $gtime);
    $this->assertEquals($gtime, $vo->getGenerationTime());
  }

  public function testGetExpirationTime() {
    $value = 'piszczek';
    $ttl = 3600;
    $expiration_timestamp = time() + $ttl;
    $vo = new Cache_Value($value, $ttl);
    $this->assertEquals($expiration_timestamp, $vo->getExpirationTimestamp());
  }

  public function testIfNotProvidedGenerationTimeEqualsToTtl() {
    $value = 'barrios';
    $ttl = 3600;
    $vo = new Cache_Value($value, $ttl);
    $this->assertEquals($ttl, $vo->getGenerationTime());
  }

  public function testIsExpired() {
    $value = 'kagawa';

    $ttl = -1;
    $vo = new Cache_Value($value, $ttl);
    $this->assertTrue($vo->isExpired());

    $ttl = 3600;
    $vo = new Cache_Value($value, $ttl);
    $this->assertFalse($vo->isExpired());
  }

}
