<?php

namespace Grummfy\EloquentExtendedCast\Contracts;

interface StdAbleData
{
	/**
	 * Build a new object instance from the stdClass data
	 * @return self
	 */
	public static function fromStdClass(\stdClass $data);
}
