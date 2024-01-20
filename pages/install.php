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

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">
  <title>
    HLStatsX: CE Install
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css" rel="stylesheet" />
</head>

<body class="">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
      <img src="../assets/images/logo.png" class="navbar-brand-img h-100" alt="main_logo">
    </div>
  </nav>
  <!-- End Navbar -->
  <main class="main-content">
  <div class="container">
      <div class="row justify-content-center mt-8">
        <div class="col-xl-7 col-lg-7 col-md-10 mx-auto">
          <div class="card z-index-0 mb-4">
            <div class="card-header text-center pt-4">
              <h5>Install HLStatsX: CE</h5>
            </div>
            <div id="install"></div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            <?php echo '<a href="https://github.com/NomisCZ/hlstatsx-community-edition" target="_blank">HLstatsX Community Edition '.getVersion('version').' '.getVersion('dev').'</a> '.getVersion('git'); ?>
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.js?v=2.0.4"></script>
  <script>
    function loadPage(page) {
        console.log(`Loading ${page}.php`);

        let url = `install/${page}.php`;

        // If it's step 2, add the form data to the fetch request
        if (page === 'step2') {
            const formData = new FormData(document.getElementById('step2Form'));
            url += '?' + new URLSearchParams(formData).toString();
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                console.log(`Loaded ${page}.php content`);
                document.getElementById('install').innerHTML = data;
                addEventListeners(page);
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }


    document.addEventListener('DOMContentLoaded', function () {
        // Load the initial page
        loadPage('step1');

        // Event listener for the "Next" button
        document.addEventListener('click', function (event) {
            const target = event.target;
            if (target.tagName === 'BUTTON' && target.classList.contains('nextButton')) {
                handleNextButtonClick();
            }
        });
    });


    function addEventListeners(page) {
        // Event listeners for form submissions
        const form = document.getElementById(`${page}Form`);
        if (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                submitForm(form, `install/database_install.php?step=${page}`);
            });
        }

        // Add more event listeners based on the page, if needed
    }

    function postData(url = '', data = {}) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
            .then(response => response.json())
            .catch(error => console.error('Error:', error));
    }

    function validateFormInputs(...inputs) {
        return inputs.every(input => input.trim() !== '');
    }

    function submitForm(form, action) {
        const formData = new FormData(form);

        // Validate the form data
        const requiredFields = Array.from(form.querySelectorAll('[required]'));
        const isValid = validateFormInputs(...requiredFields.map(field => formData.get(field.name)));

        if (isValid) {
            // Make the fetch request
            postData(action, Object.fromEntries(formData))
                .then(data => {
                    if (data.success) {
                        console.log(`Form submitted successfully. Moving to next step...`);
                        const nextPage = getNextPage(form.id);
                        loadPage(nextPage);
                    } else {
                        displayErrorMessage('errorMessages', data.message, 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                });
        } else {
            displayErrorMessage('errorMessages', 'Please fill in all required fields.', 'warning');
        }
    }

    function handleNextButtonClick() {
        // Determine the current page based on the loaded content
        const currentPage = getCurrentPage();

        // Determine the next page based on the current page
        const nextPage = getNextPage(currentPage);

        // Load the next page
        loadPage(nextPage);
    }

    function getCurrentPage() {
        // Look for identifiers in the content of the 'install' div
        const installDiv = document.getElementById('install');
        if (installDiv.classList.contains('install-script')) {
            // This assumes that each step page has a class 'page-content'
            return installDiv.id;
        }
        // Default to step1 if no specific identifier is found
        return 'step1';
    }

    function getNextPage(currentPage) {
        // Logic to determine the next page based on the current page
        switch (currentPage) {
            case 'step1':
                return 'step2';
            case 'step2':
                return 'step3';
            case 'step3':
                return 'step4';
            case 'step4':
                return 'step5';
            // Add more cases if needed
            default:
                return '';
        }
    }

    // Function to check the database connection
    function checkDatabaseConnection() {
        const formData = new FormData(document.getElementById('mysqlForm'));

        console.log('Submitting form data:', formData);

        fetch('install/database_install.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text(); // Change to text() to see the raw response
        })
        .then(data => {
            console.log('Raw response from check_database.php:', data);

            // Parse the JSON response
            const jsonData = JSON.parse(data);

            console.log('Parsed response from check_database.php:', jsonData);

            if (jsonData.success) {
                // Database connection is successful, proceed to the next page
                loadPage('step3');
            } else {
                // Database connection failed, display error message
                displayErrorMessage('errorMessages', jsonData.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('errorMessages').innerHTML = 'An error occurred while checking the database connection.';
        });
    }

    // Function to display error messages in a stylized way
    function displayErrorMessage(targetId, message, alertType = 'danger') {
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('alert', `alert-${alertType}`, 'mx-auto', 'col-8', 'text-center');
        errorDiv.setAttribute('role', 'alert');

        // Create a <strong> element for the bold text
        const strongElement = document.createElement('strong');
        strongElement.innerText = message;

        // Append the <strong> element to the errorDiv
        errorDiv.appendChild(strongElement);

        // Clear previous error messages
        document.getElementById(targetId).innerHTML = '';

        // Append the new error message
        document.getElementById(targetId).appendChild(errorDiv);
    }
  </script>

</body>
</html>