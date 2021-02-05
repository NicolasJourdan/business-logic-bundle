<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use PHPUnit\Framework\TestCase;
use <?= $specificationClassFullName ?>;

/**
 * Class <?= $class_name ?>

 *
 * @package <?= $namespace; ?>

 *
 * @covers \<?= $specificationClassFullName ?>

 */
class <?= $class_name ?> extends TestCase
{
    /** @var <?= $specificationClassName ?> */
    private $specification;

    /** {@inheritDoc} */
    public function setUp()
    {
        $this->specification = new <?= $specificationClassName ?>();
    }

    /**
     * @covers \<?= $specificationClassFullName ?>::isSatisfiedBy
     */
    public function testIsSatisfiedBy(): void
    {
        $this->assertFalse($this->specification->isSatisfiedBy());
        $this->assertTrue($this->specification->isSatisfiedBy());
    }
}
