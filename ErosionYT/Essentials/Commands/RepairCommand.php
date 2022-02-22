<?php

namespace ErosionYT\Essentials\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\item\Armor;
use pocketmine\item\Tool;
use pocketmine\item\Item;
use pocketmine\item\Durable;

use cooldogedev\BedrockEconomy\BedrockEconomy;
use ErosionYT\Essentials\Main;

class RepairCommand extends Command
{
    public $Config;
    public function __construct(string $name, Main $plugin)
    {
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->setDescription("Open the Repairui");
        $this->setAliases(['repair']);
        $this->setPermission("essentials.repairui.command");
        $this->setUsage("/repairui");
        $this->economy = BedrockEconomyAPI::getInstance();
        $this->config = $plugin->getConfig();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender->hasPermission("essentials.repairui.command")) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
            return false;
        }
        $this->RepairUI($sender);
        return true;
    }
    public function RepairUI(Player $sender){
        $api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
        $f = $api->createCustomForm(function(Player $sender, ?array $data){
            if(!isset($data)) return;
            $economy = BedrockEconomyAPI::getInstance();
            $mymoney = $economy->getPlayerBalance($sender);
            $cash = $this->plugin->getConfig()->get("price");
            $dg = $sender->getInventory()->getItemInHand()->getMeta();
            if($mymoney >= $cash * $dg){
                $economy->reduceMoney($sender, $cash * $dg);
                $index = $sender->getInventory()->getHeldItemIndex();
                $item = $sender->getInventory()->getItem($index);
                $id = $item->getId();
                if($item instanceof Armor or $item instanceof Tool){
                    if($item->getMeta() > 0){
                        $sender->getInventory()->setItem($index, $item->setDamage(0));
                        $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "Your item have been repaired");
                        return true;
                    }else{
                        $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "Item doesn't have any damage.");
                        return false;
                    }
                }else{
                    $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "This item can't repaired");
                    return false;
                }
            }else{
                $sender->sendMessage($this->config->get("prefix") . TextFormat::GRAY . "You don't have enough money!");
                return true;
            }
        });
        $mny = $this->plugin->getConfig()->get("price");
        $dg = $sender->getInventory()->getItemInHand()->getMeta();
        $pc = $mny * $dg;
        $economy = BedrockEconomyAPI::getInstance();
        $mne = $economy->getPlayerBalance($sender);
        $f->setTitle("•RepairUI•");
        $f->addLabel("§cYour money: §f$mne \n§cPrice per Damage: §f$mny\n§cItem damage: §f$dg \n§cTotal money needed : §f$pc");
        $f->sendToPlayer($sender);
    }
}