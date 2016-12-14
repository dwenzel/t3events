Format / ArrayToCsvViewHelper
==========================

Converts a one dimensional array to a string.  Delimiter and quote character can be choosen.  
If the _quote_ character is contained in the value it will be doubled!

**note**: This view helper is deprecated and will be removed.
          
## Examples

**Convert an array to comma-separated string**
```
<ts:format.arrayToCsv source="{'foo', 'bar'}" />
```
**Output**
```
"foo","bar"
```
**Convert an array to pipe-separated string quoted by back ticks**
```
<ts:format.arrayToCsv source="{'foo', 'bar'}" delimiter="|", quote="`" />
```
**Output**
```
`foo`|`bar`
```

**Inline usage**
```
{ts:format.arrayToCsv(source: '{\'foo\'}')}
```

## Arguments

| Argument | Type   | Description                       | Default |
| -------- | -------| --------------------------------- | ------- |
| source   | array  | required, must be one dimensional |         |
| delimiter| string | optional                          | ,       |
| source   | string | optional                          | "       |

