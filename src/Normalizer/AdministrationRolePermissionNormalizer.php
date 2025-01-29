<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Normalizer;

use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Entity\Permission;

final class AdministrationRolePermissionNormalizer implements AdministrationRolePermissionNormalizerInterface
{
    private const IMPORTABLE_RESOURCES = [
        'countries_management' => 'country',
        'customers' => 'customer',
        'payment_methods_management' => 'payment_method',
        'tax_categories_management' => 'tax_category',
        'products_management' => 'product'
    ];

    private const EXPORTABLE_RESOURCES = [
        'countries_management' => 'country',
        'orders_management' => 'order',
        'customers' => 'customer',
        'products_management' => 'product'
    ];

    public function normalize(?array $administrationRolePermissions): array
    {
        if (null === $administrationRolePermissions) {
            return [];
        }

        $normalizedPermissions = [];

        foreach ($administrationRolePermissions as $administrationRolePermission => $operationTypes) {
            $normalizedOperationTypes = [];

            if (is_array($operationTypes)) {
                foreach ($operationTypes as $operationType) {
                    switch ($operationType) {
                        case OperationType::READ:
                            $normalizedOperationTypes[] = OperationType::read();
                            break;
                        case OperationType::CREATE:
                            $normalizedOperationTypes[] = OperationType::create();
                            break;
                        case OperationType::UPDATE:
                            $normalizedOperationTypes[] = OperationType::update();
                            break;
                        case OperationType::DELETE:
                            $normalizedOperationTypes[] = OperationType::delete();
                            break;
                        case OperationType::IMPORT:
                            if (array_key_exists($administrationRolePermission, self::IMPORTABLE_RESOURCES)) {
                                $normalizedOperationTypes[] = OperationType::import();
                            }
                            break;
                        case OperationType::EXPORT:
                            if (array_key_exists($administrationRolePermission, self::EXPORTABLE_RESOURCES)) {
                                $normalizedOperationTypes[] = OperationType::export();
                            }
                            break;
                    }
                }
            }

            if (!empty($normalizedOperationTypes)) {
                $normalizedPermissions[$administrationRolePermission] = $normalizedOperationTypes;
            }
        }

        return $normalizedPermissions;
    }
}
