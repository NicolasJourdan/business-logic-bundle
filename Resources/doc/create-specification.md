# Create a specification

## Contents

* [Specification class](#specification-class)
  * [Example](#example)

## Specification class

A specification has to extend `NicolasJourdan\BusinessLogicBundle\Service\Specification\CompositeSpecification`.

### Example

```php
<?php

namespace Your\Namespace\Specification;

use NicolasJourdan\BusinessLogicBundle\Service\Specification\CompositeSpecification;

class IsDummySpecification extends CompositeSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        // Your business condition...
    }
}
```

You can generate this class with the next command `bin/console make:specification IsDummySpecification`.
