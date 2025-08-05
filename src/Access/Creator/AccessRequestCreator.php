<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Creator;

use Odiseo\SyliusRbacPlugin\Access\Exception\UnresolvedRouteNameException;
use Odiseo\SyliusRbacPlugin\Access\Model\AccessRequest;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Access\Model\Section;

final class AccessRequestCreator implements AccessRequestCreatorInterface
{
    public function __construct(
        private array $configuration,
    ) {
    }

    public function createFromRouteName(string $routeName, string $requestMethod): AccessRequest
    {
        $operationType = $this->resolveOperationType($requestMethod);

        if ($routeName === 'sylius_admin_dashboard') {
            return new AccessRequest(Section::dashboard(), $operationType);
        }
        if (str_starts_with($routeName, 'sylius_admin_admin_user')) {
            return new AccessRequest(Section::administrators(), $operationType);
        }
        foreach ($this->configuration['product_listings'] as $productListingsRoutePrefix){
            if (str_starts_with($routeName, $productListingsRoutePrefix)) {
                return new AccessRequest(Section::productListings(), $operationType);
            }
        }
        foreach ($this->configuration['vendor'] as $vendorRoutePrefix){
            if (str_starts_with($routeName, $vendorRoutePrefix)) {
                return new AccessRequest(Section::vendor(), $operationType);
            }
        }
        foreach ($this->configuration['settlement'] as $settlementRoutePrefix){
            if (str_starts_with($routeName, $settlementRoutePrefix)) {
                return new AccessRequest(Section::settlement(), $operationType);
            }
        }
        foreach ($this->configuration['virtual_wallet'] as $virtualWalletRoutePrefix){
            if (str_starts_with($routeName, $virtualWalletRoutePrefix)) {
                return new AccessRequest(Section::virtualWallet(), $operationType);
            }
        }
        foreach ($this->configuration['messages'] as $messagesRoutePrefix){
            if (str_starts_with($routeName, $messagesRoutePrefix)) {
                return new AccessRequest(Section::messages(), $operationType);
            }
        }
        foreach ($this->configuration['messages_category'] as $messagesCategoryRoutePrefix){
            if (str_starts_with($routeName, $messagesCategoryRoutePrefix)) {
                return new AccessRequest(Section::messagesCategory(), $operationType);
            }
        }
        foreach ($this->configuration['channels_management'] as $channelsRoutePrefix) {
            if (str_starts_with($routeName, $channelsRoutePrefix)) {
                return new AccessRequest(Section::channels(), $operationType);
            }
        }
        foreach ($this->configuration['countries_management'] as $countriesRoutePrefix) {
            if (str_starts_with($routeName, $countriesRoutePrefix)) {
                return new AccessRequest(Section::countries(), $operationType);
            }
        }
        foreach ($this->configuration['zones_management'] as $zonesRoutePrefix) {
            if (str_starts_with($routeName, $zonesRoutePrefix)) {
                return new AccessRequest(Section::zones(), $operationType);
            }
        }
        foreach ($this->configuration['currencies_management'] as $currenciesRoutePrefix) {
            if (str_starts_with($routeName, $currenciesRoutePrefix)) {
                return new AccessRequest(Section::currencies(), $operationType);
            }
        }
        foreach ($this->configuration['locales_management'] as $localesRoutePrefix) {
            if (str_starts_with($routeName, $localesRoutePrefix)) {
                return new AccessRequest(Section::locales(), $operationType);
            }
        }
        foreach ($this->configuration['shipping_categories_management'] as $shippingCategoriesRoutePrefix) {
            if (str_starts_with($routeName, $shippingCategoriesRoutePrefix)) {
                return new AccessRequest(Section::shippingCategories(), $operationType);
            }
        }
        foreach ($this->configuration['shipping_methods_management'] as $shippingMethodsRoutePrefix) {
            if (str_starts_with($routeName, $shippingMethodsRoutePrefix)) {
                return new AccessRequest(Section::shippingMethods(), $operationType);
            }
        }
        foreach ($this->configuration['payment_methods_management'] as $paymentMethodsRoutePrefix) {
            if (str_starts_with($routeName, $paymentMethodsRoutePrefix)) {
                return new AccessRequest(Section::paymentMethods(), $operationType);
            }
        }
        foreach ($this->configuration['exchange_rates_management'] as $exchangeRatesRoutePrefix) {
            if (str_starts_with($routeName, $exchangeRatesRoutePrefix)) {
                return new AccessRequest(Section::exchangeRates(), $operationType);
            }
        }
        foreach ($this->configuration['tax_rates_management'] as $taxRatesRoutePrefix) {
            if (str_starts_with($routeName, $taxRatesRoutePrefix)) {
                return new AccessRequest(Section::taxRates(), $operationType);
            }
        }
        foreach ($this->configuration['tax_categories_management'] as $taxCategoriesRoutePrefix) {
            if (str_starts_with($routeName, $taxCategoriesRoutePrefix)) {
                return new AccessRequest(Section::taxCategories(), $operationType);
            }
        }
        foreach ($this->configuration['customers'] as $customersRoutePrefix) {
            if (str_starts_with($routeName, $customersRoutePrefix)) {
                return new AccessRequest(Section::customers(), $operationType);
            }
        }
        foreach ($this->configuration['product_reviews'] as $productReviewsRoutePrefix) {
            if (str_starts_with($routeName, $productReviewsRoutePrefix)) {
                return new AccessRequest(Section::productReviews(), $operationType);
            }
        }
        foreach ($this->configuration['promotions'] as $promotionsRoutePrefix) {
            if (str_starts_with($routeName, $promotionsRoutePrefix)) {
                return new AccessRequest(Section::promotions(), $operationType);
            }
        }
        foreach ($this->configuration['catalog_promotions'] as $catalogPromotionsRoutePrefix) {
            if (str_starts_with($routeName, $catalogPromotionsRoutePrefix)) {
                return new AccessRequest(Section::catalogPromotions(), $operationType);
            }
        }
        foreach ($this->configuration['shipping_management'] as $shippingRoutePrefix) {
            if (str_starts_with($routeName, $shippingRoutePrefix)) {
                return new AccessRequest(Section::shipping(), $operationType);
            }
        }
        foreach ($this->configuration['payments_management'] as $paymentsRoutePrefix) {
            if (str_starts_with($routeName, $paymentsRoutePrefix)) {
                return new AccessRequest(Section::payments(), $operationType);
            }
        }
        foreach ($this->configuration['orders_management'] as $ordersRoutePrefix) {
            if (str_starts_with($routeName, $ordersRoutePrefix)) {
                return new AccessRequest(Section::orders(), $operationType);
            }
        }
        foreach ($this->configuration['products_management'] as $productsRoutePrefix) {
            if (str_starts_with($routeName, $productsRoutePrefix)) {
                return new AccessRequest(Section::products(), $operationType);
            }
        }
        foreach ($this->configuration['attributes_management'] as $attributesRoutePrefix) {
            if (str_starts_with($routeName, $attributesRoutePrefix)) {
                return new AccessRequest(Section::attributes(), $operationType);
            }
        }
        foreach ($this->configuration['inventory_management'] as $inventoryRoutePrefix) {
            if (str_starts_with($routeName, $inventoryRoutePrefix)) {
                return new AccessRequest(Section::inventory(), $operationType);
            }
        }
        foreach ($this->configuration['taxons_management'] as $taxonsRoutePrefix) {
            if (str_starts_with($routeName, $taxonsRoutePrefix)) {
                return new AccessRequest(Section::taxons(), $operationType);
            }
        }
        foreach ($this->configuration['options'] as $optionsRoutePrefix) {
            if (str_starts_with($routeName, $optionsRoutePrefix)) {
                return new AccessRequest(Section::options(), $operationType);
            }
        }
        foreach ($this->configuration['association_types_management'] as $associationTypesRoutePrefix) {
            if (str_starts_with($routeName, $associationTypesRoutePrefix)) {
                return new AccessRequest(Section::associationTypes(), $operationType);
            }
        }
        foreach ($this->configuration['blocks_management'] as $blocksRoutePrefix){
            if (str_starts_with($routeName, $blocksRoutePrefix)) {
                return new AccessRequest(Section::blocks(), $operationType);
            }
        }
        foreach ($this->configuration['pages_management'] as $pagesRoutePrefix){
            if (str_starts_with($routeName, $pagesRoutePrefix)) {
                return new AccessRequest(Section::pages(), $operationType);
            }
        }
        foreach ($this->configuration['faq_management'] as $faqRoutePrefix){
            if (str_starts_with($routeName, $faqRoutePrefix)) {
                return new AccessRequest(Section::faq(), $operationType);
            }
        }
        foreach ($this->configuration['cities_management'] as $citiesRoutePrefix){
            if (str_starts_with($routeName, $citiesRoutePrefix)) {
                return new AccessRequest(Section::cities(), $operationType);
            }
        }
        foreach ($this->configuration['media_management'] as $mediaRoutePrefix){
            if (str_starts_with($routeName, $mediaRoutePrefix)) {
                return new AccessRequest(Section::media(), $operationType);
            }
        }
        foreach ($this->configuration['sections_management'] as $sectionsRoutePrefix){
            if (str_starts_with($routeName, $sectionsRoutePrefix)) {
                return new AccessRequest(Section::sections(), $operationType);
            }
        }
        foreach ($this->configuration['custom'] as $sectionName => $sectionPrefixes) {
            foreach ($sectionPrefixes as $prefix) {
                if (str_starts_with($routeName, $prefix)) {
                    return new AccessRequest(Section::ofType($sectionName), $operationType);
                }
            }
        }

        throw UnresolvedRouteNameException::withRouteName($routeName);
    }

    public function resolveOperationType(string $requestMethod): OperationType
    {
        $operationType = match (strtoupper($requestMethod)) {
            'GET' => OperationType::read(),
            'POST' => OperationType::create(),
            'PUT', 'PATCH' => OperationType::update(),
            'DELETE' => OperationType::delete(),
            default => OperationType::read(),
        };

        return $operationType;
    }
}
