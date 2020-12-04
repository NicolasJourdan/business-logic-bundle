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
 * Class CompositeSpecification
 *
 * @package NicolasJourdan\BusinessLogicBundle\Service\Specification
 */
abstract class CompositeSpecification implements Specification
{
    /**
     * {@inheritDoc}
     */
    final public function and(Specification $other): Specification
    {
        return new AndSpecification($this, $other);
    }

    /**
     * {@inheritDoc}
     */
    final public function or(Specification $other): Specification
    {
        return new OrSpecification($this, $other);
    }

    /**
     * {@inheritDoc}
     */
    final public function not(): Specification
    {
        return new NotSpecification($this);
    }
}
