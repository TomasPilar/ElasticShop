<?php
declare(strict_types=1);

namespace App\Command;

use App\Service\ElasticsearchClientFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


final class CreateIndexCommand extends Command
{

	/**
	 * @var ElasticsearchClientFactory
	 */
	private $elasticsearchClientFactory;


	public function __construct(ElasticsearchClientFactory $elasticsearchClientFactory)
	{
		parent::__construct();

		$this->elasticsearchClientFactory = $elasticsearchClientFactory;
	}


	protected function configure(): void
	{
		$this->setName('app:create-index');
	}


	protected function execute(InputInterface $input, OutputInterface $output): ?int
	{
		$client = $this->elasticsearchClientFactory->create();
		$indexName = 'eshop';

		// delete existing index
		$exists = $client->indices()->exists(['index' => $indexName]);
		if ($exists) {
			$client->indices()->delete(['index' => $indexName]);
		}

		// create new index
		$indexSettings = json_decode(file_get_contents(SNIPPET_DIR . '/index.json'), TRUE);
		$createParams = [
			'index' => $indexName,
			'body' => $indexSettings
		];
		$result = $client->indices()->create($createParams);

		if (isset($result['acknowledged']) && $result['acknowledged'] === TRUE) {
			$output->writeln('<info>Done!</info>');

		} else {
			$output->writeln('<error>' . print_r($result, TRUE) . ' </error>');
		}

		return 0;
	}

}
