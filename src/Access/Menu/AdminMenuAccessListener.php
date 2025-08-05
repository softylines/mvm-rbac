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
        if($this->hasAdminNoAccessToSection($adminUser, Section::administrators())){
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('admin_users');
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
        if ($this->hasAdminNoAccessToSection($adminUser, Section::packs())) {
            $configuration = $menu->getChildren()['configuration'] ?? null;
            if ($configuration) {
                $configuration->removeChild('packs');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::customers())) {
            $menu->removeChild('customers');
        }
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
        if ($this->hasAdminNoAccessToSection($adminUser, Section::shipping())) {
            $sales = $menu->getChildren()['sales'] ?? null;
            if ($sales) {
                $sales->removeChild('shipments');
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
        if ($this->hasAdminNoAccessToSection($adminUser, Section::productListings())) {
         $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('open_marketplace_product_listings');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::vendor())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('vendors');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::settlement())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('settlement');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::virtualWallet())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('virtual_wallet');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::messages())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('conversations');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::messagesCategory())) {
            $marketplace = $menu->getChildren()['marketplace'] ?? null;
            if ($marketplace) {
                $marketplace->removeChild('conversations_category');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::media())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('media');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::blocks())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('blocks');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::pages())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('pages');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::faq())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('faq');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::cities())) {
            $config = $menu->getChildren()['addressing'] ?? null;
            if ($config) {
                $config->removeChild('cities');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::provinces())) {
            $config = $menu->getChildren()['addressing'] ?? null;
            if ($config) {
                $config->removeChild('provinces');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::sections())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('sections');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::testimonials())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('testimonials');
            }
        }
        if ($this->hasAdminNoAccessToSection($adminUser, Section::testimonialsSections())) {
            $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
            if ($bitbag_cms) {
                $bitbag_cms->removeChild('testimonials_section');
            }
        }
        /** @var string $customSection */
        foreach (array_keys($this->configuration['custom']) as $customSection) {
            if ($this->hasAdminNoAccessToSection($adminUser, Section::ofType($customSection))) {
                $menu->removeChild($customSection);
            }
        }
        $marketplace = $menu->getChildren()['marketplace'] ?? null;
        if ($marketplace) {
            if (count($marketplace->getChildren()) === 0) {
                $menu->removeChild('marketplace');
            }
        }
        $catalog = $menu->getChildren()['catalog'] ?? null;
        if ($catalog) {
            if (count($catalog->getChildren()) === 0) {
                $menu->removeChild('catalog');
            }
        }
        $sales = $menu->getChildren()['sales'] ?? null;
        if ($sales) {
            if (count($sales->getChildren()) === 0) {
                $menu->removeChild('sales');
            }
        }
        $marketing = $menu->getChildren()['marketing'] ?? null;
        if ($marketing) {
            if (count($marketing->getChildren()) === 0) {
                $menu->removeChild('marketing');
            }
        }
        $configuration = $menu->getChildren()['configuration'] ?? null;
        if ($configuration) {
            if (count($configuration->getChildren()) === 0) {
                $menu->removeChild('configuration');
            }
        }
        $addressing = $menu->getChildren()['addressing'] ?? null;
        if ($addressing) {
            if (count($addressing->getChildren()) === 0) {
                $menu->removeChild('addressing');
            }
        }
        $bitbag_cms = $menu->getChildren()['bitbag_cms'] ?? null;
        if ($bitbag_cms) {
            if (count($bitbag_cms->getChildren()) === 0) {
                $menu->removeChild('bitbag_cms');
            }
        }
    }

    private function hasAdminNoAccessToSection(AdminUserInterface $adminUser, Section $section): bool
    {
        try {
            return !$this->accessChecker->canAccessSection(
                $adminUser,
                new AccessRequest($section, OperationType::read()),
            );
        } catch (\Exception $e) {
            // If there's any error checking permissions, deny access by default
            return true;
        }
    }
}
