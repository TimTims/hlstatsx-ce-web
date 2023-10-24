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

	pageHeader(array('Admin'), array('Admin' => ''));
?>
<div class="container-fluid py-4">
	<div class="row justify-content-center">
		<div class="col-sm-6">
			<div class="card mb-4">
				<div class="card-header pb-0 text-center">
					<h6>Login Required</h6>
				</div>
				<form method="post" name="auth">
					<div class="form-group col-6 mx-auto">
						<label class="mb-2" for="authusername">Username</label>
						<input type="text" name="authusername" size="20" maxlength="16" value="<?php echo $this->username; ?>" class="form-control">
					</div>
					<div class="form-group col-6 mx-auto">
						<label class="mb-2" for="authpassword">Password</label>
						<input type="password" name="authpassword" size="20" maxlength="16" value="<?php echo $this->password; ?>" class="form-control">
					</div>
					<div class="form-group text-center">
						<span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="Remember to enable cookies">
							<button type="submit" id="authsubmit" class="btn btn-primary">Login</button>
						</span>
					</div>
				</form>		
				<?php
					if ($this->error)
					{
						echo '<div class="alert alert-danger col-8 mx-auto text-center" role="alert"><strong>'.$this->error.'</strong></div>';
					}
				?>		
			</div>
		</div>
	</div>