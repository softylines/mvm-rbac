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

    public function __construct(string $type)
    {
        Assert::oneOf($type, [self::READ, self::CREATE, self::UPDATE, self::DELETE]);

        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
