## Installation

1. Run `composer require softylines/mvm-rbac`

2. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
 Odiseo\SyliusRbacPlugin\OdiseoSyliusRbacPlugin::class => ['all' => true],
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
  # ...
  - { resource: '@OdiseoSyliusRbacPlugin/Resources/config/config.yaml' }
```

4. Add the admin route

```yml
# config/routes.yaml
odiseo_sylius_rbac_plugin_admin:
  resource: '@OdiseoSyliusRbacPlugin/Resources/config/routing/admin.yaml'
  prefix: /admin
```

5. Include traits and override the models

```php
/src/Entity/Admin/AdminUser.php
<?php
declare(strict_types=1);namespace BitBag\OpenMarketplace\Component\Core\Admin\Entity;use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;
use Sylius\Component\Core\Model\AdminUserInterface;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity]#[ORM\Table(name: 'sylius_admin_user')]
class AdminUser extends BaseAdminUser implements AdminUserInterface, AdministrationRoleAwareInterface
{
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Groups(['admin:admin_user:read', 'admin:admin_user:create', 'admin:admin_user:update'])]
    protected ?string $phoneNumber;

    /** @var Collection<int, AdministrationRoleInterface> */
    #[ORM\ManyToMany(targetEntity: AdministrationRoleInterface::class, inversedBy: 'adminUsers')]
    #[ORM\JoinTable(name: 'sylius_admin_user_administration_roles')]
    protected Collection $administrationRoles;

    public function __construct()
    {
        parent::__construct();
        $this->administrationRoles = new ArrayCollection();
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAdministrationRoles(): Collection
    {
        return $this->administrationRoles;
    }

    public function addAdministrationRole(AdministrationRoleInterface $role): void
    {
        if (!$this->administrationRoles->contains($role)) {
            $this->administrationRoles->add($role);
            $role->addAdminUser($this);
        }
    }

    public function removeAdministrationRole(AdministrationRoleInterface $role): void
    {
        if ($this->administrationRoles->contains($role)) {
            $this->administrationRoles->removeElement($role);
            $role->removeAdminUser($this);
        }
    }

    public function hasAdministrationRole(AdministrationRoleInterface $role): bool
    {
        return $this->administrationRoles->contains($role);
    }

    public function getAdministrationRole(): ?AdministrationRoleInterface
    {
        return $this->administrationRoles->first() ?: null;
    }

    public function setAdministrationRole(?AdministrationRoleInterface $role): void
    {
        $this->administrationRoles->clear();
        if (null !== $role) {
            $this->addAdministrationRole($role);
        }
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();

        foreach ($this->getAdministrationRoles() as $administrationRole) {
            $roles[] = 'ROLE_' . strtoupper($administrationRole->getName());
        }

        return array_unique($roles);
    }
}

```

6. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:migrations:migrate
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```

7. Run installation command

   ```
   php bin/console odiseo:rbac:install
   ```

   Which consists of:

   - `sylius:fixtures:load`

     Loading fixture with a default "No sections access" role.

     The command runs in non-interactive mode so it will NOT purge your database.
     However, once you run it again it will throw an exception because of duplicate entry constraint violation.

     If you want to install RBAC plugin again on the same environment you will have to remove all roles manually
     via administration panel or run all commands except `sylius:fixtures:load` separately.
