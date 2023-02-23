<?php

declare(strict_types=1);

namespace skymin\CommandHelper\utils;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use Ramsey\Uuid\Uuid;
use function bin2hex;
use function trim;

final class SubCommandGenerator{

	private static int $id = 0;

	private function __construct(){ }

	public static function generate(string $name) : CommandParameter{
		$name = trim($name);
		if($name === ''){
			return CommandParameter::enum('', new CommandEnum('', ['']), 0);
		}
		$uuid = Uuid::uuid3(Uuid::NIL, $name . self::$id)->getBytes();
		return CommandParameter::enum('sub_command', new CommandEnum(bin2hex($uuid), [$name]), 0);
	}
}