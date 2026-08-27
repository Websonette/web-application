<?php

declare(strict_types=1);

namespace Websonette\WebApplication\Page;

/**
 * Framework-independent metadata and presentation context of the current page.
 *
 * The object contains data only. HTML and JSON response rendering belong to
 * framework-specific adapter packages.
 */
final class PageContext
{
    private ?string $title = null;

    private ?string $heading = null;

    private ?string $description = null;

    private ?string $canonicalUrl = null;

    /** @var array<string, string> */
    private array $alternateUrls = [];

    /** @var array<string, string> */
    private array $meta = [];

    /** @var array<string, string> */
    private array $properties = [];

    private bool $indexable = true;

    private bool $followable = true;

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setHeading(?string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setCanonicalUrl(?string $canonicalUrl): self
    {
        $this->canonicalUrl = $canonicalUrl;

        return $this;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function addAlternateUrl(string $language, string $url): self
    {
        $this->alternateUrls[$language] = $url;

        return $this;
    }

    public function removeAlternateUrl(string $language): self
    {
        unset($this->alternateUrls[$language]);

        return $this;
    }

    /** @return array<string, string> */
    public function getAlternateUrls(): array
    {
        return $this->alternateUrls;
    }

    public function setMeta(string $name, string $content): self
    {
        $this->meta[$name] = $content;

        return $this;
    }

    public function removeMeta(string $name): self
    {
        unset($this->meta[$name]);

        return $this;
    }

    /** @return array<string, string> */
    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setProperty(string $property, string $content): self
    {
        $this->properties[$property] = $content;

        return $this;
    }

    public function removeProperty(string $property): self
    {
        unset($this->properties[$property]);

        return $this;
    }

    /** @return array<string, string> */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setIndexable(bool $indexable): self
    {
        $this->indexable = $indexable;

        return $this;
    }

    public function isIndexable(): bool
    {
        return $this->indexable;
    }

    public function setFollowable(bool $followable): self
    {
        $this->followable = $followable;

        return $this;
    }

    public function isFollowable(): bool
    {
        return $this->followable;
    }

    public function getRobots(): string
    {
        return sprintf(
            '%s, %s',
            $this->indexable ? 'index' : 'noindex',
            $this->followable ? 'follow' : 'nofollow',
        );
    }

    public function clear(): void
    {
        $this->title = null;
        $this->heading = null;
        $this->description = null;
        $this->canonicalUrl = null;
        $this->alternateUrls = [];
        $this->meta = [];
        $this->properties = [];
        $this->indexable = true;
        $this->followable = true;
    }

    /**
     * @return array{
     *     title: ?string,
     *     heading: ?string,
     *     description: ?string,
     *     canonicalUrl: ?string,
     *     alternateUrls: array<string, string>,
     *     robots: string,
     *     meta: array<string, string>,
     *     properties: array<string, string>
     * }
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'heading' => $this->heading,
            'description' => $this->description,
            'canonicalUrl' => $this->canonicalUrl,
            'alternateUrls' => $this->alternateUrls,
            'robots' => $this->getRobots(),
            'meta' => $this->meta,
            'properties' => $this->properties,
        ];
    }
}