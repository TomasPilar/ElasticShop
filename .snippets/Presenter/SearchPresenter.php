<?php declare(strict_types=1);

namespace App\Presenter;

use App\Model\CategoryRepository;
use App\Model\Contract\ProductRepositoryInterface;
use Nette\Application\UI\Presenter;
use Nette\Utils\Paginator;


final class SearchPresenter extends Presenter
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


	public function renderDefault(string $keyword = '', int $page = 1)
	{
		// get all categories
		$categories = $this->categoryRepository->findAll();

		$paginator = new Paginator;
		$paginator->setItemsPerPage(self::PRODUCTS_PER_PAGE);
		$paginator->setPage($page);

		// get products
		$products = $this->productRepository->search(
			$keyword,
			[
				'limit' => $paginator->getLength(),
				'offset' => $paginator->getOffset()
			] + $this->getHttpRequest()->getQuery()
		);

		$paginator->setItemCount($products['total']);

		$this->template->setParameters([
			'categories' => $categories,
			'keyword' => $keyword,
			'paginator' => $paginator,
			'products' => $products
		]);
	}

}
