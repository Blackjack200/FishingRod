<?php


namespace blackjack200\fishingrod;


use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase implements Listener {
	private static $instance;
	/** @var FishingHook[]|null[] */
	public $fishingHook = [];

	public static function getInstance() : self {
		return self::$instance;
	}

	public function onEnable() : void {
		self::$instance = $this;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		ItemFactory::registerItem(new FishingRod(), true);
		Entity::registerEntity(FishingHook::class, true, ["___FishingHook"]);
	}

	public function onPlayerJoin(PlayerJoinEvent $event) : void {
		$this->fishingHook[spl_object_hash($event->getPlayer())] = null;
	}

	public function onPlayerQuit(PlayerQuitEvent $event) : void {
		unset($this->fishingHook[spl_object_hash($event->getPlayer())]);
	}
}