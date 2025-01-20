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

        // Get all administration roles
        $administrationRoles = $admin->getAdministrationRoles();
        if ($administrationRoles->isEmpty()) {
            return false;
        }

        // Check each role's permissions
        foreach ($administrationRoles as $administrationRole) {
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
                        
                        if (in_array($requestedOperation, $grantedOperations, true)) {
                            return true; // If any role grants access, return true
                        }
                        continue; // Check next permission if this one doesn't grant access
                    }

                    // For non-marketplace sections, maintain existing behavior
                    if (in_array($requestedOperation, $grantedOperations, true)) {
                        return true; // If any role grants access, return true
                    }
                }
            }
        }

        return false; // No role grants access
    }

    private function canReadAccess(Permission $permission): bool
    {
        return $this->hasOperationType($permission, OperationType::READ);
    }

    private function canCreateAccess(Permission $permission): bool
    {
        return $this->hasOperationType($permission, OperationType::CREATE);
    }

    private function canUpdateAccess(Permission $permission): bool
    {
        return $this->hasOperationType($permission, OperationType::UPDATE);
    }

    private function canDeleteAccess(Permission $permission): bool
    {
        return $this->hasOperationType($permission, OperationType::DELETE);
    }

    private function hasOperationType(Permission $permission, string $type): bool
    {
        foreach ($permission->operationTypes() as $operationType) {
            if ($type === $operationType->__toString()) {
                return true;
            }
        }
        return false;
    }
}
