<?php

declare(strict_types=1);

namespace skymin\CommandHelper\type;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

enum ParameterTypes: int{

	case BLOCK_POSITION = AvailableCommandsPacket::ARG_TYPE_INT_POSITION;
	case FLOAT = AvailableCommandsPacket::ARG_TYPE_FLOAT;
	case INT = AvailableCommandsPacket::ARG_TYPE_INT;
	case STRING = AvailableCommandsPacket::ARG_TYPE_STRING;
	case TEXT = AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
	case POSITION = AvailableCommandsPacket::ARG_TYPE_POSITION;
}
