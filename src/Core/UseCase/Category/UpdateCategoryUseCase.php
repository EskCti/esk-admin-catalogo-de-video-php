<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Mappers\Category\CategoryInputMapper;
use Core\UseCase\Mappers\Category\CategoryOutputMapper;
use Core\UseCase\DTO\Category\CategoryUpdateInputDto;
use Core\UseCase\DTO\Category\CategoryOutputDto;
use Core\Domain\Exception\NotFoundException;

class UpdateCategoryUseCase
{
  protected $repository;

  public function __construct(CategoryRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function execute(CategoryUpdateInputDto $input): CategoryOutputDto
  {
    // Busca a entidade no repositório
    $category = $this->repository->findById($input->id);

    // Verifica se a categoria existe
    if ($category === null) {
      throw new NotFoundException("Category not found");
    }

    // Atualiza a entidade usando o mapper
    $category = CategoryInputMapper::fromUpdateDto($input, $category);

    // Persiste a entidade atualizada
    $updatedCategory = $this->repository->update($category);

    // Converte a entidade para DTO de saída
    return CategoryOutputMapper::toDto($updatedCategory);
  }
}