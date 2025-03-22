<?php

namespace Core\UseCase\Mappers\Category;

use Core\Domain\Entity\Category;
use Core\Domain\ValueObject\BooleanValue;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryUpdateInputDto;

class CategoryInputMapper
{
  /**
   * Converte um DTO de criação para uma entidade Category
   */
  public static function fromCreateDto(CategoryCreateInputDto $input): Category
  {
    return new Category(
      name: SimpleName::create($input->name),
      description: SimpleText::create($input->description, 0, 255),
      isActive: new BooleanValue($input->isActive)
    );
  }

  /**
   * Converte um DTO de atualização para uma entidade Category
   * Observação: Este método seria implementado quando houver um DTO de atualização
   */
  public static function fromUpdateDto(CategoryUpdateInputDto $input, Category $existingCategory): Category
  {
    $existingCategory->update(
      name: SimpleName::create($input->name),
      description: SimpleText::create($input->description, 0, 255)
    );

    if ($input->isActive && !$existingCategory->isActive()) {
      $existingCategory->activate();
    } elseif (!$input->isActive && $existingCategory->isActive()) {
      $existingCategory->deactivate();
    }

    return $existingCategory;
  }
}