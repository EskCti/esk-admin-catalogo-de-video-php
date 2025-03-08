<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;

class DomainValidationUnitTest extends TestCase
{
  public function testNotNull()
  {
    try {
      $value = '';
      DomainValidation::notNull($value);

      $this->assertTrue(false);
    } catch (\Throwable $th) {
      $this->assertInstanceOf(DomainValidation::class, $th);
    }
  }
}