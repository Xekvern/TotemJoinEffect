<?php

namespace xek;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
			$this->TotemEffect($player);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		switch($cmd->getName()){
			case "totem":
				if ($sender instanceof Player) {
					$this->TotemEffect($sender);
                }
            case "abby":
                if ($sender instanceof Player) {
                    $sender->sendMessage("xek's crush is abby.");
                }
			break;			
        }
    }
        
    public function TotemEffect(Player $player){
        $original = $player->getInventory()->getItemInHand();
        $player->getInventory()->setItemInHand(Item::get(Item::TOTEM));
        
        $pk = new ActorEventPacket();
        $pk->entityRuntimeId = $player->getId();
        $pk->event = ActorEventPacket::CONSUME_TOTEM;
        $pk->data = 0;
        $player->dataPacket($pk);
        
        $pk = new LevelEventPacket();
        $pk->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
        $pk->data = 0;
        $pk->position = $player->asVector3();
        $player->dataPacket($pk);
        $player->getInventory()->setItemInHand($original);
        }
    }