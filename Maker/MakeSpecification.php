<?php

namespace NicolasJourdan\BusinessLogicBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class MakeSpecification
 *
 * @package NicolasJourdan\BusinessLogicBundle\Maker
 */
final class MakeSpecification extends AbstractMaker
{
    /** {@inheritDoc} */
    public static function getCommandName(): string
    {
        return 'make:specification';
    }

    /** {@inheritDoc} */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new specification class')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the specification class (e.g. <fg=yellow>IsDummy</>)')
            ->setHelp(file_get_contents(__DIR__ . '/../Resources/help/MakeSpecification.txt'))
        ;
    }

    /** {@inheritDoc} */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /** {@inheritDoc} */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $specificationClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Specification\\',
            'Specification'
        );

        $specificationTestClassNameDetails = $generator->createClassNameDetails(
            $specificationClassNameDetails->getRelativeName(),
            'Tests\\Specification\\',
            'Test'
        );

        $generator->generateClass(
            $specificationClassNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/specification/Specification.tpl.php'
        );

        $generator->writeChanges();

        $generator->generateClass(
            $specificationTestClassNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/specification/Test.tpl.php',
            [
                'specificationClassName' => $specificationClassNameDetails->getShortName(),
                'specificationClassFullName' => $specificationClassNameDetails->getFullName(),
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }
}
