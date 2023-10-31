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
    
?>

<div class="col-12">
	<div class="card mb-4">
		<div class="card-header pb-0">
			<h6><?php echo $task->title; ?></h6>
		</div>
		<p class="ms-5">You can enter a player or clan ID number directly, or you can search for a player or clan.</p>
		<div class="container-fluid py-4">
			<div class="row">
				<div class="col-12">
					<div class="card mb-4">
						<div class="card-header pb-0">
							<h6>Jump Direct</h6>
						</div>
						<form method="GET" action="<?php echo $g_options["scripturl"]; ?>">
							<input type="hidden" name="mode" value="admin">
							<div class="table-responsive ms-4">
								<table class="table mb-0" style="width: 100%;">
									<tr>
										<td style="width: 5%;">Type:</td>
										<td style="width: 5%;">
										<?php
											echo getSelect("task",
											array(
												"tools_editdetails_player"=>"Player",
												"tools_editdetails_clan"=>"Clan"
												)
											);
										?>
										</td>
									</tr>
									<tr>
										<td style="width: 5%;">ID Number:</td>
										<td style="width: 5%;"><input type="text" name="id" size=15 maxlength=12 class="form-control"></td>
									</tr>
									<tr>
										<td style="width: 5%;"></td>
										<td style="width: 5%;"><input type="submit" value=" Edit &gt;&gt; " class="btn btn-primary"></td>
									</tr>				
								</table>
							</div>
						</form>							
					</div>
				</div>
			</div>
		</div>
<?php
	require(PAGE_PATH . "/search-class.php");
	
	$sr_query = $_GET["q"];
    $search_pattern  = array("/script/i", "/;/", "/%/");
    $replace_pattern = array("", "", "");
    $sr_query = preg_replace($search_pattern, $replace_pattern, $sr_query);

	$sr_type = valid_request($_GET["st"], false) or "player";
	$sr_game = valid_request($_GET["game"], false);
	
	$search = new Search($sr_query, $sr_type, $sr_game);
	
	$search->drawForm(array(
		"mode"=>"admin",
		"task"=>$selTask
	));
	
	if ($sr_query)
	{
		$search->drawResults(
			"mode=admin&task=tools_editdetails_player&id=%k",
			"mode=admin&task=tools_editdetails_clan&id=%k"
		);
	}
?>
	</div>
</div>