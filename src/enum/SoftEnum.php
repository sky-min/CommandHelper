<?php

declare(strict_types=1);

namespace skymin\CommandHelper\enum;

use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;
use pocketmine\Server;
use function array_unique;
use function in_array;

final class SoftEnum extends CustomEnum{

	protected Server $server;

	public function __construct(string $name, string ...$values){
		parent::__construct($name, ...$values);
		$this->server = Server::getInstance();
	}

	protected final function broadcastPacket(UpdateSoftEnumPacket $packet) : void{
		$this->server->broadcastPackets($this->server->getOnlinePlayers(), [$packet]);
	}


	/** @internal  */
	public function generate() : CommandEnum{
		return new CommandEnum($this->name, $this->values, true);
	}

	/** @internal  */
	public function encode() : CommandEnum{
		/** @var SoftEnum $enum */
		$enum = EnumManager::getInstance()->getEnum($this->name);
		return $enum->generate();
	}

	public function addValue(string ...$values) : void{
		$newValues = [];
		foreach($values as $v){
			if(!in_array($v, $this->values, true)){
				$this->values[] = $v;
				$newValues[] = $v;
			}
		}
		$this->broadcastPacket(UpdateSoftEnumPacket::create($this->name, $newValues, UpdateSoftEnumPacket::TYPE_ADD));
	}

	public function removeValue(string ...$values) : void{
		foreach($this->values as $key => $v){
			if(in_array($v, $values, true)){
				unset($this->values[$key]);
			}
		}
		$this->broadcastPacket(UpdateSoftEnumPacket::create($this->name, $values, UpdateSoftEnumPacket::TYPE_REMOVE));
	}

	public function setValues(string ...$values) : void{
		$this->values = array_unique($values);
		$this->broadcastPacket(UpdateSoftEnumPacket::create($this->name, $values, UpdateSoftEnumPacket::TYPE_SET));
	}
}