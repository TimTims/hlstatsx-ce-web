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

	global $game;
	$resultGames = $db->query
	("
		SELECT
			hlstats_Games.code,
			hlstats_Games.name
		FROM
			hlstats_Games
		WHERE
			hlstats_Games.hidden = '0'
		ORDER BY
			hlstats_Games.name ASC 
		LIMIT
			0,
			1
	");
	list($game) = $db->fetch_row($resultGames);
// Help
	pageHeader
	(
		array ('Help'),
		array ('Help' => '')
	);
?>


<div class="container-fluid py-4">
    <div class="row">
    	<div class="col-12">
        	<div class="card mb-4">
            	<div class="card-header pb-0">
              		<h6>Help</h6>
            	</div>
				<ol class="list-group list-group-numbered list-group-flush ms-4">
					<li class="list-group-item">
						<a href="#players">How are players tracked? Or, why is my name listed more than once?</a><br />
					</li>
					<li class="list-group-item">
						<a href="#points">How is the "points" rating calculated?</a><br />
					</li>
					<li class="list-group-item">
						<a href="#weaponmods">What are all the weapon points modifiers?</a><br />
					</li>
					<li class="list-group-item">
						<a href="#set">How can I set my real name, e-mail address, and homepage?</a><br />
					</li>
					<li class="list-group-item">
						<a href="#hideranking">My rank is embarrassing. How can I opt out?</a>
					</li>
				</ol>
			</div>	
		</div>
		<div class="col-12">
        	<div class="card mb-4">
            	<div class="card-header pb-0">
              		<h6><a name="players">1. How are players tracked? Or, why is my name listed more than once?</a></h6>
            	</div>
				<div class="ms-4">
					<?php
						if ($g_options['Mode'] == 'NameTrack')
						{
					?>
					<p>Players are tracked by nickname. All statistics for any player using a particular name will be grouped under that name. It is not possible for a name to be listed more than once for each game.</p>
					<?php
						}
						else
						{
							if ($g_options['Mode'] == 'LAN')
							{
								$uniqueid = 'IP Address';
								$uniqueid_plural = 'IP Addresses';
					?>
					<p>Players are tracked by IP Address. IP addresses are specific to a computer on a network.</p>
					<?php
							}
							else
							{
								$uniqueid = 'Unique ID';
								$uniqueid_plural = 'Unique IDs';
					?>
					<p>Players are tracked by Unique ID. Your Unique ID is the last two sections of your Steam ID (X:XXXX).</p>
					<?php
							}
					?>
					<p>A player may have more than one name. On the Player Rankings pages, players are shown with the most recent name they used in the game. If you click on a player's name, the Player Details page will show you a list of all other names that this player uses, if any, under the Aliases section (if the player has not used any other names, the Aliases section will not be displayed).</p>
					<p>Your name may be listed more than once if somebody else (with a different <?php echo $uniqueid; ?>) uses the same name.</p>
					<p>You can use the <a href="<?php echo $g_options['scripturl']; ?>?mode=search">Search</a> function to find a player by name or <?php echo $uniqueid; ?>.</p>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="col-12">
        	<div class="card mb-4">
            	<div class="card-header pb-0">
              		<h6><a name="points">2. How is the "points" rating calculated?</a></h6>
            	</div>
				<div class="ms-4">
					<p>A new player has 1000 points. Every time you make a kill, you gain a certain amount of points depending on a) the victim's points rating, and b) the weapon you used. If you kill someone with a higher points rating than you, then you gain more points than if you kill someone with a lower points rating than you. Therefore, killing newbies will not get you as far as killing the #1 player. And if you kill someone with your knife, you gain more points than if you kill them with a rifle, for example.<br /><br />
					When you are killed, you lose a certain amount of points, which again depends on the points rating of your killer and the weapon they used (you don't lose as many points for being killed by the #1 player with a rifle than you do for being killed by a low ranked player with a knife). This makes moving up the rankings easier, but makes staying in the top spots harder.</p>
					<p>Specifically, the equations are:</p>
					<pre>Killer Points = Killer Points + (Victim Points / Killer Points)
					x Weapon Modifier x 5</pre>

					<pre>Victim Points = Victim Points - (Victim Points / Killer Points)
					x Weapon Modifier x 5</pre>
					<p>Plus, the following point bonuses are available for completing objectives in some games:</p>
					<a name="actions"></a>
					<?php
						$tblActions = new Table
						(
							array
							(
								new TableColumn
								(
									'gamename',
									'Game',
									'width=24&sort=no&align=center'
								),
								new TableColumn
								(
									'for_PlayerActions',
									'Player Action',
									'width=4&sort=no&align=center'
								),
								new TableColumn
								(
									'for_PlayerPlayerActions',
									'PlyrPlyr Action',
									'width=4&sort=no&align=center'
								),
								new TableColumn
								(
									'for_TeamActions',
									'Team Action',
									'width=4&sort=no&align=center'
								),
								new TableColumn
								(
									'for_WorldActions',
									'World Action',
									'width=4&sort=no&align=center'
								),
								new TableColumn
								(
									'description',
									'Action',
									'width=33&align=center'
								),
								new TableColumn
								(
									's_reward_player',
									'Player Reward',
									'width=12'
								),
								new TableColumn
								(
									's_reward_team',
									'Team Reward',
									'width=15'
								)
							),
							'id',
							'description',
							's_reward_player',
							false,
							9999,
							'act_page',
							'act_sort',
							'act_sortorder',
							'actions',
							'asc'
						);
						$result = $db->query
						("
							SELECT
								hlstats_Games.name AS gamename,
								hlstats_Actions.description,
								IF(SIGN(hlstats_Actions.reward_player) > 0, CONCAT('+', hlstats_Actions.reward_player), hlstats_Actions.reward_player) AS s_reward_player,
								IF(hlstats_Actions.team != '' AND hlstats_Actions.reward_team != 0,
								IF(SIGN(hlstats_Actions.reward_team) >= 0, CONCAT(hlstats_Teams.name, ' +', hlstats_Actions.reward_team), CONCAT(hlstats_Teams.name, ' ', hlstats_Actions.reward_team)), '') AS s_reward_team,
								IF(for_PlayerActions='1', 'Yes', 'No') AS for_PlayerActions,
								IF(for_PlayerPlayerActions='1', 'Yes', 'No') AS for_PlayerPlayerActions,
								IF(for_TeamActions='1', 'Yes', 'No') AS for_TeamActions,
								IF(for_WorldActions='1', 'Yes', 'No') AS for_WorldActions
							FROM
								hlstats_Actions
							INNER JOIN
								hlstats_Games
							ON
								hlstats_Games.code = hlstats_Actions.game
								AND hlstats_Games.hidden = '0'
							LEFT JOIN
								hlstats_Teams
							ON
								hlstats_Teams.code = hlstats_Actions.team
								AND hlstats_Teams.game = hlstats_Actions.game
							ORDER BY
								hlstats_Actions.game ASC,
								$tblActions->sort $tblActions->sortorder,
								$tblActions->sort2 $tblActions->sortorder
						");
						$numitems = $db->num_rows($result);
						$tblActions->draw($result, $numitems, 90, 'center');
					?>
					<p><strong>Note:</strong> The player who triggers an action may receive both the player reward and the team reward.</p>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card mb-4">
				<div class="card-header pb-0">
					<h6><a name="weaponmods">3. What are all the weapon points modifiers?</a></h6>
				</div>
				<div class="ms-4 mb-3">
					<p>Weapon points modifiers are used to determine how many points you should gain or lose when you make a kill or are killed by another player. Higher modifiers indicate that more points will be gained when killing with that weapon (and similarly, more points will be lost when being killed <em>by</em> that weapon). Modifiers generally range from 0.00 to 2.00.</p>
					<a name="weapons"></a>
					<?php
					$tblWeapons = new Table
					(
						array
						(
							new TableColumn
							(
								'gamename',
								'Game',
								'width=24&sort=no&align=center'
							),
							new TableColumn
							(
								'code',
								'Weapon',
								'width=14&align=center'
							),
							new TableColumn
							(
								'name',
								'Name',
								'width=50&align=center'
							),
							new TableColumn
							(
								'modifier',
								'Points Modifier',
								'width=12&align=center'
							)
						),
						'weaponId',
						'modifier',
						'code',
						false,
						9999,
						'weap_page',
						'weap_sort',
						'weap_sortorder',
						'weapons',
						'desc'
					);
					$result = $db->query
					("
						SELECT
							hlstats_Games.name AS gamename,
							hlstats_Weapons.code,
							hlstats_Weapons.name,
							hlstats_Weapons.modifier
						FROM
							hlstats_Weapons
						INNER JOIN
							hlstats_Games
						ON
							hlstats_Games.code = hlstats_Weapons.game
							AND hlstats_Games.hidden = '0'
						ORDER BY
							hlstats_Weapons.game ASC,
							$tblWeapons->sort $tblWeapons->sortorder,
							$tblWeapons->sort2 $tblWeapons->sortorder
					");
					$numitems = $db->num_rows($result);
					$tblWeapons->draw($result, $numitems, 90, "center");
					?>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card mb-4">
				<div class="card-header pb-0">
					<h6><a name="set">4. How can I set my real name, e-mail address, and homepage?</a></h6>
				</div>
				<div class="ms-4">
					<p>Player profile options can be configured by saying the appropriate <strong>HLX_SET</strong> command while you are playing on a participating game server. To say commands, push your chat key and type the command text.</p>
					<p>Syntax: say <strong>/hlx_set option value</strong>.</p>
					Acceptable "options" are:
					<ul>
						<li><strong>realname</strong><br />
							Sets your Real Name as shown in your profile.<br />
							Example: &nbsp; <strong>/hlx_set realname Joe Bloggs</strong><br /><br />
						</li>
					
						<li><strong>email</strong><br />
							Sets your E-mail Address as shown in your profile.<br />
							Example: &nbsp; <strong>/hlx_set email joe@joebloggs.com</strong><br /><br />
						</li>
						
						<li><strong>homepage</strong><br />
							Sets your Home Page as shown in your profile.<br />
							Example: &nbsp; <strong>/hlx_set homepage http://www.joebloggs.com/</strong><br /><br />
						</li>
					</ul>
				</p><strong>Note:</strong> These are not standard Half-Life console commands. If you type them in the console, Half-Life will give you an error.<br /><br />For a full list of supported ingame commands, type the word help into ingame chat.</p>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card mb-4">
				<div class="card-header pb-0">
					<h6><a name="hideranking">5. My rank is embarrassing. How can I opt out?</a></h6>
				</div>
				<div class="ms-4">
					<p>Say <b>/hlx_hideranking</b> while playing on a participating game server. This will toggle you between being visible on the Player Rankings and being invisible.</p>
					<p><strong>Note:</strong> You will still be tracked and you can still view your Player Details page. Use the <a href="<?php echo $g_options['scripturl']; ?>?mode=search">Search</a> page to find yourself.</p>
				</div>
			</div>
		</div>
	</div>
</div>
