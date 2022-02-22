<?php
namespace ErosionYT\Essentials\Tasks;

use ErosionYT\Essentials\Main;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\plugin;
use pocketmine\utils\Config;
use function array_rand;

class AnnouncementsTask extends Task
{
    public function onRun() : void
    {
        $announcements = (array) Main::getInstance()->getConfig()->get("announcements");

        if (count(Server::getInstance()->getOnlinePlayers()) > 0) Main::getInstance()->getServer()->broadcastMessage("§a   §b\n§7 » ". $announcements[array_rand($announcements)] ."\n§e      §d");
    }
}
