<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class FreezeCommand extends Command implements PluginOwned
{

    public Main $plugin;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setDescription("Freeze a player");
        $this->setPermission("essentials.freeze.command");
        $this->setUsage("/freeze <player>");
        $this->setAliases(['ss']);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("essentials.freeze.command")) {
            $sender->sendMessage(C::RED . "You dont have permission to use that command.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage($this->getUsage());
            return false;
        }

        $target = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
        if ($target instanceof Player) {
            if (in_array($target->getName(), $this->plugin->freezeList)) {
                unset($this->plugin->freezeList[array_search($target->getName(), $this->plugin->freezeList)]);
                $unfreeze = $this->plugin->getFormattedValue('unfreeze', ['{player}' => $target->getName()]);
                $sender->sendMessage($this->plugin->getFormattedValue('prefix') . $unfreeze);
                $target->setImmobile(false);
                return true;
            }

            $this->plugin->freezeList[] = $target->getName();
            $freeze = $this->getFormattedValue('unfreeze', ['{player}' => $target->getName()]);
            $sender->sendMessage($this->plugin->getFormattedValue('prefix') . $freeze);
            $target->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
            $target->setImmobile(true);
            $target->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . "You cannot move while frozen");
            return true;
        }

        $sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . 'Player not found');

    }
    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}
