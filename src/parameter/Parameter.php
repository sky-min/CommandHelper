<?php

declare(strict_types=1);

namespace skymin\CommandHelper\parameter;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use skymin\CommandHelper\enum\CustomEnum;
use skymin\CommandHelper\enum\SoftEnum;
use skymin\CommandHelper\type\ParameterTypes;
use function spl_object_hash;

final class Parameter{

	public function __construct(
		private readonly string $name,
		private readonly string|CustomEnum|ParameterTypes $arg,
		private readonly bool $optional = false
	){}

	public function isSoftEnum() : bool{
		return $this->arg instanceof SoftEnum;
	}

	public function encode() : CommandParameter{
		if($this->arg instanceof ParameterTypes){
			return CommandParameter::standard($this->name, ParameterTypes::encode($this->arg), 0, $this->optional);
		}
		if($this->arg instanceof CustomEnum){
			return CommandParameter::enum($this->name, $this->arg->encode(), 0, $this->optional);
		}
		return CommandParameter::enum($this->name, new CommandEnum(spl_object_hash($this), [$this->arg]), 0, $this->optional);
	}
}