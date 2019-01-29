<?php declare(strict_types=1);
namespace app\Shared\Contracts;

interface Specification 
{
	public function isSatisfiedBy($object) : bool ;
}
