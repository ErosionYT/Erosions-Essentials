<?php

namespace ErosionYT\Essentials;

use ErosionYT\Essentials\Main;

use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

class EventListener implements Listener {

    public Config $config;
    private Main $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->config = Main::getInstance()->getConfig();
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $name = $ev->getPlayer()->getName();

        $msg = $this->plugin->getFormattedValue('Join-message', ['{player}' => $name]);
        $ev->setJoinMessage("$msg");

    }

    public function onQuit (PlayerQuitEvent $ev) {
        $name = $ev->getPlayer()->getName();

        $msg = $this->plugin->getFormattedValue('Quit-message', ['{player}' => $name]);
        $ev->setQuitMessage("$msg");

    }

    public function onChat(PlayerChatEvent $ev) : void {
        $player = $ev->getPlayer();
        $message = $ev->getMessage();

        if (in_array($ev->getPlayer()->getName(), $this->plugin->freezeList)) {
            $ev->cancel();
            $ev->getPlayer()->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . "You cannot use commands or chat while frozen.");
        }

        if (isset($this->plugin->staffchat[strtolower($player->getName())])) {
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $target) {
                if ($target->hasPermission("essentials.staffchat.command")) {
                    $target->sendMessage($this->plugin->getFormattedValue('staffchat-prefix') . C::RED . $player->getName() . ": " . C::WHITE . $message);
                    $ev->Cancel();
                }
            }
        }
    }

    public function onLogout(PlayerQuitEvent $ev) : void {
        $player = $ev->getPlayer();
        if (in_array($ev->getPlayer()->getName(), $this->plugin->freezeList)) {
            $ev->setQuitMessage($this->plugin->getFormattedValue('prefix') . C::AQUA . $player->getName() . C::RED . " Has logged out while frozen.");
        }
    }

    public function onDrop(PlayerDropItemEvent $ev) : void {
        if(in_array($ev->getPlayer()->getName(), $this->plugin->freezeList)) {
            $ev->cancel();
            $ev->getPlayer()->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . "You cannot drop items while frozen.");
        }
    }


    public function onBreak(BlockBreakEvent $ev) : void {
        if(in_array($ev->getPlayer()->getName(), $this->plugin->freezeList)) {
            $ev->cancel();
            $ev->getPlayer()->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED ."You cannot break blocks while frozen.");
        }
    }

    public function onPlace(BlockPlaceEvent $ev) : void {
        if(in_array($ev->getPlayer()->getName(), $this->plugin->freezeList)) {
            $ev->cancel();
            $ev->getPlayer()->sendMessage($this->plugin->getFormattedValue('prefix') . C::RED . "You cannot place blocks while frozen.");
        }
    }
}
