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

	// Awards Info Page

	$db->query("SELECT name FROM hlstats_Games WHERE code='$game'");
	if ($db->num_rows() < 1) error("No such game '$game'.");

	list($gamename) = $db->fetch_row();
	$db->free_result();

	$type = valid_request($_GET['type'] ?? '');
	$tab = valid_request($_GET['tab'] ?? '');

	if ($type == 'ajax' )
	{
		$tabs = explode('|', preg_replace('[^a-z]', '', $tab));
		
		foreach ( $tabs as $tab )
		{
			if ( file_exists(PAGE_PATH . '/awards_' . $tab . '.php') )
			{
				@include(PAGE_PATH . '/awards_' . $tab . '.php');
			}
		}
		exit;
	}

	pageHeader(
		array($gamename, 'Awards Info'),
		array($gamename=>"%s?game=$game", 'Awards Info'=>'')
	);
?>

<?php if ($g_options['playerinfo_tabs']=='1') { ?>
<div class="container-fluid py-4">
	<div class="row">
		<div class="col-12">
			<div class="card mb-4">
				<div class="card-header pb-0">
					<h6>Awards Info</h6>
					<div class="ms-auto" id="main">
						<div class="nav-wrapper mb-3">
							<ul class="nav nav-pills nav-fill p-1" role="tablist" id="tabs_submenu">
								<li class="nav-item">
									<a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true" id="tab_daily">
									<i class="bi bi-trophy-fill"></i>
									<span class="ms-2">Daily Awards</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false" id="tab_global">
									<i class="bi bi-globe2"></i>
									<span class="ms-2">Global Awards</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false" id="tab_ranks">
									<i class="bi bi-list-ol"></i>
									<span class="ms-2">Ranks</span>
									</a>
								</li>
								<li class="nav-item">
									<a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false" id="tab_ribbons">
									<i class="bi bi-award-fill"></i>
									<span class="ms-2">Ribbons</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card mb-4">
				<div class="mt-3" id="main_content"></div>
			</div>
		</div>
	</div>
<?php
if ($tab)
{
	$defaulttab = $tab;
}
else
{
	$defaulttab = 'daily';
}
} else {

	echo "\n<div id=\"daily\">\n";
	include PAGE_PATH.'/awards_daily.php';
	echo "\n</div>\n";

	echo "\n<div id=\"global\">\n";
	include PAGE_PATH.'/awards_global.php'; 
	echo "\n</div>\n";

	echo "\n<div id=\"ranks\">\n";
	include PAGE_PATH.'/awards_ranks.php';
	echo "\n</div>\n";

	echo "\n<div id=\"ribbons\">\n";
	include PAGE_PATH.'/awards_ribbons.php';
	echo "\n</div>\n";

}
?>
<script type="text/javascript">
	if (typeof main_content !== 'undefined') {
		document.addEventListener('DOMContentLoaded', function() {
			const myTabs = new Tabs('main_content', ['tab_daily', 'tab_global', 'tab_ranks', 'tab_ribbons'], {
				defaultTab: '<?php echo $defaulttab; ?>', // Default tab ID
				loadingImage: '<?php echo IMAGE_PATH; ?>/ajax.gif',
				game: '<?php echo $game; ?>',
				mode: 'awards'
			});

	// Programmatically click the default tab
			const defaultTabElement = document.getElementById(
			'<?php echo $defaulttab; ?>'); // Change this to match your default tab's ID
			if (defaultTabElement) {
				defaultTabElement.click();
			}
		});
	}
</script>