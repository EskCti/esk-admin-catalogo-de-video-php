<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Mappers\Category\CategoryInputMapper;
use Core\UseCase\Mappers\Category\CategoryOutputMapper;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;

class CreateCategoryUseCase
{
  protected $repository;

  public function __construct(CategoryRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function execute(CategoryCreateInputDto $input): CategoryOutputDto
  {
    // Converte DTO de entrada para entidade utilizando o mapper
    $category = CategoryInputMapper::fromCreateDto($input);

    // Persiste a entidade
    $newCategory = $this->repository->insert($category);

    // Converte a entidade para DTO de saída utilizando o mapper
    return CategoryOutputMapper::toCreateDto($newCategory);
  }
}