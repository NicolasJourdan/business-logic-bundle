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
 * Class NotSpecification
 *
 * @package NicolasJourdan\BusinessLogicBundle\Service\Specification
 */
class NotSpecification extends CompositeSpecification
{
    /** @var Specification $specification */
    private $specification;

    /**
     * NotSpecification constructor.
     * @param Specification $specification
     */
    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    /**
     * {@inheritDoc}
     */
    public function isSatisfiedBy($candidate): bool
    {
        return !$this->specification->isSatisfiedBy($candidate);
    }
}
