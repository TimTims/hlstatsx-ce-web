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

	$result = $db->query("
		SELECT
			rankName,
			minKills,
			rankId,
			count(playerId) AS obj_count
		FROM
			hlstats_Ranks
		INNER JOIN
			hlstats_Players
		ON (
           hlstats_Ranks.game=hlstats_Players.game
           )	
		WHERE
			kills>=minKills
			AND kills<=maxKills
			AND hlstats_Ranks.game='$game'
		GROUP BY
			rankName,
			minKills,
			rankId
	");
	
	while ($r = $db->fetch_array())
	{
		$ranks[$r['rankId']] = $r['obj_count'];
	}

	// select the available ranks
	$result = $db->query("
		SELECT
			rankName,
			minKills,
			maxKills,
			rankId,
			image
		FROM
			hlstats_Ranks
		WHERE
			hlstats_Ranks.game='$game'	
		ORDER BY
			minKills
	");
?>

<div class="card-header pb-0">
	<h6>Ranks</h6>
</div>
<div class="table-responsive">
	<table class="table table-hover">
		<?php
			// draw the rank info table (5 columns)
			$i = 0;

			$cols = 3;

			while ($r = $db->fetch_array())
			{
				if ($i == $cols)
				{
					echo "</tr>";
					$i = 0;
				}
				if ($i == 0)
				{
					echo "<tr>";
				}
		
				$image = getImage('/ranks/'.$r['image'].'_small');
				$link = '<a href="hlstats.php?mode=rankinfo&amp;rank='.$r['rankId']."&amp;game=$game\">";
				if ($image)
				{
					$imagestring = '<img src="'.$image['url'].'" alt="'.$r['image'].'" />';
				}
				else
				{
					$imagestring = 'Player List';
				}
				$achvd = '';
				if ($ranks[$r['rankId']] > 0)
				{
					$imagestring = "$link$imagestring</a>";
					$achvd = 'Achieved by '.$ranks[$r['rankId']].' Players';
				}    
		
				echo "<td class=\"align-top text-center\">"
					.'<strong>'.$r['rankName'].'</strong><br />'
					.'<span>('.$r['minKills'].'-'.$r['maxKills'].'&nbsp;kills)'.'<br />'
					."$achvd<br /></span>"
					.$imagestring.'
					</td>';
				$i++;
			}
			if ($i != 0)
			{
				for ($i = $i; $i < $cols; $i++)
				{
					echo '<td>&nbsp;</td>';
				}
				echo '</tr>';
			}
		?>
	</table>
</div>