<?php

namespace Grummfy\EloquentExtendedCast\ValueObject;

use Countable;
use ArrayAccess;
use Illuminate\Support\Collection;
use JsonSerializable;
use IteratorAggregate;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class ReadOnlyCollection implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
{
	/**
	 * @var Collection
	 */
	protected $_collection;

	public static function fromArrayOfStdClass(callable $callback, \stdClass ...$data): self
	{
		return new static(Collection::make($data)->map($callback));
	}

	public function __construct(?Collection $collection = null)
	{
		$this->_collection = $collection ?? new Collection();
	}

	public function toCollection(): Collection
	{
		return clone $this->_collection;
	}

	public function push($item): self
	{
		return $this->_cloneIt(function(Collection $collection) use ($item)
		{
			$collection->push($item);
			return $collection;
		});
	}

	public function __toString()
	{
		return $this->toJson();
	}

	public function toJson($options = 0)
	{
		return $this->_collection->toJson($options);
	}

	public function jsonSerialize()
	{
		return $this->_collection->jsonSerialize();
	}

	public function toArray()
	{
		return $this->_collection->toArray();
	}

	public function getIterator()
	{
		return $this->_collection->getIterator();
	}

	public function offsetExists($offset)
	{
		return $this->_collection->offsetExists($offset);
	}

	public function offsetGet($offset)
	{
		return $this->_collection->offsetGet($offset);
	}

	public function first(?callable $callback = null, $default = null)
	{
		return $this->_collection->first($callback, $default);
	}

	public function offsetSet($offset, $value)
	{
		return $this->_cloneIt(function(Collection $collection) use ($offset, $value)
		{
			$collection->offsetSet($offset, $value);
			return $collection;
		});
	}

	public function offsetUnset($offset)
	{
		return $this->_cloneIt(function(Collection $collection) use ($offset)
		{
			$collection->offsetUnset($offset);
			return $collection;
		});
	}

	public function count()
	{
		return $this->_collection->count();
	}

	/**
	 * Create a new object after change
	 * @param callable $callback the callback that take a collection as argument
	 *
	 * @return ReadOnlyCollection
	 */
	protected function _cloneIt(callable $callback): self
	{
		return new self($callback($this->toCollection()));
	}
}
