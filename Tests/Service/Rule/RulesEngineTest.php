<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\Tests\Service\Rule;

use NicolasJourdan\BusinessLogicBundle\Service\Rule\RuleInterface;
use NicolasJourdan\BusinessLogicBundle\Service\Rule\RulesEngine;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class RulesEngineTest
 *
 * @package NicolasJourdan\BusinessLogicBundle\Tests\Service\Rule
 *
 * @covers \NicolasJourdan\BusinessLogicBundle\Service\Rule\RulesEngine
 */
class RulesEngineTest extends TestCase
{
    /** @var MockObject */
    private $firstRule;

    /** @var MockObject */
    private $secondRule;

    /** @var RulesEngine */
    private $rulesEngine;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->firstRule = $this->createMock(RuleInterface::class);
        $this->secondRule = $this->createMock(RuleInterface::class);

        $this->rulesEngine = new RulesEngine([$this->firstRule, $this->secondRule]);
    }

    public function testExecuteWithoutRule(): void
    {
        $candidate = 'dummy';
        $rulesEngine = new RulesEngine([]);

        $this->assertSame($candidate, $rulesEngine->execute($candidate));
    }

    public function testExecuteOnlyFirstRuleShouldRun(): void
    {
        $candidate = 'dummy';
        $candidateFirstUpdated = $candidate . 'First';

        $this->firstRule
            ->expects($this->once())
            ->method('shouldRun')
            ->with($candidate)
            ->willReturn(true)
        ;

        $this->firstRule
            ->expects($this->once())
            ->method('execute')
            ->with($candidate)
            ->willReturn($candidateFirstUpdated)
        ;

        $this->secondRule
            ->expects($this->once())
            ->method('shouldRun')
            ->with($candidateFirstUpdated)
            ->willReturn(false)
        ;

        $this->secondRule
            ->expects($this->never())
            ->method('execute')
        ;

        $this->assertEquals(
            $candidateFirstUpdated,
            $this->rulesEngine->execute($candidate)
        );
    }

    public function testExecute(): void
    {
        $candidate = 'dummy';
        $candidateFirstUpdated = $candidate . 'First';
        $candidateSecondUpdated = $candidateFirstUpdated . 'Second';

        $this->firstRule
            ->expects($this->once())
            ->method('shouldRun')
            ->with($candidate)
            ->willReturn(true)
        ;

        $this->firstRule
            ->expects($this->once())
            ->method('execute')
            ->with($candidate)
            ->willReturn($candidateFirstUpdated)
        ;

        $this->secondRule
            ->expects($this->once())
            ->method('shouldRun')
            ->with($candidateFirstUpdated)
            ->willReturn(true)
        ;

        $this->secondRule
            ->expects($this->once())
            ->method('execute')
            ->with($candidateFirstUpdated)
            ->willReturn($candidateSecondUpdated)
        ;

        $this->assertEquals(
            $candidateSecondUpdated,
            $this->rulesEngine->execute($candidate)
        );
    }
}
