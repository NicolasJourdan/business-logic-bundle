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

use NicolasJourdan\BusinessLogicBundle\Service\Specification\NotSpecification;
use NicolasJourdan\BusinessLogicBundle\Service\Specification\Specification;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class NotSpecificationTest
 *
 * @package NicolasJourdan\BusinessLogicBundle\Tests\Service\Specification
 *
 * @covers \NicolasJourdan\BusinessLogicBundle\Service\Specification\NotSpecification
 */
class NotSpecificationTest extends TestCase
{
    /** @var MockObject */
    private $specification;

    /** @var NotSpecification */
    private $notSpecification;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->specification = $this->createMock(Specification::class);

        $this->notSpecification = new NotSpecification($this->specification);
    }

    public function testIsSatisfiedBy(): void
    {
        $candidate = 'dummy';

        $this->specification
            ->expects($this->exactly(2))
            ->method('isSatisfiedBy')
            ->willReturnOnConsecutiveCalls(true, false)
        ;

        $this->assertFalse($this->notSpecification->isSatisfiedBy($candidate));
        $this->assertTrue($this->notSpecification->isSatisfiedBy($candidate));
    }
}
