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

	if ($auth->userdata['acclevel'] < 80) {
        die ('Access denied!');
	}

?>
<div class="col-12">
	<div class="card mb-4">
		<div class="card-header pb-0">
			<h6>General Settings</h6>
		</div>	
		<div class="col-6 alert alert-danger text-center mx-auto" role="alert">
			<b>Options with an asterisk (*) beside them require a restart of the perl daemon to fully take effect.</b>
		</div>
<?php

	class OptionGroup
	{
		var $title = '';
		var $options = array();

		function __construct($title)
		{
			$this->title = $title;
		}

		function draw ()
		{
			global $g_options;
?>
	<div class="table-responsive ms-4">
	<table class="table">
		<?php
			foreach ($this->options as $opt)
			{
				$opt->draw();
			}
?>
	</table>
	</div>
<?php
		}
		
		function update ()
		{
			global $db;
			
			foreach ($this->options as $opt)
			{
				if (($this->title == 'Fonts') || ($this->title == 'General')) {
					$optval = $_POST[$opt->name];
					$search_pattern  = array('/script/i', '/;/', '/%/');
					$replace_pattern = array('', '', '');
					$optval = preg_replace($search_pattern, $replace_pattern, $optval);
				} else {
					$optval = valid_request($_POST[$opt->name], false);
 	 			}
				
				$result = $db->query("
					SELECT
						value
					FROM
						hlstats_Options
					WHERE
						keyname='$opt->name'
				");
				
				if ($db->num_rows($result) == 1)
				{
					$result = $db->query("
						UPDATE
							hlstats_Options
						SET
							value='$optval'
						WHERE
							keyname='$opt->name'
					");
				}
				else
				{
					$result = $db->query("
						INSERT INTO
							hlstats_Options
							(
								keyname,
								value
							)
						VALUES
						(
							'$opt->name',
							'$optval'
						)
					");
				}
			}
		}
	}

	class Option
	{
		var $name;
		var $title;
		var $description;
		var $type;

		function __construct($name, $title, $description, $type)
		{
			$this->name = $name;
			$this->title = $title;
			$this->description = $description;
			$this->type = $type;
		}

		function draw()
		{
			global $g_options, $optiondata, $db;
?>
				<tr>
					<td><b><?php echo $this->title . ":"; ?></b><p><?php echo $this->description; ?></p></td>
					<td><?php
						switch ($this->type)
						{
							case 'textarea':
								echo "<textarea name=\"$this->name\" cols=\"35\" rows=\"4\" wrap=\"virtual\" class=\"form-control\">";
								echo html_entity_decode($optiondata[$this->name]);
								echo '</textarea>';
								break;
							
							case 'select':
								echo "<select name=\"$this->name\" style=\"width: 226px\" class=\"form-select\">";
								$result = $db->query("SELECT `value`,`text` FROM hlstats_Options_Choices WHERE keyname='$this->name' ORDER BY isDefault desc");
								while ($rowdata = $db->fetch_array($result)) {
									if ($rowdata['value'] == $optiondata[$this->name]) {
										echo '<option value="'.$rowdata['value'].'" selected="selected">'.$rowdata['text'];
									} else {
										echo '<option value="'.$rowdata['value'].'">'.$rowdata['text'];
									}
								}
								echo '</select>';
								break;
							
							case 'select-disabled':
								echo "<select name=\"$this->name\" style=\"width: 226px\" class=\"form-select\" disabled>";
								$result = $db->query("SELECT `value`,`text` FROM hlstats_Options_Choices WHERE keyname='$this->name' ORDER BY isDefault desc");
								while ($rowdata = $db->fetch_array($result)) {
									if ($rowdata['value'] == $optiondata[$this->name]) {
										echo '<option value="'.$rowdata['value'].'" selected="selected">'.$rowdata['text'];
									} else {
										echo '<option value="'.$rowdata['value'].'">'.$rowdata['text'];
									}
								}
								echo '</select>';
								break;
								
							default:
								echo "<input type=\"text\" name=\"$this->name\" size=\"35\" value=\"";
								echo html_entity_decode($optiondata[$this->name]);
								echo '" class="textbox form-control" maxlength="255" />';
						} ?>
					</td>
				</tr>
<?php
		}
	}

	$optiongroups = array();

	$optiongroups[0] = new OptionGroup('Site Settings');
	$optiongroups[0]->options[] = new Option('sitename', 'Site Name', 'Name of your site', 'text');
	$optiongroups[0]->options[] = new Option('siteurl', 'Site URL', 'URL of the site (make sure to include http:// or https://)', 'text');
	$optiongroups[0]->options[] = new Option('contact', 'Contact Email', 'Email address for web admin', 'text');
	$optiongroups[0]->options[] = new Option('bannerdisplay', 'Show Banner', 'Show banner site wide', 'select');
	$optiongroups[0]->options[] = new Option('bannerfile', 'Banner file name', '(in hlstatsimg/) or full banner URL', 'text');
	$optiongroups[0]->options[] = new Option('playerinfo_tabs', 'Use tabs in playerinfo', 'To show/hide sections current page or just show all at once', 'select');
	$optiongroups[0]->options[] = new Option('slider', 'Enable AJAX gliding server list', 'Accordion effect on homepage of each game (only affects games with more than one server)', 'select');
	$optiongroups[0]->options[] = new Option('nav_globalchat', 'Show Chat nav-link', 'Enables/disables chat link in menu', 'select');
	$optiongroups[0]->options[] = new Option('nav_cheaters', 'Show Banned Players nav-link', 'Shows banned players in server menu (obsolete)', 'select');
	$optiongroups[0]->options[] = new Option('sourcebans_address', 'SourceBans URL', 'Full path to your SourceBans web site, if you have one. Ex: http://www.yoursite.com/sourcebans/', 'text');
	$optiongroups[0]->options[] = new Option('forum_address', 'Forum URL', 'Full path to your forum/message board, if you have one. Ex: http://www.yoursite.com/forum/', 'text');
	$optiongroups[0]->options[] = new Option('show_weapon_target_flash', 'Show hitbox flash animation', 'Instead of plain html table for games with accuracy tracking (on supported games)', 'select');
	$optiongroups[0]->options[] = new Option('show_server_load_image', 'Load summaries', 'Shows load summaries from all monitored servers', 'select');
	$optiongroups[0]->options[] = new Option('showqueries', 'Footer message', 'Show "Executed X queries, generated this page in Y Seconds." message in footer?', 'select');
	$optiongroups[0]->options[] = new Option('sigbackground', 'Default background for forum signature', 'Numbers 1-11 or random. Look in sig folder to see background choices', 'text');
	$optiongroups[0]->options[] = new Option('modrewrite', 'Forum compatibility','Use modrewrite to make forum signature image compatible with more forum types.</br>To utilize this, you <strong>must</strong> have modrewrite enabled on your webserver.', 'select');
	
	$optiongroups[1] = new OptionGroup('GeoIP data & Google Map settings <strong>Obsolete</strong>');
	$optiongroups[1]->options[] = new Option('countrydata', 'Country Data','Shows features requiring GeoIP data', 'select-disabled');
	$optiongroups[1]->options[] = new Option('show_google_map', 'Show worldmap','Shows Google worldmap on main page', 'select-disabled');
	$optiongroups[1]->options[] = new Option('google_map_region', 'Show map region','Shows Google Maps Region', 'select-disabled');
	$optiongroups[1]->options[] = new Option('google_map_type', 'Google Maps Type', 'Change Google Map type', 'select-disabled');
	$optiongroups[1]->options[] = new Option('UseGeoIPBinary', 'GeoCityLite loading method *','Choose whether to use GeoCityLite data loaded into mysql database or from binary file.</br>If binary, GeoLiteCity.dat goes in perl/GeoLiteCity and Geo::IP::PurePerl module is required.', 'select-disabled');

	$optiongroups[2] = new OptionGroup('Awards settings');
	$optiongroups[2]->options[] = new Option('gamehome_show_awards', 'Show awards','Show daily award winners on Game Frontpage', 'select');
	$optiongroups[2]->options[] = new Option('awarddailycols', 'Daily Award Columns','Daily Awards: columns count', 'text');
	$optiongroups[2]->options[] = new Option('awardglobalcols', 'Global Award Columns','Global Awards: columns count', 'text');
	$optiongroups[2]->options[] = new Option('awardrankscols', 'Player Rank Columns','Player Ranks: columns count', 'text');
	$optiongroups[2]->options[] = new Option('awardribbonscols', 'Ribbon Columns','Ribbons: columns count', 'text');

	// $optiongroups[3] = new OptionGroup('Hit counter settings');
	// $optiongroups[3]->options[] = new Option('counter_visit_timeout', 'Visit cookie timeout in minutes', 'text');
	// $optiongroups[3]->options[] = new Option('counter_visits', 'Current Visits', 'text');
	// $optiongroups[3]->options[] = new Option('counter_hits', 'Current Page Hits', 'text');
	
	// $optiongroups[20] = new OptionGroup('Paths');
	// $optiongroups[20]->options[] = new Option('map_dlurl', 'Map Download URL<br /><span class="fSmall">(%MAP% = map, %GAME% = gamecode)</span>. Leave blank to suppress download link.', 'text');

	$optiongroups[30] = new OptionGroup('Visual style settings');
	$optiongroups[30]->options[] = new Option('graphbg_load', 'Server Load Background Colour', 'Server Load graph: background color hex# (RRGGBB)', 'text');
	$optiongroups[30]->options[] = new Option('graphtxt_load', 'Server Load Text Colour', 'Server Load graph: text color# (RRGGBB)', 'text');
	$optiongroups[30]->options[] = new Option('graphbg_trend', 'Player Trend Background Colour', 'Player Trend graph: background color hex# (RRGGBB)', 'text');
	$optiongroups[30]->options[] = new Option('graphtxt_trend', 'Player Trend Text Colour', 'Player Trend graph: text color hex# (RRGGBB)', 'text');
	$optiongroups[30]->options[] = new Option('display_gamelist', 'Enable Gamelist icons', 'Enables or Disables the game icons near the top-right of all pages.', 'select');

	
	$optiongroups[35] = new OptionGroup('Ranking settings');
	$optiongroups[35]->options[] = new Option('rankingtype', 'Ranking Type *', 'Ranking type', 'select');
	$optiongroups[35]->options[] = new Option('MinActivity', 'Minimum Activity *', 'HLstatsX will automatically hide players which have no event more days than this value.', 'text');
	
	$optiongroups[40] = new OptionGroup('Daemon Settings');
	$optiongroups[40]->options[] = new Option('Mode', 'Player Tracking Mode *', '<ul><LI><b>Steam ID</b>     - Recommended for public Internet server use. Players will be tracked by Steam ID.<LI><b>Player Name</b>  - Useful for shared-PC environments, such as Internet cafes, etc. Players will be tracked by nickname. <LI><b>IP Address</b>        - Useful for LAN servers where players do not have a real Steam ID. Players will be tracked by IP Address. </UL>', 'select');
	// $optiongroups[40]->options[] = new Option('AllowOnlyConfigServers', '*Allow only servers set up in admin panel to be tracked. Other servers will NOT automatically added and tracked! This is a big security thing', 'select');
	$optiongroups[40]->options[] = new Option('DeleteDays', 'Delete Days *', 'HLstatsX will automatically delete history events from the events tables when they are over this many days old.</br>This is important for performance reasons.</br>Set lower if you are logging a large number of game servers or find the load on the MySQL server is too high', 'text');
	$optiongroups[40]->options[] = new Option('DNSResolveIP', 'DNS Resolve *', 'Resolve player IP addresses to hostnames. Requires a working DNS setup (on the box running hlstats.pl)', 'select');
	$optiongroups[40]->options[] = new Option('DNSTimeout', 'DNS Timeout *', 'Time, in seconds, to wait for DNS queries to complete before cancelling DNS resolves.</br>You may need to increase this if on a slow connection or if you find a lot of IPs are not being resolved</br>However, hlstats.pl cannot be parsing log data while waiting for an IP to resolve', 'text');
	$optiongroups[40]->options[] = new Option('MailTo', 'Mail To *', 'E-mail address to mail database errors to', 'text');
	$optiongroups[40]->options[] = new Option('MailPath', 'Mail Path *', 'Path to the mail program -- usually /usr/sbin/sendmail on webhosts', 'text');
	$optiongroups[40]->options[] = new Option('Rcon', 'Server RCON *', 'Allow HLstatsX to send Rcon commands to the game servers', 'select');
	$optiongroups[40]->options[] = new Option('RconIgnoreSelf', 'Ignore Self RCON\'s', 'Ignore (do not log) Rcon commands originating from the same IP as the server being rcon-ed</br>(useful if you run any kind of monitoring script which polls the server regularly by rcon)', 'select');
	$optiongroups[40]->options[] = new Option('RconRecord', 'Record RCON Commands *', 'Record Rcon commands to the Admin event table.</br>This can be useful to see what your admins are doing.</br>If you run programs like PB it can also fill your database up with a lot of useless junk', 'select');
	$optiongroups[40]->options[] = new Option('UseTimestamp', 'Use Timestamps *', 'If no (default), use the current time on the database server for the timestamp when recording events.</br>If yes, use the timestamp provided on the log data. Unless you are processing old log files on STDIN</br>or your game server is in a different timezone than webhost, you probably want to set this to no', 'select');
	$optiongroups[40]->options[] = new Option('TrackStatsTrend', 'Track daily stats for trends *', 'Save how many players, kills etc, are in the database each day and give access to graphical statistics', 'select');
	$optiongroups[40]->options[] = new Option('GlobalBanning', 'Global Bans (Obsolete)', 'Make player bans available on all participating servers.</br>Players who were banned permanently are automatic hidden from rankings', 'select-disabled');
	$optiongroups[40]->options[] = new Option('LogChat', 'Log Chat *', 'Log player chat to database', 'select');
	$optiongroups[40]->options[] = new Option('LogChatAdmins', 'Log Admin Chat *', 'Log admin chat to database', 'select');
	$optiongroups[40]->options[] = new Option('GlobalChat', 'Global Chat *', 'Broadcast chat messages through all particapting servers. To all, none, or admins only', 'select');
	
	$optiongroups[50] = new OptionGroup('Point calculation settings');
	$optiongroups[50]->options[] = new Option('SkillMaxChange', 'Max. Skill Points per Frag *', 'Maximum number of skill points a player will gain from each frag. Default 25', 'text');
	$optiongroups[50]->options[] = new Option('SkillMinChange', 'Min. Skill Points per Frag *', 'Minimum number of skill points a player will gain from each frag. Default 2', 'text');
	$optiongroups[50]->options[] = new Option('PlayerMinKills', 'Min. Kills *', 'Number of kills a player must have before receiving regular points.</br>Before this threshold is reached, the killer and victim will only gain/lose the minimum point value.', 'text');
	$optiongroups[50]->options[] = new Option('SkillRatioCap', 'Skill Ratio Cap *', 'Cap killer\'s gained skill with ratio using *XYZ*SaYnt\'s method.</br>Designed such that an excellent player will have to get about a 2:1 ratio against noobs to hold steady in points.', 'select');

	$optiongroups[60] = new OptionGroup('Proxy Settings');
	$optiongroups[60]->options[] = new Option('Proxy_Key', 'Proxy Key *', 'Key to use when sending remote commands to Daemon, empty for disable', 'text');
	$optiongroups[60]->options[] = new Option('Proxy_Daemons', 'Proxy Daemon *', 'List of daemons to send PROXY events from (used by proxy-daemon.pl), use "," as delimiter, eg &lt;ip&gt;:&lt;port&gt;,&lt;ip&gt;:&lt;port&gt;,... ', 'text');
	
	if (!empty($_POST))
	{
			foreach ($optiongroups as $og)
			{
				$og->update();
			}
			message('success', 'Options updated successfully.');
	}
	
	
	$result = $db->query("SELECT keyname, value FROM hlstats_Options");
	while ($rowdata = $db->fetch_row($result))
	{
		$optiondata[$rowdata[0]] = $rowdata[1];
	}
	
	foreach ($optiongroups as $og)
	{
		$og->draw();
	}
?>
	<tr>
		<td><input type="submit" value="Apply" class="col-4 btn btn-primary mx-auto" /></td>
	</tr>
</table>
</div>
</div>

