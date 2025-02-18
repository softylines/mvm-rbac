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

        $existingConfiguratorRole = $roleRepository->findOneBy(['name' => 'configurator']);
        if (!$existingConfiguratorRole) {
            $configuratorRole = new AdministrationRole();
            $configuratorRole->setName('configurator');
            
            $configuratorPermissions = [
                'rbac', 'vendor', 'options', 'messages', 'dashboard', 'promotions',
                'settlement', 'virtual_wallet', 'product_reviews', 'product_listings',
                'zones_management', 'messages_category', 'orders_management',
                'taxons_management', 'catalog_promotions', 'locales_management',
                'channels_management', 'payments_management', 'products_management',
                'shipping_management', 'countries_management', 'customers_management',
                'inventory_management', 'tax_rates_management', 'attributes_management',
                'currencies_management', 'administrators_management', 'exchange_rates_management',
                'tax_categories_management', 'payment_methods_management',
                'shipping_methods_management', 'association_types_management',
                'shipping_categories_management'
            ];

            foreach ($configuratorPermissions as $section) {
                $operationTypes = [
                    OperationType::read(),
                    OperationType::create(),
                    OperationType::update(),
                    OperationType::delete(),
                ];
                
                // Add import/export for specific sections
                if (in_array($section, [
                    'options',
                    'attributes_management',
                    'taxons_management',
                    'vendor',
                    'products_management',
                    'customers',
                    'countries_management'
                ])) {
                    $operationTypes[] = OperationType::import();
                    $operationTypes[] = OperationType::export();
                }
                
                $configuratorRole->addPermission(Permission::ofType($section, $operationTypes));
            }

            $this->entityManager->persist($configuratorRole);
            $io->text('Created Configurator role with full permissions');
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
        
        if (!$roleRepository->findOneBy(['name' => 'products_management'])) {
            $vendorRole = new AdministrationRole();
            $vendorRole->setName('products_management');
            
            $vendorPermissions = [
                'products_management' => [OperationType::read(), OperationType::create(), OperationType::update(), OperationType::delete()],
            ];

            foreach ($vendorPermissions as $section => $operations) {
                $vendorRole->addPermission(Permission::ofType($section, $operations));
            }

            $this->entityManager->persist($vendorRole);
            $io->text('Created products_management role');
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
        
        // Check if the table exists
        $schemaManager = $connection->createSchemaManager();
        $tableExists = $schemaManager->tablesExist(['sylius_admin_user_administration_roles']);
        
        if (!$tableExists) {
            try {
                // Create the table if it doesn't exist
                $connection->executeStatement('
                    CREATE TABLE sylius_admin_user_administration_roles (
                        admin_user_id INT NOT NULL,
                        administration_role_id INT NOT NULL,
                        PRIMARY KEY(admin_user_id, administration_role_id)
                    ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB
                ');

                $connection->executeStatement('
                    ALTER TABLE sylius_admin_user_administration_roles 
                    ADD CONSTRAINT FK_ADMIN_USER_ID 
                    FOREIGN KEY (admin_user_id) 
                    REFERENCES sylius_admin_user (id) ON DELETE CASCADE
                ');

                $connection->executeStatement('
                    ALTER TABLE sylius_admin_user_administration_roles 
                    ADD CONSTRAINT FK_ADMINISTRATION_ROLE_ID 
                    FOREIGN KEY (administration_role_id) 
                    REFERENCES odiseo_rbac_administration_role (id) ON DELETE CASCADE
                ');

                $connection->executeStatement('
                    CREATE INDEX IDX_ADMIN_USER 
                    ON sylius_admin_user_administration_roles (admin_user_id)
                ');

                $connection->executeStatement('
                    CREATE INDEX IDX_ADMINISTRATION_ROLE 
                    ON sylius_admin_user_administration_roles (administration_role_id)
                ');

                $io->success('Created sylius_admin_user_administration_roles table');
            } catch (\Exception $e) {
                $io->error(sprintf('Failed to create table: %s', $e->getMessage()));
                return;
            }
        }

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