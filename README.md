# CommandHelper
Set command arguments using php attribute

## NOTICE
Requires php 8.1 or later version

## How to use
Add attributes to the Command class as shown in the example

```php
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use skymin\CommandHelper\enum\DefaultEnums;
use skymin\CommandHelper\parameter\CommandParameters;
use skymin\CommandHelper\parameter\Parameter;

#[CommandParameters]
#[CommandParameters(
	null,
	'see',
	new Parameter('level', DefaultEnums::ONLINE_PLAYER)
)]
#[CommandParameters(
	'level.cmd.op',
	'manager',
	'add'
)]
class ExampleCommand extends Command{

    public function __construct() {
        parent::__construct('level');
        $this->setPermission('level.cmd.user');
    }
}
```

![Example](https://i.ibb.co/nCsr0Rk/example.jpg)
