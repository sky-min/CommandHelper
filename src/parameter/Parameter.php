<?php

declare(strict_types=1);

namespace skymin\CommandHelper\parameter;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use skymin\CommandHelper\type\ParameterTypes;
use function is_string;
use function spl_object_hash;

final class Parameter{

	public function __construct(
		private readonly string $name,
		private readonly string|array|ParameterTypes $arg,
		private readonly bool $optional = false
	){}

	public function encode() : CommandParameter{
		if($this->arg instanceof ParameterTypes && $this->arg !== ParameterTypes::BOOLEAN){
			return CommandParameter::standard($this->name, ParameterTypes::encode($this->arg), 0, $this->optional);
		}
		return CommandParameter::enum(
			$this->name, new CommandEnum(spl_object_hash($this), match (true) {
			is_string($this->arg) => [$this->arg],
			$this->arg === ParameterTypes::BOOLEAN => ['true', 'false'],
			default => $this->arg
		}), 0, $this->optional);
	}
}