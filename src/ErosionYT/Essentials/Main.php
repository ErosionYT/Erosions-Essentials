<?php

namespace ErosionYT\Essentials;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use ErosionYT\Essentials\Commands\{BragCommand,
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
    StaffchatCommand};
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
        $this->getServer()->getNetwork()->setName($this->getFormattedValue('motd'));

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
            new StaffchatCommand("staffchat", $this),
            new BragCommand("brag", $this)

        ]);

        $this->getScheduler()->scheduleRepeatingTask(new AnnouncementsTask(), 3200); // 5 minutes
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getLogger()->notice("---===Essentials has loaded!===---");
    }

    protected function onDisable() : void {

        foreach ($this->getServer()->getOnlinePlayers() as $player) $player->kick($this->getFormattedValue('kick-message'), false);
    }

    /**
     * @param string $key
     * @param array $wildcards
     * @return string|array
     */
    public function getFormattedValue (string $key, array $wildcards = []) : string|array
    {
        $value = $this->getConfig()->getNested($key);

        if (is_array($value)) {
            $items = [];
            foreach ($value as $item) {
                foreach ($wildcards as $find => $replace) $item = str_replace($find, $replace, $value);
                $items[] = TextFormat::colorize($item);
            }

            return $items;
        }

        foreach ($wildcards as $find => $replace) $value = str_replace($find, $replace, $value);

        return TextFormat::colorize($value);
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }
}
