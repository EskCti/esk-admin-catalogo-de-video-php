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
  
  public function testUpdate() 
  {
    $uuid = 'uuid.value';

    $category = new Category(
      id: $uuid,
      name: 'New category',
      description: 'New description',
      isActive: true
    );

    $category->update(
      name: 'new_name',
      description: 'new_desc',
    );

    $this->assertEquals('new_name', $category->name);
    $this->assertEquals('new_desc', $category->description);
  }
} 