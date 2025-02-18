<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Model;

final class ImportExportableResources
{
    public const IMPORTABLE_RESOURCES = [
        'country',
        'customer_group',
        'payment_method',
        'tax_category',
        'customer',
        'product',
        'product_option',
        'product_attribute',
        'taxon',
        'vendor'
    ];

    public const EXPORTABLE_RESOURCES = [
        'country',
        'order',
        'customer',
        'product',
        'product_option',
        'product_attribute',
        'taxon',
        'vendor'
    ];

    public static function supportsImport(string $resource): bool
    {
        return in_array($resource, self::IMPORTABLE_RESOURCES, true);
    }

    public static function supportsExport(string $resource): bool
    {
        return in_array($resource, self::EXPORTABLE_RESOURCES, true);
    }
} 