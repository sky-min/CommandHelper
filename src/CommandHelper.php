<?php

declare(strict_types=1);

namespace skymin\CommandHelper;

use pocketmine\event\EventPriority;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use ReflectionClass;
use skymin\CommandHelper\parameter\CommandParameters;
use function count;

final class CommandHelper extends PluginBase{

	/**
	 * @var CommandParameter[][]|CommandParameters[]
	 * @phpstan-var array<string, CommandParameter[]|CommandParameters>
	 */
	private array $overloads = [];

	/**
	 * @var null|string|Permission[][]
	 * @phpstan-var array<string, null|string|Permission>
	 */
	private array $permissions = [];

	protected function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function(DataPacketSendEvent $ev) : void{
			$packets = $ev->getPackets();
			$targets = $ev->getTargets();
			if(
				count($packets) !== 1 ||
				!($packet = $packets[0]) instanceof AvailableCommandsPacket ||
				count($targets) !== 1
			) return;
			$player = $targets[0]->getPlayer();
			if($player === null) return;
			/** @var AvailableCommandsPacket $packet */
			foreach($packet->commandData as $name => $commandData){
				if(!isset($this->overloads[$name])) continue;
				$newOverloads = [];
				if($this->overloads[$name] instanceof CommandParameters){
					$newOverloads[] = $this->overloads[$name]->encode();
					continue;
				}
				foreach($this->overloads[$name] as $index => $overload){
					$permission = $this->permissions[$name][$index];
					if($permission !== null && !$player->hasPermission($permission)) continue;
					$newOverloads[] = $overload;
				}
				$commandData->overloads = $newOverloads;
			}
		}, EventPriority::MONITOR, $this);

		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function() : void{
			foreach($this->getServer()->getCommandMap()->getCommands() as $name => $command){
				$ref = new ReflectionClass($command);
				foreach($ref->getAttributes() as $attribute){
					if($attribute->getName() !== CommandParameters::class) continue;
					/** @var CommandParameters $parameters */
					$parameters = $attribute->newInstance();
					if($parameters->hasSoftEnum()){
						$this->overloads[$name][] = $parameters;
						continue;
					}
					$this->overloads[$name][] = $parameters->encode();
					$this->permissions[$name][] = $parameters->getPermission();
				}
			}
		}), 0);
	}
}