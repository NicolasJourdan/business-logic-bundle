<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\Service\Rule;

/**
 * Interface for classes describing a business rule.
 *
 * @package NicolasJourdan\BusinessLogicBundle\Service\Rule
 */
interface RuleInterface
{
    /**
     * Evaluates if the business rule should be applied to the passing candidate.
     *
     * @param $candidate
     *
     * @return bool
     */
    public function shouldRun($candidate): bool;

    /**
     * Executes the business rule to the passing candidate.
     *
     * @param $candidate
     *
     * @return mixed
     */
    public function execute($candidate);
}
