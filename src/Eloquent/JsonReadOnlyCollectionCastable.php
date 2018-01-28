<?php

namespace Grummfy\EloquentExtendedCast\Eloquent;

use Grummfy\EloquentExtendedCast\ValueObject\ReadOnlyCollection;

/**
 * Helper for json collection.
 *
 * Add the name of the field in $this->castParameters
 * Then in the cast array ($this->casts) add your field with 'jsonCollection'
 *
 * protected $castParameters = ['foo' => Foo::class];
 * protected $casts = ['foo' => 'jsonReadOnlyCollection'];
 *
 * public function addFoo(Foo $foo)
 * {
 *  if (!$this->foo) {$this->foo = new ReadOnlyCollection()}
 *  $this->foo = $this->foo->push(new Phone($prefix, $number));
 * }
 */
trait JsonReadOnlyCollectionCastable
{
	public function toJsonReadOnlyCollection(ReadOnlyCollection $collection): string
	{
		return $collection->toJson();
	}

	public function fromJsonReadOnlyCollection(?string $json, ?string $className = null): ReadOnlyCollection
	{
		$data = array_values((array)$this->fromJson($json, true));
		$castClass = $className ?? \stdClass::class;
		return ReadOnlyCollection::fromArrayOfStdClass(function($data) use ($castClass)
		{
			return $castClass::fromStdClass($data);
		}, ...$data);
	}
}
