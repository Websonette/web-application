# Websonette Web Application

Framework-independent request-scoped state shared by Websonette application adapters.

> The package is currently being prepared and does not have a stable public API yet.

## Scope

The package contains data and operations only. It does not depend on Nette, Latte, React, HTML, sessions, or a particular response format.

- `Websonette\WebApplication\Breadcrumbs\Breadcrumbs` manages an ordered breadcrumb trail identified by URL.
- `Websonette\WebApplication\Page\PageContext` describes the current page and exposes a serializable SPA-friendly contract.

Rendering and framework integration belong to adapter packages such as `websonette/latte-application` and the Nette/Contributte Apitte plus React adapter `websonette/react-spa-application`.

The repository also carries development tooling without coupling it to the runtime:

- [`contracts/`](contracts/) contains versioned JSON Schemas for PageContext, breadcrumbs, and their SPA application envelope;
- [`docs/standards/`](docs/standards/) defines standards common to all Websonette web projects;
- [`skills/`](skills/) contains adapter-neutral Cursor workflows for creating, updating, and auditing projects;
- adapter repositories own their project profiles and framework-specific references.

See [Architecture](docs/architecture.md) and [Project profiles](docs/PROJECT_PROFILES.md). The dependency direction remains `project -> adapter -> web-application`.

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

For an SPA response, use the versioned envelope defined by [`contracts/application-context.schema.json`](contracts/application-context.schema.json):

```php
return new JsonResponse([
    'contractVersion' => 1,
    'page' => $page->toArray(),
    'breadcrumbs' => $breadcrumbs->toArray(),
]);
```

## Installation

After the first release and Packagist registration:

```bash
composer require websonette/web-application
```

## License

Websonette Web Application is licensed under the MIT License.
