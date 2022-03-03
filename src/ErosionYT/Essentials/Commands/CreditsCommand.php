<?php

namespace ErosionYT\Essentials\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;
use ErosionYT\Essentials\Main;

class CreditsCommand extends Command implements PluginOwned
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setPermission("essentials.credits.command");
        $this->setDescription("Check the credits");
        $this->setAliases(['credit', 'cred']);
        $this->setUsage("/credits");
        $this->setPermissionMessage(C::RED . "Unknown command. Try /help for a list of commands");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("essentials.credits.command")) {
            $sender->sendMessage(C::RED . "You do not have permission to use this command");
            return;
        }

        $sender->sendMessage( C::AQUA . "This plugin was coded and developed by ErosionYT & Wolfden133\n" . C::YELLOW . "https://github.com/ErosionYT/\n" . C::YELLOW . "https://github.com/WolfDen133\n" . C::YELLOW . "https://discord.eclipsepe.xyz/");
    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }

}