<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\ElasticsearchClientFactory;
use Nette\Database\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


final class FillIndexCommand extends Command
{

	/**
	 * @var Context
	 */
	private $database;

	/**
	 * @var ElasticsearchClientFactory
	 */
	private $elasticsearchClientFactory;


	public function __construct(Context $database, ElasticsearchClientFactory $elasticsearchClientFactory)
	{
		parent::__construct();

		$this->database = $database;
		$this->elasticsearchClientFactory = $elasticsearchClientFactory;
	}


	protected function configure(): void
	{
		$this->setName('app:fill-index');
	}


	protected function execute(InputInterface $input, OutputInterface $output): ?int
	{
		$client = $this->elasticsearchClientFactory->create();

		$products = $this->database->table('product')->fetchAll();
		foreach	($products as $product) {
			$client->index([
				'index' => 'eshop',
				'type' => 'product',
				'id' => $product['id'],
				'body' => $product->toArray()
			]);
		}

		$indexedProducts = count($products);

		$output->writeln('<info>' . $indexedProducts . ' products indexed to ElasticSearch!</info>');

		return 0;
	}

}
