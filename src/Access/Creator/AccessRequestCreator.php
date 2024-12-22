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

      //  foreach ($this->configuration['configuration'] as $configurationRoutePrefix) {
      //      if (str_starts_with($routeName, $configurationRoutePrefix)) {
      //          return new AccessRequest(Section::configuration(), $operationType);
      //      }
      //  }

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
        

        foreach ($this->configuration['customers_management'] as $customersRoutePrefix) {
            if (str_starts_with($routeName, $customersRoutePrefix)) {
                return new AccessRequest(Section::customers(), $operationType);
            }
        }


      //  foreach ($this->configuration['marketing_management'] as $marketingRoutePrefix) {
      //      if (str_starts_with($routeName, $marketingRoutePrefix)) {
      //          return new AccessRequest(Section::marketing(), $operationType);
      //      }
      //  }

        foreach ($this->configuration['product_reviews'] as $productReviewsRoutePrefix) {
            if (str_starts_with($routeName, $productReviewsRoutePrefix)) {
                return new AccessRequest(Section::product_reviews(), $operationType);
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
      //  foreach ($this->configuration['sales_management'] as $salesRoutePrefix) {
      //      if (str_starts_with($routeName, $salesRoutePrefix)) {
      //          return new AccessRequest(Section::sales(), $operationType);
      //      }
      //  }
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
      //  foreach ($this->configuration['catalog_management'] as $catalogRoutePrefix) {
      //      if (str_starts_with($routeName, $catalogRoutePrefix)) {
      //          return new AccessRequest(Section::catalog(), $operationType);
      //      }
      //  }
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
        if ('GET' === $requestMethod || 'HEAD' === $requestMethod) {
            return OperationType::read();
        }

        return OperationType::write();
    }
}
