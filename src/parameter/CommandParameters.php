<?php

declare(strict_types=1);

namespace skymin\CommandHelper\parameter;

use Attribute;
use InvalidArgumentException;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use skymin\CommandHelper\enum\SoftEnum;
use function count;
use function explode;


#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class CommandParameters{

	/** @var Parameter[] */
	private readonly array $parameters;

	private bool $hasSoftEnum = false;

	public function __construct(
		private readonly null|string|Permission $permission = null,
		Parameter ...$parameters
	){
		if($this->permission !== null){
			foreach(explode(';', $permission) as $perm){
				if(PermissionManager::getInstance()->getPermission($perm) === null){
					throw new InvalidArgumentException("Cannot use non-existing permission \"$perm\"");
				}
			}
		}
		foreach($parameters as $parameter){
			if($parameter instanceof SoftEnum){
				$this->hasSoftEnum = true;
			}
		}
		if(count($parameters) === 0){
			$parameters = [new Parameter('', '')];
		}
		$this->parameters = $parameters;
	}

	public function hasSoftEnum() : bool{
		return $this->hasSoftEnum;
	}

	public function getPermission() : Permission|string|null{
		return $this->permission;
	}

	/**
	 * @internal
	 * @return CommandParameter[]
	 */
	public function encode() : array{
		$overload = [];
		foreach($this->parameters as $parameter){
			$overload[] = $parameter->encode();
		}
		return $overload;
	}
}