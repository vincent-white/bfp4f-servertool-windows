<?php

/**
 * Battlefield Play4Free RCON Players Sub-Class
 * Provides Player based Methods
 * 
 * This Package is based on 'bf2php' from 'jamie.rfurness@gmail.com' (http://code.google.com/p/bf2php/)
 * 
 * Requires PHP 5.3 or greater
 * 
 * @package   T4G\BFP4F
 * @author    Ronny 'roennel' Gysin <roennel@alchemical.cc>
 * @copyright (c) 2012 Ronny Gysin / 2009 Jamie Furness
 * @license   GPL v3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @version   0.3.4
 */

namespace T4G\BFP4F\Rcon;

class Players
{
    /**
     * Fetches Player Info from Server
     * @return array
     */
    public function fetch()
    {
        $data = Base::query('bf2cc pl');
        
        $spl = explode("\r", $data);
        
        $vars = 47; // Total Amount of Variables to parse for each Soldier

        $chunks = array_chunk($spl, $vars);

        $soldiers = array();
        
        for ($i=0,$c=count($chunks[0]);$i<$c;$i++) {
            $soldiers[$i] = new \stdClass;
        }
        
        $i = 0;

        $ch = array();

        for ($b=0,$c=count($spl);$b<$c;$b++) {
            $ch[$b] = explode("\t", $spl[$b]);
        }
        
        foreach ($ch as $item) {
            $current = &$soldiers[$i];
            
            $current->index = @$item[0];
            $current->name = @$item[1];
            $current->team = @$item[2];
            $current->ping = @$item[3];
            $current->connected = @$item[4];
            $current->valid =@ $item[5];
            $current->remote = @$item[6];
            $current->ai = @$item[7];
            $current->alive = @$item[8];
            $current->manDown = @$item[9];
            $current->profileId = @$item[10];
            $current->flagHolder = @$item[11];
            $current->suicide = @$item[12];
            $current->timeToSpawn = @$item[13];
            $current->squadId = @$item[14];
            $current->squadLeader = @$item[15];
            $current->commander = @$item[16];
            $current->spawnGroup = @$item[17];
            $current->ip = @$item[18];
            $current->damageAssists = @$item[19];
            $current->passengerAssists = @$item[20];
            $current->targetAssists = @$item[21];
            $current->revives = @$item[22];
            $current->teamDamages = @$item[23];
            $current->teamVehicleDamages = @$item[24];
            $current->cpCaptures = @$item[25];
            $current->cpDefends = @$item[26];
            $current->cpAssists = @$item[27];
            $current->cpNeutralizes = @$item[28];
            $current->cpNeutralizeAssists = @$item[29];
            $current->suicides = @$item[30];
            $current->kills = @$item[31];
            $current->tk = @$item[32];
            $current->vehicleType = @$item[33];
            $current->kit = @$item[34];
            $current->time = @$item[35];
            $current->deaths = @$item[36];
            $current->score = @$item[37];
            $current->vehicleName = @$item[38];
            $current->level = @$item[39];
            $current->position = @$item[40];
            $current->idle = @$item[41];
            $current->cdKeyHash = @$item[42];
            //$current->tkPunished = @$item[43];
            //$current->tkTimesPunished = @$item[44];
            //$current->tkTimesForgiven = @$item[45];
            $current->vip = @$item[46];
            $current->nucleusId = @$item[47];
            
            $i++;
        }
        
        return $soldiers;
    }

	/**
	 * Set VIP status
	 * 
	 * @param $name Playername
	 * @param $profileId Profile ID
	 * @param $vip 0=deactivate VIP status, 1=activate VIP status
	 * 
	 * @return
	 * 
	 * ADDED BY SHARPBUNNY
	 */
	public function setVip($name, $profileId, $vip=1) {
		return Base::query("exec game.setPersonaVipStatus {$name} {$profileId} {$vip}");
	}

    /**
     * List the players on the server
     */
    public function listPlayers()
    {
        return Base::query("list");
    }

    public function listPlayersAlt()
    {
        return Base::query("exec admin.listPlayers");
    }

    /**
     * Prints profileid (nucleus_id) of selected player
     */
    public function getProfileId($player_index)
    {
        return Base::query("profileid {$player_index}");
    }

    /**
     * Prints ckKeyHash (soldier_id) of selected player
     */
    public function getHash($player_index)
    {
        return Base::query("hash {$player_index}");
    }

    /**
     * Print a list of players and their profileid's
     */
    public function getProfileIds()
    {
        return Base::query("profileids");
    }
    
    /**
     * Kicks a Player from Server
     *
     * @param string|int $playerId Player index from `bf2cc pl`
     * @param string     $reason   default=null
     *
     * @return void
     */
    public function kick($playerId, $reason=null)
    {
        Base::query("kick \"{$playerId}\" \"{$reason}\"");
    }
	
	/**
	 * Warns a Player
     *
     * @param string|int $playerId Player index from `bf2cc pl`
     * @param string     $reason   default=null
     *
     * @return void
	 * 
	 * ADDED BY SHARPBUNNY
     */
    public function warn($playerId, $reason=null)
    {
        Base::query("iga warn \"{$playerId}\" \"{$reason}\"");
    }
    
    /**
     * Bans a Player from Server
     *
     * @param string|int $playerId Player index from `bf2cc pl`
     * @param int        $time     Time = round, perm
     * @param string     $reason   default=null text message
     *
     * @return void
     */
    public function ban($playerId, $time, $reason=null)
    {
        Base::query("ban {$playerId} {$time} \"{$reason}\"");
    }

    public function banTime($playerId, $time, $reason=null)
    {
        Base::query("exec admin.banTime {$playerId} {$time} \"{$reason}\"");
    }

    /**
     * Ban a player for a specified timer period from the server with a message.
     * 
     * @return
     */
    public function banby($playerId, $bannedby, $period, $reason=null)
    {
        Base::query("banby {$playerId} {$bannedby} {$period} \"{$reason}\"");
    }

    /**
     * Display the current banlist.
     * 
     * @return
     */
    public function banlist()
    {
        return Base::query("banlist");
    }

    /**
     * Clear the current banlist
     * 
     * @return
     */
    public function clearBans()
    {
        return Base::query("clearbans");
    }

    /**
     * Unban a player with an optional reason.
     * 
     * @return
     */
    public function unban($soldier_id, $reason=null)
    {
        return Base::query("unban {$soldierId} \"{$reason}\"");
    }

    /**
     * Switch player between teams.
     * 
     * @param  string/int $player Exact soldier name, or player index.
     *
     * @return
     */
    public function switchPlayer($player)
    {
        return Base::query("exec admin.switchTeamsForPlayer {$player}");
    }

    /**
     * Enables (disable) auto balance.
     * 
     * @param integer $enabled 1/0
     *
     * @return
     */
    public function switchAutobalance($enabled = 1)
    {
        return Base::query("exec admin.autoBalanceTeam {$enabled}");
    }

    /**
     * Sets number of teamkills to autokick player.
     * 
     * @param int $value
     */
    public function setNumOfTeamKills($value)
    {
        Base::query("exec admin.nrOfTKToKick {$value}");
    }

    /**
     * Sets punish mode for team kills.
     * 
     * @param int $value
     */
    public function setTKPunishMode($value)
    {
        Base::query("exec admin.TKPunishMode {$value}");
    }
}
