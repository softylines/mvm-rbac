<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Model;

final class Section
{
    public const DASHBOARD = 'dashboard';
    public const PRODUCTS = 'products_management'; 
    public const ATTRIBUTES = 'attributes_management';
    public const INVENTORY = 'inventory_management';
    public const TAXONS = 'taxons_management';
    public const OPTIONS = 'options';
    public const ASSOCIATION_TYPES='association_types_management';
    public const CONFIGURATION = 'configuration';
    public const CHANNELS = 'channels_management';
    public const COUNTRIES = 'countries_management';
    public const ZONES = 'zones_management';
    public const CURRENCIES = 'currencies_management';
    public const LOCALES = 'locales_management';
    public const SHIPPING_CATEGORIES = 'shipping_categories_management';
    public const SHIPPING_METHODS = 'shipping_methods_management';
    public const PAYMENT_METHODS = 'payment_methods_management';
    public const EXCHANGE_RATES = 'exchange_rates_management';
    public const TAX_RATES = 'tax_rates_management';   
    public const TAX_CATEGORIES = 'tax_categories_management'; 
    public const CUSTOMERS = 'customers';
    public const MARKETING = 'marketing';
    public const PRODUCT_REVIEWS = 'product_reviews';
    public const PROMOTIONS = 'promotions';
    public const CATALOG_PROMOTIONS = 'catalog_promotions';
    public const SALES = 'sales_management';
    public const SHIPPING = 'shipping_management';
    public const PAYMENTS = 'payments_management';
    public const ORDERS = 'orders_management';
    public const ADMINISTRATORS = 'administrators_management';
    public const PRODUCT_LISTINGS = 'product_listings';
    public const VENDOR = 'vendor';
    public const SETTLEMENT = 'settlement';
    public const VIRTUAL_WALLET = 'virtual_wallet';
    public const MESSAGES = 'messages';
    public const MESSAGES_CATEGORY = 'messages_category';
    public const BLOCKS = 'blocks_management';
    public const MEDIA = 'media_management';
    public const PAGES = 'pages_management';
    public const FAQ = 'faq_management';
    public const SECTIONS = 'sections_management';
    public const CITIES = 'cities_management';
    public const PROVINCES = 'provinces_management';

    private string $type;

    public static function dashboard(): self{
        return new self(self::DASHBOARD);
    }   
    public static function administrators(): self
    {
        return new self(self::ADMINISTRATORS);
    }
    public static function configuration(): self
    {
        return new self(self::CONFIGURATION);
    }
    public static function channels(): self
    {
        return new self(self::CHANNELS);
    }
    public static function countries(): self
    {
        return new self(self::COUNTRIES);
    }
    public static function zones(): self
    {
        return new self(self::ZONES);
    }
    public static function currencies(): self
    {
        return new self(self::CURRENCIES);
    }
    public static function locales(): self
    {
        return new self(self::LOCALES);
    }
    public static function shippingCategories(): self
    {
        return new self(self::SHIPPING_CATEGORIES);
    }
    public static function shippingMethods(): self
    {
        return new self(self::SHIPPING_METHODS);
    }   
    public static function paymentMethods(): self
    {
        return new self(self::PAYMENT_METHODS);
    }
    public static function exchangeRates(): self
    {
        return new self(self::EXCHANGE_RATES);
    }
    public static function taxRates(): self
    {
        return new self(self::TAX_RATES);
    }
    public static function taxCategories(): self
    {
        return new self(self::TAX_CATEGORIES);
    }

    //public static function customers(): self
    //{
    //    return new self(self::CUSTOMERS);
    //}

    public static function marketing(): self
    {
        return new self(self::MARKETING);
    }
    public static function productReviews(): self
    {
        return new self(self::PRODUCT_REVIEWS);
    }
    public static function promotions(): self
    {
        return new self(self::PROMOTIONS);
    }
    public static function catalogPromotions(): self
    {
        return new self(self::CATALOG_PROMOTIONS);
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
    public static function options(): self
    {
        return new self(self::OPTIONS);
    }
    public static function customers(): self
    {
        return new self(self::CUSTOMERS);
    }
    //public static function customerGroups(): self
    //{
    //    return new self(self::CUSTOMER_GROUPS);
    //}
    public static function taxons(): self
    {
        return new self(self::TAXONS);
    }
    public static function associationTypes(): self
    {
        return new self(self::ASSOCIATION_TYPES);
    }
    public static function sales(): self
    {
        return new self(self::SALES);
    }
    public static function shipping(): self
    {
        return new self(self::SHIPPING);
    }
    public static function payments(): self
    {
        return new self(self::PAYMENTS);
    }
    public static function orders(): self
    {
        return new self(self::ORDERS);
    }   
    public static function productListings(): self
    {
        return new self(self::PRODUCT_LISTINGS);
    }
    public static function vendor(): self
    {
        return new self(self::VENDOR);
    }
    public static function settlement(): self
    {
        return new self(self::SETTLEMENT);
    }
    public static function virtualWallet(): self
    {
        return new self(self::VIRTUAL_WALLET);
    }
    public static function messages(): self
    {
        return new self(self::MESSAGES);
    }
    public static function messagesCategory(): self
    {
        return new self(self::MESSAGES_CATEGORY);
    }

    public static function blocks(): self
    {
        return new self(self::BLOCKS);
    }
    public static function media(): self
    {
        return new self(self::MEDIA);
    }
    public static function pages(): self
    {
        return new self(self::PAGES);
    }
    public static function faq(): self
    {
        return new self(self::FAQ);
    }
    public static function sections(): self
    {
        return new self(self::SECTIONS);
    }

    public static function cities(): self
    {
        return new self(self::CITIES);
    }

    public static function provinces(): self
    {
        return new self(self::PROVINCES);
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
