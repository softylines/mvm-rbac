<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Action;

use Odiseo\SyliusRbacPlugin\Message\UpdateAdministrationRole;
use Odiseo\SyliusRbacPlugin\Normalizer\AdministrationRolePermissionNormalizerInterface;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
final class UpdateAdministrationRoleAction
{
    public function __construct(
        private MessageBusInterface $bus,
        private AdministrationRolePermissionNormalizerInterface $administrationRolePermissionNormalizer,
        private UrlGeneratorInterface $router,
        private RepositoryInterface $administrationRoleRepository,
        private Security $security,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        try {
            /** @var AdministrationRoleInterface $administrationRole */
            $administrationRole = $this->administrationRoleRepository->find($request->attributes->getInt('id'));
            
            $newPermissions = $request->request->all()['permissions'] ?? [];
            $currentPermissions = $administrationRole->getPermissions();

            $currentUser = $this->security->getUser();
            $userRoles = $currentUser->getAdministrationRoles();

            var_dump('==== DEBUG START ====');
            var_dump([
                'Current User ID' => $currentUser->getId(),
                'Current User Email' => $currentUser->getEmail(),
                'Target Role ID' => $administrationRole->getId(),
                'Target Role Name' => $administrationRole->getName(),
            ]);

            $isTargetRoleConfigurator = strtolower($administrationRole->getName()) === 'configurator';
            $isUserConfigurator = false;
            
            foreach ($userRoles as $role) {
                var_dump('User has role: ' . $role->getName());
                if (strtolower($role->getName()) === 'configurator') {
                    $isUserConfigurator = true;
                    break;
                }
            }

            var_dump([
                'Is Target Role Configurator' => $isTargetRoleConfigurator,
                'Is User Configurator' => $isUserConfigurator
            ]);


            $currentRbacPermissions = [];
            foreach ($currentPermissions as $permission) {
                $reflectionClass = new \ReflectionClass($permission);
                $typeProperty = $reflectionClass->getProperty('type');
                $typeProperty->setAccessible(true);
                $permissionType = $typeProperty->getValue($permission);

                if ($permissionType === 'rbac') {
                    $operationTypesProperty = $reflectionClass->getProperty('operationTypes');
                    $operationTypesProperty->setAccessible(true);
                    $operationTypes = $operationTypesProperty->getValue($permission);
                    
                    $currentRbacPermissions = array_map(
                        function($op) {
                            $opReflection = new \ReflectionClass($op);
                            $typeProperty = $opReflection->getProperty('type');
                            $typeProperty->setAccessible(true);
                            return $typeProperty->getValue($op);
                        },
                        $operationTypes
                    );
                    break;
                }
            }

            $newRbacPermissions = $newPermissions['rbac'] ?? [];
            
            var_dump([
                'Current RBAC Permissions' => $currentRbacPermissions,
                'New RBAC Permissions' => $newRbacPermissions
            ]);

            if ($isTargetRoleConfigurator) {
                if (!isset($newPermissions['rbac']) || 
                    count($newRbacPermissions) < count($currentRbacPermissions) ||
                    array_diff($currentRbacPermissions, array_keys($newRbacPermissions))) {
                    
                    var_dump('BLOCKED: Attempt to remove or modify Configurator RBAC permissions');
                    $flashBag->add('error', 'odiseo_sylius_rbac_plugin.configurator_cannot_modify_own_rbac');
                    return new RedirectResponse(
                        $this->router->generate(
                            'odiseo_sylius_rbac_plugin_admin_administration_role_update_view',
                            ['id' => $request->attributes->get('id')],
                        )
                    );
                }
            }

            var_dump('==== DEBUG END ====');

            /** @var string $administrationRoleName */
            $administrationRoleName = $request->request->get('administration_role_name');

            $normalizedPermissions = $this->administrationRolePermissionNormalizer->normalize($newPermissions);

            $this->bus->dispatch(new UpdateAdministrationRole(
                $request->attributes->getInt('id'),
                $administrationRoleName,
                $normalizedPermissions,
            ));

            $flashBag->add('success', 'odiseo_sylius_rbac_plugin.administration_role_successfully_updated');

        } catch (\Exception $exception) {
            var_dump('Exception occurred: ' . $exception->getMessage());
            $flashBag->add('error', $exception->getMessage());
        }

        return new RedirectResponse(
            $this->router->generate(
                'odiseo_sylius_rbac_plugin_admin_administration_role_update_view',
                ['id' => $request->attributes->get('id')],
            )
        );
    }
}