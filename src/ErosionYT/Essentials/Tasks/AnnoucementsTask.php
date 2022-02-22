<?php
namespace ErosionYT\Essentials\Tasks;

use ErosionYT\Essentials\Main;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\plugin;
use function array_rand;

class AnnoucementsTask extends Task
{
    public $plugin;
    public $config;

    public $lastMessage = [];

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->config = $plugin->getConfig();
    }

    public function onRun() : void
    {
        $online = count(Server::getInstance()->getOnlinePlayers());
        $plugin = $this->plugin;
        $announcements = (array) Main::getInstance()->getConfig()->get("announcements");

        if (count(Server::getInstance()->getOnlinePlayers()) > 0) {
            Main::getInstance()->getServer()->broadcastMessage("§a   §b\n§7 » ".$announcements[array_rand($announcements)]."\n§e      §d");

        }

    }

}
