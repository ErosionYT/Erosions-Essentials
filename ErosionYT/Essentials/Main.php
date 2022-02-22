<?php

namespace ErosionYT\Essentials;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use ErosionYT\Essentials\Commands\{CreditsCommand,
    FlyCommand,
    FeedCommand,
    HealCommand,
    GmspcCommand,
    GmsCommand,
    GmcCommand,
    GmaCommand,
    RepairCommand};
use ErosionYT\Essentials\Tasks\{AnnoucementsTask};


class Main extends PluginBase
{
    /** @var self */
    private static $instance;

    public array $Config = [];

    public function onEnable() : void
    {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        $this->saveDefaultConfig();
        self::$instance = $this;

        $this->getServer()->getNetwork()->setName($config->get("motd"));

        // Load worlds
        foreach ((array)$this->getConfig()->get("worlds") as $level_name) {

            if (!$this->getServer()->getWorldManager()->isWorldLoaded($level_name)) {
                $this->getServer()->getWorldManager()->loadWorld($level_name);
                if ($this->getServer()->getWorldManager()->isWorldLoaded($level_name)) {
                    continue;
                }

                $this->getServer()->getLogger()->notice("Cannot load level: $level_name");
            }

        }

        // Register commands
        $this->getServer()->getCommandMap()->registerAll("Essentials", [
            new FlyCommand("fly", $this),
            new FeedCommand("feed", $this),
            new HealCommand("heal", $this),
            new GmspcCommand("gmspc", $this),
            new GmsCommand("gms", $this),
            new GmcCommand("gmc", $this),
            new GmaCommand("gma", $this),
            new CreditsCommand("credits", $this),
            new RepairCommand("repair", $this)

        ]);

        $this->getScheduler()->scheduleRepeatingTask(new AnnoucementsTask($this), 3200); // 5 minutes
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->getLogger()->notice("---===Essentials has loaded!===---");
    }
    public function onDisable() : void {
        $config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML);
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $player->kick($config->get("kick-message"), false);
        }
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }
}
