<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;

class PingCommand extends Command implements PluginOwned {

    private Main $plugin;

    public function __construct(string $name, Main $plugin) {
        parent::__construct($name);
        $this->setUsage("/ping");
        $this->setPermission("ping.command");
        $this->setPermissionMessage(C::RED . "Unknown command. Try /help for a list of commands");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage($this->getPermissionMessage());
            return;
        }

        if(isset($args[0])) {
            $player = $this->plugin->getServer()->getPlayerByPrefix($args[0]);
            if($player === null) {
                $sender->sendMessage(C::RED . "Invalid player. Please check your spelling.");
                return;
            }
        }
        if(isset($player)) {
            $ping = $player->getNetworkSession()->getPing();
            $name = $player->getName() . "'s";
        }
        else {
            $ping = $sender->getNetworkSession()->getPing();
            $name = "Your";
        }
        $sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . "$name ping: " .  C::WHITE . "$ping ");
    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}