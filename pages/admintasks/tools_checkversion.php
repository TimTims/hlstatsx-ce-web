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

&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo IMAGE_PATH; ?>/downarrow.gif" ><strong>&nbsp;<?php echo $task->title; ?></strong>

<p></p>
<table class="data-table">
    <tr class="bg1">
            <td width="40%"><label for="main-version"><b>Version:</b></label><p>Checks the main version of your HLX:CE.</p></td>
            <td><p><?php echo getVersion('version'); ?></p></td>
    </tr>
    <tr class="bg2">
            <td><label for="developer"><b>Development Branch:</b></label><p>Checks if this version of your HLX:CE is a development branch version.</p></td>
            <td><p><?php if (getVersion('dev') == "Dev"){ echo "Yes"; } else { echo "No"; }?></p></td>
    </tr>
    <tr class="bg1">
            <td><label for="git-version"><b>Current Git Version:</b></label><p>Checks the Git Version of this instance of HLX:CE.</p></td>
            <td><p><?php echo getVersion('gitversion'); ?></p></td>
    </tr>
    <tr class="bg2">
            <td><label for="repo-version"><b>Git Repo Version:</b></label><p>Checks the Git version of the remote repository. This will check to see if your version of HLX:CE is up-to-date.</p></td>
            <td><p><?php echo getVersion('remoteversion'); ?></p></td>
    </tr>
</table>