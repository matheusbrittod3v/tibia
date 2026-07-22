<?php
/**
 * Player Class
 *
 * @package   CanaryAAC
 * @author    Lucas Giovanni <lucasgiovannidesigner@gmail.com>
 * @copyright 2022 CanaryAAC
 */

    namespace App\Model\Functions;

use App\DatabaseManager\Database;
use App\Model\Entity\Groups;
use App\Model\Entity\Guilds;
use App\Model\Entity\Houses;
use App\Model\Entity\Player as EntityPlayer;
use App\Model\Entity\ServerConfig;
use App\Model\Functions\Guilds as FunctionsGuilds;

    class Player{
        /**
         * Converte ID vocação para nome
         *
         * @param int $vocationId
         * @return string $converted
         */
        public static function convertVocation($vocationId){
            $vocationList = [
                '0' => 'None',
                '1' => 'Sorcerer',
                '2' => 'Druid',
                '3' => 'Paladin',
                '4' => 'Knight',
                '5' => 'Master Sorcerer',
                '6' => 'Elder Druid',
                '7' => 'Royal Paladin',
                '8' => 'Elite Knight',
***REMOVED***;
            foreach($vocationList as $key => $value){
                if($key == $vocationId){
                    $converted = $value;
***REMOVED***
***REMOVED***
            return $converted;
        }

        public static function getAllCharacters($accountId)
***REMOVED***
            $allPlayers = [];
            $resultsAllPlayers = (new Database('players'))->select([ 'account_id' => $accountId]);
            while($obAllPlayers = $resultsAllPlayers->fetchObject()){
                $allPlayers[] = [
                    'id' => $obAllPlayers->id,
                    'name' => $obAllPlayers->name,
                    'level' => (int)$obAllPlayers->level,
                    'vocation' => self::convertVocation($obAllPlayers->vocation),
                    'main' => (int)$obAllPlayers->main,
                    'world' => Server::getWorldById($obAllPlayers->world),
                    'online' => self::isOnline($obAllPlayers->id),
                    'outfit' => self::getOutfit($obAllPlayers->id),
                    'group' => self::convertGroup($obAllPlayers->group_id),
                    'marriage' => self::convertMarried($obAllPlayers->id),
                    'deletion' => $obAllPlayers->deletion,
                    'display' => self::getDisplay($obAllPlayers->id),
    ***REMOVED***;
***REMOVED***
            return $allPlayers;
        }

        public static function convertMarried($player_id)
***REMOVED***
            $resultsAllPlayers = (new Database('players'))->select([ 'id' => $player_id])->fetchObject();
            $spouse = (new Database('players'))->select([ 'id' =>$resultsAllPlayers->marriage_spouse])->fetchObject();
            $married = [
                'status' => $resultsAllPlayers->marriage_status,
                'spouse' => $spouse->name ?? '',
***REMOVED***;
            return $married;
        }

        public static function getAchievements($player_id, $storage)
***REMOVED***
            $results = (new Database('player_storage'))->select([ 'player_id' => $player_id]);
            while($obAchievements = $results->fetchObject()){
                if($obAchievements->key == $storage){
                    $achievements[] = [
                        'key' => $obAchievements->key,
                        'value' => $obAchievements->value,
        ***REMOVED***;
***REMOVED***
***REMOVED***
            return $achievements ?? '';
        }

        public static function getAchievementPoints($player_id)
***REMOVED***
            $points = 0;
            $results = (new Database('player_storage'))->select([ 'player_id' => $player_id]);
            while($obPoints = $results->fetchObject()){
                if($obPoints->key > 30000){
                    $points++;
***REMOVED***
***REMOVED***
            return $points;
        }

        public static function convertWorld($world_id)
***REMOVED***
            $select_world = ServerConfig::getWorlds([ 'id' => $world_id])->fetchObject();
            return $select_world->name ?? 'None';
            
        }

        public static function getOutfitImage($looktype = 0, $lookaddons = 0, $lookbody = 0, $lookfeet = 0, $lookhead = 0, $looklegs = 0, $mount = 0)
***REMOVED***
            $outfit = 'https://outfit-images.ots.me/latest/animoutfit.php?id='.$looktype.'&addons='.$lookaddons.'&head='.$lookhead.'&body='.$lookbody.'&legs='.$looklegs.'&feet='.$lookfeet.'&mount='.$mount.'';
            return $outfit;
        }

        public static function getOutfit($player_id)
***REMOVED***
            $select = (new Database('players'))->select([ 'id' => $player_id]);
            while($obOutfit = $select->fetchObject()){
                $outfit = [
                    'image_url' => self::getOutfitImage($obOutfit->looktype, $obOutfit->lookaddons, $obOutfit->lookbody, $obOutfit->lookfeet, $obOutfit->lookhead, $obOutfit->looklegs, $obOutfit->lookmountbody),
                    'lookbody' => (int)$obOutfit->lookbody,
                    'lookfeet' => (int)$obOutfit->lookfeet,
                    'lookhead' => (int)$obOutfit->lookhead,
                    'looklegs' => (int)$obOutfit->looklegs,
                    'looktype' => (int)$obOutfit->looktype,
                    'lookaddons' => (int)$obOutfit->lookaddons,
    ***REMOVED***;
***REMOVED***
            return $outfit;
        }

        public static function getEquipaments($player_id)
***REMOVED***
            // Initialize all equipment slots with default images
            $default_images = [
                1 => 'no_helmet.gif',
                2 => 'no_ring.gif',
                3 => 'no_backpack.gif',
                4 => 'no_armor.gif',
                5 => 'no_handleft.gif',
                6 => 'no_handright.gif',
                7 => 'no_legs.gif',
                8 => 'no_boots.gif',
                9 => 'no_necklace.gif',
                10 => 'no_ammo.gif',
***REMOVED***;

            for ($i = 1; $i <= 10; $i++) {
                $equipaments[$i] = [
                    'url' => 'objects/'.$default_images[$i],
                    'pid' => 0,
                    'sid' => 0,
                    'itemtype' => 0,
                    'count' => 0,
    ***REMOVED***;
***REMOVED***

            $results = (new Database('player_items'))->select([ 'player_id' => $player_id]);
            while($obEquipaments = $results->fetchObject()){
                if($obEquipaments->pid <= 10){
                    $equipaments[$obEquipaments->pid] = [
                        'url' => 'items/'.$obEquipaments->itemtype.'.gif',
                        'pid' => (int)$obEquipaments->pid,
                        'sid' => (int)$obEquipaments->sid,
                        'itemtype' => (int)$obEquipaments->itemtype,
                        'count' => (int)$obEquipaments->count
        ***REMOVED***;
***REMOVED***
***REMOVED***
            return $equipaments ?? '';
        }

        public static function getSkull($player_id)
***REMOVED***
            $skulls = [
                0 => '',
                1 => 'yellow_skull.gif',
                2 => 'green_skull.gif',
                3 => 'white_skull.gif',
                4 => 'red_skull.gif',
                5 => 'black_skull.gif'
***REMOVED***;

            $select = EntityPlayer::getPlayer([ 'id' => $player_id], null, null, ['skull'])->fetchObject();
            foreach($skulls as $key => $value){
                if($select->skull == $key){
                    return $value;
***REMOVED***
***REMOVED***
        }

        public static function getSkullTime($player_id)
***REMOVED***
            $select = EntityPlayer::getPlayer([ 'id' => $player_id], null, null, ['skull, skulltime'])->fetchObject();
            if($select->skulltime > 0){
                return false;
***REMOVED***else{
                return true;
***REMOVED***
        }

        public static function getDisplay($player_id)
***REMOVED***
            $select = EntityPlayer::getDisplay([ 'player_id' => $player_id])->fetchObject();
            if (empty($select)) {
                return [];
***REMOVED***
            $arrayDisplay = [
                'account' => $select->account,
                'outfit' => $select->outfit,
                'inventory' => $select->inventory,
                'health_mana' => $select->health_mana,
                'skills' => $select->skills,
                'bonus' => $select->bonus,
                'comment' => $select->comment,
***REMOVED***;
            return $arrayDisplay ?? [];
        }

        public static function getHouse($playerId)
***REMOVED***
            $results = (new Database('houses'))->select([ 'owner' => $playerId]);
            while($obHouses = $results->fetchObject()){
                $houses[] = [
                    'id' => $obHouses->id,
                    'owner' => $obHouses->owner,
                    'paid' => $obHouses->paid,
                    'warnings' => $obHouses->warnings,
                    'name' => $obHouses->name,
                    'rent' => $obHouses->rent,
                    'town_id' => $obHouses->town_id,
                    'bid' => $obHouses->bid,
                    'bid_end' => $obHouses->bid_end,
                    'last_bid' => $obHouses->last_bid,
                    'highest_bidder' => $obHouses->highest_bidder,
                    'size' => $obHouses->size,
                    'guildid' => $obHouses->guildid,
                    'beds' => $obHouses->beds,
    ***REMOVED***;
***REMOVED***
            return $houses ?? '';
        }

        public static function isOnline($player_id)
***REMOVED***
            $results = (new Database('players_online'))->select([ 'player_id' => $player_id]);
            if($results->fetchObject() == null){
                return false;
***REMOVED***else{
                return true;
***REMOVED***
        }

        /**
         * Verifica se já logou alguma vez
         *
         * @param date $lastlogin
         * @return mixed
         */
        public static function convertLastLogin($lastlogin)
***REMOVED***
            if((int)$lastlogin == 0){
                $converted = 'Never logged.';
***REMOVED***else{
                $converted = date('M d Y, h:i:s', (int)$lastlogin);
***REMOVED***
            return $converted;
        }

        /**
         * Verifica os dias de premium account
         *
         * @param int $account_id
         * @return string
         */
        public static function convertPremy($account_id)
***REMOVED***
            $select = (new Database('accounts'))->select([ 'id' => $account_id])->fetchObject();

            if($select->premdays > 0){
                $converted = 'Premium Account';
***REMOVED***else{
                $converted = 'Free Account';
***REMOVED***
            return $converted;
        }

        public static function getPremDays($account_id)
***REMOVED***
            $select = EntityPlayer::getAccount([ 'id' => $account_id])->fetchObject();
            return date('d m Y', strlen($select->premdays));
        }

        public static function getCoins($account_id)
***REMOVED***
            $select = EntityPlayer::getAccount([ 'id' => $account_id], null, null, ['coins'])->fetchObject();
            return number_format($select->coins, 0, '.', '.');
        }

        /**
         * Pega informações do player membro de guild
         *
         * @param string $player_id
         * @return array
         */
        public static function getGuildMember($player_id)
***REMOVED***
            $select_member = EntityPlayer::getGuildMember([ 'player_id' => $player_id])->fetchObject();
            if (empty($select_member)) {
                return null;
***REMOVED***
            $select_guild = Guilds::getGuilds([ 'id' => $select_member->guild_id])->fetchObject();
            $rankdois = FunctionsGuilds::convertRankGuild($select_member->rank_id);
            $rankname = $rankdois['name'];
            $guilds = [
                'guild_id' => $select_member->guild_id,
                'guild_name' => $select_guild->name,
                'rank_id' => $select_member->rank_id,
                'nick' => $select_member->nick,
                'rank_name' => $rankname ?? '',
***REMOVED***;
            return $guilds ?? '';
        }

        /**
         * Pega informações do player dono de guild
         *
         * @param int $player_id
         * @return array
         */
        public static function getGuildOwner($player_id)
***REMOVED***
            $select = (new Database('guilds'))->select([ 'ownerid' => $player_id]);
            while($obGuilds = $select->fetchObject()){
                $guilds[] = [
                    'level' => $obGuilds->level,
                    'name' => $obGuilds->name,
                    'ownerid' => $obGuilds->ownerid,
                    'creationdata' => $obGuilds->creationdata,
                    'motd' => $obGuilds->motd,
                    'residence' => $obGuilds->residence,
                    'balance' => $obGuilds->balance,
                    'points' => $obGuilds->points,
    ***REMOVED***;
***REMOVED***
            return $guilds ?? '';
        }

        /**
         * Pega mortes do player
         *
         * @param int $player_id
         * @return array
         */
        public static function getDeaths($player_id)
***REMOVED***
            $select_deaths = EntityPlayer::getDeaths([ 'player_id' => $player_id], 'time DESC', 5);
            while($obDeaths = $select_deaths->fetchObject()){
                $countDeaths = (int)(new Database('player_deaths'))->select(null, null, null, ['COUNT(*) as qtd'])->fetchObject()->qtd;

                $lasthit = ($obDeaths->is_player) ? $obDeaths->killed_by : $obDeaths->killed_by;
                $description =  'Killed at level ' . $obDeaths->level . ' by ' . $lasthit;
                if($obDeaths->unjustified){
                    $description .= ' <span style="color: red; font-style: italic;">(unjustified)</span>';
***REMOVED***
                $mostdamage = ($obDeaths->mostdamage_by !== $obDeaths->killed_by) ? true : false;
                if($mostdamage){
                    $mostdamage = ($obDeaths->mostdamage_is_player) ? $obDeaths->mostdamage_by : $obDeaths->mostdamage_by;
                    $description .=  ' and by ' . $mostdamage;
                    if($obDeaths->mostdamage_unjustified){
                        $description .=  ' <span style="color: red; font-style: italic;">(unjustified)</span>';
  ***REMOVED***
***REMOVED***else{
                    $description .=  " <b>(soloed)</b>";
***REMOVED***
                $deaths[] = [
                    'time' => $obDeaths->time,
                    'description' => $description,
    ***REMOVED***;
***REMOVED***
            return $deaths ?? '';
        }

        public static function getFrags($player_id)
***REMOVED***
            $select_kills = EntityPlayer::getKills([ 'player_id' => $player_id], 'time DESC', 5);
            while($obKills = $select_kills->fetchObject()){
                $player_name_fragged = EntityPlayer::getPlayer([ 'id' => $obKills->target])->fetchObject();
                $player_deaths = EntityPlayer::getDeaths([ 'killed_by' => $obKills->player_id])->fetchObject();
                $description = 'Fragged <a href="' . URL . '/community/characters/' . $player_name_fragged->name . '">' . $player_name_fragged->name . '</a> at level ' . $player_deaths->level;
                $frags = [
                    'time' => $obKills->time,
                    'description' => $description,
                    'unjustified' => $obKills->unavenged != 0,
    ***REMOVED***;
***REMOVED***
            return $frags ?? null;
        }

        /**
         * Converte sexo do player
         *
         * @param int $sexId
         * @return string
         */
        public static function convertSex($sexId)
***REMOVED***
            $sexList = [
                '1' => 'male',
                '0' => 'female'
***REMOVED***;
            foreach($sexList as $key => $value){
                if($key == $sexId){
                    $converted = $value;
***REMOVED***
***REMOVED***
            return $converted;
        }

        /**
         * Converte group do player
         *
         * @param int $groupId
         * @return string
         */
        public static function convertGroup($groupId)
***REMOVED***
            $select_group = Groups::getGroups([ 'group_id' => $groupId])->fetchObject();
            return $select_group->name ?? 'None';
        }

        /**
         * Converter town do player
         *
         * @param int $town_id
         * @return string
         */
        public static function convertTown($town_id)
***REMOVED***
            $towns = Houses::getTowns([ 'id' => $town_id])->fetchObject();
            return $towns->name ?? null;
        }

        public static function getPlayerStorage($player_id, $storage)
***REMOVED***
            $select_storage = EntityPlayer::getStorage([ 'player_id =' => $player_id])->fetchObject();
            if (empty($select_storage)) {
                return 0;
***REMOVED***
            if ($select_storage->key == $storage) {
                return 1;
***REMOVED*** else {
                return 0;
***REMOVED***
        }

        public static function getPlayerStorageByValue($player_id, $storage, $value)
***REMOVED***
            $select_storage = EntityPlayer::getStorage([ 'player_id =' => $player_id, 'key =' => $storage,  'value =' => $value])->fetchObject();
            if (empty($select_storage)) {
                return false;
***REMOVED*** else {
                return true;
***REMOVED***
        }

        public static function getGlobalStorage($storage, $value)
***REMOVED***
            return true;
        }
    }