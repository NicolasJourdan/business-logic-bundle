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
 * Class RulesEngine
 *
 * @package NicolasJourdan\BusinessLogicBundle\Service\Rule
 */
class RulesEngine
{
    /** @var RuleInterface $rules */
    private $rules;

    /**
     * RulesEngine constructor.
     *
     * @param iterable $rules
     */
    public function __construct(iterable $rules)
    {
        $this->rules = $rules;
        foreach ($this->rules as $rule) {
            if (!$rule instanceof RuleInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The rule "%s" must implement "%s"',
                        get_class($rule),
                        RuleInterface::class
                    )
                );
            }
        }
    }

    /**
     * @param $candidate
     *
     * @return mixed
     */
    public function execute($candidate)
    {
        /** @var RuleInterface $rule */
        foreach ($this->rules as $rule) {
            if ($rule->shouldRun($candidate)) {
                $candidate = $rule->execute($candidate);
            }
        }

        return $candidate;
    }
}
