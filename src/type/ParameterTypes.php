<?php

declare(strict_types=1);

namespace skymin\CommandHelper\type;

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

enum ParameterTypes{

	case BLOCK_POSITION;
	case BOOLEAN;
	case FLOAT;
	case INT;
	case STRING;
	case TEXT;
	case POSITION;

	public static function encode(self $value) : int{
		return match($value) {
			self::BLOCK_POSITION => AvailableCommandsPacket::ARG_TYPE_INT_POSITION,
			self::BOOLEAN => -1,
			self::FLOAT => AvailableCommandsPacket::ARG_TYPE_FLOAT,
			self::INT => AvailableCommandsPacket::ARG_TYPE_INT,
			self::STRING => AvailableCommandsPacket::ARG_TYPE_STRING,
			self::TEXT => AvailableCommandsPacket::ARG_TYPE_RAWTEXT,
			self::POSITION => AvailableCommandsPacket::ARG_TYPE_POSITION
		};
	}
}
