<?php

namespace Core\UseCase\DTO\Category;

class CategoryListInputDto
{
  public function __construct(
    public string $filter = '',
    public string $order = 'DESC'
  ) {
  }
}