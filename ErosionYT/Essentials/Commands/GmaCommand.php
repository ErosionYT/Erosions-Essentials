<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;

use pocketmine\player\Player;
use pocketmine\player\GameMode;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;


class GmaCommand extends Command{

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->setPermission("essentials.gma.command");
        $this->setDescription("Change your gamemode to adventure");
        $this->setAliases(['2', 'a', 'adventure']);
        $this->setUsage("/gma");
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
        $sender->setGamemode(GameMode::ADVENTURE());
        $sender->sendMessage($this->config->get("prefix") . C::AQUA . $this->config->get("adventure-mode"));
        return true;
    }
}