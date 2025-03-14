<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\UseCase\Category\CreateCategoryUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;
use stdClass;

class CreateCategoryUseCaseTest extends TestCase
{
  public function testCreatedNewCategory()
  {
    $categoryId = Uuid::uuid4()->toString();
    $categoryName = SimpleName::create("Joao Silva");
    $this->mockEntity = Mockery::mock(Category::class, [
      $categoryId,
      $categoryName,
    ]);

    $this->mockRepo = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
    $this->mockRepo->shouldReceive('insert')->andReturn($this->mockEntity);

    $useCase = new CreateCategoryUseCase($this->mockRepo);
    $useCase->execute();

    $this->assertTrue(true);

    Mockery::close();
  }

}
