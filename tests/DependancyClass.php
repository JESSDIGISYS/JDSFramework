<?php

namespace JDS\Tests;

use JDS\Tests\SubDependencyClass;

class DependancyClass
{

	// with php > 8.0 you don't have to specify the propertys explicitly 
	// you can do it in the constructor like below
	public function __construct(private SubDependencyClass $subDependency)
	{
	}

	public function getSubDependency(): SubDependencyClass
	{
		return $this->subDependency;
	}
}
