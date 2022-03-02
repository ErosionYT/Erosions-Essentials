<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;


class BragCommand extends Command implements PluginOwned
{

    private Config $config;
    private Main $plugin;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setDescription("Brag an item");
        $this->setPermission("essentials.brag.command");
        $this->setAliases(['']);
        $this->setUsage("/brag");
        $this->plugin = $plugin;
        $this->config = $plugin->getConfig();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $name = $sender->getName();
        $item = $sender->getInventory()->getItemInHand()->getName();
        $count = $sender->getInventory()->getItemInHand()->getCount();

        $msg = $this->getFormattedValue('brag', ['{player}' => $name, '{item}' => $item, '{count}' => $count]);
        $prefix = $this->getFormattedValue('prefix');

        $this->plugin->getServer()->broadcastMessage($prefix . $msg);

        return true;
    }
}