<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Model;

final class Section
{
    public const CATALOG = 'catalog';
    public const PRODUCTS = 'products'; 
    public const ATTRIBUTES = 'attributes';
    public const INVENTORY = 'inventory';
    public const TAXONS = 'taxons';
    public const OPTIONS = 'options';
    public const ASSOCIATION_TYPES='association_types';
    public const CONFIGURATION = 'configuration';
    public const CHANNELS = 'channels';
    public const COUNTRIES = 'countries';
    public const ZONES = 'zones';
    public const CURRENCIES = 'currencies';
    public const LOCALES = 'locales';
    public const SHIPPING_CATEGORIES = 'shipping_categories';
    public const SHIPPING_METHODS = 'shipping_methods';
    public const PAYMENT_METHODS = 'payment_methods';
    public const EXCHANGE_RATES = 'exchange_rates';
    public const TAX_RATES = 'tax_rates';   
    public const TAX_CATEGORIES = 'tax_categories'; 
    public const CUSTOMERS = 'customers';
    //public const MARKETING = 'marketing';
    public const PRODUCT_REVIEWS = 'product_reviews';
    public const PROMOTIONS = 'promotions';
    public const CATALOG_PROMOTIONS = 'catalog_promotions';
    public const SALES = 'sales';
    public const SHIPPING = 'shipping';
    public const PAYMENTS = 'payments';
    public const ORDERS = 'orders';
    public const CUSTOMER = 'customers';
    //custom section
    public const MARKETPLACE = 'marketplace';
    public const PRODUCT_LISTINGS = 'product_listings';
    public const VENDORS = 'vendors';
    public const SETTLEMENTS = 'settlements';
    public const VIRTUAL_WALLETS = 'virtual_wallets';
    public const CONVERSATIONS = 'conversations';
    public const CONVERSATION_CATEGORIES = 'conversation_categories';
    
    private string $type;

    public static function catalog(): self
    {
        return new self(self::CATALOG);
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

    public static function customers(): self
    {
        return new self(self::CUSTOMERS);
    }

  //  public static function marketing(): self
  //  {
  //      return new self(self::MARKETING);
  //  }
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
    public static function customer(): self
    {
        return new self(self::CUSTOMER);
    }
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
    //custom section
    public static function marketPlaceManagement(): self
    {
        return new self(self::MARKETPLACE);
    }
    public static function productListings(): self
    {
        return new self(self::PRODUCT_LISTINGS);
    }
    public static function vendors(): self
    {
        return new self(self::VENDORS);
    }

    public static function settlements(): self
    {
        return new self(self::SETTLEMENTS);
    }

    public static function virtualWallets(): self
    {
        return new self(self::VIRTUAL_WALLETS);
    }

    public static function conversations(): self
    {
        return new self(self::CONVERSATIONS);
    }

    public static function conversationCategories(): self
    {
        return new self(self::CONVERSATION_CATEGORIES);
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
