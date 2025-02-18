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
    private const IMPORTABLE_RESOURCES = [
        'countries_management' => 'country',
        'customers' => 'customer',
        'payment_methods_management' => 'payment_method',
        'tax_categories_management' => 'tax_category',
        'products_management' => 'product',
        'options' => 'product_option',
        'attributes_management' => 'product_attribute',
        'taxons_management' => 'taxon',
        'vendor' => 'vendor'
    ];

    private const EXPORTABLE_RESOURCES = [
        'countries_management' => 'country',
        'orders_management' => 'order',
        'customers' => 'customer',
        'products_management' => 'product',
        'options' => 'product_option',
        'attributes_management' => 'product_attribute',
        'taxons_management' => 'taxon',
        'vendor' => 'vendor'
    ];

    public function canAccessSection(AdminUserInterface $admin, AccessRequest $accessRequest): bool
    {
        if (!$admin instanceof AdministrationRoleAwareInterface) {
            return false;
        }

        $requestedSection = $accessRequest->section()->__toString();
        $requestedOperation = $accessRequest->operationType()->__toString();

        // Early return if the resource doesn't support import/export
        if ($requestedOperation === OperationType::IMPORT && !isset(self::IMPORTABLE_RESOURCES[$requestedSection])) {
            return false;
        }

        if ($requestedOperation === OperationType::EXPORT && !isset(self::EXPORTABLE_RESOURCES[$requestedSection])) {
            return false;
        }

        // Check user permissions
        $hasPermission = false;
        foreach ($admin->getAdministrationRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                if ($permission === null) {
                    continue;
                }

                if ($permission->type() === $requestedSection) {
                    $operations = array_map(
                        fn(OperationType $type) => $type->__toString(),
                        $permission->operationTypes()
                    );

                    if (in_array($requestedOperation, $operations, true)) {
                        $hasPermission = true;
                        break 2;
                    }
                }
            }
        }

        return $hasPermission;
    }

    public function hasImportPermission(AdminUserInterface $admin, string $section): bool
    {
        if (!isset(self::IMPORTABLE_RESOURCES[$section])) {
            return false;
        }

        foreach ($admin->getAdministrationRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                if ($permission === null) {
                    continue;
                }

                if ($permission->type() === $section) {
                    foreach ($permission->operationTypes() as $operationType) {
                        if ($operationType->__toString() === OperationType::IMPORT) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function hasExportPermission(AdminUserInterface $admin, string $section): bool
    {
        if (!isset(self::EXPORTABLE_RESOURCES[$section])) {
            return false;
        }

        foreach ($admin->getAdministrationRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                if ($permission === null) {
                    continue;
                }

                if ($permission->type() === $section) {
                    foreach ($permission->operationTypes() as $operationType) {
                        if ($operationType->__toString() === OperationType::EXPORT) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function getImportReaderType(string $section): ?string
    {
        if (!$this->canImportResource($section)) {
            return null;
        }

        $resource = self::IMPORTABLE_RESOURCES[$section];
        $readerTypes = [
            'product' => ['csv', 'excel', 'json'],
            'customer' => ['csv', 'excel', 'json'],
            'country' => ['csv', 'excel', 'json'],
            'payment_method' => ['csv', 'excel', 'json'],
            'tax_category' => ['csv', 'excel', 'json'],
            'product_option' => ['csv', 'excel', 'json'],
            'product_attribute' => ['csv', 'excel', 'json'],
            'taxon' => ['csv', 'excel', 'json'],
            'vendor' => ['csv', 'excel', 'json']
        ];

        return $readerTypes[$resource] ?? null;
    }

    public function getExportWriterType(string $section): ?string
    {
        if (!$this->canExportResource($section)) {
            return null;
        }

        $resource = self::EXPORTABLE_RESOURCES[$section];
        $writerTypes = [
            'product' => ['csv', 'excel', 'json'],
            'customer' => ['csv', 'excel', 'json'],
            'country' => ['csv', 'excel', 'json'],
            'order' => ['csv', 'excel', 'json'],
            'product_option' => ['csv', 'excel', 'json'],
            'product_attribute' => ['csv', 'excel', 'json'],
            'taxon' => ['csv', 'excel', 'json'],
            'vendor' => ['csv', 'excel', 'json']
        ];

        return $writerTypes[$resource] ?? null;
    }

    private function canImportResource(string $section): bool
    {
        return isset(self::IMPORTABLE_RESOURCES[$section]);
    }

    private function canExportResource(string $section): bool
    {
        return isset(self::EXPORTABLE_RESOURCES[$section]);
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
 