<?php

declare(strict_types=1);

namespace skymin\CommandHelper;

use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use ReflectionClass;
use skymin\CommandHelper\parameter\CommandParameters;
use skymin\event\EventManager;

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
		EventManager::register(new EventListener($this), $this);

		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function() : void{
			foreach($this->getServer()->getCommandMap()->getCommands() as $name => $command){
				$ref = new ReflectionClass($command);
				foreach($ref->getAttributes() as $attribute){
					if($attribute->getName() !== CommandParameters::class) continue;
					/** @var CommandParameters $parameters */
					$parameters = $attribute->newInstance();
					if($parameters->hasSoftEnum()){
						$this->overloads[$name][] = $parameters;
					}else{
						$this->overloads[$name][] = $parameters->encode();
					}
					$this->permissions[$name][] = $parameters->getPermission();
				}
			}
		}), 0);
	}

	public function getOverloads() : array{
		return $this->overloads;
	}

	public function getPermissions() : array{
		return $this->permissions;
	}
}