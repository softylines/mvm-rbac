<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Odiseo\SyliusRbacPlugin\Exception\CannotDeleteConfiguratorRoleException;

final class DeleteAdministrationRoleAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $router,
        private RepositoryInterface $administrationRoleRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        try {
            $administrationRole = $this->administrationRoleRepository->find($request->attributes->get('id'));
            
            if ($administrationRole === null) {
                throw new \Exception('Administration role not found');
            }

            if (strtolower($administrationRole->getName()) === 'configurator') {
                throw new CannotDeleteConfiguratorRoleException();
            }

            $this->entityManager->remove($administrationRole);
            $this->entityManager->flush();

            $flashBag->add('success', 'Administration role has been successfully deleted');
        } catch (CannotDeleteConfiguratorRoleException $exception) {
            $flashBag->add('error', $exception->getMessage());
        } catch (\Exception $exception) {
            $flashBag->add('error', $exception->getMessage());
        }

        return new RedirectResponse(
            $this->router->generate('odiseo_sylius_rbac_plugin_admin_administration_role_index')
        );
    }
} 