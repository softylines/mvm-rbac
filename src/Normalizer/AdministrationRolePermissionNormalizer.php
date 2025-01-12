<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Normalizer;

use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;

final class AdministrationRolePermissionNormalizer implements AdministrationRolePermissionNormalizerInterface
{
    public function normalize(?array $administrationRolePermissions): array
    {
        if (null === $administrationRolePermissions) {
            return [];
        }

        $normalizedPermissions = [];

        foreach ($administrationRolePermissions as $administrationRolePermission => $operationTypes) {
            if (is_array($operationTypes)) {
                $hasReadOperation = in_array(
                    OperationType::READ,
                    $operationTypes,
                    true
                );

                $hasCreateOperation = in_array(
                    OperationType::CREATE,
                    $operationTypes,
                    true
                );

                $hasUpdateOperation = in_array(
                    OperationType::UPDATE,
                    $operationTypes,
                    true
                );

                $hasDeleteOperation = in_array(
                    OperationType::DELETE,
                    $operationTypes,
                    true
                );

                if ($hasReadOperation) {
                    $normalizedPermissions[$administrationRolePermission][] = OperationType::read();
                }

                if ($hasCreateOperation) {
                    $normalizedPermissions[$administrationRolePermission][] = OperationType::create();
                }

                if ($hasUpdateOperation) {
                    $normalizedPermissions[$administrationRolePermission][] = OperationType::update();
                }

                if ($hasDeleteOperation) {
                    $normalizedPermissions[$administrationRolePermission][] = OperationType::delete();
                }
            }
        }

        return $normalizedPermissions;
    }
}
