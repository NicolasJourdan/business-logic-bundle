<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\Service\Specification;

/**
 * Class AndSpecification
 *
 * @package NicolasJourdan\BusinessLogicBundle\Service\Specification
 */
class AndSpecification extends CompositeSpecification
{
    /** @var Specification $leftCondition */
    private $leftCondition;

    /** @var Specification $rightCondition */
    private $rightCondition;

    /**
     * AndSpecification constructor.
     *
     * @param Specification $leftCondition
     * @param Specification $rightCondition
     */
    public function __construct(Specification $leftCondition, Specification $rightCondition)
    {
        $this->leftCondition = $leftCondition;
        $this->rightCondition = $rightCondition;
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return $this->leftCondition->isSatisfiedBy($candidate) && $this->rightCondition->isSatisfiedBy($candidate);
    }
}
