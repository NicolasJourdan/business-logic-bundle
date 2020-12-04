# Create a rule

## Contents

* [Business rule class](#business-rule-class)
  * [Example](#example)
* [Rule declaration](#rule-declaration)

## Business rule class

A specification has to implement `NicolasJourdan\BusinessLogicBundle\Service\Rule\RuleInterface`.

### Example

```php
<?php

namespace Your\Namespace\Rule;

use NicolasJourdan\BusinessLogicBundle\Service\Rule\RuleInterface;
use Your\Namespace\Specification\IsDummy;

class DummyRule implements RuleInterface
{
    private const BUSINESS_LOGIC_TAGS = [
        ['rule.user.awesome', 10],
        ['rule.user.another_tag'],
    ];

    /** @var IsDummy */
    private $specification;

    public function __construct(IsDummy $specification)
    {
        $this->specification = $specification;
    }

    public function shouldRun($candidate): bool
    {
        return $this->specification
            ->not()
            ->isSatisfiedBy($candidate);
    }

    public function execute($candidate)
    {
        // Your business rule...
    }
}
```

 Thanks to the private constant `BUSINESS_LOGIC_TAGS` you can dynamically add tags on your rule. 
 This constant must be an array of arrays. Each array corresponds to a tag. The first argument must be a string
 and it is corresponding to the tag **name**. The second one is optional, it must be an integer and it is corresponding to the **priority**.

## Rule declaration

You dont need to declare your rules into the **services.yml** file thanks to the indication `implements RuleInterface`. 
If you decide to declare it into this file, the tags that you defined will be automatically merged with those of the constant.  
For instance, the `DummyRule` will be tagged with : `rule.user.vip`, `rule.user.basic`, `rule.user.awesome` and `rule.user.another_tag`.

```yaml
# config/services.yml
    Your\Namespace\Rule\DummyRule:
        arguments:
            $specification: '@Your\Namespace\Specification\IsDummy'
        tags:
            - { name: 'rule.user.vip', priority: 20 } # Tag your rule in order to include it into the related RulesEngine
            - 'rule.user.basic' # You can add several tags to a single rule

```

It is possible to specify one or more **tags** in order to inject it in dependency of a [RulesEngine](index.md#rulesengine).
