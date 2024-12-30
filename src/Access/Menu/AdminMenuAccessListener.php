<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Menu;

use Odiseo\SyliusRbacPlugin\Access\Checker\AdministratorAccessCheckerInterface;
use Odiseo\SyliusRbacPlugin\Access\Model\AccessRequest;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Access\Model\Section;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Sylius\Component\Core\Model\AdminUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

final class AdminMenuAccessListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private AdministratorAccessCheckerInterface $accessChecker,
        private array $configuration,
    ) {
    }

    public function removeInaccessibleAdminMenuParts(MenuBuilderEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        Assert::notNull($token, 'There is no logged in user');

        $adminUser = $token->getUser();
        Assert::isInstanceOf($adminUser, AdminUserInterface::class, 'Logged in user should be an administrator');

        $menu = $event->getMenu();

         //if ($this->hasAdminNoAccessToSection($adminUser, Section::catalog())) {
       //     $menu->removeChild('catalog');
       //  }
         if ($this->hasAdminNoAccessToSection($adminUser, Section::products())) {
            $catalog = $menu->getChildren()['catalog'] ?? null;  
 
            if ($catalog) {
                $catalog->removeChild('products');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::attributes())) {
            $catalog = $menu->getChildren()['catalog'] ?? null;  
 
            if ($catalog) {
                $catalog->removeChild('attributes');
            }
        }
        if($this->hasAdminNoAccessToSection($adminUser, Section::taxons())) {
            $catalog = $menu->getChildren()['catalog'] ?? null;  
 
            if ($catalog) {
                $catalog->removeChild('taxons');
            }
        }

        if($this->hasAdminNoAccessToSection($adminUser, Section::inventory())) {
            $catalog = $menu->getChildren()['catalog'] ?? null;  
 
            if ($catalog) {
                $catalog->removeChild('inventory');
            }
        }
        if($this->hasAdminNoAccessToSection($adminUser, Section::options())) {
            $catalog = $menu->getChildren()['catalog'] ?? null;  
 
            if ($catalog) {
                $catalog->removeChild('options');
            }
        }
        if($this->hasAdminNoAccessToSection($adminUser, Section::associationTypes())) {
            $catalog = $menu->getChildren()['catalog'] ?? null;  
 
            if ($catalog) {
                $catalog->removeChild('association_types');
            }
        }
      //  if ($this->hasAdminNoAccessToSection($adminUser, Section::configuration())) {
      //      $menu->removeChild('configuration');
      //  }
        if($this->hasAdminNoAccessToSection($adminUser, Section::channels())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('channels');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::countries())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('countries');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::zones())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('zones');
            }
        }   

        if ($this->hasAdminNoAccessToSection($adminUser, Section::currencies())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('currencies');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::locales())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('locales');
            }
        }   

        if ($this->hasAdminNoAccessToSection($adminUser, Section::shippingCategories())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('shipping_categories');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::shippingMethods())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('shipping_methods');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::paymentMethods())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('payment_methods');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::exchangeRates())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('exchange_rates');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::taxRates())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('tax_rates');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::taxCategories())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('tax_categories');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::customers())) {
            $menu->removeChild('customers');
        }

       // if ($this->hasAdminNoAccessToSection($adminUser, Section::marketing())) {
       //     $menu->removeChild('marketing');
       // }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::productReviews())) {
            $marketing = $menu->getChildren()['marketing'] ?? null;
            if ($marketing) {
                $marketing->removeChild('product_reviews');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::promotions())) {
            $marketing = $menu->getChildren()['marketing'] ?? null;
            if ($marketing) {
                $marketing->removeChild('promotions');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::catalogPromotions())) {
            $marketing = $menu->getChildren()['marketing'] ?? null;
            if ($marketing) {
                $marketing->removeChild('catalog_promotions');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::sales())) {
            $menu->removeChild('sales');
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::shipping())) {
            $sales = $menu->getChildren()['sales'] ?? null;
            if ($sales) {
                $sales->removeChild('shipping');
            }
        }

        if ($this->hasAdminNoAccessToSection($adminUser, Section::payments())) {
            $sales = $menu->getChildren()['sales'] ?? null;
            if ($sales) {
                $sales->removeChild('payments');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::orders())) {
            $sales = $menu->getChildren()['sales'] ?? null;
            if ($sales) {
                $sales->removeChild('orders');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::marketPlaceManagement())) {
            $menu->removeChild('marketplace');
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::productListings())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('open_marketplace_product_listings'); 
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::vendors())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('vendors');
            }
        }   
        if ($this->hasAdminNoAccessToSection($adminUser, Section::settlements())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('settlement');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::virtualWallets())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('virtual_wallet');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::conversations())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('conversations');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::conversationCategories())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('conversations_category');
            }
        }
        
        /** @var string $customSection */
       // foreach (array_keys($this->configuration['custom']) as $customSection) {
        //    if ($this->hasAdminNoAccessToSection($adminUser, Section::ofType($customSection))) {
           //     $menu->removeChild($customSection);
         //   }
     //   }
    }

    private function hasAdminNoAccessToSection(AdminUserInterface $adminUser, Section $section): bool
    {
        return !$this->accessChecker->canAccessSection(
            $adminUser,
            new AccessRequest($section, OperationType::read()),
        );
    }
}
