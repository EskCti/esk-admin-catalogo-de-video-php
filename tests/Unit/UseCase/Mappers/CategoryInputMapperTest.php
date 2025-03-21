<?php

namespace Tests\Unit\UseCase\Category\Mappers;

use Core\Domain\Entity\Category;
use Core\Domain\ValueObject\BooleanValue;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\UseCase\Mappers\Category\CategoryInputMapper;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryUpdateInputDto;
use Mockery;
use PHPUnit\Framework\TestCase;

class CategoryInputMapperTest extends TestCase
{
  public function testFromCreateDto()
  {
    // Arrange
    $name = 'Movies Action';
    $description = 'Action movies description';
    $isActive = true;

    $input = new CategoryCreateInputDto(
      name: $name,
      description: $description,
      isActive: $isActive
    );

    // Act
    $category = CategoryInputMapper::fromCreateDto($input);

    // Assert
    $this->assertInstanceOf(Category::class, $category);
    $this->assertEquals($name, $category->getName());
    $this->assertEquals($description, $category->getDescription());
    $this->assertEquals($isActive, $category->isActive());
  }

  public function testFromUpdateDto()
  {
    // Arrange
    $name = 'Movies Action';
    $description = 'Action movies description';
    $isActive = false;

    $input = new CategoryUpdateInputDto(
      id: 'any-id',
      name: $name,
      description: $description,
      isActive: $isActive
    );

    $existingCategory = Mockery::mock(Category::class);
    $existingCategory->shouldReceive('update')
      ->once()
      ->with(Mockery::type(SimpleName::class), Mockery::type(SimpleText::class))
      ->andReturnSelf();

    $existingCategory->shouldReceive('isActive')
      ->once()
      ->andReturn(true);

    $existingCategory->shouldReceive('deactivate')
      ->once();

    // Act
    $category = CategoryInputMapper::fromUpdateDto($input, $existingCategory);

    // Assert
    $this->assertSame($existingCategory, $category);
  }

  public function testFromUpdateDtoActivateCategory()
  {
    // Arrange
    $input = new CategoryUpdateInputDto(
      id: 'any-id',
      name: 'Movies Action',
      description: 'Action movies description',
      isActive: true
    );

    $existingCategory = Mockery::mock(Category::class);
    $existingCategory->shouldReceive('update')->andReturnSelf();
    $existingCategory->shouldReceive('isActive')->andReturn(false);
    $existingCategory->shouldReceive('activate')->once();

    // Act
    $category = CategoryInputMapper::fromUpdateDto($input, $existingCategory);

    // Assert
    $this->assertSame($existingCategory, $category);
  }

  protected function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }
}