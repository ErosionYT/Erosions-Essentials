<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class BragCommand extends Command implements PluginOwned
{
    private Main $plugin;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setDescription("Brag an item");
        $this->setPermission("essentials.brag.command");
        $this->setAliases(['']);
        $this->setUsage("/brag");
        $this->setPermissionMessage(TextFormat::RED . "Unknown command. Try /help for a list of commands");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!($sender instanceof Player))
            return false;

        $name = $sender->getName();
        $item = $sender->getInventory()->getItemInHand()->getName();
        $count = $sender->getInventory()->getItemInHand()->getCount();

        $msg = $this->plugin->getFormattedValue('brag', ['{player}' => $name, '{item}' => $item, '{count}' => $count]);
        $prefix = $this->plugin->getFormattedValue('prefix');

        $this->plugin->getServer()->broadcastMessage($prefix . $msg);

        return true;
    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}