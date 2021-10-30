<?php declare(strict_types = 1);

namespace WebChemistry\PropertyInfo;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoCacheExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

final class PropertyInfoExtractorFactory
{

	public static function create(bool $preferDoc, CacheItemPoolInterface $cacheItemPool): PropertyInfoExtractorInterface
	{
		$doc = new PhpDocExtractor();
		$reflection = new ReflectionExtractor();

		return new PropertyInfoCacheExtractor(
			new PropertyInfoExtractor(
				[$reflection],
				$preferDoc ? [$doc, $reflection] : [$reflection, $doc],
				[$doc],
				[$reflection],
				[$reflection],
			),
			$cacheItemPool,
		);
	}

}
