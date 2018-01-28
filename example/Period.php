<?php

namespace App\Models;

class Period
{
	protected $month;
	protected $year;

	public static function fromString($period): self
	{
		$period = explode('-', $period);
		return new self($period[0], $period[1]);
	}

	public static function fromDateTime(\DateTimeInterface $date): self
	{
		return new self($date->format('Y'), $date->format('n'));
	}

	public function __construct(int $year, int $month)
	{
		if (1 > $month || $month > 12)
		{
			throw new \OutOfRangeException('A month is between 1 and 12');
		}

		$this->year = $year;
		$this->month = $month;
	}

	public function getMonth(): int
	{
		return $this->month;
	}

	public function getYear(): int
	{
		return $this->year;
	}

	public function format(): string
	{
		return sprintf('%04d-%02d', $this->year, $this->month);
	}

	public function __toString()
	{
		return $this->format();
	}
}
