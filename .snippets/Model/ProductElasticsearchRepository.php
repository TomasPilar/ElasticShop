<?php declare(strict_types=1);

namespace App\Model;

use App\Model\Contract\ProductRepositoryInterface;
use App\Service\ElasticsearchClientFactory;
use Elasticsearch\Client;


final class ProductElasticsearchRepository implements ProductRepositoryInterface
{
	/**
	 * @var Client
	 */
	private $client;


	public function __construct(ElasticsearchClientFactory $elasticsearchClientFactory)
	{
		$this->client = $elasticsearchClientFactory->create();
	}


	/**
	 * {@inheritdoc}
	 */
	public function findByCategory(string $categoryId, array $filters = []): array
	{
		$query = [
			'index' => 'eshop',
			'type' => 'product',
			'body' => [
				'query' => [
					'term' => [
						'category_id' => $categoryId
					]
				]
			]
		];

		// add limit
		if (isset($filters['limit'])) {
			$query['size'] = $filters['limit'];

			if (isset($filters['offset'])) {
				$query['from'] = $filters['offset'];
			}
		}

		$result = $this->client->search($query);

		$return = [
			'total' => $result['hits']['total'],
			'products' => [],
		];

		foreach ($result['hits']['hits'] as $product) {
			$return['products'][] = $product['_source'];
		}

		return $return;
	}


	public function search(string $keyword, array $filters = []): array
	{
		$query = [
			'index' => 'eshop',
			'type' => 'product',
			'body' => [
				'query' => [
					'match' => [
						'title' => $keyword
					]
				]
			]
		];

		// add limit
		if (isset($filters['limit'])) {
			$query['size'] = $filters['limit'];

			if (isset($filters['offset'])) {
				$query['from'] = $filters['offset'];
			}
		}

		$result = $this->client->search($query);

		$return = [
			'total' => $result['hits']['total'],
			'products' => []
		];

		foreach ($result['hits']['hits'] as $product) {
			$return['products'][] = $product['_source'];
		}

		return $return;
	}

}
