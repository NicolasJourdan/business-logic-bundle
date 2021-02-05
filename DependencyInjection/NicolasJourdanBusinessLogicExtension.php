<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\DependencyInjection;

use NicolasJourdan\BusinessLogicBundle\Service\Rule\RuleInterface;
use NicolasJourdan\BusinessLogicBundle\Service\Specification\CompositeSpecification;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class NicolasJourdanBusinessLogicExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(RuleInterface::class)
            ->addTag('business_logic.rule');

        $container->registerForAutoconfiguration(CompositeSpecification::class)
            ->addTag('business_logic.specification');
    }
}
