<?php

declare(strict_types=1);

namespace Websonette\WebApplication\Tests\Page;

use JsonException;
use PHPUnit\Framework\TestCase;
use Websonette\WebApplication\Page\PageContext;

final class PageContextTest extends TestCase
{
    public function testStartsWithSafeDefaults(): void
    {
        $page = new PageContext();

        self::assertSame([
            'title' => null,
            'heading' => null,
            'description' => null,
            'canonicalUrl' => null,
            'alternateUrls' => [],
            'robots' => 'index, follow',
            'meta' => [],
            'properties' => [],
        ], $page->toArray());
    }

    public function testStoresPageDataWithFluentApi(): void
    {
        $page = (new PageContext())
            ->setTitle('Products')
            ->setHeading('Our products')
            ->setDescription('Product overview')
            ->setCanonicalUrl('https://example.com/products')
            ->addAlternateUrl('cs', 'https://example.com/cs/produkty')
            ->addAlternateUrl('en', 'https://example.com/en/products')
            ->setMeta('author', 'Websonette')
            ->setProperty('og:title', 'Products')
            ->setIndexable(false)
            ->setFollowable(false);

        self::assertSame('Products', $page->getTitle());
        self::assertSame('Our products', $page->getHeading());
        self::assertSame('Product overview', $page->getDescription());
        self::assertSame('https://example.com/products', $page->getCanonicalUrl());
        self::assertSame([
            'cs' => 'https://example.com/cs/produkty',
            'en' => 'https://example.com/en/products',
        ], $page->getAlternateUrls());
        self::assertSame(['author' => 'Websonette'], $page->getMeta());
        self::assertSame(['og:title' => 'Products'], $page->getProperties());
        self::assertFalse($page->isIndexable());
        self::assertFalse($page->isFollowable());
        self::assertSame('noindex, nofollow', $page->getRobots());
    }

    public function testCanRemoveCollectionValues(): void
    {
        $page = (new PageContext())
            ->addAlternateUrl('cs', '/cs')
            ->setMeta('author', 'Websonette')
            ->setProperty('og:title', 'Title');

        $page
            ->removeAlternateUrl('cs')
            ->removeMeta('author')
            ->removeProperty('og:title');

        self::assertSame([], $page->getAlternateUrls());
        self::assertSame([], $page->getMeta());
        self::assertSame([], $page->getProperties());
    }

    public function testClearRestoresDefaults(): void
    {
        $page = (new PageContext())
            ->setTitle('Products')
            ->setHeading('Products')
            ->setDescription('Description')
            ->setCanonicalUrl('/products')
            ->addAlternateUrl('cs', '/cs/produkty')
            ->setMeta('author', 'Websonette')
            ->setProperty('og:title', 'Products')
            ->setIndexable(false)
            ->setFollowable(false);

        $page->clear();

        self::assertSame([
            'title' => null,
            'heading' => null,
            'description' => null,
            'canonicalUrl' => null,
            'alternateUrls' => [],
            'robots' => 'index, follow',
            'meta' => [],
            'properties' => [],
        ], $page->toArray());
    }

    /** @throws JsonException */
    public function testToArrayProvidesSerializableSpaContract(): void
    {
        $page = (new PageContext())
            ->setTitle('Produkty')
            ->setHeading('Naše produkty')
            ->setDescription('Přehled produktů')
            ->setCanonicalUrl('https://example.com/produkty')
            ->addAlternateUrl('en', 'https://example.com/products')
            ->setProperty('og:title', 'Produkty');

        $json = json_encode($page->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        self::assertSame(
            $page->toArray(),
            json_decode($json, associative: true, flags: JSON_THROW_ON_ERROR),
        );
    }
}