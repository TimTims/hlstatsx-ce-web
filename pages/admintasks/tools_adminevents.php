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

	$table = new Table(
		array(
			new TableColumn(
				"eventTime",
				"Date",
				"width=20&align=center"
			),
			new TableColumn(
				"eventType",
				"Type",
				"width=10&align=center"
			),
			new TableColumn(
				"eventDesc",
				"Description",
				"width=40&sort=no&append=.&embedlink=yes&align=center"
			),
			new TableColumn(
				"serverName",
				"Server",
				"width=20&align=center"
			),
			new TableColumn(
				"map",
				"Map",
				"width=10&align=center"
			)
		),
		"eventTime",
		"eventTime",
		"eventType",
		false,
		50,
		"page",
		"sort",
		"sortorder"
	);
	
	$db->query("DROP TABLE IF EXISTS hlstats_AdminEventHistory");

	$sql_create_temp_table = "
		CREATE TEMPORARY TABLE hlstats_AdminEventHistory
		(
			eventType VARCHAR(64) NOT NULL,
			eventTime DATETIME NOT NULL,
			eventDesc VARCHAR(255) NOT NULL,
			serverName VARCHAR(255) NOT NULL,
			map VARCHAR(64) NOT NULL
		) DEFAULT CHARSET=" . DB_CHARSET . " DEFAULT COLLATE=" . DB_COLLATE . ";
	";

	$db->query($sql_create_temp_table);

	function insertEvents ($table, $select)
	{
		global $db;
		
		$select = str_replace("<table>", "hlstats_Events_$table", $select);
		$db->query("
			INSERT INTO
				hlstats_AdminEventHistory
				(
					eventType,
					eventTime,
					eventDesc,
					serverName,
					map
				)
			$select
		");
	}
	
	insertEvents("Rcon", "
		SELECT
			CONCAT(<table>.type, ' Rcon'),
			<table>.eventTime,
			CONCAT('\"', command, '\"\nFrom: %A%".$g_options['scripturl']."?mode=search&q=', remoteIp, '&st=ip&game=%', remoteIp, '%/A%', IF(password<>'',CONCAT(', password: \"', password, '\"'),'')),
			IFNULL(hlstats_Servers.name, 'Unknown'),
			<table>.map
		FROM
			<table>
		LEFT JOIN hlstats_Servers ON
			hlstats_Servers.serverId = <table>.serverId
	");
	
	insertEvents("Admin", "
		SELECT
			<table>.type,
			<table>.eventTime,
			IF(playerName != '',
				CONCAT('\"', playerName, '\": ', message),
				message
			),
			IFNULL(hlstats_Servers.name, 'Unknown'),
			<table>.map
		FROM
			<table>
		LEFT JOIN hlstats_Servers ON
			hlstats_Servers.serverId = <table>.serverId
	");

	$where = "";
    $select_type = "";

	if (isset($_GET['type']) && $_GET['type'] != '') {
		$select_type = $_GET['type'];
		$where = "WHERE eventType='" . $db->escape($_GET['type']) . "'";
	}
	
	$result = $db->query("
		SELECT
			eventTime,
			eventType,
			eventDesc,
			serverName,
			map
		FROM
			hlstats_AdminEventHistory
		$where
		ORDER BY
			$table->sort $table->sortorder,
			$table->sort2 $table->sortorder
		LIMIT
			$table->startitem,$table->numperpage
	");
	
	$resultCount = $db->query("
		SELECT
			COUNT(*)
		FROM
			hlstats_AdminEventHistory
		$where
	");
	
	list($numitems) = $db->fetch_row($resultCount);
?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
				<div class="card-header pb-0">
              	    <h6><?php echo $task->title; ?> (Last <?php echo $g_options["DeleteDays"]; ?> Days)</h6>
                </div>
				<form method="get" action="<?php echo $g_options["scripturl"]; ?>">
					<input type="hidden" name="mode" value="admin" />
					<input type="hidden" name="task" value="<?php echo $code; ?>" />
					<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
					<input type="hidden" name="sortorder" value="<?php echo $sortorder; ?>" />

					<div class="ms-4">&#149; Show only events of type: <?php
						$resultTypes = $db->query("
							SELECT
								DISTINCT eventType
							FROM
								hlstats_AdminEventHistory
							ORDER BY
								eventType ASC
						");
						
						$types[""] = "(All)";
						
						while (list($k) = $db->fetch_row($resultTypes)) {
							$types[$k] = $k;
						}
						
						echo '<div class="col-md-2">'.getSelect("type", $types, $select_type).'</div>';
					?>
					<input type="submit" value="Filter" class="btn btn-primary" />
					</div>
				</form>
				<?php
					$table->draw($result, $numitems, 95, "center");
				?>
			</div>
		</div>
	</div>
