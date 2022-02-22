<?php

namespace ErosionYT\Essentials;

use ErosionYT\Essentials\Main;

use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

    public Main $plugin;
    public $config;
    public const LINE = "\n§r";

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->config = $plugin->getConfig();
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $player = $ev->getPlayer();
        $name = $player->getName();
        {
            $message = $this->config->get("Join-message");
            $msg = str_replace("{player}", $name, $message);
            $ev->setJoinMessage("$msg");
        }

    }

    public function onQuit (PlayerQuitEvent $ev) {
        $player = $ev->getPlayer();
        $name = $player->getName();
        {
            $message = $this->config->get("Quit-message");
            $msg = str_replace("{player}", $name, $message);
            $ev->setQuitMessage("$msg");
        }
    }
}