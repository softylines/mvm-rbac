<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Access\Checker;

use Odiseo\SyliusRbacPlugin\Access\Model\AccessRequest;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Access\Model\Section;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Odiseo\SyliusRbacPlugin\Model\Permission;
use Sylius\Component\Core\Model\AdminUserInterface;
use Webmozart\Assert\Assert;

final class AdministratorAccessChecker implements AdministratorAccessCheckerInterface
{
    public function canAccessSection(AdminUserInterface $admin, AccessRequest $accessRequest): bool
    {
        if ($admin instanceof AdministrationRoleAwareInterface) {
            $administrationRole = $admin->getAdministrationRole();
            Assert::notNull($administrationRole);
    
            $sectionType = $accessRequest->section()->__toString();
            if ($sectionType === 'products_management') {
                return $administrationRole->hasPermission(
                    Permission::productsManagement([OperationType::read()])
                );
            }
            

            if ($sectionType === 'attributes_management') {
                return $administrationRole->hasPermission(
                    Permission::attributesManagement([OperationType::read()])
                );
            }
            

            if ($sectionType === 'taxons_management') {
                return $administrationRole->hasPermission(
                    Permission::taxonsManagement([OperationType::read()])
                );
            }


            foreach ($administrationRole->getPermissions() as $permission) {
                if ($this->getSectionForPermission($permission)->equals($accessRequest->section())) {
                    if (OperationType::READ === $accessRequest->operationType()->__toString()) {
                        return true;
                    }
                    return $this->canWriteAccess($permission);
                }
            }
        }
    
        return false;
    }

    private function getSectionForPermission(Permission $permission): Section
    {
        return match (true) {
          //  $permission->equals(Permission::configuration()) => Section::configuration(),
            $permission->equals(Permission::channelsManagement()) => Section::channels(),
            $permission->equals(Permission::countriesManagement()) => Section::countries(),
            $permission->equals(Permission::zonesManagement()) => Section::zones(),
            $permission->equals(Permission::currenciesManagement()) => Section::currencies(),
            $permission->equals(Permission::localesManagement()) => Section::locales(),
            $permission->equals(Permission::shippingCategoriesManagement()) => Section::shippingCategories(),
            $permission->equals(Permission::shippingMethodsManagement()) => Section::shippingMethods(),
            $permission->equals(Permission::paymentMethodsManagement()) => Section::paymentMethods(),
            $permission->equals(Permission::exchangeRatesManagement()) => Section::exchangeRates(),
            $permission->equals(Permission::taxRatesManagement()) => Section::taxRates(),
            $permission->equals(Permission::taxCategoriesManagement()) => Section::taxCategories(),
         //   $permission->equals(Permission::catalogManagement()) => Section::catalog(),
           // $permission->equals(Permission::marketingManagement()) => Section::marketing(),
            $permission->equals(Permission::ProductReviews()) => Section::productReviews(),
            $permission->equals(Permission::promotions()) => Section::promotions(),
            $permission->equals(Permission::catalogPromotions()) => Section::catalogPromotions(),
            $permission->equals(Permission::customerManagement()) => Section::customers(),
           // $permission->equals(Permission::salesManagement()) => Section::sales(),
            $permission->equals(Permission::shippingManagement()) => Section::shipping(),
            $permission->equals(Permission::paymentsManagement()) => Section::payments(),
            $permission->equals(Permission::ordersManagement()) => Section::orders(),
            $permission->equals(Permission::productsManagement()) => Section::products(),
            $permission->equals(Permission::attributesManagement()) => Section::attributes(),
            $permission->equals(Permission::taxonsManagement()) => Section::taxons(),
            $permission->equals(Permission::inventoryManagement()) => Section::inventory(),
            $permission->equals(Permission::optionsManagement()) => Section::options(),
            $permission->equals(Permission::associationTypesManagement()) => Section::associationTypes(),
            $permission->equals(Permission::marketPlaceManagement()) => Section::marketPlaceManagement(),
            $permission->equals(Permission::productListingsManagement()) => Section::productListings(),
            $permission->equals(Permission::vendorsManagement()) => Section::vendors(),
            $permission->equals(Permission::settlementsManagement()) => Section::settlements(),
            $permission->equals(Permission::virtualWalletsManagement()) => Section::virtualWallets(),
            $permission->equals(Permission::conversationsManagement()) => Section::conversations(),
            $permission->equals(Permission::conversationCategoriesManagement()) => Section::conversationCategories(),
            default => Section::ofType($permission->type()),
        };
    }

    private function canWriteAccess(Permission $permission): bool
    {
        /** @var OperationType $operationType */
        foreach ($permission->operationTypes() as $operationType) {
            if (OperationType::WRITE === $operationType->__toString()) {
                return true;
            }
        }

        return false;
    }
}
