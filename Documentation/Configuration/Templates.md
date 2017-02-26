# Templates


## Default templates
The default templates are located in the extensions resources folder. This location is configured in default TypoScript:
```
plugin.tx_t3events {
    view {
        # keep old templateRootPath for backward compatibility
        templateRootPath = {$plugin.tx_t3events.view.templateRootPath}
        templateRootPaths {
            10 = {$plugin.tx_t3events.view.templateRootPath}
        }
        partialRootPath = {$plugin.tx_t3events.view.partialRootPath}
        partialRootPaths {
            10 = {$plugin.tx_t3events.view.partialRootPath}
        }
        layoutRootPath = {$plugin.tx_t3events.view.layoutRootPath}
        templateRootPaths {
            10 = {$plugin.tx_t3events.view.layoutRootPath}
        }
    }
}
```

## Custom templates

In order to change a single template, partial or layout just configure additional root paths (and put your files there)
```
plugin.tx_t3events {
    view {
        templateRootPaths {
            20 = path/to/additional/template/root
        }
        partialRootPaths {
            20 = path/to/additional/partial/root
        }
        layoutRootPaths {
            20 = path/to/additional/layout/root
        }
    }
}
```

## Additional template layouts
Add to your Page TS something like:
```
tx_t3events.templateLayouts {
  10 = Foo
  20 = Bar
}
```

After clearing the cache the layout selector in the *Template* tab of the plugin should display two additional options *Foo* and *Bar*. 

The selected key is now available in the template:
```
<f:if condition="{settings.templateLayout} == 10">
  <f:render section="foo"
            arguments="{performances: performances, settings: settings, data: data}"/>
</f:if>
```
