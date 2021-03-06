<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class StaffchatCommand extends Command implements PluginOwned
{
    private Main $plugin;

    public function __construct(string $name, Main $plugin) {
        parent::__construct($name);
        $this->setDescription("Speak in staffchat");
        $this->setPermission("essentials.staffchat.command");
        $this->setAliases(['sc']);
        $this->setUsage("/staffchat <on:off>");
        $this->setPermissionMessage(C::RED . "Unknown command. Try /help for a list of commands");
        $this->plugin = $plugin;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage($this->getPermissionMessage());
            return false;
        }
        if (count($args) < 1) {
            $sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . "Usage: /staffchat <on:off>");
            return false;
        }
        if (isset($args[0])) {
            switch (strtolower($args[0])) {
                case "on":
                    $this->plugin->staffchat[strtolower($sender->getName())] = $sender;
                    $sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::GRAY . "You have enabled staffchat");
                    return true;
                case "off":
                    unset($this->plugin->staffchat[strtolower($sender->getName())]);
                    $sender->sendMessage($this->plugin->getFormattedValue('prefix') . C::GRAY . "You have disabled staffchat");
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