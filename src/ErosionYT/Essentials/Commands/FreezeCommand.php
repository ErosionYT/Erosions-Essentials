<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class FreezeCommand extends Command implements PluginOwned
{
    public Config $config;
    public Main $plugin;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setDescription("Freeze a player");
        $this->setPermission("essentials.freeze.command");
        $this->setUsage("/freeze <player>");
        $this->setAliases(['ss']);
        $this->plugin = $plugin;
        $this->config = $plugin->getConfig();
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
                $sender->sendMessage($this->config->get("prefix") . C::AQUA . 'Player ' . C::RED . $target->getName() . C::AQUA . ' will no longer be frozen');
                $target->setImmobile(false);
                return true;
            }

            $this->plugin->freezeList[] = $target->getName();
            $sender->sendMessage($this->config->get("prefix") . C::AQUA . 'Player ' . C::RED . $target->getName() . C::AQUA . ' will now be frozen');
            $target->teleport($this->plugin->getWorldManager()->getDefaultWorld()->getSafeSpawn());
            $target->setImmobile(true);
            $target->sendMessage($this->config->get("prefix") . C::RED . "You cannot move while frozen");
            return true;
        }

        $sender->sendMessage($this->config->get("prefix") . C::RED . 'Player not found');

    }
    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}
