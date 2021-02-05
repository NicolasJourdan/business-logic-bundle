Business Logic Bundle
=====================
[![Build Status](https://travis-ci.org/NicolasJourdan/business-logic-bundle.svg?branch=main)](https://travis-ci.org/github/NicolasJourdan/business-logic-bundle)
[![Latest Stable Version](https://poser.pugx.org/nicolasjourdan/business-logic-bundle/v)](//packagist.org/packages/nicolasjourdan/business-logic-bundle)
[![Total Downloads](https://poser.pugx.org/nicolasjourdan/business-logic-bundle/downloads)](//packagist.org/packages/nicolasjourdan/business-logic-bundle)
[![Latest Unstable Version](https://poser.pugx.org/nicolasjourdan/business-logic-bundle/v/unstable)](//packagist.org/packages/nicolasjourdan/business-logic-bundle)
[![License](https://poser.pugx.org/nicolasjourdan/business-logic-bundle/license)](//packagist.org/packages/nicolasjourdan/business-logic-bundle)

The **BusinessLogicBundle** helps you to create and to implement your business rules.

Installation
============

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute:

```console
$ composer require nicolasjourdan/business-logic-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    NicolasJourdan\BusinessLogicBundle\NicolasJourdanBusinessLogicBundle::class => ['all' => true],
];
```

Documentation
=============

The source of the documentation is stored in the `Resources/doc/` folder in this bundle.
The full documentation is [here](Resources/doc/index.md).

This bundle implements 3 design patterns (Specification, Rules and Chain of responsibility) in order to define a system to help you to create your own business rules.  

There are 3 important objects in this conception : 
- **Specification** : This is your business condition. It is used to define if a business rule has to be executed on the current candidate.
- **Rule** : This is your business rule. 
- **RulesEngine** : This object contains several rules and executes ones which have to be executed on the current candidate.

Please, feel free to see the [demo](https://github.com/NicolasJourdan/demo-business-logic-bundle).

Basic Usage
=============

## Create a Specification

### The specification class
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

## Create a Rule

### The rule class

```php
<?php

namespace Your\Namespace\Rule;

use NicolasJourdan\BusinessLogicBundle\Service\Rule\RuleInterface;
use Your\Namespace\Specification\IsDummySpecification;

class DummyRule implements RuleInterface
{
    private const BUSINESS_LOGIC_TAGS = [
        ['rule.user.awesome', 10],
        ['rule.user.another_tag'],
    ];

    /** @var IsDummySpecification */
    private $specification;

    public function __construct(IsDummySpecification $specification)
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

You can generate this class with the next command `bin/console make:rule DummyRule`.

### The service declaration

You dont need to declare your rules into the **services.yml** file thanks to the indication `implements RuleInterface`.
If you decide to declare it into this file, the tags that you defined will be automatically merged with those of the constant.  
For instance, the `DummyRule` will be tagged with : `rule.user.vip`, `rule.user.basic`, `rule.user.awesome` and `rule.user.another_tag`.

```yaml
# config/services.yml
    Your\Namespace\Rule\DummyRule:
        arguments:
            $specification: '@Your\Namespace\Specification\IsDummySpecification'
        tags:
            - { name: 'rule.user.vip', priority: 20 } # Tag your rule in order to include it into the related RulesEngine
            - 'rule.user.basic' # You can add several tags to a single rule

```

## Create a RulesEngine

### RulesEngine declaration

```yaml
# config/services.yml
    rules.engine.user.vip:
        class: NicolasJourdan\BusinessLogicBundle\Service\Rule\RulesEngine
        arguments:
            $rules: !tagged rule.user.vip # Get all Rules tagged `rule.user.vip`
```

## Use your created RulesEngine

### Your service class
```php
<?php

namespace Your\Namespace\Service;

use NicolasJourdan\BusinessLogicBundle\Service\Rule\RulesEngine;

class Dummy
{
    /** @var RulesEngine */
    private $rulesEngine;

    public function __construct(RulesEngine $rulesEngine)
    {
        $this->rulesEngine = $rulesEngine;
    }

    public function foo(): void
    {
        $candidate = ... 
        
        // The RulesEngine will execute all the business rules which have to be executed on the candidate.
        $updatedCandidate = $this->rulesEngine->execute($candidate);
        
        //...
    }
}
```

### The service declaration

```yaml
# config/services.yml
    Your\Namespace\Service\Dummy:
        arguments:
            $rulesEngine: '@rules.engine.user.vip'
```

License
=============

This bundle is under the MIT license. See the complete license [in this bundle](LICENSE).
