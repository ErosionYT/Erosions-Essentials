<?php

namespace ErosionYT\Essentials;

use ErosionYT\Essentials\Main;

use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

    public Config $config;

    public function __construct()
    {
        $this->config = Main::getInstance()->getConfig();
    }

    public function onJoin(PlayerJoinEvent $ev)
    {
        $name = $ev->getPlayer()->getName();

        $message = $this->config->get("Join-message");
        $msg = str_replace("{player}", $name, $message);
        $ev->setJoinMessage("$msg");

    }

    public function onQuit (PlayerQuitEvent $ev) {
        $name = $ev->getPlayer()->getName();

        $message = $this->config->get("Quit-message");
        $msg = str_replace("{player}", $name, $message);
        $ev->setQuitMessage("$msg");

    }
}
