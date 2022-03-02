<?php

namespace ErosionYT\Essentials\Commands;

use pocketmine\player\Player;
use pocketmine\player\GameMode;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;
use ErosionYT\Essentials\Main;

class GmcCommand extends Command implements PluginOwned {

    private Main $plugin;

	public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setPermission("essentials.gmc.command");
        $this->setDescription("Change your gamemode to creative");
        $this->setAliases(['creative', '1', 'c']);
        $this->setUsage("/gmc");
        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Use the command in-game");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(C::RED . "You do not have permission to use this command");
            return false;
        }
		$sender->setGamemode(GameMode::CREATIVE());
		$sender->sendMessage($this->plugin->getFormattedValue('prefix') . $this->plugin->getFormattedValue('creative-mode'));
		return true;
	}
    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}