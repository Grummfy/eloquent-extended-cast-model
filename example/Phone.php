<?php

namespace App\Models;

use Grummfy\EloquentExtendedCast\Contracts\StdAbleData;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Phone implements Arrayable, Jsonable, StdAbleData, \JsonSerializable
{
	/**
	 * @var string
	 */
	protected $number;

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * @var string
	 */
	protected $msisdn;

	public static function fromStdClass(\stdClass $data)
	{
		return new static($data->prefix, $data->number, $data->msisdn ?? null);
	}

	public function __construct(string $prefix, string $number, string $msisdn = null)
	{
		$this->number = preg_replace('/[^0-9]/', '', $number);
		$this->prefix = preg_replace('/[^0-9]/', '', $prefix);
		$this->msisdn = $msisdn ?? $this->computeMsisdn();
	}

	/**
	 * @return string
	 */
	public function getNumber(): string
	{
		return $this->number;
	}

	/**
	 * @return string
	 */
	public function getPrefix(): string
	{
		return $this->prefix;
	}

	/**
	 * @return string
	 */
	public function getMsisdn(): string
	{
		return $this->msisdn;
	}

	public function __toString(): string
	{
		return '(+' . $this->getPrefix() . ') ' . $this->getNumber();
	}

	public function jsonSerialize()
	{
		return $this->toArray();
	}

	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	public function toArray(): array
	{
		return [
			'prefix' => $this->getPrefix(),
			'number' => $this->getNumber(),
			'msisdn' => $this->getMsisdn(),
		];
	}

	protected function computeMsisdn()
	{
		return ltrim($this->getPrefix(), '0') . ltrim($this->getNumber(), '0');
	}
}
