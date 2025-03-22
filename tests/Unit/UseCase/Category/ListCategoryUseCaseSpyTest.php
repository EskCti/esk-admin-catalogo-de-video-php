<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Category\ListCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryListInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use PHPUnit\Framework\TestCase;
use Mockery;
use Mockery\MockInterface;

class ListCategoryUseCaseSpyTest extends TestCase
{
  /**
   * @var CategoryRepositoryInterface|MockInterface
   */
  private $repository;

  /**
   * @var ListCategoryUseCase
   */
  private $useCase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->repository = Mockery::spy(CategoryRepositoryInterface::class);
    $this->useCase = new ListCategoryUseCase($this->repository);
  }

  public function testShouldCallFindAllWithCorrectParameters()
  {
    // Arrange
    $category = Mockery::mock(Category::class, [
      'getId' => 'uuid-1',
      'getName' => 'Category 1',
      'getDescription' => 'Description 1',
      'isActive' => true,
      'getCreatedAt' => '2023-01-01T00:00:00'
    ]);

    $this->repository->shouldReceive('findAll')
      ->with('test-filter', 'ASC')
      ->andReturn([$category]);

    $input = new CategoryListInputDto(
      filter: 'test-filter',
      order: 'ASC'
    );

    // Act
    $result = $this->useCase->execute($input);

    // Assert
    $this->repository->shouldHaveReceived('findAll')
      ->with('test-filter', 'ASC')
      ->once();

    $category->shouldHaveReceived('getId')->once();
    $category->shouldHaveReceived('getName')->once();
    $category->shouldHaveReceived('getDescription')->once();
    $category->shouldHaveReceived('isActive')->once();
    $category->shouldHaveReceived('getCreatedAt')->once();

    $this->assertInstanceOf(CategoryOutputDto::class, $result[0]);
  }

  public function testShouldCallFindAllWithDefaultParametersWhenInputIsEmpty()
  {
    // Arrange
    $this->repository->shouldReceive('findAll')
      ->with('', 'DESC')
      ->andReturn([]);

    $input = new CategoryListInputDto();

    // Act
    $result = $this->useCase->execute($input);

    // Assert
    $this->repository->shouldHaveReceived('findAll')
      ->with('', 'DESC')
      ->once();

    // Verificações adicionais para o resultado
    $this->assertIsArray($result);
    $this->assertEmpty($result);
    $this->assertEquals([], $result);
  }

  public function testShouldProcessEachCategoryReturnedFromRepository()
  {
    // Arrange
    $categories = [
      Mockery::mock(Category::class, [
        'getId' => 'uuid-1',
        'getName' => 'Category 1',
        'getDescription' => 'Description 1',
        'isActive' => true,
        'getCreatedAt' => '2023-01-01T00:00:00'
      ]),
      Mockery::mock(Category::class, [
        'getId' => 'uuid-2',
        'getName' => 'Category 2',
        'getDescription' => 'Description 2',
        'isActive' => false,
        'getCreatedAt' => '2023-01-01T00:00:00'
      ])
    ];

    $this->repository->shouldReceive('findAll')
      ->andReturn($categories);

    $input = new CategoryListInputDto();

    // Act
    $result = $this->useCase->execute($input);

    // Assert
    $this->assertCount(2, $result);

    // Verificar cada chamada de método nas entidades de categoria mock
    foreach ($categories as $index => $category) {
      $category->shouldHaveReceived('getId')->once();
      $category->shouldHaveReceived('getName')->once();
      $category->shouldHaveReceived('getDescription')->once();
      $category->shouldHaveReceived('isActive')->once();
      $category->shouldHaveReceived('getCreatedAt')->once();

      $this->assertInstanceOf(CategoryOutputDto::class, $result[$index]);
    }
  }

  public function testShouldReturnEmptyArrayWhenRepositoryReturnsEmpty()
  {
    // Arrange
    $this->repository->shouldReceive('findAll')
      ->andReturn([]);

    $input = new CategoryListInputDto();

    // Act
    $result = $this->useCase->execute($input);

    // Assert
    $this->assertIsArray($result);
    $this->assertEmpty($result);
    $this->repository->shouldHaveReceived('findAll')->once();
  }

  public function testShouldHandleNullFilterAndOrder()
  {
    // Arrange
    $this->repository->shouldReceive('findAll')
      ->with('', 'DESC')
      ->andReturn([]);

    // Input com valores null para simular ausência de parâmetros
    $input = new CategoryListInputDto();

    // Act
    $result = $this->useCase->execute($input);

    // Assert
    $this->repository->shouldHaveReceived('findAll')
      ->with('', 'DESC')
      ->once();

    // Verificações adicionais para o resultado
    $this->assertIsArray($result);
    $this->assertEmpty($result);
    $this->assertEquals([], $result);

  }

  protected function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }
}