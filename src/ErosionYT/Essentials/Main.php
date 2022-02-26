<?php

namespace ErosionYT\Essentials;

use pocketmine\plugin\PluginBase;

use ErosionYT\Essentials\Commands\{
    CreditsCommand,
    FlyCommand,
    FeedCommand,
    HealCommand,
    GmspcCommand,
    GmsCommand,
    GmcCommand,
    GmaCommand,
    RepairCommand,
    FreezeCommand,
    StaffchatCommand
};
use ErosionYT\Essentials\Tasks\AnnouncementsTask;

class Main extends PluginBase
{
    /** @var self */
    private static Main $instance;

    /** @var array */
    public array $freezeList = [];

    public array $staffchat = [];

    protected function onLoad(): void
    {
        $this->saveDefaultConfig();
        self::$instance = $this;
    }

    protected function onEnable() : void
    {
        $config = $this->getConfig();

        $this->getServer()->getNetwork()->setName($config->get("motd"));

        // Load worlds
        foreach ((array)$this->getConfig()->get("worlds") as $level_name) {
            if (!$this->getServer()->getWorldManager()->isWorldLoaded($level_name)) {
                $this->getServer()->getWorldManager()->loadWorld($level_name);
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
            new CreditsCommand("credits"),
            new RepairCommand("repair", $this),
            new FreezeCommand("freeze", $this),
            new StaffchatCommand("staffchat", $this)

        ]);

        $this->getScheduler()->scheduleRepeatingTask(new AnnouncementsTask(), 3200); // 5 minutes
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getLogger()->notice("---===Essentials has loaded!===---");
    }

    protected function onDisable() : void {
        $config = $this->getConfig();

        foreach ($this->getServer()->getOnlinePlayers() as $player) $player->kick($config->get("kick-message"), false);
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }
}
