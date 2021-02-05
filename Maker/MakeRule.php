<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\Maker;

use NicolasJourdan\BusinessLogicBundle\Service\Specification\CompositeSpecification;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class MakeRule
 *
 * @package NicolasJourdan\BusinessLogicBundle\Maker
 */
final class MakeRule extends AbstractMaker
{
    /** @var CompositeSpecification[] */
    private $specifications;

    /**
     * MakeRule constructor.
     *
     * @param iterable $specifications
     */
    public function __construct(iterable $specifications)
    {
        $this->specifications = $specifications;
        foreach ($this->specifications as $specification) {
            if (!$specification instanceof CompositeSpecification) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The specification "%s" must implement "%s"',
                        get_class($specification),
                        CompositeSpecification::class
                    )
                );
            }
        }
    }

    /** {@inheritDoc} */
    public static function getCommandName(): string
    {
        return 'make:rule';
    }

    /** {@inheritDoc} */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->setDescription('Creates a new rule class')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the rule class (e.g. <fg=yellow>DummyRule</>)')
            ->setHelp(file_get_contents(__DIR__ . '/../Resources/help/MakeRule.txt'))
        ;
    }

    /** {@inheritDoc} */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /** {@inheritDoc} */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $ruleClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('name'),
            'Rule\\',
            'Rule'
        );

        $ruleTestClassNameDetails = $generator->createClassNameDetails(
            $ruleClassNameDetails->getRelativeName(),
            'Tests\\Rule\\',
            'Test'
        );

        $tags = $this->getTags($io);
        $specifications = $this->getSpecifications($io);

        $generator->generateClass(
            $ruleClassNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/rule/Rule.tpl.php',
            [
                'tags' => $tags,
                'specifications' => $specifications,
            ]
        );

        $generator->writeChanges();

        $generator->generateClass(
            $ruleTestClassNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/rule/Test.tpl.php',
            [
                'ruleClassName' => $ruleClassNameDetails->getShortName(),
                'ruleClassFullName' => $ruleClassNameDetails->getFullName(),
                'specifications' => $specifications,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
    }

    /**
     * @param ConsoleStyle $io
     *
     * @return array
     */
    private function getSpecifications(ConsoleStyle $io): array
    {
        $currentSpecifications = [];
        $isFirstSpecification = true;
        while (true) {
            $newSpecification = $this->askForNextSpecification($io, $currentSpecifications, $isFirstSpecification);
            $isFirstSpecification = false;

            if (null === $newSpecification) {
                break;
            }

            $currentSpecifications[] = $newSpecification;
        }

        return $currentSpecifications;
    }

    /**
     * @param ConsoleStyle $io
     *
     * @return array
     */
    private function getTags(ConsoleStyle $io): array
    {
        $currentTags = [];
        $isFirstTag = true;
        while (true) {
            $newTag = $this->askForNextTag($io, $currentTags, $isFirstTag);
            $isFirstTag = false;

            if (null === $newTag) {
                break;
            }

            $currentTags[] = $newTag;
        }

        return $currentTags;
    }

    /**
     * @param ConsoleStyle $io
     * @param array $tags
     * @param bool $isFirstTag
     *
     * @return array
     */
    public function askForNextTag(ConsoleStyle $io, array $tags, bool $isFirstTag): ?array
    {
        $io->write('');

        if ($isFirstTag) {
            $questionText = 'New tag (press <return> to stop adding tags)';
        } else {
            $questionText = 'Add another tag ? Enter the tag name (or press <return> to stop adding tags)';
        }

        $tagName = $io->ask($questionText, null, function ($name) use ($tags) {
            // allow it to be empty
            if (!$name) {
                return $name;
            }

            if (\in_array($name, array_column($tags, 'tagName'))) {
                throw new \InvalidArgumentException(sprintf('The "%s" tag already exists.', $name));
            }

            return $name;
        });

        if (!$tagName) {
            return null;
        }
        
        $priority = null;
        
        while(true) {
            $question = new Question('Priority of the tag (must be an interger)');
            $priority = $io->askQuestion($question);

            if (null !== $priority && !is_numeric($priority)) {
                $io->error(sprintf('Invalid priority "%s". (Must be an integer)', $priority));
                $io->writeln('');

                $priority = null;
                continue;
            }

            break;
        }

        return [
            'tagName' => $tagName,
            'priority' => null !== $priority ? intval($priority) : null,
        ];
    }

    /**
     * @param ConsoleStyle $io
     * @param array $specifications
     * @param bool $isFirstSpecification
     *
     * @return array|null
     */
    public function askForNextSpecification(
        ConsoleStyle $io,
        array $specifications,
        bool $isFirstSpecification
    ): ?array {
        $io->write('');

        if ($isFirstSpecification) {
            $question = new Question('New specification (press <return> to stop adding specifications)');
        } else {
            $question = new Question('Add another specification ? Enter the specification name (or press <return> to stop adding specification)');
        }

        $specificationsData = $this->getSpecificationsData();

        $question->setAutocompleterValues(array_column($specificationsData, 'shortClassName'));
        $question->setValidator(function ($name) use ($specifications) {
            // allow it to be empty
            if (!$name) {
                return $name;
            }

            if (\in_array($name, array_column($specifications, 'shortClassName'))) {
                throw new \InvalidArgumentException(sprintf('The "%s" specification already exists.', $name));
            }

            return $name;
        });

        $newSpecification = null;

        while (null === $newSpecification) {
            $specificationName = $io->askQuestion($question);

            if (!$specificationName) {
                return null;
            }

            $newSpecification = $this->getSpecificationFromShorClassName($specificationsData, $specificationName);

            if (null === $newSpecification) {
                $io->error(sprintf('The specification "%s" doesn\'t exist.', $specificationName));
            }
        }

        return $newSpecification;
    }

    /**
     * @return array
     */
    private function getSpecificationsData(): array
    {
        $specifications = [];
        foreach ($this->specifications as $specification) {
            $reflection = new \ReflectionClass(get_class($specification));
            $specifications[] = [
                'fullClassName' => get_class($specification),
                'shortClassName' => $reflection->getShortName(),
                'namespaceName' => $reflection->getNamespaceName(),
            ];
        }

        return $specifications;
    }

    /**
     * @param array $specifications
     * @param string $name
     *
     * @return array|null
     */
    private function getSpecificationFromShorClassName(array $specifications, string $name): ?array
    {
        foreach ($specifications as $specification) {
            if ($specification['shortClassName'] === $name) {
                return $specification;
            }
        }

        return null;
    }
}
