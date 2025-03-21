<?php

namespace Core\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\ValueObject\BooleanValue;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryCreateOutputDto;

class CreateCategoryUseCase
{
  protected $repository;
  public function __construct(CategoryRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function execute(CategoryCreateInputDto $input): CategoryCreateOutputDto
  {
    $category = new Category(
      name: SimpleName::create($input->name),
      description: SimpleText::create($input->description, 0, 255),
      isActive: new BooleanValue($input->isActive)
    );
    $newCategory = $this->repository->insert($category);

    return new CategoryCreateOutputDto(
      id: $newCategory->getId(),
      name: $newCategory->getName(),
      description: $newCategory->getDescription(),
      is_active: $newCategory->isActive(),
    );
  }
}