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
 * Interface for classes describing a business specification.
 *
 * @package NicolasJourdan\BusinessLogicBundle\Service\Specification
 */
interface Specification
{
    /**
     * @param $candidate
     *
     * @return bool
     */
    public function isSatisfiedBy($candidate): bool;

    /**
     * @param Specification $specification
     *
     * @return Specification
     */
    public function and(Specification $specification): Specification;

    /**
     * @param Specification $specification
     *
     * @return Specification
     */
    public function or(Specification $specification): Specification;

    /**
     * @return Specification
     */
    public function not(): Specification;
}
