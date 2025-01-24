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
<?php

declare(strict_types=1);

namespace Sylius\Component\Core\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleAwareInterface;
use Odiseo\SyliusRbacPlugin\Entity\AdministrationRoleInterface;
use Sylius\Component\User\Model\User;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUser extends User implements AdminUserInterface, EquatableInterface, AdministrationRoleAwareInterface
{
    /** @var string|null */
    protected $firstName;

    /** @var string|null */
    protected $lastName;

    /** @var string|null */
    protected $localeCode;

    /** @var ImageInterface|null */
    protected $avatar;

    /** @var Collection<int, AdministrationRoleInterface> */
    protected Collection $administrationRoles;

    public function __construct()
    {
        parent::__construct();
        $this->roles = [AdminUserInterface::DEFAULT_ADMIN_ROLE];
        $this->administrationRoles = new ArrayCollection();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s', $this->firstName, $this->lastName));
    }

    public function getLocaleCode(): ?string
    {
        return $this->localeCode;
    }

    public function setLocaleCode(?string $code): void
    {
        $this->localeCode = $code;
    }

    public function getImage(): ?ImageInterface
    {
        return $this->avatar;
    }

    public function setImage(?ImageInterface $image): void
    {
        if ($image !== null) {
            $image->setOwner($this);
        }
        $this->avatar = $image;
    }

    public function getAvatar(): ?ImageInterface
    {
        return $this->getImage();
    }

    public function setAvatar(?ImageInterface $avatar): void
    {
        $this->setImage($avatar);
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $user instanceof AdminUserInterface && $this->isEnabled() === $user->isEnabled();
    }

    /**
     * @return Collection<int, AdministrationRoleInterface>
     */
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
