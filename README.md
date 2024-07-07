# Category Import Export Module for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/module-category-import-export.svg?style=flat-square)](https://packagist.org/packages/opengento/module-category-import-export)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-category-import-export.svg?style=flat-square)](./LICENSE) 
[![Packagist](https://img.shields.io/packagist/dt/opengento/module-category-import-export.svg?style=flat-square)](https://packagist.org/packages/opengento/module-category-import-export/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/module-category-import-export.svg?style=flat-square)](https://packagist.org/packages/opengento/module-category-import-export/stats)

This module add the many countries to many stores relation and make it available to the storefront.

 - [Setup](#setup)
   - [Composer installation](#composer-installation)
   - [Setup the module](#setup-the-module)
 - [Features](#features)
 - [Settings](#settings)
 - [Documentation](#documentation)
 - [Support](#support)
 - [Authors](#authors)
 - [License](#license)

## Setup

Magento 2 Open Source or Commerce edition is required.

### Composer installation

Run the following composer command:

```
composer require opengento/module-category-import-export
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

- You can import categories from System > Data Transfer > Import Categories
- You can export categories from System > Data Transfer > Export Categories

## Documentation

- A new attribute category attribute is added with the module: `category_code` which allows to identify the categories.

## Support

Raise a new [request](https://github.com/opengento/magento2-category-import-export/issues) to the issue tracker.

## Authors

- **Opengento Community** - *Lead* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)
- **Thomas Klein** - *Maintainer* - [![GitHub followers](https://img.shields.io/github/followers/thomas-kl1.svg?style=social)](https://github.com/thomas-kl1)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-category-import-export.svg?style=flat-square)](https://github.com/opengento/magento2-category-import-export/graphs/contributors)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
