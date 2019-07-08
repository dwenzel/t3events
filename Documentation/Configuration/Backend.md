Backend Configuration
=====================

## Modules
Backend modules can be configured via TypoScript.
The settings can be inspected using the TypoScript Object Browser in the Backend.
**Note**: The TypoScript for modules is loaded from the files `ext_typoscript_setup.txt` and `ext_typoscript_constants.txt`.
The display in TypoScript Object Browse might differ from the settings actually presented to the module. (I.e. changes in 
a template record might be ineffective)

### Storage Pages
The _Events_ and _Schedules_ modules do not show a page tree. The storage pages must be set via TypoScript.

**Constants**
```typo3_typoscript
module {
  tx_t3events {
    persistence {
      storagePid = 1
    }
  }
}
```

**Setup**
```typo3_typoscript
module {
  tx_t3events {
    persistence {
      storagePid = {$module.tx_t3events.persistence.storagePid}
    }
  }
}
```

### Template Paths
Templates, partials and layouts can be extended by configuring additional paths
```typo3_typoscript
module.tx_t3events {
  persistence {
     storagePid = {$module.tx_t3events.persistence.storagePid}
  }

  view {
    templateRootPaths {
      20 = /path/to/additional/template/folder
    }
    
    partialRootPaths {
      20 = /path/to/additional/partial/folder
    }
    
    layoutRootPaths {
      20 = /path/to/additional/layout/folder
    }
  }
}

````

### Resources

#### Require Js
The TYPO3 Backend uses requireJs in order to load JavaScript modules. 
Event modules allow to include custom libraries and modules 

**Register namespace and folder** 
```typo3_typoscript
module.tx_t3events {
  view {
    pageRenderer {
      requireJs {
        nameOfBarLibrary {
            path = /path/to/other/folder
        }    
      }
    }
  }
}
```
Registers the namespace `nameOfBarLibrary`. Any file ending in `.js` in this file can be required by its name:
For instance a file `baz.min.js` would be required like:
```javascript
    define (['nameOfBarLibrary/baz.min'], function (x) {
     x.myAwsomeCode();
 });
```

**Register namespace and require module(s)**
```typo3_typoscript
module.tx_t3events {
  view {
    pageRenderer {
      requireJs {
        nameOfFooLibrary {
            path = /path/to/custom/folder
            modules {
              0 = fancyStuffIWrote
            }
        }
      }
    }
  }
}
```
Registers the namespace `nameOfFooLibrary` and requires the module `fancyStuffIwrote`. 
