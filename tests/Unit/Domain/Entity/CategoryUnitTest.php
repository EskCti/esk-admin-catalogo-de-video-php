<?php

namespace Tests\Unit\Domain\Entity;

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\BooleanValue;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Throwable;

class CategoryUnitTest extends TestCase
{
  public function testAttributes()
  {
    $category = new Category(
      name: 'New category',
      description: 'New description',
      isActive: true
    );

    $this->assertNotEmpty($category->getId());
    $this->assertTrue(RamseyUuid::isValid($category->getId()));
    $this->assertEquals('New category', $category->getName());
    $this->assertEquals('New description', $category->getDescription());
    $this->assertTrue($category->isActive());
  }

  public function testImmutability()
  {
    $uuid = RamseyUuid::uuid4()->toString();
    $category = new Category(
      id: $uuid,
      name: 'Test Category',
      description: 'Test Description'
    );

    $this->assertEquals($uuid, $category->getId());
    $this->assertInstanceOf(Uuid::class, $category->id);
    $this->assertInstanceOf(SimpleName::class, $category->name);
    $this->assertInstanceOf(SimpleText::class, $category->description);
    $this->assertInstanceOf(BooleanValue::class, $category->isActive);
  }

  public function testActivateAndDeactivate()
  {
    $category = new Category(
      name: 'New Category',
      isActive: false,
    );

    $this->assertFalse($category->isActive());
    $category->activate();
    $this->assertTrue($category->isActive());

    $category->deactivate();
    $this->assertFalse($category->isActive());
  }

  public function testUpdate()
  {
    $uuid = RamseyUuid::uuid4()->toString();
    $category = new Category(
      id: $uuid,
      name: 'New category',
      description: 'New description',
      isActive: true
    );

    $category->update(
      name: 'Updated name',
      description: 'Updated description',
    );

    $this->assertEquals($uuid, $category->getId());
    $this->assertEquals('Updated name', $category->getName());
    $this->assertEquals('Updated description', $category->getDescription());
    $this->assertTrue($category->isActive());
  }

  public function testExceptionInvalidName()
  {
    $this->expectException(EntityValidationException::class);
    new Category(
      name: 'Ne',
      description: 'New Desc',
    );
  }

  public function testExceptionInvalidDescription()
  {
    $longDescription = str_repeat('a', 1001);

    $this->expectException(EntityValidationException::class);
    new Category(
      name: 'New Category',
      description: $longDescription,
    );
  }

  public function testExceptionInvalidSimpleName()
  {
    $this->expectException(EntityValidationException::class);
    new Category(
      name: 'InvalidName@!',
      description: 'New Description'
    );
  }

  public function testMagicMethods()
  {
    $category = new Category(
      name: 'Test Category',
      description: 'Test Description'
    );

    // Test magic getter
    $this->assertInstanceOf(Uuid::class, $category->id);
    $this->assertInstanceOf(SimpleName::class, $category->name);
    $this->assertInstanceOf(SimpleText::class, $category->description);
    $this->assertInstanceOf(BooleanValue::class, $category->isActive);

    // Test magic method ID
    $this->assertEquals($category->getId(), $category->id());
  }
}