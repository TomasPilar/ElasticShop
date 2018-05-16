<?php declare(strict_types=1);

namespace App\Model;

use App\Model\Contract\ProductRepositoryInterface;
use Nette\Database\Context;


final class ProductRepository implements ProductRepositoryInterface
{
	/**
	 * @var Context
	 */
	private $database;


	public function __construct(Context $database)
	{
		$this->database = $database;
	}


	/**
	 * {@inheritdoc}
	 */
	public function findByCategory(string $categoryId, array $filters = []): array
	{
		$productsQuery = $this->database
			->table('product')
			->where('category_id', $categoryId);

		$return = [
			'total' => count($productsQuery),
			'products' => [],
		];

		if (isset($filters['limit'])) {
			$offset = isset($filters['offset']) ? $filters['offset'] : NULL;
			$productsQuery->limit($filters['limit'], $offset);
		}

		$productsResource = $productsQuery->fetchAll();
		foreach ($productsResource as $product) {
			$return['products'][] = $product->toArray();
		}

		return $return;
	}


	public function search(string $keyword, array $filters = []): array
	{
		// TODO implement search logic in database

		return [
			'products' => [],
			'total' => 0
		];
	}

}
