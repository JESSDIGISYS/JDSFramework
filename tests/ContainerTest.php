<?php

namespace JDS\Tests;

use JDS\Container\Container;
use JDS\Exceptions\ContainerException;
use JDS\Tests\DependantClass;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
	/** @test */
	public function a_service_can_be_received_from_the_container()
	{
		// Setup
		$container = new Container();

		// Do something
		// take 2 args: 1 - id string, 2 - concrete class name string | object
		$container->add('dependant-class', DependantClass::class);

		// Make assertions
		$this->assertInstanceOf(DependantClass::class, $container->get('dependant-class'));
	}

	/** @test */
	public function a_ContainerException_is_thrown_if_a_service_cannot_be_found()
	{
		// Setup
		$container = new Container();

		// Expect exception
		$this->expectException(ContainerException::class);

		// Do something
		$container->add('foobar');

	}

	public function test_a_service_is_in_the_container()
	{
		// Setup
		$container = new Container();

		// Do something
		$container->add('dependant-class', DependantClass::class);

		// Make assertion
		$this->assertTrue($container->has('dependant-class'));

		$this->assertFalse(($container->has('non-exist-class')));
	}
	
}
