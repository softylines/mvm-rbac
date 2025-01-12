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
        if (!$admin instanceof AdministrationRoleAwareInterface) {
            return false;
        }

        $administrationRole = $admin->getAdministrationRole();
        if ($administrationRole === null) {
            return false;
        }


        /** @var Permission $permission */
        foreach ($administrationRole->getPermissions() as $permission) {
            if ($permission === null) {
                continue;
            }

            // Direct match - permission type matches section exactly
            if ($permission->type() === $accessRequest->section()->__toString()) {
                $requestedOperation = $accessRequest->operationType()->__toString();
                $grantedOperations = array_map(
                    fn(OperationType $type) => $type->__toString(),
                    $permission->operationTypes()
                );


                // Special handling for marketplace sections - strict operation type checking
                if (str_starts_with($permission->type(), 'product_listings') ||
                    str_starts_with($permission->type(), 'vendor') ||
                    str_starts_with($permission->type(), 'settlement') ||
                    str_starts_with($permission->type(), 'virtual_wallet') ||
                    str_starts_with($permission->type(), 'messages') ||
                    str_starts_with($permission->type(), 'messages_category')) {
                    
                    $hasAccess = in_array($requestedOperation, $grantedOperations, true);
                    return $hasAccess;
                }

                // For non-marketplace sections, maintain existing behavior
                $hasAccess = in_array($requestedOperation, $grantedOperations, true);
                return $hasAccess;
            }
        }

        return false;
    }

    private function canReadAccess(Permission $permission): bool
    {
        /** @var OperationType $operationType */
        foreach ($permission->operationTypes() as $operationType) {
            if (OperationType::READ === $operationType->__toString()) {
                return true;
            }
        }

        return false;
    }

    private function canCreateAccess(Permission $permission): bool
    {
        /** @var OperationType $operationType */
        foreach ($permission->operationTypes() as $operationType) {
            if (OperationType::CREATE === $operationType->__toString()) {
                return true;
            }
        }

        return false;
    }

    private function canUpdateAccess(Permission $permission): bool
    {
        /** @var OperationType $operationType */
        foreach ($permission->operationTypes() as $operationType) {
            if (OperationType::UPDATE === $operationType->__toString()) {
                return true;
            }
        }

        return false;
    }

    private function canDeleteAccess(Permission $permission): bool
    {
        /** @var OperationType $operationType */
        foreach ($permission->operationTypes() as $operationType) {
            if (OperationType::DELETE === $operationType->__toString()) {
                return true;
            }
        }

        return false;
    }
}
