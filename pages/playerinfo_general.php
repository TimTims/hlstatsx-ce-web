<?php
/*
HLstatsX Community Edition - Real-time player and clan rankings and statistics
Copyleft (L) 2008-20XX Nicholas Hastings (nshastings@gmail.com)
http://www.hlxcommunity.com

HLstatsX Community Edition is a continuation of 
ELstatsNEO - Real-time player and clan rankings and statistics
Copyleft (L) 2008-20XX Malte Bayer (steam@neo-soft.org)
http://ovrsized.neo-soft.org/

ELstatsNEO is an very improved & enhanced - so called Ultra-Humongus Edition of HLstatsX
HLstatsX - Real-time player and clan rankings and statistics for Half-Life 2
http://www.hlstatsx.com/
Copyright (C) 2005-2007 Tobias Oetzel (Tobi@hlstatsx.com)

HLstatsX is an enhanced version of HLstats made by Simon Garner
HLstats - Real-time player and clan rankings and statistics for Half-Life
http://sourceforge.net/projects/hlstats/
Copyright (C) 2001  Simon Garner
            
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

For support and installation notes visit http://www.hlxcommunity.com
*/

    if (!defined('IN_HLSTATS')) {
        die('Do not access this file directly.');
    }

	// Check for Steam Avatar
	$db->query
	("
		SELECT
			hlstats_PlayerUniqueIds.uniqueId,
			CAST(LEFT(hlstats_PlayerUniqueIds.uniqueId,1) AS unsigned) + CAST('76561197960265728' AS unsigned) + CAST(MID(hlstats_PlayerUniqueIds.uniqueId, 3,10)*2 AS unsigned) AS communityId
		FROM
			hlstats_PlayerUniqueIds
		WHERE
			hlstats_PlayerUniqueIds.playerId = '$player'
	");
	list($uqid, $coid) = $db->fetch_row();

	$status = 'Unknown';
	$avatar_full = IMAGE_PATH."/unknown.jpg";

	if ($coid !== '76561197960265728') {

		$profileUrl = "https://steamcommunity.com/profiles/$coid?xml=1";

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($curl, CURLOPT_URL, $profileUrl);

		$xml = curl_exec($curl);
		curl_close($curl);
	}
	
	$xmlDoc = null;
	if ($xml) {
		$xmlDoc = simplexml_load_string($xml);
	}

	if ($xmlDoc) {
		$status = ucwords($xmlDoc->onlineState);
		$avatar_full = $xmlDoc->avatarFull;
	}

	$db->query
		("
			SELECT
				DATE_FORMAT(eventTime, '%a. %b. %D, %Y @ %T')
			FROM
				hlstats_Events_Connects
			WHERE
				hlstats_Events_Connects.playerId = '$player'
			ORDER BY
				id desc
			LIMIT
				1
		");
		list($lastevent) = $db->fetch_row();
		if ($lastevent)
			$last_connect = $lastevent;
		else
			$last_connect = "Unknown";
	
	$db->query
		("
			SELECT
				ROUND(SUM(hlstats_Events_Latency.ping) / COUNT(hlstats_Events_Latency.ping), 0) AS av_ping,
				ROUND(ROUND(SUM(hlstats_Events_Latency.ping) / COUNT(ping), 0) / 2, 0) AS av_latency
			FROM
				hlstats_Events_Latency
			WHERE 
				hlstats_Events_Latency.playerId = '$player'
		");
		list($av_ping, $av_latency) = $db->fetch_row();
		if ($av_ping)
			$average_ping = $av_ping." ms (Latency: $av_latency ms)";
		else
			$average_ping = '-';
	$db->query
		("
			SELECT
				hlstats_Events_Entries.serverId,
				hlstats_Servers.name,
				COUNT(hlstats_Events_Entries.serverId) AS cnt
			FROM
				hlstats_Events_Entries
			INNER JOIN
				hlstats_Servers
			ON
				hlstats_Servers.serverId = hlstats_Events_Entries.serverId
			WHERE 
				hlstats_Events_Entries.playerId = '$player'
			GROUP BY
				hlstats_Events_Entries.serverId
			ORDER BY
				cnt DESC
			LIMIT
				1
		");
		list($favServerId, $favServerName) = $db->fetch_row();
		$fav_server = "<a href='hlstats.php?game=$game&amp;mode=servers&amp;server_id=$favServerId'> $favServerName </a>";

	$db->query
		("
			SELECT
				hlstats_Events_Entries.map,
				COUNT(map) AS cnt
			FROM
				hlstats_Events_Entries
			WHERE
				hlstats_Events_Entries.playerId = '$player'
			GROUP BY
				hlstats_Events_Entries.map
			ORDER BY
				cnt DESC
			LIMIT
				1
		");
		list($favMap) = $db->fetch_row();
		$fav_map = "<a href=\"hlstats.php?game=$game&amp;mode=mapinfo&amp;map=$favMap\"> $favMap </a>";

	$result = $db->query("
	SELECT
		hlstats_Events_Frags.weapon,
		hlstats_Weapons.name,
		COUNT(hlstats_Events_Frags.weapon) AS kills,
		SUM(hlstats_Events_Frags.headshot=1) as headshots
	FROM
		hlstats_Events_Frags
	LEFT JOIN
		hlstats_Weapons
	ON
		hlstats_Weapons.code = hlstats_Events_Frags.weapon
	WHERE
		hlstats_Events_Frags.killerId=$player
	GROUP BY
		hlstats_Events_Frags.weapon,
		hlstats_Weapons.name
	ORDER BY
		kills desc, headshots desc
	LIMIT
		1
	");

	$fav_weapon = '';
	$weap_name = '';

	while ($rowdata = $db->fetch_row($result)) {
		$fav_weapon = $rowdata[0];
		$weap_name = htmlspecialchars($rowdata[1]);
	}

	if ($fav_weapon == '') {
		$fav_weapon = 'Unknown';
	}

	$image = getImage("/games/$game/weapons/$fav_weapon");
// Check if image exists
	$weaponlink = "<a href=\"hlstats.php?mode=weaponinfo&amp;weapon=$fav_weapon&amp;game=$game\">";
	if ($image)
	{
		$cellbody = "$weaponlink<img src=\"" . $image['url'] . "\" alt=\"$weap_name\" title=\"$weap_name\" />";
	}
	else
	{
		$cellbody = "$weaponlink$weap_name";
	}
	$cellbody .= "</a>";
	$favourite_weapon = $cellbody;

	if (($playerdata['activity'] > 0) && ($playerdata['hideranking'] == 0))
	{
		$rank = get_player_rank($playerdata);
	}
	else
	{
		if ($playerdata['hideranking'] == 1)
		{
			$rank = "Hidden";
		}
		elseif ($playerdata['hideranking'] == 2)
		{
			$rank = "<span class=\"text-danger\">Banned</span>";
		}
		else
		{
			$rank = 'Not active';
		}
	} 
	if (is_numeric($rank))
	{
		$ps_rank = number_format($rank);
	}
	else
	{
		$ps_rank = $rank;
	}
	$db->query
		("
			SELECT
				IFNULL(ROUND(SUM(hlstats_Events_Frags.killerId = '$player') / IF(SUM(hlstats_Events_Frags.victimId = '$player') = 0, 1, SUM(hlstats_Events_Frags.victimId = '$player')), 2), '-')
			FROM
				hlstats_Events_Frags
			WHERE
				(
					hlstats_Events_Frags.killerId = '$player'
					OR hlstats_Events_Frags.victimId = '$player'
				)
		");
		list($realkpd) = $db->fetch_row();
		$stats_kpd = $playerdata['kpd'] . " ($realkpd*)";
	$db->query
		("
			SELECT
				IFNULL(ROUND((SUM(hlstats_Events_Statsme.hits) / SUM(hlstats_Events_Statsme.shots) * 100), 2), 0.0) AS accuracy,
				SUM(hlstats_Events_Statsme.shots) AS shots,
				SUM(hlstats_Events_Statsme.hits) AS hits,
				SUM(hlstats_Events_Statsme.kills) AS kills
			FROM
				hlstats_Events_Statsme
			WHERE
				hlstats_Events_Statsme.playerId='$player'
		");
		list($playerdata['accuracy'], $sm_shots, $sm_hits, $sm_kills) = $db->fetch_row();
		if ($sm_kills > 0)
		{
			$stats_spk = sprintf('%.2f', ($sm_shots / $sm_kills));
		}
		else
		{
			$stats_spk = '-';
		}
	$db->query
		("
			SELECT
				IFNULL(SUM(hlstats_Events_Frags.headshot=1) / COUNT(*), '-')
			FROM
				hlstats_Events_Frags
			WHERE
				hlstats_Events_Frags.killerId = '$player'
		");
		list($realhpk) = $db->fetch_row();
		$stats_hpk = $playerdata['hpk'] . " ($realhpk*)";
		$db->query
		("
			SELECT
				hlstats_Players.kill_streak
			FROM
				hlstats_Players
			WHERE
				hlstats_Players.playerId = '$player'
		");
		list($kill_streak) = $db->fetch_row();
		$stats_ks = number_format($kill_streak);
	$db->query
		("
			SELECT
				hlstats_Players.death_streak
			FROM
				hlstats_Players
			WHERE
				hlstats_Players.playerId = '$player'
		");
		list($death_streak) = $db->fetch_row();
		$stats_ds = number_format($death_streak);
	$db->query
		("
			SELECT
				hlstats_Ranks.rankName,
				hlstats_Ranks.image,
				hlstats_Ranks.minKills
			FROM
				hlstats_Ranks
			WHERE
				hlstats_Ranks.minKills <= ".$playerdata['kills']."
				AND hlstats_Ranks.game = '$game'
			ORDER BY
				hlstats_Ranks.minKills DESC
			LIMIT
				1
		");
		$result = $db->fetch_array();
		$rankimage = getImage('/ranks/'.$result['image']);
		$rankName = $result['rankName'];
		$rankCurMinKills = $result['minKills']; 
		$db->query
		("
			SELECT
				hlstats_Ranks.rankName,
				hlstats_Ranks.minKills
			FROM
				hlstats_Ranks
			WHERE
				hlstats_Ranks.minKills > ".$playerdata['kills']."
				AND hlstats_Ranks.game = '$game'
			ORDER BY
				hlstats_Ranks.minKills
			LIMIT
				1
		");
		if ($db->num_rows() == 0)
		{
			$rankKillsNeeded = 0;
			$rankPercent = 0;
		}
		else
		{
			$result = $db->fetch_array();
			$rankKillsNeeded = $result['minKills'] - $playerdata['kills'];
			$rankPercent = ($playerdata['kills'] - $rankCurMinKills) * 100 / ($result['minKills'] - $rankCurMinKills);
		}
		$db->query
		("
			SELECT
				hlstats_Ranks.rankName,
				hlstats_Ranks.image
			FROM
				hlstats_Ranks
			WHERE
				hlstats_Ranks.minKills <= ".$playerdata['kills']."
				AND hlstats_Ranks.game = '$game'
			ORDER BY
				hlstats_Ranks.minKills
		");

		$rankHistory = "";
		$db_num_rows = $db->num_rows();

		for ($i = 1; $i < $db_num_rows; $i++) {
			$result = $db->fetch_array();

			$histimage = getImage('/ranks/' . $result['image'] . '_small');
			$rankHistory .= '<img src="' . $histimage['url'] . '" title="' . $result['rankName'] . '" alt="' . $result['rankName'] . '" /> ';
		}
	// Awards
	$numawards = $db->query
	("
		SELECT
			hlstats_Ribbons.awardCode,
			hlstats_Ribbons.image
		FROM
			hlstats_Ribbons
		WHERE
			hlstats_Ribbons.game = '$game'
			AND
			(
				hlstats_Ribbons.special = 0
				OR hlstats_Ribbons.special = 2
			)
		GROUP BY
			hlstats_Ribbons.awardCode,
			hlstats_Ribbons.image
	");
	$res = $db->query
	("
		SELECT
			hlstats_Ribbons.awardCode AS ribbonCode,
			hlstats_Ribbons.ribbonName AS ribbonName,
			IF(ISNULL(hlstats_Players_Ribbons.playerId), 'noaward.png', hlstats_Ribbons.image) AS image,
			hlstats_Ribbons.special,
			hlstats_Ribbons.image AS imagefile,
			hlstats_Ribbons.awardCount
		FROM
			hlstats_Ribbons
		LEFT JOIN
		(
			SELECT
				hlstats_Players_Ribbons.playerId,
				hlstats_Ribbons.awardCode,
				hlstats_Players_Ribbons.ribbonId
			FROM
				hlstats_Players_Ribbons
			INNER JOIN
				hlstats_Ribbons 
			ON
				hlstats_Ribbons.ribbonId = hlstats_Players_Ribbons.ribbonId
				AND hlstats_Ribbons.game = hlstats_Players_Ribbons.game 
			WHERE
				hlstats_Players_Ribbons.playerId = ".$playerdata['playerId']."
				AND hlstats_Players_Ribbons.game = '$game'
			ORDER BY
				hlstats_Ribbons.awardCount DESC
		) AS hlstats_Players_Ribbons
		ON
			hlstats_Players_Ribbons.ribbonId = hlstats_Ribbons.ribbonId
		WHERE
			hlstats_Ribbons.game = '$game'
			AND
			(
				ISNULL(hlstats_Players_Ribbons.playerId)
				OR hlstats_Players_Ribbons.playerId = ".$playerdata['playerId']."
			)
		ORDER BY
			hlstats_Ribbons.awardCode,
			hlstats_Players_Ribbons.playerId DESC,
			hlstats_Ribbons.special,
			hlstats_Ribbons.awardCount DESC
	");
	$ribbonList = '';
	$lastImage = '';
	$awards_done = array ();
	while ($result = $db->fetch_array($res))
	{
		$ribbonCode=$result['ribbonCode'];
		$ribbonName=$result['ribbonName'];
		if(!isset($awards_done[$ribbonCode]))
		{
			if (file_exists(IMAGE_PATH."/games/$game/ribbons/".$result['image']))
			{
				$image = IMAGE_PATH."/games/$game/ribbons/".$result['image'];
			}
			elseif (file_exists(IMAGE_PATH."/games/$realgame/ribbons/".$result['image']))
			{
				$image = IMAGE_PATH."/games/$realgame/ribbons/".$result['image'];
			}
			else
			{
				$image = IMAGE_PATH."/award.png";
			}		
			$ribbonList .= '<img src="'.$image.'" style="border:0px;" alt="'.$result['ribbonName'].'" title="'.$result["ribbonName"].'" /> ';
			$awards_done[$ribbonCode]=$ribbonCode;
		}
	}
	$awards = array ();
	$res = $db->query
	("
		SELECT
			hlstats_Awards.awardType,
			hlstats_Awards.code,
			hlstats_Awards.name
		FROM
			hlstats_Awards
		WHERE
			hlstats_Awards.game = '$game'
			AND hlstats_Awards.g_winner_id = $player
		ORDER BY
			hlstats_Awards.name;
	");

	while ($r1 = $db->fetch_array()) {
		unset($tmp_arr);
		$tmp_arr = new StdClass;

		$tmp_arr->aType = $r1['awardType'];
		$tmp_arr->code = $r1['code'];
		$tmp_arr->ribbonName = $r1['name'];

		// Unused code, undefined variable $id
		/*if ($id == 0)
		{
			$tmp_arr->playerName = $r1['lastname'];
			$tmp_arr->flag = $r1['flag'];
			$tmp_arr->playerId = $r1['g_winner_id'];
			$tmp_arr->kills = $r1['g_winner_count'];
			$tmp_arr->verb = $r1['verb'];
		}*/

		array_push($awards, $tmp_arr);
	}

	$GlobalAwardsList = '';
	foreach ($awards as $a)
	{
		if ($image = getImage("/games/$game/gawards/".strtolower($a->aType."_$a->code")))
		{
			$image = $image['url'];
		}
		elseif ($image = getImage("/games/$realgame/gawards/".strtolower($a->aType."_$a->code")))
		{
			$image = $image['url'];
		}
		else
		{
			$image = IMAGE_PATH."/award.png";
		}		
		$GlobalAwardsList .= "<img src=\"$image\" alt=\"$a->ribbonName\" title=\"$a->ribbonName\" /> ";
	}
?>
    <div class="card shadow-lg mx-4 card-profile-bottom">
      <div class="card-body p-3">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="<? echo $avatar_full; ?>" alt="Steam Profile Picture" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
            	<h5 class="mb-1">
                	<?php echo htmlspecialchars($playerdata['lastName'], ENT_COMPAT); ?>
              	</h5>
              	<p class="mb-0 font-weight-bold text-sm">
				<?php 
					$prefix = ((!preg_match('/^BOT/i',$uqid)) && $g_options['Mode'] == 'Normal') ? 'STEAM_0:' : '';
					echo "<a href=\"http://steamcommunity.com/profiles/$coid\" target=\"_blank\">$prefix" . "$uqid</a>";
				?>              
				</p>
            </div>
          </div>
          <div class="col-lg-5 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1">
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " href="<?php echo $g_options['scripturl']; ?>?mode=playerhistory&amp;player=<?php echo $player; ?>">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span class="ms-2">Events</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " href="<?php echo $g_options['scripturl']; ?>?mode=playersessions&amp;player=<?php echo $player; ?>">
                    <i class="bi bi-dpad-fill"></i>
                    <span class="ms-2">Sessions</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " href="<?php echo $g_options['scripturl']; ?>?mode=playerawards&amp;player=<?php echo $player; ?>">
                    <i class="bi bi-award-fill"></i>
                    <span class="ms-2">Awards</span>
                  </a>
                </li>
				<?php
				if ($g_options["nav_globalchat"] == 1){
				echo '<li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" href="' . $g_options['scripturl'] . '?mode=chathistory&amp;player=' . $player .'">
                    <i class="bi bi-chat-fill"></i>
                    <span class="ms-2">Chat</span>
                  </a>
                </li>';
				}
				if (isset($_SESSION['loggedin'])){
				echo '<li class="nav-item">
				<a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" href="'.$g_options['scripturl'].'?mode=admin&amp;task=tools_editdetails_player&amp;id='.$player.'">
				<i class="bi bi-chat-fill"></i>
				<span class="ms-2">Edit Profile (Admin)</span>
				</a>
				</li>';
				}
				?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
	<div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-8 mt-2">
          <div class="card">
            <div class="card-body">
			  <?php if (isset($_SESSION['loggedin'])){ echo '<a class="btn btn-primary btn-sm ms-auto float-end" href="'.$g_options['scripturl'].'?mode=admin&amp;task=tools_editdetails_player&amp;id='.$player.'">Edit Player Details</a>'; } ?>
              <p class="text-uppercase text-sm">Player Info</p>
              <div class="row">
                <div class="col-md-6">
                    <label>Country</label>
                    <p class="ms-1"><?php if ($playerdata['country']){ if ($playerdata['city']){ echo htmlspecialchars($playerdata['city'], ENT_COMPAT) . ', '; } echo '<a href="'.$g_options['scripturl'].'?mode=countryclansinfo&amp;flag='.$playerdata['flag']."&amp;game=$game\">" . $playerdata['country'] . '</a>'; } else{ echo 'Unknown'; }?> <img src="<?php echo getFlag($playerdata['flag']); ?>" alt="<?php echo $playerdata['country']; ?>" title="<?php echo $playerdata['country']; ?>" /></p>
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <p class="ms-1"><?php echo $status; ?></p>
                </div>
                <div class="col-md-6">
                    <label>Real Name</label>
                    <p class="ms-1"><?php if ($playerdata['fullName']){ echo htmlspecialchars($playerdata['fullName'], ENT_COMPAT); } else echo "<a href=\"" . $g_options['scripturl'] . '?mode=help#set">Not Specified</a>'; ?></p>
                </div>
                <div class="col-md-6">
                    <label>Email Address</label>
					<p class="ms-1"><?php if ($email = getEmailLink($playerdata['email'])){ echo $email; } else echo "<a href=\"" . $g_options['scripturl'] . '?mode=help#set">Not Specified</a>'; ?></p>
                </div>
				<div class="col-md-6">
                    <label>Home Page</label>
					<p class="ms-1"><?php if ($playerdata['homepage']){ echo getLink($playerdata['homepage']); } else echo "<a href=\"" . $g_options['scripturl'] . '?mode=help#set">Not Specified</a>'; ?></p>
                </div>
				<div class="col-md-6">
					<label>Clan</label>
					<p class="ms-1"><?php if ($playerdata['clan']){ echo '&nbsp;<a href="' . $g_options['scripturl'] . '?mode=claninfo&amp;clan=' . $playerdata['clan'] . '">' . htmlspecialchars($playerdata['clan_name'], ENT_COMPAT) . '</a>'; } else echo 'None'; ?></p>
				</div>
              </div>
              <hr class="horizontal dark">
              <p class="text-uppercase text-sm">In-game Info</p>
              <div class="row">
                <div class="col-md-6">
					<label>Last Connect</label>
					<p class="ms-1"><?php echo $last_connect; ?></p>
                </div>
				<div class="col-md-6">
					<label>Total Connect Time</label>
					<p class="ms-1"><?php echo timestamp_to_str($playerdata['connection_time']); ?></p>
                </div>
				<div class="col-md-6">
					<label>Average Ping</label>
					<p class="ms-1"><?php echo $average_ping; ?></p>
                </div>
				<div class="col-md-12">
					<label>Favourite Server</label>
					<p class="ms-1"><?php echo $fav_server; ?></p>
                </div>
				<div class="col-md-6">
					<label>Favourite Map</label>
					<p class="ms-1"><?php echo $fav_map; ?></p>
                </div>
				<div class="col-md-6">
					<label>Favourite Weapon</label>
					<p class="ms-1"><?php echo $favourite_weapon; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 mt-2">
          <div class="card">
            <div class="card-body">
			<p class="text-uppercase text-sm">Player Statistics</p>
              <div class="row">
				<div class="col-md-12">
					<label>Activity</label>
					<div class="progress ms-1" role="progressbar" aria-label="Animated striped example" aria-valuemin="0" aria-valuemax="100" style="height: 25px">
						<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: <?php echo $playerdata['activity']; ?>%"><?php echo $playerdata['activity'].'%'; ?></div>
					</div>
					<p></p>
				</div>
				<div class="col-md-6">
					<label>Points</label>
					<p class="ms-1"><?php echo number_format($playerdata['skill']); ?></p>
				</div>
				<div class="col-md-6">
					<label>Rank</label>
					<p class="ms-1"># <?php echo $ps_rank; ?></p>
				</div>
				<div class="col-md-6">
					<label>Kills</label>
					<p class="ms-1"><?php echo number_format($playerdata['kills']) . ' ('.number_format($realkills).'*)'; ?></p>
				</div>
				<div class="col-md-6">
					<label>Deaths</label>
					<p class="ms-1"><?php echo number_format($playerdata['deaths']) . ' ('.number_format($realdeaths).'*)'; ?></p>
				</div>
				<div class="col-md-6">
					<label>Headshots</label>
					<p class="ms-1"><?php echo number_format($playerdata['headshots']) . ' ('.number_format($realheadshots).'*)'; ?></p>
				</div>
				<div class="col-md-6">
					<label>Accuracy</label>
					<p class="ms-1"><?php echo $playerdata['acc'] . '%' . " (".sprintf('%.0f', $playerdata['accuracy']).'%*)'; ?></p>
				</div>
				<div class="col-md-6">
					<label>Kills per Minute</label>
					<p class="ms-1"><?php if ($playerdata['connection_time'] > 0){ echo sprintf('%.2f', ($playerdata['kills'] / ($playerdata['connection_time'] / 60))); } else{ echo '-'; } ?></p>
				</div>
				<div class="col-md-6">
					<label>Kills per Death</label>
					<p class="ms-1"><?php echo $stats_kpd; ?></p>
				</div>
				<div class="col-md-6">
					<label>Shots per Kill</label>
					<p class="ms-1"><?php echo $stats_spk; ?></p>
				</div>
				<div class="col-md-6">
					<label>Shots per Kill</label>
					<p class="ms-1"><?php echo $stats_hpk; ?></p>
				</div>
				<div class="col-md-6">
					<label>Longest Killstreak</label>
					<p class="ms-1"><?php echo $stats_ks; ?></p>
				</div>
				<div class="col-md-6">
					<label>Longest Deathstreak</label>
					<p class="ms-1"><?php echo $stats_ds; ?></p>
				</div>
				<div class="col-md-6">
					<label>Suicides</label>
					<p class="ms-1"><?php echo number_format($playerdata['suicides']); ?></p>
				</div>
				<div class="col-md-6">
					<label>Teammate Kills</label>
					<p class="ms-1"><?php echo number_format($playerdata['teamkills']) . ' ('.number_format($realteamkills).'*)'; ?></p>
				</div>
              </div>
            </div>
          </div>
        </div>
		<div class="col-md-12 mt-2">
          <div class="card">
            <div class="card-body">
              <p class="text-uppercase text-sm">Rank Info</p>
              <div class="row">
				<div class="col-md-6">
					<label>Rank</label>
					<p class="ms-1"><?php echo htmlspecialchars($rankName, ENT_COMPAT); ?></p>
					<?php echo '<img src="'.$rankimage['url']."\" alt=\"$rankName\" title=\"$rankName\" />"; ?>
				</div>
				<div class="col-md-6">
					<label>Rank History</label>
					<p><?php echo $rankHistory; ?></p>
				</div>
              </div>
            </div>
          </div>
		</div>
		<?php
		if ($ribbonList != '' || $GlobalAwardsList != ''){ ?>
		<div class="col-md-8 mt-2">
          <div class="card">
            <div class="card-body">
              <p class="text-uppercase text-sm">Ribbons</p>
              <div class="row">
				<div class="col-md-12">
					<p class="ms-1"><?php echo $ribbonList; ?></p>
				</div>
              </div>
            </div>
          </div>
        </div>
		<div class="col-md-4 mt-2">
          <div class="card">
            <div class="card-body">
              <p class="text-uppercase text-sm">Global Awards</p>
              <div class="row">
				<div class="col-md-12">
					<p class="ms-1"><?php echo $GlobalAwardsList; ?></p>
				</div>
              </div>
            </div>
          </div>
        </div>
 <?php } ?>