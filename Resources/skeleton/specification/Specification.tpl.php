<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use NicolasJourdan\BusinessLogicBundle\Service\Specification\CompositeSpecification;

/**
 * Class <?= $class_name ?>

 *
 * @package <?= $namespace; ?>

 */
class <?= $class_name ?> extends CompositeSpecification
{
    /** {@inheritDoc} */
    public function isSatisfiedBy($candidate): bool
    {
        // Your business condition...
    }
}
