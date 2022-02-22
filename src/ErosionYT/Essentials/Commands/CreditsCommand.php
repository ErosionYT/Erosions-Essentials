<?php

namespace ErosionYT\Essentials\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use ErosionYT\Essentials\Main;

class CreditsCommand extends Command
{
    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->setPermission("essentials.credits.command");
        $this->setDescription("Check the credits");
        $this->setAliases(['credit', 'cred']);
        $this->setUsage("/credits");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("essentials.credits.command")) {
            $sender->sendMessage(C::RED . "You do not have permission to use this command");
            return false;
        }

        $sender->sendMessage( C::AQUA . "This plugin was coded and developed by ErosionYT\n" . C::YELLOW . "https://github.com/ErosionYT/\n" . C::YELLOW . "https://discord.eclipsepe.xyz/");
    }

}