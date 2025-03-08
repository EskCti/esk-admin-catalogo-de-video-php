<?php

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

class DomainValidation
{
  public static function notNull(string $value, string $exceptMessage = null)
  {
    if (empty($value)) {
      throw new EntityValidationException($exceptMessage ?? "Should not empty");
    }
  }

  public static function strMaxLength(string $value, int $length = 255, string $exceptMessage = null)
  {
    if (strlen($value) > $length) {
      throw new EntityValidationException($exceptMessage ?? "");
    }
  }
}
