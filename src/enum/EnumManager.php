<?php

declare(strict_types=1);

namespace skymin\CommandHelper\enum;

final class EnumManager{

	/**
	 * @var CustomEnum[]
	 * @phpstan-var array<string, CustomEnum>
	 */
	private static array $enums = [];

	/** @internal */
	public static function register(CustomEnum $enum) : void{
		$enumName = $enum->getName();
		if(isset(self::$enums[$enumName])){
			throw new EnumException("$enumName is already registered");
		}
		self::$enums[$enumName] = $enum;
	}

	public static function getEnum(string $enumName) : CustomEnum{
		if(isset(self::$enums[$enumName])){
			return self::$enums[$enumName];
		}
		throw new EnumException("$enumName is not registered");
	}
}
