<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

class FlyCommand extends Command implements PluginOwned {

    public Config $config;
    public Main $plugin;

	public function __construct(string $name, Main $plugin) {
        parent::__construct($name);
        $this->setDescription("Fly in Survival");
        $this->setAliases(['']);
        $this->setPermission("essentials.fly.command");

		$this->plugin = $plugin;
        $this->config = $plugin->getConfig();
    }
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
		if (!$sender->hasPermission($this->getPermission())) {
			$sender->sendMessage(C::RED . "You do not have permission to use this command");
			return false;
		}

        if (!($sender instanceof Player)) {
            $sender->sendMessage(C::RED . "This command is for players only");
            return false;
        }

        if (count($args) < 1) {
			$sender->sendMessage(C::RED . "/fly <on|off>");
            return false;
        }
		if (isset($args[0])) {
			switch($args[0]) {
				case "on":
                case "enable":
				$sender->setAllowFlight(true);
			    $sender->setFlying(true);
			    $sender->sendMessage($this->config->get("prefix") . C::RED . "You have enabled fly");
				return true;
				case "off":
                case "disable":
				$sender->setAllowFlight(false);
				$sender->setFlying(false);
				$sender->sendMessage($this->config->get("prefix") . C::AQUA . "You have disabled fly");
				return true;
			}
		}
	    return true;
	}

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}