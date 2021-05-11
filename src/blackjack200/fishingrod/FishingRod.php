<?php


namespace blackjack200\fishingrod;


use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\Player;

class FishingRod extends Item {
	public function __construct(int $meta = 0) {
		parent::__construct(self::FISHING_ROD, $meta, "Fishing Rod");
	}

	public function onClickAir(Player $player, Vector3 $directionVector) : bool {
		if (!$player->hasItemCooldown($this)) {
			$player->resetItemCooldown($this);
			$fishingHook = &Loader::getInstance()->fishingHook[spl_object_hash($player)];
			if ($fishingHook === null) {
				$direction = $player->getDirectionVector();
				$radY = ($direction->y / 180) * M_PI;
				$x = cos($radY) * 0.16;
				$z = sin($radY) * 0.16;
				/** @var FishingHook $hook */
				$hook = Entity::createEntity("___FishingHook",
					$player->getLevelNonNull(),
					Entity::createBaseNBT(
						$player->add(-$x, $player->getEyeHeight() - 0.10000000149011612, -$z),
						$player->getDirectionVector()->multiply(0.4)),
					$player);
				$hook->spawnToAll();
			} else {
				$fishingHook->handleHookRetraction();
				$fishingHook->flagForDespawn();
			}
			$player->broadcastEntityEvent(AnimatePacket::ACTION_SWING_ARM);
			return true;
		}
		return false;
	}
}