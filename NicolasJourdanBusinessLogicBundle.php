<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle;

use NicolasJourdan\BusinessLogicBundle\DependencyInjection\Compiler\RuleCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class NicolasJourdanBusinessLogicBundle
 *
 * @author Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * @package NicolasJourdan\BusinessLogicBundle
 */
final class NicolasJourdanBusinessLogicBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RuleCompilerPass());
    }
}
