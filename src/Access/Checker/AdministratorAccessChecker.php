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

            /** @var Permission $permission */
            foreach ($administrationRole->getPermissions() as $permission) {
                if ($this->getSectionForPermission($permission)->equals($accessRequest->section())) {
                    if (OperationType::READ === $accessRequest->operationType()->__toString()) {
                        return true;
                    }

                    return $this->canWriteAccess($permission);
                }
               if ($accessRequest->section()->__toString() === 'products_management') {
                 return $administrationRole->hasPermission(Permission::ofType('products_management', [OperationType::read()]));
               }
                if ($accessRequest->section()->__toString() === 'attributes_management') {
                    return $administrationRole->hasPermission(Permission::ofType('attributes_management', [OperationType::read()]));
                }
                if ($accessRequest->section()->__toString() === 'inventory_management') {
                    return $administrationRole->hasPermission(Permission::ofType('inventory_management', [OperationType::read()]));
                }
                if ($accessRequest->section()->__toString() === 'taxons_management') {
                    return $administrationRole->hasPermission(Permission::ofType('taxons_management', [OperationType::read()]));
                }
            }
        }

        return false;
    }

    private function getSectionForPermission(Permission $permission): Section
    {
        return match (true) {
            $permission->equals(Permission::configuration()) => Section::configuration(),
            // $permission->equals(Permission::catalogManagement()) => Section::catalog(),
            $permission->equals(Permission::marketingManagement()) => Section::marketing(),
            $permission->equals(Permission::customerManagement()) => Section::customers(),
            $permission->equals(Permission::salesManagement()) => Section::sales(),
            $permission->equals(Permission::productsManagement()) => Section::products(),
            $permission->equals(Permission::attributesManagement()) => Section::attributes(),
            $permission->equals(Permission::taxonsManagement()) => Section::taxons(),
            $permission->equals(Permission::associationsManagement()) => Section::associations(),
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
