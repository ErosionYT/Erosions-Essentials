<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as C;

class ListCommand extends Command implements PluginOwned {

    private Main $plugin;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->setPermission("list.command");
        $this->setUsage("/list");
        $this->setDescription("Check who is online");
        $this->setAliases(["online"]);
        $this->setPermissionMessage(C::RED . "Unknown command. Try /help for a list of commands");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $playerNames = array_map(function(Player $player) : string{
            return $player->getName();
        }, array_filter($sender->getServer()->getOnlinePlayers(), function(Player $player) use ($sender) : bool{
            return $player->isOnline() and (!($sender instanceof Player) or $sender->canSee($player));
        }));
        sort($playerNames, SORT_STRING);
        $count = count($playerNames);
        $max_players = $sender->getServer()->getMaxPlayers();

        $prefix = $this->plugin->getFormattedValue('prefix');
        $msg = $this->plugin->getFormattedValue('list', ['{count}' => $count, '{max}' => $max_players, '{player_names}' => implode("\n", $playerNames)]);

        $sender->sendMessage($prefix . $msg);
        return true;
    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}