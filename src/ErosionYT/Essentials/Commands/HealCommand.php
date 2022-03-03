<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;

class HealCommand extends Command implements PluginOwned {

    public Main $plugin;

    public function __construct(string $name, Main $plugin) {
        parent::__construct($name);
        $this->setDescription("Heal yourself");
        $this->setPermission("essentials.heal.command");
        $this->setUsage("/heal");
        $this->setPermissionMessage(C::RED . "Unknown command. Try /help for a list of commands");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{

        if (!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage($this->getPermissionMessage());
            return false;
        }

        if (!($sender instanceof Player)) {
            $sender->sendMessage(C::RED . "This command is for players only");
            return false;
        }

		$sender->setHealth($sender->getMaxHealth());
		$sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::AQUA .  "You have been healed");
		return true;
	}
    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}