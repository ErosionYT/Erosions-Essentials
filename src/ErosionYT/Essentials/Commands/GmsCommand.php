<?php

namespace ErosionYT\Essentials\Commands;

use pocketmine\player\Player;
use pocketmine\player\GameMode;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use ErosionYT\Essentials\Main;

class GmsCommand extends Command implements PluginOwned {

    private Config $config;

	public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setPermission("essentials.gms.command");
        $this->setDescription("Change your gamemode to survival");
        $this->setAliases(['survival', '0', 's']);
        $this->setUsage("/gms");
        $this->config = $plugin->getConfig();
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

        $sender->setGamemode(GameMode::SURVIVAL());
        $sender->sendMessage($this->config->get("prefix") . C::AQUA . $this->config->get("survival-mode"));
        return true;
    }
    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}