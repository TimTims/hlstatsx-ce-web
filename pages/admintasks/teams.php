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

	$edlist = new EditList("teamId", "hlstats_Teams", "team", false);
	$edlist->columns[] = new EditListColumn("game", "Game", 0, true, "hidden", $gamecode);
	$edlist->columns[] = new EditListColumn("code", "Team Code", 20, true, "text", "", 32);
	$edlist->columns[] = new EditListColumn("name", "Team Name", 20, true, "text", "", 64);
	$edlist->columns[] = new EditListColumn("playerlist_color", "Color Code", 20, false, "text", "", 64);
	$edlist->columns[] = new EditListColumn("playerlist_bgcolor", "Bg Color Code", 20, false, "text", "", 64);
	$edlist->columns[] = new EditListColumn("hidden", "<center>Hide Team</center>", 0, false, "checkbox");

	if ($_POST) {
		if ($edlist->update())
			message("success", "Operation successful.");
		else
			message("warning", $edlist->error());
	}
?>
<div class="col-12">
	<div class="card mb-4">
		<div class="card-header pb-0">
			<h6>Teams</h6>
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
		<p class="ms-4">You can specify descriptive names for each game's team codes.</p>
		<form method="post" action="<?php echo $g_options['scripturl']; ?>?mode=admin&amp;task=<?php echo $code; ?>#<?php echo $code; ?>">	

			<?php $result = $db->query("
					SELECT
						teamId,
						code,
						name,
						hidden,
						playerlist_color,
						playerlist_bgcolor
					FROM
						hlstats_Teams
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
