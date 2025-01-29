<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Model;

use Webmozart\Assert\Assert;

final class OperationType
{
    public const READ = 'read';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
    public const IMPORT = 'import';
    public const EXPORT = 'export';

    private string $type;

    public static function read(): self
    {
        return new self(self::READ);
    }

    public static function create(): self
    {
        return new self(self::CREATE);
    }

    public static function update(): self
    {
        return new self(self::UPDATE);
    }

    public static function delete(): self
    {
        return new self(self::DELETE);
    }

    public static function import(): self
    {
        return new self(self::IMPORT);
    }

    public static function export(): self
    {
        return new self(self::EXPORT);
    }

    public function __construct(string $type)
    {
        Assert::oneOf($type, [
            self::READ, 
            self::CREATE, 
            self::UPDATE, 
            self::DELETE, 
            self::IMPORT, 
            self::EXPORT
        ]);

        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->type;
    }

    public static function getTypes(): array
    {
        return [
            self::READ,
            self::CREATE,
            self::UPDATE,
            self::DELETE,
            self::IMPORT,
            self::EXPORT,
        ];
    }
}
