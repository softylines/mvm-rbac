<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Cli;

use Doctrine\ORM\EntityManagerInterface;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRole;
use Odiseo\SyliusRbacPlugin\Model\Permission;
use Odiseo\SyliusRbacPlugin\Access\Model\OperationType;
use Odiseo\SyliusRbacPlugin\Provider\SyliusSectionsProvider;
use Sylius\Component\Core\Model\AdminUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\DBAL\Connection;

final class InstallPluginCommand extends Command
{
    public function __construct(
        private SyliusSectionsProvider $syliusSectionsProvider,
        private EntityManagerInterface $entityManager,
        private string $rootAdministratorEmail,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('odiseo:rbac:install')
            ->setDescription('Installs RBAC plugin with default configuration')
            ->addOption(
                'admin-email',
                null,
                InputOption::VALUE_REQUIRED,
                'Email of the admin user to assign the configurator role'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Installing Odiseo RBAC Plugin');

        $adminEmail = $input->getOption('admin-email');
        if (!$adminEmail) {
            $adminEmail = $io->ask('Please enter the email of the admin user who will be assigned the configurator role');
        }

        if (!$adminEmail) {
            $io->error('Admin email is required');
            return Command::FAILURE;
        }


        $configuratorRole = $this->createDefaultRoles($io);

        $this->assignConfiguratorRole($io, $adminEmail, $configuratorRole);

        $io->success('Plugin has been installed successfully.');

        return Command::SUCCESS;
    }

    private function createDefaultRoles(SymfonyStyle $io): ?AdministrationRole
    {
        $io->section('Creating default administration roles');

        $roleRepository = $this->entityManager->getRepository(AdministrationRole::class);
        $configuratorRole = null;

        $existingConfiguratorRole = $roleRepository->findOneBy(['name' => 'Configurator']);
        if (!$existingConfiguratorRole) {
            $configuratorRole = new AdministrationRole();
            $configuratorRole->setName('Configurator');
            foreach ($this->syliusSectionsProvider->getSections() as $section => $routes) {
                $configuratorRole->addPermission(Permission::ofType($section, [
                    OperationType::read(),
                    OperationType::create(),
                    OperationType::update(),
                    OperationType::delete(),
                ]));
            }
            $this->entityManager->persist($configuratorRole);
            $io->text('Created Configurator role');
        } else {
            $configuratorRole = $existingConfiguratorRole;
            $io->text('Using existing Configurator role');
        }
        
        if (!$roleRepository->findOneBy(['name' => 'No sections access'])) {
            $noAccessRole = new AdministrationRole();
            $noAccessRole->setName('No sections access');
            $this->entityManager->persist($noAccessRole);
            $io->text('Created No sections access role');
        }
        
        if (!$roleRepository->findOneBy(['name' => 'Vendor'])) {
            $vendorRole = new AdministrationRole();
            $vendorRole->setName('Vendor');
            
            $vendorPermissions = [
                'products_management' => [OperationType::read(), OperationType::create(), OperationType::update(), OperationType::delete()],
                'inventory_management' => [OperationType::read(), OperationType::update()],
                'orders_management' => [OperationType::read()],
                'product_listings' => [OperationType::read(), OperationType::create(), OperationType::update(), OperationType::delete()],
                'messages' => [OperationType::read(), OperationType::create(), OperationType::update(), OperationType::delete()],
                'virtual_wallet' => [OperationType::read()]
            ];

            foreach ($vendorPermissions as $section => $operations) {
                $vendorRole->addPermission(Permission::ofType($section, $operations));
            }

            $this->entityManager->persist($vendorRole);
            $io->text('Created Vendor role');
        }

        $this->entityManager->flush();

        $io->success('Default administration roles have been processed');
        
        return $configuratorRole;
    }

    private function assignConfiguratorRole(SymfonyStyle $io, string $adminEmail, ?AdministrationRole $configuratorRole): void
    {
        if (null === $configuratorRole) {
            $io->error('Configurator role not found');
            return;
        }

        $connection = $this->entityManager->getConnection();
        
        $adminUser = $this->entityManager->getRepository(AdminUser::class)->findOneBy(['email' => $adminEmail]);
        
        if (!$adminUser) {
            $io->error(sprintf('Admin user with email %s not found', $adminEmail));
            return;
        }

        try {
            $connection->executeStatement(
                'INSERT INTO sylius_admin_user_administration_roles (admin_user_id, administration_role_id) VALUES (:userId, :roleId)',
                [
                    'userId' => $adminUser->getId(),
                    'roleId' => $configuratorRole->getId(),
                ]
            );

            $io->success(sprintf('Successfully assigned Configurator role to admin user %s', $adminEmail));
        } catch (\Exception $e) {
            $io->error(sprintf('Failed to assign role: %s', $e->getMessage()));
        }
    }
}