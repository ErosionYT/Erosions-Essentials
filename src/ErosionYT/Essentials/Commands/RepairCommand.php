<?php

namespace ErosionYT\Essentials\Commands;

use ErosionYT\Essentials\Libs\CustomForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\player\Player;

use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use ErosionYT\Essentials\Main;

class RepairCommand extends Command implements PluginOwned
{
    public Config $config;
    public Main $plugin;

    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->setDescription("Open the Repairui");
        $this->setAliases(['repair']);
        $this->setPermission("essentials.repairui.command");
        $this->setUsage("/repairui");
        $this->config = $plugin->getConfig();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
            return false;
        }

        if (!($sender instanceof Player)) {
            $sender->sendMessage(TextFormat::RED . "This command is for players only");
            return false;
        }

        $this->RepairUI($sender);
        return true;
    }

    public function RepairUI(Player $sender){
        $f = new CustomForm(function(Player $sender, array $data = null){
            if(is_null($data)) return;


            $economy = BedrockEconomyAPI::getInstance();
            $mymoney = $economy->getPlayerBalance($sender);
            $cash = $this->plugin->getConfig()->get("price");
            $dg = $sender->getInventory()->getItemInHand()->getMeta();
            $index = $sender->getInventory()->getHeldItemIndex();
            $item = $sender->getInventory()->getItem($index);


            if($mymoney->count() < $cash * $dg){
                $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "You don't have enough money!");
                return;
            }

            if(!($item instanceof Armor or $item instanceof Tool)) {
                $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "This item can't repaired");
                return;
            }

            if($item->getMeta() <= 0){
                $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "Item doesn't have any damage.");
                return;
            }


            $economy->subtractFromPlayerBalance($sender->getName(), $cash * $dg);
            $sender->getInventory()->setItem($index, $item->setDamage(0));
            $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "Your item have been repaired");

        });

        $mny = $this->plugin->getConfig()->get("price");
        $dg = $sender->getInventory()->getItemInHand()->getMeta();
        $pc = $mny * $dg;
        $economy = BedrockEconomyAPI::getInstance();
        $mne = $economy->getPlayerBalance($sender)->count();

        $f->setTitle("•RepairUI•");
        $f->addLabel("§cYour money: §f$mne \n§cPrice per Damage: §f$mny\n§cItem damage: §f$dg \n§cTotal money needed : §f$pc");
        $sender->sendForm($f);
    }

    public function getOwningPlugin(): Plugin
    {
        return Main::getInstance();
    }
}