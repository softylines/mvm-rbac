<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Model;

use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Webmozart\Assert\Assert;

final class Permission implements PermissionInterface
{
    public const CATALOG_MANAGEMENT_PERMISSION = 'catalog_management';
    public const PRODUCTS_MANAGEMENT_PERMISSION = 'products_management';
    public const ATTRIBUTES_MANAGEMENT_PERMISSION ='attributes_management';
    public const INVENTORY_MANAGEMENT_PERMISSION = 'inventory_management';
    public const TAXONS_MANAGEMENT_PERMISSION = 'taxons_management';
    public const ASSOCIATION_TYPES_MANAGEMENT_PERMISSION = 'association_types_management';
    public const CONFIGURATION_PERMISSION = 'configuration';
    public const CHANNELS_MANAGEMENT_PERMISSION = 'channels_management';
    public const COUNTRIES_MANAGEMENT_PERMISSION = 'countries_management';  
    public const ZONES_MANAGEMENT_PERMISSION = 'zones_management';
    public const CURRENCIES_MANAGEMENT_PERMISSION = 'currencies_management';
    public const LOCALES_MANAGEMENT_PERMISSION = 'locales_management';
    public const SHIPPING_CATEGORIES_MANAGEMENT_PERMISSION = 'shipping_categories_management';
    public const SHIPPING_METHODS_MANAGEMENT_PERMISSION = 'shipping_methods_management';
    public const PAYMENT_METHODS_MANAGEMENT_PERMISSION = 'payment_methods_management';
    public const EXCHANGE_RATES_MANAGEMENT_PERMISSION = 'exchange_rates_management';
    public const TAX_RATES_MANAGEMENT_PERMISSION = 'tax_rates_management';
    public const TAX_CATEGORIES_MANAGEMENT_PERMISSION = 'tax_categories_management';

    public const CUSTOMERS_MANAGEMENT_PERMISSION = 'customers_management';

    public const MARKETING_MANAGEMENT_PERMISSION = 'marketing_management';
    public const PRODUCT_REVIEWS_PERMISSION = 'product_reviews';
    public const PROMOTIONS_PERMISSION = 'promotions';
    public const CATALOG_PROMOTIONS_PERMISSION = 'catalog_promotions';
    public const SALES_MANAGEMENT_PERMISSION = 'sales_management';
    public const SHIPPING_MANAGEMENT_PERMISSION ='shipping_management';
    public const PAYMENTS_MANAGEMENT_PERMISSION = 'payments_management';    
    public const ORDERS_MANAGEMENT_PERMISSION = 'orders_management';
    public const OPTIONS_PERMISSION = 'options';
    private string $type;

    private array $operationTypes;

