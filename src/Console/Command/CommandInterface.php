<?php

namespace JDS\Console\Command;

interface CommandInterface
{
	public function execute(array $params = []): int; 

}


