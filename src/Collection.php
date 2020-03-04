<?php


namespace VirajKhatavkar\Collect;


use Closure;

class Collection implements \ArrayAccess
{
    /**
     * @var array
     */
    protected array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function first(callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($this->items) ? $default : $this->items[array_key_first($this->items)];
        }

        foreach ($this->items as $item) {
            if (call_user_func($callback, $item)) {
                return $item;
            }
        }

        return $default;
    }

    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_combine($keys, array_map($callback, $this->items, $keys));

        return new static($items);
    }

    public function each(callable $callback)
    {
        array_walk($this->items, $callback);

        return $this;
    }

    public function filter(callable $callback)
    {
        return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    public function reject(callable $callback)
    {
        return $this->filter(function ($item, $key) use ($callback) {
            return call_user_func($callback, $item, $key) === false;
        });
    }

    public function flatten()
    {

    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        is_null($offset) ? $this->items[] = $value : $this->items[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}