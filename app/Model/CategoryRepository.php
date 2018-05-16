<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Database\Context;


final class CategoryRepository
{
	/**
	 * @var Context
	 */
	private $database;


	public function __construct(Context $database)
	{
		$this->database = $database;
	}


	public function findById(string $categoryId): array
	{
		$category = $this->database
			->table('category')
			->where('id', $categoryId)
			->fetch();

		return $category->toArray();
	}


	public function findAll(): array
	{
		$categoryResource = $this->database
			->table('category')
			->order('title')
			->fetchAll();

		$categories = [];
		foreach ($categoryResource as $category) {
			$categories[$category['id']] = $category->toArray();
		}

		return $categories;
	}
}

