<?php declare(strict_types=1);

namespace App\Presenter;

use App\Model\CategoryRepository;
use App\Model\Contract\ProductRepositoryInterface;
use Nette\Application\UI\Presenter;


final class HomepagePresenter extends Presenter
{

	/**
	 * @var CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var ProductRepositoryInterface
	 */
	protected $productRepository;


	public function __construct(CategoryRepository $categoryRepository, ProductRepositoryInterface $productRepository)
	{
		$this->categoryRepository = $categoryRepository;
		$this->productRepository = $productRepository;
	}


	public function renderDefault()
	{
		// get all categories
		$categories = $this->categoryRepository->findAll();

		// find random category
		$category = $categories[array_rand($categories)];

		// get products from random category
		$products = $this->productRepository->findByCategory($category['id'], [
			'limit' => 21
		]);

		$this->template->setParameters([
			'categories' => $categories,
			'products' => $products
		]);
	}

}
