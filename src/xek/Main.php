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
    
    public function onDisable(){
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
		        break;
        }
    }
        
    public function TotemEffect(Player $player){
        $original = $player->getInventory()->getItemInHand();
	$player->getInventory()->setItemInHand(Item::get(450,0,1));
	$player->broadcastEntityEvent(ActorEventPacket::CONSUME_TOTEM);
	$pk = new LevelEventPacket();
	$pk->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
	$pk->position = $player->add(0, $player->eyeHeight, 0);
	$pk->data = 0;
	$player->dataPacket($pk);
        $player->getInventory()->setItemInHand($original);
        }
    }
