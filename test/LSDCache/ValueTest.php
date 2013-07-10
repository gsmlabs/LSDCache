<?php
namespace LSDCache\Tests;
use LSDCache\Value;

class ValueTest extends \PHPUnit_Framework_TestCase {

  public function testGetValue() {
    $value = 'lewandowski';
    $vo = new Value($value);
    $this->assertEquals($value, $vo->getValue());
  }

  public function testGetTtl() {
    $value = 'błaszczykowski';
    $ttl = 3600;
    $vo = new Value($value, $ttl);
    $this->assertEquals($ttl, $vo->getTtl());
  }

  public function testGetExpirationTime() {
    $value = 'piszczek';
    $ttl = 3600;
    $expiration_timestamp = time() + $ttl;
    $vo = new Value($value, $ttl);
    $this->assertEquals($expiration_timestamp, $vo->getExpirationTimestamp());
  }

  public function testIsExpired() {
    $value = 'kagawa';

    $ttl = -1;
    $vo = new Value($value, $ttl);
    $this->assertTrue($vo->isExpired());

    $ttl = 3600;
    $vo = new Value($value, $ttl);
    $this->assertFalse($vo->isExpired());
  }

}
