# Create a rules engine

## Contents
* [RulesEngine declaration](#rulesengine-declaration)

## RulesEngine declaration

```yaml
# config/services.yml
    rules.engine.user.vip:
        class: NicolasJourdan\BusinessLogicBundle\Service\Rule\RulesEngine
        arguments:
            $rules: !tagged rule.user.vip # Get all Rules tagged `rule.user.vip`
```

The argument `$rules` corresponds to all the rules that compose the `RulesEngine`. These rules are retrieved thanks to 
to their **tags**. In the example above, all the rules tagged `rule.user.vip` compose 
our`RulesEngine`.
