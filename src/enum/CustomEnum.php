<?php

declare(strict_types=1);

namespace skymin\CommandHelper\enum;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use function array_unique;

abstract class CustomEnum{

	protected array $values;

	public function __construct(
		protected string $name,
		string ...$values
	){
		$this->values = array_unique($values);
	}

	public final function getName() : string{
		return $this->name;
	}

	/** @internal  */
	abstract public function encode() : CommandEnum;
}
