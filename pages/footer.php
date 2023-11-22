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
?>
<!-- Footer -->
</div>
<footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                <!--© <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                 Renenable Later <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web. -->
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
					        <?php echo 'Generated in real-time by <a href="https://github.com/NomisCZ/hlstatsx-community-edition" target="_blank">HLstatsX Community Edition '.getVersion('version').' '.getVersion('dev').'</a> '.getVersion('git'); ?>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top">
      <i class="fas fa-arrow-up"></i>
    </button>
  </main>
  <div class="fixed-plugin">
    <button class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </button> 
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Dashboard Configurator</h5>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0 text-center">Accent Colour</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-center">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this);themeColor(this);"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this);themeColor(this);"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this);themeColor(this);"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this);themeColor(this);"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this);themeColor(this);"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this);themeColor(this);"></span>
          </div>
        </a>
        <hr class="horizontal dark my-sm-4">
        <!-- Dark Mode -->
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Dark Mode</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
          <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="Cookies must be enabled for darkmode to work."><input class="form-check-input mt-1 ms-auto themec" type="checkbox" id="dark-version" onclick="darkMode(this)"></span>
          </div>
        </div>
        <?php
        if (getVersion('dev') == true){ ?>
        <hr class="horizontal dark my-sm-4">
        <div class="w-100 text-center">
          <h6 class="mt-3">Found a bug? Report it!</h6>
          <a href="https://github.com/TimTims/hlstatsx-ce-web/issues" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="bi bi-bug-fill me-1" aria-hidden="true"></i> GitHub
          </a>
          <a href="https://discord.gg/bY8NMSx3kr" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="bi bi-discord" aria-hidden="true"></i> Discord
          </a>
        </div>
        <?php } ?>
        <hr class="horizontal dark my-sm-4">
        <div class="text-center"><a class="btn btn-outline-dark w-75" href="#" onclick="cookieSettings.showDialog(); return false;">Edit Cookie Settings</a></div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <!--  JS Scripts -->
  <script>
    if(document.getElementById("chart-line") !== null){
      var ctx1 = document.getElementById("chart-line").getContext("2d");

      var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

      gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
      gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
      gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
      new Chart(ctx1, {
        type: "line",
        data: {
          labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
          datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#5e72e4",
            backgroundColor: gradientStroke1,
            borderWidth: 3,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                padding: 10,
                color: '#fbfbfb',
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
              }
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                color: '#ccc',
                padding: 20,
                font: {
                  size: 11,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
              }
            },
          },
        },
      });
    }
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Search Bar -->
  <script>
    function search() {
      // Get the user's input from the input field
      var userInput = document.getElementById("general-search").value;

      // Construct the URL based on the input
      var searchURL = "<?php echo $g_options['scripturl']; ?>?mode=search&q=" + encodeURIComponent(userInput) + "&st=player&game=";

      // Redirect to the constructed URL
      window.location.href = searchURL;
    }

    document.getElementById("general-search").addEventListener("keypress", function(event) {
      // Check if the pressed key is Enter (key code 13)
      if (event.key === 'Enter') {
        search();
      }
    });
  </script>
  <!-- Enable Popovers -->
  <script>
    document.addEventListener("DOMContentLoaded", function(){
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(element){
            return new bootstrap.Popover(element);
        });
    });
  </script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/argon-dashboard.js"></script>
  <script src="assets/js/tabs.js"></script>
  <!-- Cookie Consent -->
  <script src="assets/js/cookie-consent.js"></script>
  <script>
    var cookieSettings = new BootstrapCookieConsentSettings({
        contentURL: "assets/js/cookie-consent-content",
        privacyPolicyUrl: "<?php echo $g_options['scripturl'] ?>?mode=privacypolicy",
        legalNoticeUrl: "<?php echo $g_options['scripturl'] ?>?mode=privacypolicy",
        defaultLang: "en",
        categories: ["necessary", "personalization"],
        cookieName: "cookie-consent",
        cookieStorageDays: 365,
        postSelectionCallback: function () {
            location.reload() // reload after selection
        }
    })
  </script>  
</body>

</html>