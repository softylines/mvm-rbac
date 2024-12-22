<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Provider;

use Odiseo\SyliusRbacPlugin\Model\Permission;

final class AdminPermissionsProvider implements AdminPermissionsProviderInterface
{
    public function __construct(
        private array $rbacConfiguration,
    ) {
        $configuration = [];

        /**
         * @var string $customSection
         * @var array $_customRoutes
         */
        foreach ($rbacConfiguration['custom'] as $customSection => $_customRoutes) {
            $configuration[$customSection] = $configuration;
        }

        $rbacConfiguration = array_merge(
            array_keys($configuration),
            [
               // Permission::CATALOG_MANAGEMENT_PERMISSION,
                Permission::PRODUCTS_MANAGEMENT_PERMISSION,
                Permission::ATTRIBUTES_MANAGEMENT_PERMISSION,
                Permission::INVENTORY_MANAGEMENT_PERMISSION,
                Permission::TAXONS_MANAGEMENT_PERMISSION,
                Permission::ASSOCIATION_TYPES_MANAGEMENT_PERMISSION,
                Permission::OPTIONS_PERMISSION,
                //Permission::CONFIGURATION_PERMISSION,
                Permission::CHANNELS_MANAGEMENT_PERMISSION,
                Permission::COUNTRIES_MANAGEMENT_PERMISSION,
                Permission::ZONES_MANAGEMENT_PERMISSION,
                Permission::CURRENCIES_MANAGEMENT_PERMISSION,
                Permission::LOCALES_MANAGEMENT_PERMISSION,
                Permission::SHIPPING_CATEGORIES_MANAGEMENT_PERMISSION,
                Permission::SHIPPING_METHODS_MANAGEMENT_PERMISSION,
                Permission::PAYMENT_METHODS_MANAGEMENT_PERMISSION,
                Permission::EXCHANGE_RATES_MANAGEMENT_PERMISSION,
                Permission::TAX_RATES_MANAGEMENT_PERMISSION,
                Permission::TAX_CATEGORIES_MANAGEMENT_PERMISSION,
                Permission::CUSTOMERS_MANAGEMENT_PERMISSION,
                //Permission::MARKETING_MANAGEMENT_PERMISSION,
                Permission::PRODUCT_REVIEWS_PERMISSION,
                Permission::PROMOTIONS_PERMISSION,
                Permission::CATALOG_PROMOTIONS_PERMISSION,
                //Permission::SALES_MANAGEMENT_PERMISSION,
                Permission::SHIPPING_MANAGEMENT_PERMISSION,
                Permission::PAYMENTS_MANAGEMENT_PERMISSION,
                Permission::ORDERS_MANAGEMENT_PERMISSION,
            ],
        );

        sort($rbacConfiguration);

        $this->rbacConfiguration = $rbacConfiguration;
    }

    public function getPossiblePermissions(): array
    {
        return $this->rbacConfiguration;
    }
}
