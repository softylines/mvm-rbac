<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Model;

final class Section
{
    public const CATALOG = 'catalog';

    public const CONFIGURATION = 'configuration';

    public const CUSTOMERS = 'customers';

    public const MARKETING = 'marketing';

    public const SALES = 'sales';
    public const PRODUCTS = 'products'; 
    public const ATTRIBUTES = 'attributes';
    public const INVENTORY = 'inventory';
    public const TAXONS = 'taxons';
    public const ASSOCIATIONS = 'associations';
    private string $type;

    public static function catalog(): self
    {
        return new self(self::CATALOG);
    }

    public static function configuration(): self
    {
        return new self(self::CONFIGURATION);
    }

    public static function customers(): self
    {
        return new self(self::CUSTOMERS);
    }

    public static function marketing(): self
    {
        return new self(self::MARKETING);
    }

   public static function products(): self
   {
       return new self(self::PRODUCTS);
    }

    public static function attributes(): self
    {
        return new self(self::ATTRIBUTES);
    }
    public static function inventory(): self
    {
        return new self(self::INVENTORY);
    }
    public static function taxons(): self
    {
        return new self(self::TAXONS);
    }
    public static function sales(): self
    {
        return new self(self::SALES);
    }
    public static function associations(): self
    {
        return new self(self::ASSOCIATIONS);
    }
    public static function ofType(string $type): self
    {
        return new self($type);
    }

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->type;
    }

    public function equals(self $section): bool
    {
        return $section->__toString() === $this->__toString();
    }
}
