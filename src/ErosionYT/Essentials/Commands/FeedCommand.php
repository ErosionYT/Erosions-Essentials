<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;

class FeedCommand extends Command implements PluginOwned {

    public Main $plugin;

	public function __construct(string $name, Main $plugin) {
        parent::__construct($name);
        $this->setPermission("essentials.feed.command");
        $this->setDescription("Feed yourself");
        $this->setUsage("/feed");
        $this->setAliases(['']);

        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void
    {
        $plugin = $this->plugin;

		if (!$sender->hasPermission($this->getPermission())) {
			$sender->sendMessage(C::RED . "You do not have permission to use this command");
			return;
		}

        if (!($sender instanceof Player)) {
            $sender->sendMessage(C::RED . "This command is for players only");
            return;
        }
		$sender->getHungerManager()->setFood(20);
        $sender->getHungerManager()->setSaturation(5);
		$sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::AQUA . "You have been fed");
	}

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}