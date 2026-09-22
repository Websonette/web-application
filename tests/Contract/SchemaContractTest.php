<?php

declare(strict_types=1);

namespace Websonette\WebApplication\Tests\Contract;

use JsonException;
use PHPUnit\Framework\TestCase;
use Websonette\WebApplication\Breadcrumbs\Breadcrumbs;
use Websonette\WebApplication\Page\PageContext;

final class SchemaContractTest extends TestCase
{
    /** @throws JsonException */
    public function testPageContextSerializerMatchesRequiredSchemaProperties(): void
    {
        $schema = $this->loadSchema('page-context.schema.json');
        $required = $this->requireStringList($schema['required'] ?? null);
        $properties = $this->requireArray($schema['properties'] ?? null);

        self::assertSame(array_keys((new PageContext())->toArray()), $required);
        self::assertSame($required, array_keys($properties));
    }

    /** @throws JsonException */
    public function testBreadcrumbSerializerMatchesItemSchemaProperties(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('Home', '/');
        $schema = $this->loadSchema('breadcrumbs.schema.json');
        $items = $this->requireArray($schema['items'] ?? null);
        $required = $this->requireStringList($items['required'] ?? null);
        $properties = $this->requireArray($items['properties'] ?? null);

        self::assertSame(array_keys($breadcrumbs->toArray()[0]), $required);
        self::assertSame($required, array_keys($properties));
    }

    /** @throws JsonException */
    public function testApplicationContextComposesVersionedContracts(): void
    {
        $schema = $this->loadSchema('application-context.schema.json');
        $properties = $this->requireArray($schema['properties'] ?? null);
        $contractVersion = $this->requireArray($properties['contractVersion'] ?? null);
        $page = $this->requireArray($properties['page'] ?? null);
        $breadcrumbs = $this->requireArray($properties['breadcrumbs'] ?? null);

        self::assertSame(1, $contractVersion['const'] ?? null);
        self::assertSame('page-context.schema.json', $page['$ref'] ?? null);
        self::assertSame('breadcrumbs.schema.json', $breadcrumbs['$ref'] ?? null);
    }

    /**
     * @return array<string, mixed>
     * @throws JsonException
     */
    private function loadSchema(string $file): array
    {
        $contents = file_get_contents(__DIR__ . '/../../contracts/' . $file);
        self::assertNotFalse($contents);

        $schema = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        self::assertIsArray($schema);

        return $schema;
    }

    /** @return array<string, mixed> */
    private function requireArray(mixed $value): array
    {
        self::assertIsArray($value);

        /** @var array<string, mixed> $value */
        return $value;
    }

    /** @return list<string> */
    private function requireStringList(mixed $value): array
    {
        self::assertIsArray($value);
        foreach ($value as $item) {
            self::assertIsString($item);
        }

        /** @var list<string> $value */
        return $value;
    }
}
