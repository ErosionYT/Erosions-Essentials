<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\player\Player;

class NightVisionCommand extends Command implements PluginOwned
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setPermission("essential.nv.command");
        $this->setDescription("Enable/disable night vision");
        $this->setUsage("/nv <on|off>");
        $this->setPermissionMessage(C::RED . "Unknown command. Try /help for a list of commands");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!($sender instanceof Player)) {
            $sender->sendMessage(C::RED . "Sorry, this command is for players only.");
            return;
        }

        if (!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage($this->getPermissionMessage());
            return;
        }

        switch (strtolower($args[0])) {
            case "on":
            case "enable":
            case "add":
                Main::getInstance()->nightvisionon($sender);
                break;

            case "off":
            case "disable":
            case "remove":
                Main::getInstance()->nightvisionoff($sender);
                break;
        }

    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}