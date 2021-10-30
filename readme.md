## Installation

```neon
extensions:
	propertyInfo: WebChemistry\PropertyInfo\DI\PropertyInfoExtension

propertyInfo:
	preferDoc: true # prefer doc over reflection as type extractor, default: true
	cache: null # sets cache, default: FilesystemAdapter with "Symfony.PropertyInfo" namespace
```
