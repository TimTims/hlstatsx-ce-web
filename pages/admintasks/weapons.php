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

	if ($auth->userdata["acclevel"] < 80) {
        die ("Access denied!");
	}

	// Check if the 'game' parameter is set in the URL
	if (isset($_GET['game'])) {
		// Assign the value of 'game' to the variable $gamemode
		$gamecode = $_GET['game'];

	} else {
		// 'game' parameter is not set in the URL
		$gamecode = null;
	}

	$edlist = new EditList("weaponId", "hlstats_Weapons", "gun", false);
	$edlist->columns[] = new EditListColumn("game", "Game", 0, true, "hidden", $gamecode);
	$edlist->columns[] = new EditListColumn("code", "Weapon Code", 15, true, "text", "", 32);
	$edlist->columns[] = new EditListColumn("name", "Weapon Name", 25, true, "text", "", 64);
	$edlist->columns[] = new EditListColumn("modifier", "Points Modifier", 10, true, "text", "1.00");
?>
<div class="col-12">
	<div class="card mb-4">
		<div class="card-header pb-0">
			<h6>Weapons</h6>
		</div>
		<?php
			if ($_POST)
			{
				if ($edlist->update())
					echo '<div class="alert alert-success col-8 text-center mx-auto" role="alert"><strong>Operation Completed Successfully!</strong></div>';
				else
					echo '<div class="alert alert-danger col-8 text-center mx-auto" role="alert"><strong>' . $edlist->error() . '</strong></div>';
			}
		?>
		<p class="ms-4 me-4">You can give each weapon a <i>points modifier</i>, a multiplier which determines how many points will be gained or lost for killing with or being killed by that weapon. (Refer to <a href="<?php echo $g_options["scripturl"]; ?>?mode=help#points">Help</a> for a full description of how points ratings are 
		calculated.) The baseline points modifier for weapons is 1.00. A points modifier of 0.00 will cause kills with that weapon to have no effect on players' points.</p>
		<form method="post" name="<?php echo $code; ?>form" action="<?php echo $g_options['scripturl']; ?>?mode=admin&amp;game=<?php echo $gamecode; ?>&task=<?php echo $code; ?>#<?php echo $code; ?>">	

		<?php
	
			$result = $db->query("
				SELECT
					weaponId,
					code,
					name,
					modifier
				FROM
					hlstats_Weapons
				WHERE
					game='$gamecode'
				ORDER BY
					code ASC
			");
			
			$edlist->draw($result);
		?>

			<div class="text-center"><input type="submit" value="Apply" class="col-4 btn btn-primary mt-2"></div>
		</form>
	</div>
</div>

