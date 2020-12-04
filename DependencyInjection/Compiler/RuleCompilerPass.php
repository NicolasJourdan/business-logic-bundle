<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RuleCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $rulesServices = $container->findTaggedServiceIds('business_logic.rule');

        foreach ($rulesServices as $id => $tag) {
            $reflection = $container->getReflectionClass($id);
            $definition = $container->findDefinition($id);

            if (!($reflection->hasConstant('BUSINESS_LOGIC_TAGS') && is_array($reflection->getConstant('BUSINESS_LOGIC_TAGS')))) {
                continue;
            }

            foreach ($reflection->getConstant('BUSINESS_LOGIC_TAGS') as $tag) {
                if (!(is_array($tag) && [] !== $tag)) {
                    continue;
                }

                $name = is_string($tag[0]) ? $tag[0] : null;
                $priorityValue = isset($tag[1]) && is_int($tag[1]) ? $tag[1] : null;
                $attributes = null !== $priorityValue ? ['priority' => $priorityValue] : [];

                if (null !== $name) {
                    $definition->addTag($name, $attributes);
                }
            }
        }
    }
}
