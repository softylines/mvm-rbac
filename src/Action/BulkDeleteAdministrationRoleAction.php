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

final class BulkDeleteAdministrationRoleAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RepositoryInterface $administrationRoleRepository,
        private UrlGeneratorInterface $router,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');
        $ids = $request->request->all('ids');

        try {
            foreach ($ids as $id) {
                $role = $this->administrationRoleRepository->find($id);
                if ($role === null) {
                    continue;
                }

                if (strtolower($role->getName()) === 'configurator') {
                    throw new CannotDeleteConfiguratorRoleException();
                }

                $this->entityManager->remove($role);
            }

            $this->entityManager->flush();
            $flashBag->add('success', 'Selected administration roles have been successfully deleted');
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