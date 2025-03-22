<?php

namespace Core\UseCase\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\UseCase\DTO\Category\CategoryPaginateInputDto;
use Core\UseCase\DTO\Category\CategoryPaginateOutputDto;
use Core\UseCase\Mappers\Category\CategoryOutputMapper;

class PaginateCategoryUseCase
{
  protected $repository;

  public function __construct(CategoryRepositoryInterface $repository)
  {
    $this->repository = $repository;
  }

  public function execute(CategoryPaginateInputDto $input): CategoryPaginateOutputDto
  {
    $pagination = $this->repository->paginate(
      filter: $input->filter ?? '',
      order: $input->order ?? 'DESC',
      page: $input->page ?? 1,
      totalPage: $input->totalPage ?? 15
    );

    return $this->toOutput($pagination);
  }

  private function toOutput(PaginationInterface $pagination): CategoryPaginateOutputDto
  {
    $items = CategoryOutputMapper::toDtoCollection(
      array_map(
        fn($item) => $this->repository->toCategory($item),
        $pagination->items()
      )
    );

    return new CategoryPaginateOutputDto(
      items: $items,
      total: $pagination->total(),
      current_page: $pagination->currentPage(),
      last_page: $pagination->lastPage(),
      first_page: $pagination->firstPage(),
      per_page: $pagination->perPage(),
      to: $pagination->to(),
      from: $pagination->from()
    );
  }
}