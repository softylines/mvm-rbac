<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Provider;

final class SyliusSectionsProvider implements SyliusSectionsProviderInterface
{
    private const CUSTOM_SECTION_CONFIGURATION_KEY = 'custom';

    public function __construct(
        private array $rbacConfiguration,
    ) {
    }

    public function __invoke(): array
    {
        $mergedArray = array_diff(
            array_merge(
                array_keys($this->rbacConfiguration),
                array_keys($this->rbacConfiguration[self::CUSTOM_SECTION_CONFIGURATION_KEY]),
            ),
            [self::CUSTOM_SECTION_CONFIGURATION_KEY],
        );

        return $this->rearrangeArray($mergedArray);
    }

    private function rearrangeArray(array $arrayToRearrange): array
    {
        return array_values($arrayToRearrange);
    }

    public function getSections(): array
    {
        return [
            'catalog' => [
                'sylius_admin_product_index',
                'sylius_admin_product_create',
                'sylius_admin_product_update',
                'sylius_admin_product_delete',
            ],
            'configuration' => [
                'sylius_admin_admin_user_index',
                'sylius_admin_admin_user_create',
                'sylius_admin_admin_user_update',
                'sylius_admin_admin_user_delete',
            ],
            'customers' => [
                'sylius_admin_customer_index',
                'sylius_admin_customer_create',
                'sylius_admin_customer_update',
                'sylius_admin_customer_delete',
            ],
            'marketing' => [
                'sylius_admin_promotion_index',
                'sylius_admin_promotion_create',
                'sylius_admin_promotion_update',
                'sylius_admin_promotion_delete',
            ],
            'sales' => [
                'sylius_admin_order_index',
                'sylius_admin_order_show',
                'sylius_admin_order_update',
            ],
            'products_management' => [
                'app_admin_product_index',
                'app_admin_product_create',
                'app_admin_product_update',
                'app_admin_product_delete',
            ],
            'inventory_management' => [
                'app_admin_inventory_index',
                'app_admin_inventory_update',
            ],
            'orders_management' => [
                'app_admin_order_index',
            ],
            'product_listings' => [
                'app_admin_listing_index',
                'app_admin_listing_create',
                'app_admin_listing_update',
                'app_admin_listing_delete',
            ],
            'messages' => [
                'app_admin_message_index',
                'app_admin_message_create',
                'app_admin_message_update',
                'app_admin_message_delete',
            ],
            'virtual_wallet' => [
                'app_admin_wallet_index',
            ],
            'cities_management' => [
                'app_city_index',
                'app_city_update',
                'app_city_delete',
            ],
            'provinces_management' => [
                'app_province_index',
                'app_province_update',
                'app_province_delete',
            ],
        ];
    }
}
