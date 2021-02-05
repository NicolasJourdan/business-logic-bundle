<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use <?= $ruleClassFullName ?>;
<?php if (!empty($specifications)): ?>
<?php foreach ($specifications as $specification): ?>
use <?= $specification['fullClassName'] ?>;
<?php endforeach; ?>
use PHPUnit\Framework\MockObject\MockObject;
<?php endif; ?>
use PHPUnit\Framework\TestCase;

/**
 * Class <?= $class_name ?>

 *
 * @package <?= $namespace; ?>

 *
 * @covers \<?= $ruleClassFullName ?>

 */
class <?= $class_name ?> extends TestCase
{
<?php if (!empty($specifications)): ?>
<?php foreach ($specifications as $specification): ?>
    /** @var MockObject */
    private $<?= lcfirst($specification['shortClassName'])?>;

<?php endforeach; ?>
<?php endif; ?>
    /** @var <?= $ruleClassName ?> */
    private $specification;

    /** {@inheritDoc} */
    public function setUp()
    {
<?php if (!empty($specifications)): ?>
<?php foreach ($specifications as $specification): ?>
        $this-><?= lcfirst($specification['shortClassName'])?> = $this->createMock(<?= $specification['shortClassName'] ?>::class);
<?php endforeach; ?>
<?php endif; ?>

<?php $nbSpecification = count($specifications); ?>
<?php if ($nbSpecification === 1): ?>
        $this->specification = new <?= $ruleClassName ?>($this-><?= lcfirst($specifications[0]['shortClassName'])?>);
<?php else: ?>
        $this->specification = new <?= $ruleClassName ?>(
<?php $index = 1; ?>
<?php foreach ($specifications as $specification): ?>
            $this-><?= lcfirst($specification['shortClassName'])?><?php if ($index !== $nbSpecification): ?>,<?php endif; ?>

<?php $index++; ?>
<?php endforeach; ?>
        );
<?php endif; ?>
    }

    /**
    * @covers \<?= $ruleClassFullName ?>::shouldRun
    */
    public function testShouldRun(): void
    {
        $this->assertFalse($this->specification->shouldRun());
        $this->assertTrue($this->specification->shouldRun());
    }

    /**
    * @covers \<?= $ruleClassFullName ?>::shouldRun
    */
    public function testExecute(): void
    {
        // Your test
    }
}
