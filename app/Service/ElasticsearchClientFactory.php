<?php declare(strict_types=1);

namespace App\Service;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;


final class ElasticsearchClientFactory
{

	public function create(): Client
	{
		$params = [
			'hosts' => [
				'localhost:9200'
			]
		];

		return ClientBuilder::create($params)->build();
	}

}
