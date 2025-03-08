<?php

namespace Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Core\Domain\Entity\Category;

class CategoryUnitTest extends TestCase
{
  public function testAttributes() {
    $category = new Category(
      id: 'ssss',
      name: 'New category',
      description: 'New description',
      isActive: true
    );

    $this->assertEquals('New category', $category->name);
    $this->assertEquals('New description', $category->description);
    $this->assertEquals(true, $category->isActive);    
  }

  public function testActivated() {
    $category = new Category(
      name: 'New Category',
      isActive: false,
    );

    $this->assertFalse($category->isActive);
    $category->activate();
    $this->assertTrue($category->isActive);
  }

  public function testDeactivated() {
    $category = new Category(
      name: 'New Category',
      isActive: true,
    );

    $this->assertTrue($category->isActive);
    $category->deactivate();
    $this->assertFalse($category->isActive);
  }
}