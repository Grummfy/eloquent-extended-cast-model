<?php

namespace App\Models;

use Grummfy\EloquentExtendedCast\Contracts\StdAbleData;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class TranslatableLabel implements Arrayable, Jsonable, StdAbleData, \JsonSerializable
{
	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var string
	 */
	protected $language;

	public static function fromStdClass(\stdClass $data)
	{
		return new static($data->language, $data->label);
	}

	public function __construct(string $language, string $label)
	{
		$this->language = $language;
		$this->label = $label;
	}

	public function getLabel(): string
	{
		return $this->label;
	}

	public function getLanguage(): string
	{
		return $this->language;
	}

	public function jsonSerialize()
	{
		return $this->toArray();
	}

	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	public function toArray()
	{
		return [
			'label' => $this->getLabel(),
			'language' => $this->getLanguage(),
		];
	}
}
