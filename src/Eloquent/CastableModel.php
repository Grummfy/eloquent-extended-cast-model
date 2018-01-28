<?php

namespace Grummfy\EloquentExtendedCast\Eloquent;

/**
 * Defined extra cast on eloquent model
 *
 * The name of the cast method will be fromX or toX where X is the cast
 * If you want to add some specific parameters, you can use
 *
 * protected $castParameters = ['foo' => Foo::class];
 * protected $casts = ['foo' => 'x'];
 * public function fromX()
 * public function toX()
 *
 * Inspired by
 * @see https://github.com/reliese/laravel/blob/master/src/Database/Eloquent/Model.php
 */
trait CastableModel
{
	// see \Illuminate\Database\Eloquent\Concerns\HasAttributes::castAttribute
	protected static $BASE_CAST = [
		'int',
		'integer',
		'real',
		'float',
		'double',
		'string',
		'bool',
		'boolean',
		'object',
		'array',
		'json',
		'collection',
		'date',
		'datetime',
		'timestamp',
	];
	
	public function setAttribute($key, $value)
	{
		if ($this->hasCustomSetCast($key))
		{
			$value = $this->{$this->getCustomSetCast($key)}($value, ...$this->getCustomCastParameters($key));
		}

		return parent::setAttribute($key, $value);
	}

	protected function castAttribute($key, $value)
	{
		if ($this->hasCustomGetCast($key))
		{
			return $this->{$this->getCustomGetCast($key)}($value, ...$this->getCustomCastParameters($key));
		}

		return parent::castAttribute($key, $value);
	}

	protected function hasCustomGetCast($key): bool
	{
		return $this->hasCustomCast($key) && method_exists($this, $this->getCustomGetCast($key));
	}

	protected function hasCustomSetCast($key): bool
	{
		return $this->hasCustomCast($key) && method_exists($this, $this->getCustomSetCast($key));
	}

	protected function getCustomGetCast($key): string
	{
		return 'from' . ucfirst($this->getCastType($key));
	}

	protected function getCustomSetCast($key): string
	{
		return 'to' . ucfirst($this->getCastType($key));
	}

	protected function hasCustomCast($key): bool
	{
		return $this->hasCast($key) && !$this->hasCast($key, self::$BASE_CAST);
	}

	protected function getCustomCastParameters($key): array
	{
		return (array) ($this->castParameters[ $key ] ?? null);
	}
}
