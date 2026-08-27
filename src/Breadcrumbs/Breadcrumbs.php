<?php

declare(strict_types=1);

namespace Websonette\WebApplication\Breadcrumbs;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use LogicException;
use Traversable;

/**
 * Request-scoped collection of breadcrumb items.
 *
 * Each item is identified by its URL. Duplicate URLs are not allowed.
 * This service is UI-agnostic and holds only serializable data.
 *
 * @phpstan-type BreadcrumbItem array{
 *     title: string,
 *     url: string,
 *     icon: ?string,
 *     description: ?string
 * }
 *
 * @implements IteratorAggregate<int, BreadcrumbItem>
 */
final class Breadcrumbs implements IteratorAggregate, Countable
{
    /**
     * @var array<string, BreadcrumbItem>
     */
    private array $items = [];

    public function add(
        string $title,
        string $url,
        ?string $icon = null,
        ?string $description = null,
    ): void {
        $key = $this->createKey($url);

        if (isset($this->items[$key])) {
            if ($this->items[$key]['url'] !== $url) {
                throw new LogicException(sprintf(
                    'Breadcrumb key collision: hash for URL "%s" matches a different stored URL "%s".',
                    $url,
                    $this->items[$key]['url'],
                ));
            }

            throw new InvalidArgumentException(sprintf(
                'Breadcrumb with URL "%s" already exists.',
                $url,
            ));
        }

        $this->items[$key] = [
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'description' => $description,
        ];
    }

    public function has(string $url): bool
    {
        $key = $this->createKey($url);

        if (!isset($this->items[$key])) {
            return false;
        }

        $this->assertStoredUrl($key, $url);

        return true;
    }

    /**
     * @return BreadcrumbItem
     */
    public function get(string $url): array
    {
        return $this->items[$this->keyOf($url)];
    }

    public function changeTitle(string $url, string $title): void
    {
        $key = $this->keyOf($url);
        $this->items[$key]['title'] = $title;
    }

    public function changeIcon(string $url, ?string $icon): void
    {
        $key = $this->keyOf($url);
        $this->items[$key]['icon'] = $icon;
    }

    public function changeDescription(string $url, ?string $description): void
    {
        $key = $this->keyOf($url);
        $this->items[$key]['description'] = $description;
    }

    public function changeUrl(string $currentUrl, string $newUrl): void
    {
        $currentKey = $this->keyOf($currentUrl);

        if ($currentUrl === $newUrl) {
            return;
        }

        $newKey = $this->createKey($newUrl);

        if (isset($this->items[$newKey]) && $currentKey !== $newKey) {
            if ($this->items[$newKey]['url'] !== $newUrl) {
                throw new LogicException(sprintf(
                    'Breadcrumb key collision: hash for URL "%s" matches a different stored URL "%s".',
                    $newUrl,
                    $this->items[$newKey]['url'],
                ));
            }

            throw new InvalidArgumentException(sprintf(
                'Breadcrumb with URL "%s" already exists.',
                $newUrl,
            ));
        }

        $rebuilt = [];
        foreach ($this->items as $key => $item) {
            if ($key === $currentKey) {
                $item['url'] = $newUrl;
                $rebuilt[$newKey] = $item;
                continue;
            }

            $rebuilt[$key] = $item;
        }

        $this->items = $rebuilt;
    }

    /**
     * Removes the breadcrumb identified by URL.
     *
     * @throws InvalidArgumentException when no breadcrumb with the given URL exists
     */
    public function remove(string $url): void
    {
        unset($this->items[$this->keyOf($url)]);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @return list<BreadcrumbItem>
     */
    public function toArray(): array
    {
        return array_values($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return Traversable<int, BreadcrumbItem>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    private function createKey(string $url): string
    {
        return md5($url);
    }

    private function keyOf(string $url): string
    {
        $key = $this->createKey($url);

        if (!isset($this->items[$key])) {
            throw new InvalidArgumentException(sprintf(
                'Breadcrumb with URL "%s" does not exist.',
                $url,
            ));
        }

        $this->assertStoredUrl($key, $url);

        return $key;
    }

    private function assertStoredUrl(string $key, string $url): void
    {
        if ($this->items[$key]['url'] !== $url) {
            throw new LogicException(sprintf(
                'Breadcrumb key collision: hash for URL "%s" matches a different stored URL "%s".',
                $url,
                $this->items[$key]['url'],
            ));
        }
    }
}
