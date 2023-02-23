<?php

declare(strict_types=1);

namespace skymin\CommandHelper\parameter;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use skymin\CommandHelper\enum\CustomEnum;
use skymin\CommandHelper\enum\DefaultEnums;
use skymin\CommandHelper\enum\EnumManager;
use skymin\CommandHelper\enum\SoftEnum;
use skymin\CommandHelper\type\ParameterTypes;
use function is_string;
use function spl_object_hash;

final class Parameter{

	private ParameterTypes|CustomEnum $arg;

	/** @param string|ParameterTypes|DefaultEnums $arg If it has a string type, EnumManager get Enum. */
	public function __construct(
		private readonly string $name,
		string|ParameterTypes|DefaultEnums $arg,
		private readonly bool $optional = false
	){
		if($arg instanceof DefaultEnums || is_string($arg)){
			$arg = EnumManager::getInstance()->getEnum($arg);
		}
		$this->arg = $arg;
	}

	public function isSoftEnum() : bool{
		return $this->arg instanceof SoftEnum;
	}

	/** @internal  */
	public function encode() : CommandParameter{
		if($this->arg instanceof ParameterTypes){
			return CommandParameter::standard($this->name, $this->arg->value, 0, $this->optional);
		}else{
			return CommandParameter::enum($this->name, $this->arg->encode(), 0, $this->optional);
		}
	}
}