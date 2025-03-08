<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;
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
      $this->assertInstanceOf(EntityValidationException::class, $th);
    }
  }

  public function testStrMaxLength()
  {
    try {
      $value = 'Testes';
      DomainValidation::strMaxLength($value, 5, 'Custom Messagem');

      $this->assertTrue(false);
    } catch (\Throwable $th) {
      $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
    }
  }

  public function testStrMinLength()
  {
    try {
      $value = 'Te';
      DomainValidation::strMinLength($value, 3, 'Custom Messagem');

      $this->assertTrue(false);
    } catch (\Throwable $th) {
      $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
    }
  }

  public function testStrCanNullAndMaxLength()
  {
    try {
      $value = "";
      DomainValidation::strCanNullAndMaxLength($value, 5, 'Custom Messagem');

      $this->assertTrue(true);
    } catch (\Throwable $th) {
      $this->assertInstanceOf(EntityValidationException::class, $th, 'Custom Messagem');
    }
  }
}