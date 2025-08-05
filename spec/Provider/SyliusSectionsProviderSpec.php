<?php

declare(strict_types=1);

namespace spec\Odiseo\SyliusRbacPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Odiseo\SyliusRbacPlugin\Provider\SyliusSectionsProviderInterface;

final class SyliusSectionsProviderSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith([
                'catalog_management' => [
                    'sylius_admin_inventory',
                    'sylius_admin_product',
                    'sylius_admin_product_association_type',
                    'sylius_admin_product_attribute',
                    'sylius_admin_product_option',
                    'sylius_admin_product_variant',
                    'sylius_admin_taxon',
                ],
                'configuration' => [
                    'sylius_admin_admin_user',
                    'sylius_admin_channel',
                    'sylius_admin_country',
                    'sylius_admin_currency',
                    'sylius_admin_exchange_rate',
                    'sylius_admin_locale',
                    'sylius_admin_payment_method',
                    'sylius_admin_shipping_category',
                    'sylius_admin_shipping_method',
                    'sylius_admin_tax_category',
                    'sylius_admin_tax_rate',
                    'sylius_admin_zone',
                    'app_pack',
                ],
                'customers_management' => [
                    'sylius_admin_customer',
                    'sylius_admin_customer_group',
                    'sylius_admin_shop_user',
                ],
                'marketing_management' => [
                    'sylius_admin_product_review',
                    'sylius_admin_promotion',
                ],
                'sales_management' => [
                    'sylius_admin_order',
                ],
                'content_management' => [
                    'sylius_admin_content',
                    'sylius_admin_blocks',
                    'sylius_admin_media',
                    'sylius_admin_pages',
                    'sylius_admin_faq',
                    'sylius_admin_sections',
                ],
                'addressing' => [
                    'app_city',
                    'app_province',
                ],
                'custom' => [
                    'rbac' => [
                        'odiseo_sylius_rbac_plugin',
                    ],
                ],
            ]
        );
    }

    public function it_implements_sylius_sections_provider_interface(): void
    {
        $this->shouldImplement(SyliusSectionsProviderInterface::class);
    }

    public function it_returns_both_standard_and_custom_sylius_sections_combined(): void
    {
        $this->__invoke()->shouldReturn([
            'catalog_management',
            'configuration',
            'customers_management',
            'marketing_management',
            'sales_management',
            'content_management',
            'rbac',
            'addressing',
        ]);
    }
}
