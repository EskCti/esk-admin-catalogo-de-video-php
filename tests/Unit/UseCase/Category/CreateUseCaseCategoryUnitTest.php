<?php

namespace Tests\Unit\UseCase\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\ValueObject\BooleanValue;
use Core\Domain\ValueObject\SimpleName;
use Core\Domain\ValueObject\SimpleText;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryCreateInputDto;
use Core\UseCase\DTO\Category\CategoryCreateOutputDto;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateCategoryUseCaseTest extends TestCase
{
  /**
   * @dataProvider provideValidCategoryData
   */
  public function testCreateNewCategory(string $name, string $description, bool $isActive)
  {
    // Arrange
    $uuid = Uuid::uuid4()->toString();
    $nameObj = SimpleName::create($name);
    $descriptionObj = SimpleText::create($description, 0, 255);
    $isActiveObj = new BooleanValue($isActive);

    // Mock da entidade que será retornada pelo repositório
    $categoryMock = Mockery::mock(Category::class, [
      $uuid,
      $nameObj,
      $descriptionObj,
      $isActiveObj
    ]);

    // Configurações do mock da entidade para retornar valores esperados
    $categoryMock->shouldReceive('getId')->andReturn($uuid);
    $categoryMock->shouldReceive('getName')->andReturn($name);
    $categoryMock->shouldReceive('getDescription')->andReturn($description);
    $categoryMock->shouldReceive('isActive')->andReturn($isActive);

    // Mock do repositório
    $repositoryMock = Mockery::mock(CategoryRepositoryInterface::class);
    $repositoryMock->shouldReceive('insert')
      ->once()
      ->andReturn($categoryMock);

    // Instanciação do caso de uso com o repositório mockado
    $useCase = new CreateCategoryUseCase($repositoryMock);

    // Criação do DTO de entrada
    $input = new CategoryCreateInputDto(
      name: $name,
      description: $description,
      isActive: $isActive
    );

    // Act
    $output = $useCase->execute($input);

    // Assert
    $this->assertInstanceOf(CategoryCreateOutputDto::class, $output);
    $this->assertEquals($uuid, $output->id);
    $this->assertEquals($name, $output->name);
    $this->assertEquals($description, $output->description);
    $this->assertEquals($isActive, $output->is_active);

    Mockery::close();
  }

  /**
   * Teste usando spy para verificar a interação com o repositório
   */
  public function testCreateNewCategoryWithSpy()
  {
    // Arrange
    $name = 'Movies Action';
    $description = 'Action movies description';
    $isActive = true;

    $uuid = Uuid::uuid4()->toString();

    // Spy do repositório - não define comportamentos pré-determinados,
    // mas registra todas as chamadas para verificação posterior
    $repositorySpy = Mockery::spy(CategoryRepositoryInterface::class);

    // Prepare a mock Category to be returned by the spy
    $categoryMock = Mockery::mock(Category::class);
    $categoryMock->shouldReceive('getId')->andReturn($uuid);
    $categoryMock->shouldReceive('getName')->andReturn($name);
    $categoryMock->shouldReceive('getDescription')->andReturn($description);
    $categoryMock->shouldReceive('isActive')->andReturn($isActive);

    // Configure o spy para retornar a categoria mockada
    $repositorySpy->shouldReceive('insert')->andReturn($categoryMock);

    // Instanciação do caso de uso com o repositório spy
    $useCase = new CreateCategoryUseCase($repositorySpy);

    // Criação do DTO de entrada
    $input = new CategoryCreateInputDto(
      name: $name,
      description: $description,
      isActive: $isActive
    );

    // Act
    $output = $useCase->execute($input);

    // Assert
    // Verifica se o método insert foi chamado pelo menos uma vez
    $repositorySpy->shouldHaveReceived('insert')->once();

    // Verifica se insert foi chamado com um objeto Category
    $repositorySpy->shouldHaveReceived('insert')->with(Mockery::type(Category::class));

    // Verificar se o DTO de saída tem os valores esperados
    $this->assertEquals($uuid, $output->id);
    $this->assertEquals($name, $output->name);
    $this->assertEquals($description, $output->description);
    $this->assertEquals($isActive, $output->is_active);
  }

  /**
   * Teste usando spy para verificar a validação de campos da categoria
   */
  public function testVerifyFieldsUsedToCreateCategoryWithSpy()
  {
    // Arrange
    $name = 'Games RPG';
    $description = 'Role playing games';
    $isActive = true;

    // Crie um spy para a classe Category
    // Isto é mais avançado e verifica como a Category foi construída
    $categorySpy = Mockery::spy(Category::class);

    // Configure o spy do repositório para retornar o spy da categoria
    $repositorySpy = Mockery::spy(CategoryRepositoryInterface::class);
    $repositorySpy->shouldReceive('insert')->andReturn($categorySpy);

    // Configure o categorySpy para retornar os valores esperados na saída
    $uuid = Uuid::uuid4()->toString();
    $categorySpy->shouldReceive('getId')->andReturn($uuid);
    $categorySpy->shouldReceive('getName')->andReturn($name);
    $categorySpy->shouldReceive('getDescription')->andReturn($description);
    $categorySpy->shouldReceive('isActive')->andReturn($isActive);

    // Instanciação do caso de uso
    $useCase = new CreateCategoryUseCase($repositorySpy);

    // Criação do DTO de entrada com valores específicos para testar
    $input = new CategoryCreateInputDto(
      name: $name,
      description: $description,
      isActive: $isActive
    );

    // Act
    $output = $useCase->execute($input);

    // Assert
    // Verifica se getId foi chamado uma vez
    $categorySpy->shouldHaveReceived('getId')->once();
    // Verifica se getName foi chamado uma vez
    $categorySpy->shouldHaveReceived('getName')->once();
    // Verifica se getDescription foi chamado uma vez
    $categorySpy->shouldHaveReceived('getDescription')->once();
    // Verifica se isActive foi chamado uma vez
    $categorySpy->shouldHaveReceived('isActive')->once();

    // Verifica que o método insert do repositório foi chamado com um objeto Category
    $repositorySpy->shouldHaveReceived('insert')->with(Mockery::type(Category::class));

    // Adicionar pelo menos uma asserção explícita do PHPUnit para evitar o aviso de "risky test"
    $this->assertInstanceOf(CategoryCreateOutputDto::class, $output);
    $this->assertEquals($uuid, $output->id);
    $this->assertEquals($name, $output->name);
    $this->assertEquals($description, $output->description);
    $this->assertEquals($isActive, $output->is_active);
  }

  /**
   * Teste para verificar chamadas do SimpleName e BooleanValue
   */
  public function testCreateCategoryUseCaseWithDomainValueObjectsSpies()
  {
    // Um teste mais avançado usando espionagem em objetos de valor do domínio
    // requer mocking para classes estáticas ou internas, que geralmente não é recomendado
    // Aqui usamos uma abordagem funcional para verificar o comportamento

    $uuid = Uuid::uuid4()->toString();
    $name = 'Books Science';
    $description = 'Science books';
    $isActive = true;

    // Mock para a entidade retornada pelo repositório
    $categoryMock = Mockery::mock(Category::class);
    $categoryMock->shouldReceive('getId')->andReturn($uuid);
    $categoryMock->shouldReceive('getName')->andReturn($name);
    $categoryMock->shouldReceive('getDescription')->andReturn($description);
    $categoryMock->shouldReceive('isActive')->andReturn($isActive);

    // Spy do repositório que captura o objeto Category passado
    $repositorySpy = Mockery::spy(CategoryRepositoryInterface::class);
    $capturedCategory = null;

    // Use shouldReceive para capturar o objeto Category
    $repositorySpy->shouldReceive('insert')
      ->andReturnUsing(function ($category) use (&$capturedCategory, $categoryMock) {
        $capturedCategory = $category;
        return $categoryMock;
      });

    // Instanciação do caso de uso
    $useCase = new CreateCategoryUseCase($repositorySpy);

    // Criação do DTO de entrada
    $input = new CategoryCreateInputDto(
      name: $name,
      description: $description,
      isActive: $isActive
    );

    // Act
    $output = $useCase->execute($input);

    // Assert - verificamos indiretamente que os ValueObjects foram criados corretamente
    // verificando que o repositório recebeu um objeto Category válido
    $this->assertNotNull($capturedCategory, 'O repositório não recebeu um objeto Category');
    $this->assertInstanceOf(Category::class, $capturedCategory);

    // Verifica que o repositório foi chamado
    $repositorySpy->shouldHaveReceived('insert')->once();

    // Verificar o output para ter uma asserção explícita do PHPUnit
    $this->assertInstanceOf(CategoryCreateOutputDto::class, $output);
    $this->assertEquals($uuid, $output->id);
  }

  public function provideValidCategoryData()
  {
    return [
      'complete data' => ['Movies Action', 'Action movies description', true],
      'inactive category' => ['Series Drama', 'Drama series description', false],
      'minimal description' => ['Books Fiction', '', true],
    ];
  }

  public function testShouldThrowExceptionWhenCategoryNameIsInvalid()
  {
    $this->expectException(\Core\Domain\Exception\EntityValidationException::class);

    // Mock do repositório
    $repositoryMock = Mockery::mock(CategoryRepositoryInterface::class);

    // Instanciação do caso de uso com o repositório mockado
    $useCase = new CreateCategoryUseCase($repositoryMock);

    // Criação do DTO de entrada com nome inválido (falta o tipo/sufixo)
    $input = new CategoryCreateInputDto(
      name: 'Invalid', // Não tem duas partes (nome e tipo) conforme regra em SimpleName
      description: 'Some description',
      isActive: true
    );

    // Act - deve lançar exceção
    $useCase->execute($input);

    Mockery::close();
  }

  protected function tearDown(): void
  {
    Mockery::close();
    parent::tearDown();
  }
}