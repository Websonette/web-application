<?php

declare(strict_types=1);

namespace Websonette\WebApplication\Tests\Breadcrumbs;

use InvalidArgumentException;
use JsonException;
use PHPUnit\Framework\TestCase;
use Websonette\WebApplication\Breadcrumbs\Breadcrumbs;

final class BreadcrumbsTest extends TestCase
{
    public function testStartsEmpty(): void
    {
        $breadcrumbs = new Breadcrumbs();

        self::assertCount(0, $breadcrumbs);
        self::assertSame([], $breadcrumbs->toArray());
        self::assertSame([], iterator_to_array($breadcrumbs));
        self::assertFalse($breadcrumbs->has('/products'));
    }

    public function testAddStoresAllProperties(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add(
            title: 'Products',
            url: '/products',
            icon: 'box',
            description: 'Product overview',
        );

        self::assertTrue($breadcrumbs->has('/products'));
        self::assertSame(
            [
                'title' => 'Products',
                'url' => '/products',
                'icon' => 'box',
                'description' => 'Product overview',
            ],
            $breadcrumbs->get('/products'),
        );
    }

    public function testAddWithoutIconAndDescription(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');

        self::assertSame(
            [
                'title' => 'Home',
                'url' => '/',
                'icon' => null,
                'description' => null,
            ],
            $breadcrumbs->get('/'),
        );
    }

    public function testPreservesInsertionOrderAndIsIterable(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');
        $breadcrumbs->add('Products', '/products');
        $breadcrumbs->add('Detail', '/products/1');

        $titles = [];
        foreach ($breadcrumbs as $item) {
            $titles[] = $item['title'];
        }

        self::assertSame(['Home', 'Products', 'Detail'], $titles);
        self::assertSame(3, $breadcrumbs->count());
        self::assertCount(3, $breadcrumbs);
        self::assertSame([0, 1, 2], array_keys(iterator_to_array($breadcrumbs)));
    }

    public function testGetReturnsOriginalUrlAndDoesNotMatchDifferentUrl(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Products', '/products');

        $item = $breadcrumbs->get('/products');

        self::assertSame('/products', $item['url']);
        self::assertFalse($breadcrumbs->has('/products?x=1'));
    }

    public function testChangeTitle(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Products', '/products');

        $breadcrumbs->changeTitle('/products', 'Our products');

        self::assertSame('Our products', $breadcrumbs->get('/products')['title']);
    }

    public function testChangeIconAndClearIconWithNull(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Products', '/products', icon: 'box');

        $breadcrumbs->changeIcon('/products', 'package');
        self::assertSame('package', $breadcrumbs->get('/products')['icon']);

        $breadcrumbs->changeIcon('/products', null);
        self::assertNull($breadcrumbs->get('/products')['icon']);
    }

    public function testChangeDescriptionAndClearDescriptionWithNull(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Products', '/products', description: 'Overview');

        $breadcrumbs->changeDescription('/products', 'Current offer');
        self::assertSame('Current offer', $breadcrumbs->get('/products')['description']);

        $breadcrumbs->changeDescription('/products', null);
        self::assertNull($breadcrumbs->get('/products')['description']);
    }

    public function testChangeUrlMovesItemAndKeepsPosition(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');
        $breadcrumbs->add('Products', '/old-products');
        $breadcrumbs->add('Contact', '/contact');

        $breadcrumbs->changeUrl('/old-products', '/products');

        self::assertFalse($breadcrumbs->has('/old-products'));
        self::assertTrue($breadcrumbs->has('/products'));
        self::assertSame('Products', $breadcrumbs->get('/products')['title']);
        self::assertSame(
            ['/', '/products', '/contact'],
            array_column($breadcrumbs->toArray(), 'url'),
        );
    }

    public function testChangeUrlToSameUrlIsNoOp(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');

        $breadcrumbs->changeUrl('/', '/');

        self::assertTrue($breadcrumbs->has('/'));
        self::assertCount(1, $breadcrumbs);
    }

