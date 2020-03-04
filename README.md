# collection

A lightweight, framework agnostic collection library for PHP

```php
$items = new VirajKhatavkar\Collect\Collection([1, 2, 3]);

$result = $items->map(function($item) {
    return $item * 2;
});

$result->toArray(); //[2, 4, 6]
```
