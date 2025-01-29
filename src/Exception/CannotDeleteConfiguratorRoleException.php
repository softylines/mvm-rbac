<?php

declare(strict_types=1);

namespace Odiseo\SyliusRbacPlugin\Exception;

final class CannotDeleteConfiguratorRoleException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Cannot delete the Configurator role as it is a protected system role');
    }
} 