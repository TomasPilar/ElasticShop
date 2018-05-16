<?php declare(strict_types=1);

namespace App\Presenter;

use App\Model\CategoryRepository;
use App\Model\Contract\ProductRepositoryInterface;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;


final class CategoryPresenter extends Presenter
{

	const PRODUCTS_PER_PAGE = 30;

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


	public function renderDefault(int $page = 1, string $categoryId)
	{
		// get all categories
		$categories = $this->categoryRepository->findAll();

		$paginator = new Paginator;
		$paginator->setItemsPerPage(self::PRODUCTS_PER_PAGE);
		$paginator->setPage($page);

		// get relevant products
		$products = $this->productRepository->findByCategory(
			$categoryId,
			[
				'limit' => $paginator->getLength(),
				'offset' => $paginator->getOffset()
			] + $this->getHttpRequest()->getQuery()
		);

		$paginator->setItemCount($products['total']);

		$userFilters = $this->getHttpRequest()->getQuery();
		unset($userFilters['page']);

		$this->template->setParameters([
			'categories' => $categories,
			'currentCategory' => $categories[$categoryId],
			'products' => $products,
			'userFilters' => $userFilters,
			'paginator' => $paginator
		]);

	}

}
