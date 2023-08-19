<?php

namespace JDS\Tests;

use JDS\Tests\DependancyClass;

class DependantClass
{

	public function __construct(private DependancyClass $dependency)
	{
	}

	public function getDependency(): DependancyClass
	{
		return $this->dependency;
	}
}
