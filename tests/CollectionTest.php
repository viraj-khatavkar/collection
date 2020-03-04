<?php

namespace VirajKhatavkar\Collect\Tests;

use PHPUnit\Framework\TestCase;
use VirajKhatavkar\Collect\Collection;

class CollectionTest extends TestCase
{
    /** @test */
    public function count_returns_the_number_of_items_in_a_collection()
    {
        $items = new Collection([1, 2, 3]);

        $this->assertSame(3, $items->count());
    }

    /** @test */
    public function to_array_returns_an_array_of_all_items()
    {
        $items = new Collection([range(1, 4)]);

        $this->assertIsArray($items->toArray());
    }

    /** @test */
    public function first_returns_the_first_element_of_collection()
    {
        $items = new Collection(range(1, 4));

        $this->assertSame(1, $items->first());
    }

    /** @test */
    public function first_returns_the_first_element_of_collection_matching_the_condition_passed_in_callback()
    {
        $items = new Collection(range(1, 4));

        $this->assertSame(3, $items->first(function ($item) {
            return $item > 2;
        }));
    }

    /** @test */
    public function first_returns_the_default_value_if_no_match_found_for_condition()
    {
        $items = new Collection(range(1, 4));

        $this->assertSame(10, $items->first(function ($item) {
            return $item > 4;
        }, 10));
    }

    /** @test */
    public function first_returns_the_default_value_if_empty_collection()
    {
        $items = new Collection([]);

        $this->assertSame(null, $items->first());
    }

    /** @test */
    public function first_returns_the_first_element_of_an_associative_collection()
    {
        $items = new Collection(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->assertSame(1, $items->first());
    }

    /** @test */
    public function map_returns_collection_with_updated_values()
    {
        $items = new Collection(range(1, 4));

        $items = $items->map(function ($item) {
            return $item * 2;
        });

        $this->assertSame([2, 4, 6, 8], $items->toArray());
    }

    /** @test */
    public function map_preserves_keys_in_an_associative_collection()
    {
        $items = new Collection(['itemOne' => 'Viraj', 'itemTwo' => 'Komal']);

        $items = $items->map(function ($item, $key) {
            return $key;
        });

        $this->assertSame(['itemOne' => 'itemOne', 'itemTwo' => 'itemTwo'], $items->toArray());
    }

    /** @test */
    public function each()
    {
        $items = new Collection($initial = range(1, 5));

        $final = [];

        $items->each(function ($item, $key) use (&$final) {
            $final[$key] = $item;
        });

        $this->assertSame($initial, $final);
    }

    /** @test */
    public function filter()
    {
        $items = new Collection(range(1, 10));

        $items = $items->filter(function ($item) {
            return $item % 2 === 0;
        });

        $this->assertSame([
            1 => 2,
            3 => 4,
            5 => 6,
            7 => 8,
            9 => 10,
        ], $items->toArray());

        $items = new Collection(range(1, 10));
        $items = $items->filter(function ($item, $key) {
            return $key % 2 === 0;
        });

        $this->assertSame([
            0 => 1,
            2 => 3,
            4 => 5,
            6 => 7,
            8 => 9
        ], $items->toArray());
    }

    /** @test */
    public function reject()
    {
        $items = new Collection(range(1, 10));

        $items = $items->reject(function ($item) {
            return $item % 2 === 0;
        });

        $this->assertSame([0 => 1, 2 => 3, 4 => 5, 6 => 7, 8 => 9,], $items->toArray());

        $items = new Collection(range(1, 10));
        $items = $items->reject(function ($item, $key) {
            return $key % 2 === 0;
        });

        $this->assertSame([
            1 => 2,
            3 => 4,
            5 => 6,
            7 => 8,
            9 => 10
        ], $items->toArray());
    }

    /** @test */
    public function can_access_collection_as_array()
    {
        $items = new Collection(range(1, 5));

        $this->assertEquals(1, $items[0]);
        $this->assertEquals(2, $items[1]);
        $this->assertEquals(3, $items[2]);
        $this->assertEquals(4, $items[3]);
        $this->assertEquals(5, $items[4]);

        $items[0] = 10;
        $this->assertSame(10, $items[0]);
        $this->assertSame(10, $items->first());
    }
}
