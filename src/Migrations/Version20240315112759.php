<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240315112759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create administration roles and their many-to-many relationship with admin users';
    }

    public function up(Schema $schema): void
    {
        // Create the administration roles table
        $this->addSql('CREATE TABLE odiseo_rbac_administration_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, permissions JSON NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_BEFDB7615E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        
        // Create the join table for many-to-many relationship
        $this->addSql('CREATE TABLE sylius_admin_user_administration_roles (
            admin_user_id INT NOT NULL,
            administration_role_id INT NOT NULL,
            PRIMARY KEY(admin_user_id, administration_role_id)
        ) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        
        // Add foreign key constraints
        $this->addSql('ALTER TABLE sylius_admin_user_administration_roles 
            ADD CONSTRAINT FK_ADMIN_USER_ID FOREIGN KEY (admin_user_id) 
            REFERENCES sylius_admin_user (id) ON DELETE CASCADE');
            
        $this->addSql('ALTER TABLE sylius_admin_user_administration_roles 
            ADD CONSTRAINT FK_ADMINISTRATION_ROLE_ID FOREIGN KEY (administration_role_id) 
            REFERENCES odiseo_rbac_administration_role (id) ON DELETE CASCADE');
            
        // Add indexes for better performance
        $this->addSql('CREATE INDEX IDX_ADMIN_USER ON sylius_admin_user_administration_roles (admin_user_id)');
        $this->addSql('CREATE INDEX IDX_ADMINISTRATION_ROLE ON sylius_admin_user_administration_roles (administration_role_id)');
    }

    public function down(Schema $schema): void
    {
        // Remove foreign key constraints first
        $this->addSql('ALTER TABLE sylius_admin_user_administration_roles DROP FOREIGN KEY FK_ADMIN_USER_ID');
        $this->addSql('ALTER TABLE sylius_admin_user_administration_roles DROP FOREIGN KEY FK_ADMINISTRATION_ROLE_ID');
        
        // Drop the tables
        $this->addSql('DROP TABLE sylius_admin_user_administration_roles');
        $this->addSql('DROP TABLE odiseo_rbac_administration_role');
    }
}
