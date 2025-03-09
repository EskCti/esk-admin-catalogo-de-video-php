<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\BooleanValue;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\Domain\ValueObject\Uuid;

class Category
{
  use MethodsMagicsTrait;

  public function __construct(
    protected Uuid|string $id = '',
    protected string|SimpleName $name = '',
    protected string|SimpleText $description = '',
    protected bool|BooleanValue $isActive = true,
  ) {
    $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
    $this->name = is_string($this->name) ? SimpleName::create($this->name, 3, 255, 'name', 'Category') : $this->name;
    $this->description = is_string($this->description) ?
      SimpleText::create($this->description, 0, 1000, 'description', 'Category') : $this->description;
    $this->isActive = is_bool($this->isActive) ? new BooleanValue($this->isActive) : $this->isActive;
  }

  public function activate(): void
  {
    $this->isActive = BooleanValue::true();
  }

  public function deactivate(): void
  {
    $this->isActive = BooleanValue::false();
  }

  public function update(
    string|SimpleName $name,
    string|SimpleText $description = ''
  ) {
    $this->name = is_string($name) ? SimpleName::create($name, 3, 255, 'name', 'Category') : $name;
    $this->description = is_string($description) ?
      SimpleText::create($description, 0, 1000, 'description', 'Category') : $description;
  }

  public function getId(): string
  {
    return (string) $this->id;
  }

  public function getName(): string
  {
    return (string) $this->name;
  }

  public function getDescription(): string
  {
    return (string) $this->description;
  }

  public function isActive(): bool
  {
    return $this->isActive->isTrue();
  }
}