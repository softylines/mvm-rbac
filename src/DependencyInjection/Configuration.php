<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

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
                        ->arrayNode('dashboard')->prototype('scalar')->end()->end()
                        ->arrayNode('products_management')->prototype('scalar')->end()->end()
                        ->arrayNode('attributes_management')->prototype('scalar')->end()->end()
                        ->arrayNode('inventory_management')->prototype('scalar')->end()->end()
                        ->arrayNode('taxons_management')->prototype('scalar')->end()->end()
                        ->arrayNode('options')->prototype('scalar')->end()->end()
                        ->arrayNode('association_types_management')->prototype('scalar')->end()->end()
                        ->arrayNode('channels_management')->prototype('scalar')->end()->end()
                        ->arrayNode('countries_management')->prototype('scalar')->end()->end()
                        ->arrayNode('zones_management')->prototype('scalar')->end()->end()
                        ->arrayNode('currencies_management')->prototype('scalar')->end()->end()
                        ->arrayNode('locales_management')->prototype('scalar')->end()->end()
                        ->arrayNode('shipping_categories_management')->prototype('scalar')->end()->end()
                        ->arrayNode('shipping_methods_management')->prototype('scalar')->end()->end()
                        ->arrayNode('payment_methods_management')->prototype('scalar')->end()->end()
                        ->arrayNode('exchange_rates_management')->prototype('scalar')->end()->end()
                        ->arrayNode('tax_rates_management')->prototype('scalar')->end()->end()
                        ->arrayNode('tax_categories_management')->prototype('scalar')->end()->end()
                        //->arrayNode('customers_management')->prototype('scalar')->end()->end()
                        ->arrayNode('customers')->prototype('scalar')->end()->end()
                      //  ->arrayNode('customer_groups')->prototype('scalar')->end()->end()
                        ->arrayNode('product_reviews')->prototype('scalar')->end()->end()
                        ->arrayNode('promotions')->prototype('scalar')->end()->end()
                        ->arrayNode('catalog_promotions')->prototype('scalar')->end()->end()
                        ->arrayNode('shipping_management')->prototype('scalar')->end()->end()
                        ->arrayNode('payments_management')->prototype('scalar')->end()->end()
                        ->arrayNode('orders_management')->prototype('scalar')->end()->end()
                        ->arrayNode('product_listings')->prototype('scalar')->end()->end()
                        ->arrayNode('vendor')->prototype('scalar')->end()->end()
                        ->arrayNode('settlement')->prototype('scalar')->end()->end()
                        ->arrayNode('virtual_wallet')->prototype('scalar')->end()->end()
                        ->arrayNode('messages')->prototype('scalar')->end()->end()
                        ->arrayNode('messages_category')->prototype('scalar')->end()->end()
                        ->arrayNode('content_management')->prototype('scalar')->end()->end()
                        ->arrayNode('blocks_management')->prototype('scalar')->end()->end()
                        ->arrayNode('media_management')->prototype('scalar')->end()->end()
                        ->arrayNode('pages_management')->prototype('scalar')->end()->end()
                        ->arrayNode('faq_management')->prototype('scalar')->end()->end()
                        ->arrayNode('sections_management')->prototype('scalar')->end()->end()
                        ->arrayNode('cities_management')->prototype('scalar')->end()->end()
                    ->end()
                ->end()
                ->arrayNode('custom_sections')
                    ->children()
                        ->arrayNode('rbac')->prototype('scalar')->end()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function getSyliusSectionsNode(): ArrayNodeDefinition
    {
        $node = new ArrayNodeDefinition('sylius_sections');

        return $node
            ->children()
                ->arrayNode('dashboard')
                    ->prototype('scalar')->end()
                ->end()
                //MarketPlace
                ->arrayNode('product_listings')->variablePrototype()->end()->end()
                ->arrayNode('vendor')->variablePrototype()->end()->end()
                ->arrayNode('settlement')->variablePrototype()->end()->end()
                ->arrayNode('virtual_wallet')->variablePrototype()->end()->end()
                ->arrayNode('messages')->variablePrototype()->end()->end()
                ->arrayNode('messages_category')->variablePrototype()->end()->end()
                ->arrayNode('products_management')
                    ->prototype('scalar')->end()
                ->end()
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
                //->arrayNode('marketing_management')->variablePrototype()->end()->end()
                ->arrayNode('product_reviews')->variablePrototype()->end()->end()
                ->arrayNode('promotions')->variablePrototype()->end()->end()
                ->arrayNode('catalog_promotions')->variablePrototype()->end()->end()
                //->arrayNode('sales_management')->variablePrototype()->end()->end()
                ->arrayNode('shipping_management')->variablePrototype()->end()->end()
                ->arrayNode('payments_management')->variablePrototype()->end()->end()
                ->arrayNode('orders_management')->variablePrototype()->end()->end()
            ->end()
        ;
    }
}
