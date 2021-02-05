<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use NicolasJourdan\BusinessLogicBundle\Service\Rule\RuleInterface;
<?php if (!empty($specifications)): ?>
<?php foreach ($specifications as $specification): ?>
use <?= $specification['fullClassName'] ?>;
<?php endforeach; ?>
<?php endif; ?>

/**
 * Class <?= $class_name ?>

 *
 * @package <?= $namespace; ?>

 */
class <?= $class_name ?> implements RuleInterface
{
<?php if (empty($tags)):?>
    private const BUSINESS_LOGIC_TAGS = [];
<?php else: ?>
    private const BUSINESS_LOGIC_TAGS = [
<?php foreach ($tags as $tag): ?>
        ['<?= $tag['tagName'] ?>'<?php if (null !== $tag['priority']): ?>, <?= $tag['priority'] ?><?php endif; ?>],
<?php endforeach; ?>
    ];
<?php endif; ?>

<?php if (!empty($specifications)): ?>
<?php foreach ($specifications as $specification): ?>
    /**
     * @var <?= $specification['shortClassName']; ?>

     */
    private $<?= lcfirst($specification['shortClassName'])?>;

<?php endforeach; ?>
<?php $nbSpecification = count($specifications); ?>
    /**
    * <?= $class_name ?> constructor.
    *
<?php foreach ($specifications as $specification): ?>
    * @param <?= $specification['shortClassName']?> $<?= lcfirst($specification['shortClassName'])?>;
<?php endforeach; ?>
    */
<?php if ($nbSpecification === 1): ?>
    public function __construct(<?= $specification['shortClassName'] ?> $<?= lcfirst($specification['shortClassName'])?>)
    {
<?php else: ?>
    public function __construct(
<?php $index = 1; ?>
<?php foreach ($specifications as $specification): ?>
        <?= $specification['shortClassName'] ?> $<?= lcfirst($specification['shortClassName'])?><?php if ($index !== $nbSpecification): ?>,<?php endif; ?>

<?php $index++; ?>
<?php endforeach; ?>
    ) {
<?php endif; ?>
<?php foreach ($specifications as $specification): ?>
        $this-><?= lcfirst($specification['shortClassName'])?> = $<?= lcfirst($specification['shortClassName'])?>;
<?php endforeach; ?>
    }
<?php endif; ?>

    /** {@inheritDoc} */
    public function shouldRun($candidate): bool
    {
        // Your business logic
    }

    /** {@inheritDoc} */
    public function execute($candidate)
    {
        // Your business logic
    }
}