    public function testRemoveDeletesItem(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');
        $breadcrumbs->add('Products', '/products');

        $breadcrumbs->remove('/');

        self::assertFalse($breadcrumbs->has('/'));
        self::assertTrue($breadcrumbs->has('/products'));
        self::assertCount(1, $breadcrumbs);
    }

    public function testClearRemovesAllItems(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');
        $breadcrumbs->add('Products', '/products');

        $breadcrumbs->clear();

        self::assertCount(0, $breadcrumbs);
        self::assertSame([], $breadcrumbs->toArray());
        self::assertFalse($breadcrumbs->has('/'));
        self::assertFalse($breadcrumbs->has('/products'));
    }

    public function testToArrayReturnsListWithoutInternalKeys(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/', 'home', 'Introduction');
        $breadcrumbs->add('Products', '/products');

        $data = $breadcrumbs->toArray();

        self::assertSame(
            [
                [
                    'title' => 'Home',
                    'url' => '/',
                    'icon' => 'home',
                    'description' => 'Introduction',
                ],
                [
                    'title' => 'Products',
                    'url' => '/products',
                    'icon' => null,
                    'description' => null,
                ],
            ],
            $data,
        );
        self::assertSame([0, 1], array_keys($data));
        self::assertArrayNotHasKey(md5('/'), $data);
        self::assertArrayNotHasKey(md5('/products'), $data);
        foreach ($data as $item) {
            self::assertSame(['title', 'url', 'icon', 'description'], array_keys($item));
        }
    }

    /**
     * @throws JsonException
     */
    public function testToArrayIsJsonSerializable(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Produkty', '/produkty', 'box', 'Přehled produktů');

        $json = json_encode($breadcrumbs->toArray(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        $decoded = json_decode($json, associative: true, flags: JSON_THROW_ON_ERROR);

        self::assertSame($breadcrumbs->toArray(), $decoded);
        self::assertStringNotContainsString(md5('/produkty'), $json);
    }

    public function testAddRejectsDuplicateUrl(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Products', '/products');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Breadcrumb with URL "/products" already exists.');

        $breadcrumbs->add('Other', '/products');
    }

    public function testChangeUrlRejectsUrlUsedByAnotherItem(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');
        $breadcrumbs->add('Products', '/products');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Breadcrumb with URL "/" already exists.');

        $breadcrumbs->changeUrl('/products', '/');
    }

    public function testGetThrowsWhenUrlDoesNotExist(): void
    {
        $breadcrumbs = new Breadcrumbs();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Breadcrumb with URL "/missing" does not exist.');

        $breadcrumbs->get('/missing');
    }

    public function testChangeTitleThrowsWhenUrlDoesNotExist(): void
    {
        $breadcrumbs = new Breadcrumbs();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Breadcrumb with URL "/missing" does not exist.');

        $breadcrumbs->changeTitle('/missing', 'Title');
    }

    public function testChangeIconThrowsWhenUrlDoesNotExist(): void
    {
        $breadcrumbs = new Breadcrumbs();

        $this->expectException(InvalidArgumentException::class);

        $breadcrumbs->changeIcon('/missing', 'icon');
    }

    public function testChangeDescriptionThrowsWhenUrlDoesNotExist(): void
    {
        $breadcrumbs = new Breadcrumbs();

        $this->expectException(InvalidArgumentException::class);

        $breadcrumbs->changeDescription('/missing', 'Description');
    }

    public function testChangeUrlThrowsWhenCurrentUrlDoesNotExist(): void
    {
        $breadcrumbs = new Breadcrumbs();

        $this->expectException(InvalidArgumentException::class);

        $breadcrumbs->changeUrl('/missing', '/other');
    }

    public function testRemoveThrowsWhenUrlDoesNotExist(): void
    {
        $breadcrumbs = new Breadcrumbs();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Breadcrumb with URL "/missing" does not exist.');

        $breadcrumbs->remove('/missing');
    }
}
