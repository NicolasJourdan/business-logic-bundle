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

use NicolasJourdan\BusinessLogicBundle\Service\Specification\OrSpecification;
use NicolasJourdan\BusinessLogicBundle\Service\Specification\Specification;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class OrSpecificationTest
 *
 * @package NicolasJourdan\BusinessLogicBundle\Tests\Service\Specification
 *
 * @covers \NicolasJourdan\BusinessLogicBundle\Service\Specification\OrSpecification
 */
class OrSpecificationTest extends TestCase
{
    /** @var MockObject */
    private $leftCondition;

    /** @var MockObject */
    private $rightCondition;

    /** @var OrSpecification */
    private $orSpecification;

    protected function setUp(): void
    {
        $this->leftCondition = $this->createMock(Specification::class);
        $this->rightCondition = $this->createMock(Specification::class);

        $this->orSpecification = new OrSpecification($this->leftCondition, $this->rightCondition);
    }

    public function testIsSatisfiedBy(): void
    {
        $candidate = 'dummy';

        $this->leftCondition
            ->expects($this->exactly(3))
            ->method('isSatisfiedBy')
            ->willReturnOnConsecutiveCalls(true, false, false)
        ;

        $this->rightCondition
            ->expects($this->exactly(2))
            ->method('isSatisfiedBy')
            ->willReturnOnConsecutiveCalls(true, false)
        ;

        $this->assertTrue($this->orSpecification->isSatisfiedBy($candidate));
        $this->assertTrue($this->orSpecification->isSatisfiedBy($candidate));
        $this->assertFalse($this->orSpecification->isSatisfiedBy($candidate));
    }
}
