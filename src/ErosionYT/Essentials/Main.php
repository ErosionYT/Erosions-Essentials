<?php

namespace ErosionYT\Essentials;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\effect\EffectManager;
use pocketmine\player\Player;

use ErosionYT\Essentials\Commands\{BragCommand,
    CreditsCommand,
    FlyCommand,
    FeedCommand,
    HealCommand,
    GmspcCommand,
    GmsCommand,
    GmcCommand,
    GmaCommand,
    FreezeCommand,
    PingCommand,
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
        foreach ((array)$this->getConfig()->get("worlds") as $world_name) {
            if (!$this->getServer()->getWorldManager()->isWorldLoaded($world_name)) {
                $this->getServer()->getWorldManager()->loadWorld($world_name);
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
            new FreezeCommand("freeze", $this),
            new StaffchatCommand("staffchat", $this),
            new BragCommand("brag", $this),
            new PingCommand("ping", $this)

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

    public function nightvisionon (Player $player) : void
    {
        $this->nightvisioneffect($player);
        $player->sendMessage($this->getFormattedValue("prefix") . $this->getFormattedValue("nv-enable"));
    }

    public function nightvisioneffect (Player $player) : void
    {
        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(),2147483647, 255, false);
        $player->getEffects()->add($effect);
    }

    public function nightvisionoff (Player $player) : void
    {
        $player->getEffects()->remove(VanillaEffects::NIGHT_VISION());
        $player->sendMessage($this->getFormattedValue("prefix") . $this->getFormattedValue("nv-disable"));
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }
}
