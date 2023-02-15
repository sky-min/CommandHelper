<?php

declare(strict_types=1);

namespace skymin\CommandHelper\enum;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;

class HardcodedEnum extends CustomEnum{

	public function encode() : CommandEnum{
		return new CommandEnum($this->name, $this->values);
	}
}
