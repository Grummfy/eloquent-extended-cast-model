<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Grummfy\EloquentExtendedCast\Eloquent\JsonReadOnlyCollectionCastable;
use Grummfy\EloquentExtendedCast\Eloquent\CastableModel;
use Grummfy\EloquentExtendedCast\ValueObject\ReadOnlyCollection;

class SuperModel extends Model
{
	use CastableModel;
	use JsonReadOnlyCollectionCastable;

	protected $casts = [
		'phones' => 'jsonCollection',
		'descriptions' => 'jsonCollection',
		'period' => 'period',
	];

	protected $fillable = [
		'phones',
		'period',
		'descriptions',
	];

	protected $_castParameters = [
		'descriptions' => TranslatableLabel::class,
		'phones' => Phone::class,
	];

	public function __construct(array $attributes = [])
	{
		$this->descriptions = new ReadOnlyCollection();
		$this->phones = new ReadOnlyCollection();
		parent::__construct($attributes);
	}

	public function addDescription(string $language, string $description)
	{
		$this->descriptions = $this->descriptions
			->push(new TranslatableLabel($language, $description));
		return $this;
	}

	public function getDescription(string $language): string
	{
		$description = $this->descriptions->toCollection()->first(function(TranslatableLabel $value) use ($language)
		{
			return $value->getLanguage() == $language;
		});

		return $description ? $description->getLabel() : ($this->descriptions->toCollection()->isNotEmpty() ? $this->descriptions->toCollection()->first()->getLabel() : '-');
	}

	public function addPhone(string $prefix, string $number): self
	{
		$this->attributes['phones'] = $this->phones->push(new Phone($prefix, $number));

		return $this;
	}

	public function setPhonesAttribute(Collection $phones): self
	{
		$this->attributes['phones'] = new ReadOnlyCollection($phones);
		return $this;
	}

	public function fromPeriod(Period $period): string
	{
		return $period->format();
	}

	public function toPeriod(string $period): Period
	{
		return Period::fromString($period);
	}
}
