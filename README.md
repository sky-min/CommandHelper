# CommandHelper
Set command arguments using php attribute

## NOTICE
Requires php 8.1 or later version

## How to use
Add attributes to the Command class as shown in the example

```php
use pocketmine\command\Command;
use skymin\CommandHelper\parameter\CommandParameters;

#[CommandParameters]
#[CommandParameters(null, )]
class ExampleCommand extends Command{
//codes
}
```