<?php

/*
 * This file is part of the NicolasJourdanBusinessLogicBundle package.
 *
 * (c) Nicolas Jourdan <nicolas.jourdan.c@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NicolasJourdan\BusinessLogicBundle\Tests\Service\Specification;

use NicolasJourdan\BusinessLogicBundle\Service\Specification\AndSpecification;
use NicolasJourdan\BusinessLogicBundle\Service\Specification\Specification;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AndSpecificationTest
 *
 * @package NicolasJourdan\BusinessLogicBundle\Tests\Service\Specification
 *
 * @covers \NicolasJourdan\BusinessLogicBundle\Service\Specification\AndSpecification
 */
class AndSpecificationTest extends TestCase
{
    /** @var MockObject */
    private $leftCondition;

    /** @var MockObject */
    private $rightCondition;

    /** @var AndSpecification */
    private $andSpecification;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->leftCondition = $this->createMock(Specification::class);
        $this->rightCondition = $this->createMock(Specification::class);

        $this->andSpecification = new AndSpecification(
            $this->leftCondition,
            $this->rightCondition
        );
    }

    public function testIsSatisfiedBy(): void
    {
        $candidate = 'dummy';

        $this->leftCondition
            ->expects($this->exactly(4))
            ->method('isSatisfiedBy')
            ->willReturnOnConsecutiveCalls(true, false, false, true)
        ;

        $this->rightCondition
            ->expects($this->exactly(2))
            ->method('isSatisfiedBy')
            ->willReturnOnConsecutiveCalls(true, false)
        ;

        $this->assertTrue($this->andSpecification->isSatisfiedBy($candidate));
        $this->assertFalse($this->andSpecification->isSatisfiedBy($candidate));
        $this->assertFalse($this->andSpecification->isSatisfiedBy($candidate));
        $this->assertFalse($this->andSpecification->isSatisfiedBy($candidate));
    }
}
