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
            if ($this->security->getUser()?->getAdministrationRole()?->getName() !== 'Configurator' 
            && $administrationRole->getName() === 'Configurator') {
            $flashBag->add('error', 'Only administrators with Configurator role can modify Configurator permissions');
            return new RedirectResponse(
                $this->router->generate(
                    'odiseo_sylius_rbac_plugin_admin_administration_role_update_view',
                    ['id' => $request->attributes->get('id')],
                )
            );
        }
            if ($administrationRole->getName() === 'Configurator') {

                if (!isset($newPermissions['rbac']) || 
                    !isset($newPermissions['rbac'][OperationType::READ]) || 
                    !isset($newPermissions['rbac'][OperationType::WRITE])) {
                    
                        $flashBag->add('error', "odiseo_sylius_rbac_plugin.configurator_rbac_permission_denied");  
            return new RedirectResponse(
                        $this->router->generate(
                            'odiseo_sylius_rbac_plugin_admin_administration_role_update_view',
                            ['id' => $request->attributes->get('id')],
                        )
                    );
                }
            } else {
                $hasCurrentRbac = false;
                foreach ($currentPermissions as $permission) {
                    if ($permission->type() === 'rbac') {
                        $hasCurrentRbac = true;
                        break;
                    }
                }


                if ($this->security->getUser()?->getAdministrationRole()?->getName() !== 'Configurator' && $hasCurrentRbac && !isset($newPermissions['rbac'])) {
                    $flashBag->add('error', "odiseo_sylius_rbac_plugin.configurator_rbac_permission_denied");  
                    return new RedirectResponse(
                        $this->router->generate(
                            'odiseo_sylius_rbac_plugin_admin_administration_role_update_view',
                            ['id' => $request->attributes->get('id')],
                        )
                    );
                }

            }
            
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