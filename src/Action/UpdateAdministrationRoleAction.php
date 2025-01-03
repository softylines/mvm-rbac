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

final class UpdateAdministrationRoleAction
{
    public function __construct(
        private MessageBusInterface $bus,
        private AdministrationRolePermissionNormalizerInterface $administrationRolePermissionNormalizer,
        private UrlGeneratorInterface $router,
        private RepositoryInterface $administrationRoleRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        try {
            /** @var AdministrationRoleInterface $administrationRole */
            $administrationRole = $this->administrationRoleRepository->find($request->attributes->getInt('id'));
            
            if ($administrationRole->getName() === 'Configurator') {
                $newPermissions = $request->request->all()['permissions'] ?? [];
                $currentPermissions = $administrationRole->getPermissions();

            
                if (!isset($newPermissions['rbac']) || 
                    !isset($newPermissions['rbac'][OperationType::READ]) || 
                    !isset($newPermissions['rbac'][OperationType::WRITE])) {
                    $flashBag->add('error', 'Configurator role cannot remove RBAC permissions');
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

            $normalizedPermissions = $this->administrationRolePermissionNormalizer->normalize($request->request->all()['permissions']);

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