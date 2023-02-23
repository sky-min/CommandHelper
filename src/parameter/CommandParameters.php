<?php

declare(strict_types=1);

namespace skymin\CommandHelper\parameter;

use Attribute;
use InvalidArgumentException;
use LogicException;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use skymin\CommandHelper\utils\SubCommandGenerator;
use function bin2hex;
use function count;
use function explode;
use function is_string;
use function trim;


#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class CommandParameters{

	/** @var Parameter[] */
	private readonly array $parameters;

	private bool $hasSoftEnum = false;

	public function __construct(
		private readonly null|string|Permission $permission = null,
		Parameter|string ...$parameters
	){
		if($this->permission !== null){
			foreach(explode(';', $permission) as $perm){
				if(PermissionManager::getInstance()->getPermission($perm) === null){
					throw new InvalidArgumentException("Cannot use non-existing permission \"$perm\"");
				}
			}
		}
		foreach($parameters as $parameter){
			if($parameter instanceof Parameter && $parameter->isSoftEnum()){
				$this->hasSoftEnum = true;
			}elseif(is_string($parameter) && trim($parameter) === ''){
				throw new LogicException('Cannot contain a blank string');
			}
		}
		if(count($parameters) === 0){
			$parameters = [''];
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
			if($parameter instanceof Parameter){
				$overload[] = $parameter->encode();
			}else{
				$overload[] = SubCommandGenerator::generate($parameter);
			}
		}
		return $overload;
	}
}