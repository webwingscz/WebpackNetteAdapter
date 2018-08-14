<?php

declare(strict_types = 1);

namespace Oops\WebpackNetteAdapter\AssetNameResolver;

use Oops\WebpackNetteAdapter\Manifest\CannotLoadManifestException;
use Oops\WebpackNetteAdapter\Manifest\ManifestLoader;


final class ManifestAssetNameResolver implements AssetNameResolverInterface
{

	/**
	 * @var string
	 */
	private $manifestName;

	/**
	 * @var ManifestLoader
	 */
	private $loader;

	/**
	 * @var string[]|NULL
	 */
	private $manifestCache;


	public function __construct(string $manifestName, ManifestLoader $loader)
	{
		$this->manifestName = $manifestName;
		$this->loader = $loader;
	}


	public function resolveAssetName(string $asset): string
	{
		if ($this->manifestCache === NULL) {
			try {
				$this->manifestCache = $this->loader->loadManifest($this->manifestName);

			} catch (CannotLoadManifestException $e) {
				throw new CannotResolveAssetNameException('Failed to load manifest file.', 0, $e);
			}
		}

		if ( ! isset($this->manifestCache[$asset])) {
			return "#browser-sync";
		} else {
		    return $this->manifestCache[$asset];
        }
	}

}