    //public static function catalogManagement(array $operationTypes = []): self
    //{
    //    return new self(self::CATALOG_MANAGEMENT_PERMISSION, $operationTypes);
    //}
    public static function productsManagement(array $operationTypes = []): self
    {
        return new self(self::PRODUCTS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function attributesManagement(array $operationTypes = []): self
    {
        return new self(self::ATTRIBUTES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function inventoryManagement(array $operationTypes = []): self
    {
        return new self(self::INVENTORY_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function optionsManagement(array $operationTypes = []): self
    {
        return new self(self::OPTIONS_PERMISSION, $operationTypes);
    }
    public static function taxonsManagement(array $operationTypes = []): self
    {
        return new self(self::TAXONS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function associationTypesManagement(array $operationTypes = []): self
    {
        return new self(self::ASSOCIATION_TYPES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    //public static function configuration(array $operationTypes = []): self
    //{
    //    return new self(self::CONFIGURATION_PERMISSION, $operationTypes);
    //}
    public static function channelsManagement(array $operationTypes = []): self
    {
        return new self(self::CHANNELS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function countriesManagement(array $operationTypes = []): self
    {
        return new self(self::COUNTRIES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function zonesManagement(array $operationTypes = []): self
    {
        return new self(self::ZONES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function currenciesManagement(array $operationTypes = []): self
    {
        return new self(self::CURRENCIES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function localesManagement(array $operationTypes = []): self
    {
        return new self(self::LOCALES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function shippingCategoriesManagement(array $operationTypes = []): self
    {
        return new self(self::SHIPPING_CATEGORIES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function shippingMethodsManagement(array $operationTypes = []): self
    {
        return new self(self::SHIPPING_METHODS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function paymentMethodsManagement(array $operationTypes = []): self
    {
        return new self(self::PAYMENT_METHODS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function exchangeRatesManagement(array $operationTypes = []): self
    {
        return new self(self::EXCHANGE_RATES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function taxRatesManagement(array $operationTypes = []): self
    {
        return new self(self::TAX_RATES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function taxCategoriesManagement(array $operationTypes = []): self
    {
        return new self(self::TAX_CATEGORIES_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function customerManagement(array $operationTypes = []): self
    {
        return new self(self::CUSTOMERS_MANAGEMENT_PERMISSION, $operationTypes);
    }

    //public static function marketingManagement(array $operationTypes = []): self
    //{
    //    return new self(self::MARKETING_MANAGEMENT_PERMISSION, $operationTypes);
    //}

    public static function ProductReviews(array $operationTypes = []): self
    {
        return new self(self::PRODUCT_REVIEWS_PERMISSION, $operationTypes);
    }
    
    public static function promotions(array $operationTypes = []): self
    {
        return new self(self::PROMOTIONS_PERMISSION, $operationTypes);
    }
    public static function catalogPromotions(array $operationTypes = []): self
    {
        return new self(self::CATALOG_PROMOTIONS_PERMISSION, $operationTypes);
    }

    //public static function salesManagement(array $operationTypes = []): self
    //{
    //    return new self(self::SALES_MANAGEMENT_PERMISSION, $operationTypes);
    //}
    public static function shippingManagement(array $operationTypes = []): self
    {
        return new self(self::SHIPPING_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function paymentsManagement(array $operationTypes = []): self
    {
        return new self(self::PAYMENTS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function ordersManagement(array $operationTypes = []): self
    {
        return new self(self::ORDERS_MANAGEMENT_PERMISSION, $operationTypes);
    }
    public static function ofType(string $type, array $operationTypes = []): self
    {
        return new self($type, $operationTypes);
    }

    public function serialize(): string
    {
        /** @var string $serializedPermission */
        $serializedPermission = json_encode([
            'type' => $this->type(),
            'operation_types' => array_map(function (OperationType $operationType): string {
                return $operationType->__toString();
            }, $this->operationTypes()),
        ]);

        return $serializedPermission;
    }

    public static function unserialize(string $serialized): self
    {
        /** @var array $data */
        $data = json_decode($serialized, true);

        return new self($data['type'], array_map(function (string $operationType): OperationType {
            return new OperationType($operationType);
        }, $data['operation_types']));
    }

    private function __construct(string $type, array $operationTypes = [])
    {
        /**
         * @phpstan-ignore-next-line
         */
        Assert::allOneOf(
            array_map(function (OperationType $operationType): string {
                return $operationType->__toString();
            }, $operationTypes),
            [
                OperationType::read()->__toString(),
                OperationType::write()->__toString(),
            ],
        );

        $this->type = $type;
        $this->operationTypes = $operationTypes;
    }

    public function operationTypes(): array
    {
        return $this->operationTypes;
    }

    public function addOperationType(OperationType $operationType): void
    {
        if (in_array($operationType, $this->operationTypes, true)) {
            return;
        }

        $this->operationTypes[] = $operationType;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function equals(self $permission): bool
    {
        $isOfTheSameType = $permission->type() === $this->type();

        $hasTheSameOperationsAllowed = true;

        foreach ($permission->operationTypes() as $operationType) {
            if (!in_array($operationType, $this->operationTypes(), true)) {
                $hasTheSameOperationsAllowed = false;
            }
        }

        return $isOfTheSameType && $hasTheSameOperationsAllowed;
    }
}
