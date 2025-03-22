<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryListInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\UseCase\Mappers\Category\CategoryOutputMapper;

class ListCategoryUseCase
{
  protected $repository;

  public function __construct(CategoryRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function execute(CategoryListInputDto $input): array
  {
    $categories = $this->repository->findAll(
      filter: $input->filter ?? '',
      order: $input->order ?? 'DESC'
    );

    return array_map(
      fn($category) => CategoryOutputMapper::toDto($category),
      $categories
    );
  }
}