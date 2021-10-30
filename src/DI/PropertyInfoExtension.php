<?php declare(strict_types = 1);

namespace WebChemistry\PropertyInfo\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use WebChemistry\PropertyInfo\PropertyInfoExtractorFactory;

final class PropertyInfoExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'preferDoc' => Expect::bool(true),
			'cache' => Expect::anyOf(Expect::string(), Expect::type(Statement::class))->nullable()->default(
				FilesystemAdapter::class
			),
		]);
	}

	public function loadConfiguration(): void
	{
		/** @var stdClass $config */
		$config = $this->getConfig();

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('propertyInfoExtractor'))
			->setType(PropertyInfoExtractorInterface::class)
			->setFactory(sprintf('%s::create(?, ?);', PropertyInfoExtractorFactory::class), [
				$config->preferDoc,
				$this->createCache(),
			]);
	}

	private function createCache(): Statement
	{
		/** @var stdClass $config */
		$config = $this->getConfig();

		if ($config->cache instanceof Statement) {
			return $config->cache;

		} elseif ($config->cache === FilesystemAdapter::class) {
			$builder = $this->getContainerBuilder();

			return new Statement(
				FilesystemAdapter::class,
				[
					'namespace' => 'Symfony.PropertyInfo',
					'directory' => $builder->parameters['tempDir'] . '/cache',
				]
			);

		} elseif (is_string($config->cache)) {
			return new Statement($config->cache);

		}

		return new Statement(ArrayAdapter::class);
	}

}
