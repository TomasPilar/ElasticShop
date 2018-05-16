<?php
declare(strict_types=1);

namespace App\Model\Contract;


interface ProductRepositoryInterface
{

	public function findByCategory(string $categoryId, array $filters = []): array;

	public function search(string $keyword, array $filters = []): array;

}
