<?php

declare(strict_types=1);

namespace skymin\CommandHelper;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use skymin\CommandHelper\enum\DefaultEnums;
use skymin\CommandHelper\enum\EnumManager;
use skymin\CommandHelper\enum\SoftEnum;
use skymin\CommandHelper\parameter\CommandParameters;
use skymin\event\EventHandler;
use function count;

final class EventListener implements Listener{

	private EnumManager $manager;

	public function __construct(private readonly CommandHelper $helper){
		$this->manager = EnumManager::getInstance();
	}

	#[EventHandler(EventPriority::MONITOR)]
	public function onSendPacket(DataPacketSendEvent $ev) : void{
		$packets = $ev->getPackets();
		$targets = $ev->getTargets();
		if(
			count($packets) !== 1 ||
			!($packet = $packets[0]) instanceof AvailableCommandsPacket ||
			count($targets) !== 1
		) return;
		$player = $targets[0]->getPlayer();
		if($player === null) return;
		$overloads = $this->helper->getOverloads();
		$permissions = $this->helper->getPermissions();
		/** @var AvailableCommandsPacket $packet */
		foreach($packet->commandData as $name => $commandData){
			if(!isset($overloads[$name])) continue;
			$newOverloads = [];
			foreach($overloads[$name] as $index => $overload){
				$permission = $permissions[$name][$index];
				if($permission !== null && !$player->hasPermission($permission)) continue;
				if($overload instanceof CommandParameters){
					$newOverloads[] = $overload->encode();
				}else{
					$newOverloads[] = $overload;
				}
			}
			$commandData->overloads = $newOverloads;
		}
	}

	#[EventHandler(EventPriority::MONITOR)]
	public function onJoin(PlayerJoinEvent $ev) : void{
		/** @phpstan-var  SoftEnum $enum */
		$enum = $this->manager->getEnum(DefaultEnums::ONLINE_PLAYER);
		$enum->addValue($ev->getPlayer()->getName());
	}

	#[EventHandler(EventPriority::MONITOR)]
	public function onQuit(PlayerQuitEvent $ev) : void{
		/** @phpstan-var  SoftEnum $enum */
		$enum = $this->manager->getEnum(DefaultEnums::ONLINE_PLAYER);
		$enum->removeValue($ev->getPlayer()->getName());
	}
}