<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UndefinedMethod
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('odiseo_sylius_rbac_plugin');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('sylius_sections')
                    ->children()
                        //->arrayNode('catalog_management')->variablePrototype()->end()->end()
                        ->arrayNode('products_management')->variablePrototype()->end()->end()
                        ->arrayNode('attributes_management')->variablePrototype()->end()->end()
                        ->arrayNode('inventory_management')->variablePrototype()->end()->end()
                        ->arrayNode('taxons_management')->variablePrototype()->end()->end()
                        ->arrayNode('association_types_management')->variablePrototype()->end()->end()
                        ->arrayNode('options')->variablePrototype()->end()->end()
                        //->arrayNode('configuration')->variablePrototype()->end()->end()
                        ->arrayNode('countries_management')->variablePrototype()->end()->end()
                        ->arrayNode('zones_management')->variablePrototype()->end()->end()
                        ->arrayNode('currencies_management')->variablePrototype()->end()->end()
                        ->arrayNode('locales_management')->variablePrototype()->end()->end()
                        ->arrayNode('shipping_categories_management')->variablePrototype()->end()->end()
                        ->arrayNode('shipping_methods_management')->variablePrototype()->end()->end()
                        ->arrayNode('payment_methods_management')->variablePrototype()->end()->end()
                        ->arrayNode('exchange_rates_management')->variablePrototype()->end()->end()
                        ->arrayNode('tax_rates_management')->variablePrototype()->end()->end()
                        ->arrayNode('tax_categories_management')->variablePrototype()->end()->end()
                        ->arrayNode('channels_management')->variablePrototype()->end()->end()
                        ->arrayNode('customers_management')->variablePrototype()->end()->end()
                        ->arrayNode('product_reviews')->variablePrototype()->end()->end()
                        ->arrayNode('promotions')->variablePrototype()->end()->end()
                        ->arrayNode('catalog_promotions')->variablePrototype()->end()->end()
                        //->arrayNode('sales_management')->variablePrototype()->end()->end()
                        ->arrayNode('shipping_management')->variablePrototype()->end()->end()
                        ->arrayNode('payments_management')->variablePrototype()->end()->end()
                        ->arrayNode('orders_management')->variablePrototype()->end()->end()
                        ->arrayNode('marketplace_management')->variablePrototype()->end()->end()
                        ->arrayNode('product_listings_management')->variablePrototype()->end()->end()
                        ->arrayNode('vendors_management')->variablePrototype()->end()->end()
                        ->arrayNode('settlements_management')->variablePrototype()->end()->end()
                        ->arrayNode('virtual_wallets_management')->variablePrototype()->end()->end()
                        ->arrayNode('messages_management')->variablePrototype()->end()->end()
                        ->arrayNode('message_categories_management')->variablePrototype()->end()->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                /* it's a very MVP approach, as now we can pass almost everything there
                   TODO: create some more strict custom sections structure */
                ->arrayNode('custom_sections')
                    ->useAttributeAsKey('name')
                    ->variablePrototype()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
