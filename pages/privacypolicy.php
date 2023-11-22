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
		array ('Privacy Policy'),
		array ('Privacy' => '')
	);
?>


<div class="container-fluid py-4">
    <div class="row">
    	<div class="col-12">
        	<div class="card mb-4">
            	<div class="card-header pb-0">
              		<h6>Privacy Policy & Cookie Policy for <?php echo $g_options['sitename']; ?></h6>
            	</div>
				<p class="ms-4"><b>Privacy Policy</b></p>

				<p class="ms-4"><b>1. Information Collection</b></p>

				<p class="ms-4"><?php echo $g_options['sitename'] ?> collects the following information from game servers that have integrated the plugin:</p>

				<ul class="ms-4">
					<li>Player usernames</li>
					<li>In-game activities and statistics</li>
					<li>IP addresses</li>
					<li>Device information</li>
				</ul>

				<p class="ms-4">Users also have the option to voluntarily provide their:</p>

				<ul class="ms-4">
					<li>Name</li>
					<li>Email address</li>
				</ul>
				
				<p class="ms-4">This additional information is entirely optional and not required for the basic functionality of the software.</p>

				<p class="ms-4"><b>2. Use of Information</b></p>

				<p class="ms-4">The collected data is used for the following purposes:</p>

				<ul class="ms-4">
					<li>Compiling and generating game statistics</li>
					<li>Improving the gaming experience for users</li>
					<li>Providing personalized content and features</li>
				</ul>
				
				<p class="ms-4">User-provided names and email addresses, if provided voluntarily, may be used for:</p>

				<ul class="ms-4">
					<li>Personalized communication</li>
					<li>Notification of updates or relevant information</li>
				</ul>

				<p class="ms-4"><b>3. Protection of Information</b></p>

				<p class="ms-4">We take the security of your data seriously. The following measures are implemented:</p>

				<ul class="ms-4">
					<li>All sensitive data is securely stored in a database.</li>
					<li>Administrator user passwords are encrypted to protect against unauthorized access.</li>
				</ul>
				
				<p class="ms-4"><b>4. Cookies</b></p>

				<p class="ms-4"><?php echo $g_options['sitename'] ?> uses cookies for the following purposes:</p>
				<ul class="ms-4">
					<li>Essential functionality, including session management.</li>
					<li>Personalization to enhance user experience.</li>
				</ul>

				<p class="ms-4"><b>5. Disclosure to Third Parties</b></p>

				<p class="ms-4">We do not share personally identifiable information with third parties. The compiled statistics may be shared publicly but will not include individual player identifiers.</p>

				<p class="ms-4"><b>6. Links to Third-Party Websites</b></p>

				<p class="ms-4"><?php echo $g_options['sitename'] ?> may provide links to third-party websites for additional resources. However, this privacy policy only applies to data collected by [Your Open Source Software], and we are not responsible for the privacy practices of external sites.</p>

				<p class="ms-4"><b>7. Your Consent</b></p>

				<p class="ms-4">By using <?php echo $g_options['sitename'] ?>, you consent to the collection and use of the information as outlined in this privacy policy.</p>

				<p class="ms-4"><b>8. Changes to our Privacy Policy</b></p>

				<p class="ms-4">Any changes to this privacy policy will be communicated through the <?php echo $g_options['sitename'] ?> website.</p>

				<hr class="horizontal dark my-sm-4">

				<p class="ms-4"><b>Cookie Policy</b></p>
				
				<p class="ms-4"><b>1. What are Cookies?</b></p>

				<p class="ms-4">Cookies are small text files that are stored on your computer or mobile device when you visit a website. They are widely used to make websites work or improve the efficiency of a website, as well as to provide reporting information.</p>

				<p class="ms-4"><b>2. How We Use Cookies</b></p>

				<p class="ms-4"><?php echo $g_options['sitename'] ?> uses the following types of cookies:</p>
				<ul class="ms-4">
					<li><b>Essential Cookies:</b> These cookies are necessary for the basic functionality of our website. They enable users to navigate the site and use its features.</li>
					<li><b>Personalization Cookies:</b> These cookies allow us to remember choices you make and personalize your experience. For example, they may remember your username or language preferences.</li>
				</ul>
				<p class="ms-4"><b>3. Managing Cookies</b></p>

				<p class="ms-4">You can manage or disable cookies through your browser settings. However, please note that disabling certain cookies may affect the functionality of the site.</p>
				<ul class="ms-4">
					<li><b>Browser Settings:</b> You can usually find cookie settings in the "Options" or "Preferences" menu of your browser.</li>
					<li><b>Third-Party Tools:</b> There are also third-party tools available online that allow you to analyze and manage cookies.</li>
				</ul>
				<p class="ms-4"><b>4. Changes to Our Cookie Policy</b></p>

				<p class="ms-4">Any changes to this cookie policy will be reflected on this page.</p>

				<p class="ms-4"><b>5. Contact Information</b></p>

				<p class="ms-4">If you have any questions about our cookie policy, please contact us at <?php echo $g_options['contact'] ?>.</p>
			</div>	
		</div>
	</div>
</div>
