# Websonette Web Application

Framework-independent request-scoped state shared by Websonette application adapters.

> The package is currently being prepared and does not have a stable public API yet.

## Scope

The package contains data and operations only. It does not depend on Nette, Latte, React, HTML, sessions, or a particular response format.

- `Websonette\WebApplication\Breadcrumbs\Breadcrumbs` manages an ordered breadcrumb trail identified by URL.
- `Websonette\WebApplication\Page\PageContext` describes the current page and exposes a serializable SPA-friendly contract.

Rendering belongs to adapter packages such as `websonette/latte-application`. A future SPA adapter may serialize the same objects into an API response.

## Breadcrumbs

```php
use Websonette\WebApplication\Breadcrumbs\Breadcrumbs;

$breadcrumbs = new Breadcrumbs();
$breadcrumbs->add('Homepage', '/', 'home', 'Introduction');
$breadcrumbs->add('Products', '/products', 'box');
```

## Page context

```php
use Websonette\WebApplication\Page\PageContext;

$page = (new PageContext())
    ->setTitle('Products')
    ->setHeading('Our products')
    ->setDescription('Product overview')
    ->setCanonicalUrl('https://example.com/products')
    ->addAlternateUrl('cs', 'https://example.com/cs/produkty')
    ->setProperty('og:title', 'Products');
```

Both services provide `toArray()` output suitable for a framework-specific JSON response.

## Installation

After the first release and Packagist registration:

```bash
composer require websonette/web-application
```

## License

Websonette Web Application is licensed under the MIT License.