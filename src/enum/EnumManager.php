<?php

declare(strict_types=1);

namespace skymin\CommandHelper\enum;

use pocketmine\utils\SingletonTrait;
use function trim;

final class EnumManager{
	use SingletonTrait;

	/**
	 * @var CustomEnum[]
	 * @phpstan-var array<string, CustomEnum>
	 */
	private array $enums = [];

	public function __construct(){
		self::setInstance($this);
		$this->init();
	}

	private function init() : void{
		$this->register(new HardcodedEnum(DefaultEnums::BOOLEAN->value, 'true', 'false'));
		$this->register(new SoftEnum(DefaultEnums::ONLINE_PLAYER->value));
	}

	public function register(CustomEnum $enum) : void{
		$enumName = $enum->getName();
		if(trim($enumName) === ''){
			throw new EnumException("Enum's name cannot be empty");
		}
		if(isset($this->enums[$enumName])){
			throw new EnumException("$enumName is already registered");
		}
		$this->enums[$enum->getName()] = $enum;
	}

	public function getEnum(string|DefaultEnums $enumName) : CustomEnum{
		if($enumName instanceof DefaultEnums){
			$enumName = $enumName->value;
		}
		if(isset($this->enums[$enumName])){
			return $this->enums[$enumName];
		}
		throw new EnumException("$enumName is not registered");
	}
}
