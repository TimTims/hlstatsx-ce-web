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
	
	if ( count($_POST) > 0 ) {
		$db->query("SELECT * FROM `hlstats_Servers` WHERE `address` = '" . $db->escape(clean_data($_POST['server_address'])) . "' AND `port` = '" . $db->escape(clean_data($_POST['server_port'])) . "'");
		
		if ( $row = $db->fetch_array() )
			message("warning", "Server [" . $row['name'] . "] already exists");
		else
		{
			$db->query("SELECT `realgame` FROM `hlstats_Games` WHERE `code` = '" . $db->escape($selGame) . "'");
			if ( list($game) = $db->fetch_row() )
			{
				$script_path = (isset($_SERVER['SSL']) || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")) ? 'https://' : 'http://';
				$script_path .= $_SERVER['HTTP_HOST'];
				$script_path .= str_replace("\\","/",dirname($_SERVER["PHP_SELF"]));
				$db->query(sprintf("INSERT INTO `hlstats_Servers` (`address`, `port`, `name`, `game`, `publicaddress`, `rcon_password`) VALUES ('%s', '%d', '%s', '%s', '%s', '%s')",
					$db->escape(clean_data($_POST['server_address'])),
					$db->escape(clean_data($_POST['server_port'])),
					$db->escape(clean_data($_POST['server_name'])),
					$db->escape($selGame),
					$db->escape(clean_data($_POST['public_address'])),
					$db->escape(mystripslashes($_POST['server_rcon']))
				));
				$insert_id = $db->insert_id();
				$db->query("INSERT INTO `hlstats_Servers_Config` (`serverId`, `parameter`, `value`)
						SELECT '" . $insert_id . "', `parameter`, `value`
						FROM `hlstats_Mods_Defaults` WHERE `code` = '" . $db->escape(mystripslashes($_POST['game_mod'])) . "';");
				$db->query("INSERT INTO `hlstats_Servers_Config` (`serverId`, `parameter`, `value`) VALUES
						('" . $insert_id . "', 'Mod', '" . $db->escape(mystripslashes($_POST['game_mod'])) . "');");
				$db->query("INSERT INTO `hlstats_Servers_Config` (`serverId`, `parameter`, `value`)
						SELECT '" . $insert_id . "', `parameter`, `value`
						FROM `hlstats_Games_Defaults` WHERE `code` = '" . $db->escape($game) . "'
						ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);");
				$db->query("UPDATE hlstats_Servers_Config
							SET `value` = '" . $db->escape($script_path) . "'
							WHERE serverId = '" . $insert_id . "' AND `parameter` = 'HLStatsURL'");
				$_POST = array();
				
				// psychonic - worst. redirect. ever.
				//   but we can't just use header() since admin.php already started part of the page and hacking it in before would be even messier
				echo "<script type=\"text/javascript\"> window.location.href=\"".$g_options['scripturl']."?mode=admin&game=$selGame&task=serversettings&key=$insert_id#startsettings\"; </script>";
				exit;
			}
		}
	}
	
	function clean_data($data)
	{
		return trim(htmlspecialchars(mystripslashes($data)));
	}

    $server_ip = (!empty($_POST['server_address'])) ? clean_data($_POST['server_address']) : "";
    $server_port = (!empty($_POST['server_port'])) ? clean_data($_POST['server_port']) : "" ;
    $server_name = (!empty($_POST['server_name'])) ? clean_data($_POST['server_name']) : "";
    $server_rcon = (!empty($_POST['server_rcon'])) ? clean_data($_POST['server_rcon']) : "";
    $server_public_address = (!empty($_POST['public_address'])) ? clean_data($_POST['public_address']) : "";
?>
<div class="row">
	<div class="col-12">
		<div class="card mb-4">
			<div class="card-header pb-0">
				<h6>New Server</h6>
			</div>
			<p class="ms-4">Enter the address of a server that you want to accept data from.</p>
			<p class="ms-4">The "Public Address" should be the address you want shown to users. If left blank, it will be generated from the IP Address and Port. If you are using any kind of log relaying utility (i.e. hlstats.pl will not be receiving data directly from the game servers), you will want to set the IP Address and Port to the address of the log relay program, and set the Public Address to the real address of the game server. You will need a separate log relay for each game server. You can specify a hostname (or anything at all) in the Public Address.</p>
			<form method="post" name="<?php echo $code; ?>form" action="<?php echo $g_options['scripturl']; ?>?mode=admin&amp;game=<?php echo $gamecode; ?>&task=<?php echo $code; ?>#<?php echo $code; ?>">
				<div class="table-responsive ms-4">
					<table class="table">

						<tr valign="top" class="table_border">
							<td>
								<script type="text/javascript">
								function checkMod() {
									if (!document.newserverform.server_address.value.match(/^\b(?:[0-9]{1,3}\.){3}[0-9]{1,3}\b$/)) {
										alert('Server address must be a valid IP address');
										return false;
									}
									if (document.newserverform.game_mod.value == 'PLEASESELECT') {
										alert('You must make a selection for Admin Mod');
										return false;
									}
									document.newserverform.submit();
								}
								</script>
								<table class="table">
									<tr>
										<td>Server IP Address</td>
										<td><input class="form-control" type="text" name="server_address" maxlength="15" size="15" value="<?=$server_ip;?>" /></td>
									</tr>
									<tr>
										<td>Server Port</td>
										<td><input class="form-control" type="text" name="server_port" maxlength="5" size="5" value="<?=$server_port;?>" /></td>
									</tr>
									<tr>
										<td>Server Name</td>
										<td><input class="form-control" type="text" name="server_name" maxlength="255" size="35" value="<?=$server_name;?>" /></td>
									</tr>
									<tr>
										<td>Rcon Password</td>
										<td><input class="form-control" type="text" name="server_rcon" maxlength="128" size="15" value="<?=$server_rcon;?>" /></td>
									</tr>
									<tr>
										<td>Public Address</td>
										<td><input class="form-control" type="text" name="public_address" maxlength="128" size="15" value="<?=$server_public_address;?>" /></td>
									</tr>
									<tr>
										<td>Admin Mod</td>
										<td>
											<select class="form-select" name="game_mod">
											<option value="PLEASESELECT">PLEASE SELECT</option>
											<?php
												$db->query("SELECT code, name FROM `hlstats_Mods_Supported`");

												while ($row = $db->fetch_array()) {
													echo '<option value="' . $row['code'] . '">' . $row['name'] . '</option>';
												}
											?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
				<div class="text-center"><input type="submit" value="Add Server" class="col-4 btn btn-primary" onclick="checkMod();return false;"></div>
			</form>
		</div>
	</div>
</div>
