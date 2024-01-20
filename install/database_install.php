<?php

// Function to handle step-specific actions
function handleStep($step, $postData) {
    switch ($step) {
        case 'step2':
            // Retrieve database information from the form
            $step = isset($_GET['step']) ? $_GET['step'] : '';
        
            // If it's step 2, retrieve form data
            if ($step === 'step2') {
                // Read the raw input data
                $rawInputData = file_get_contents('php://input');
        
                // Decode the JSON data
                $jsonData = json_decode($rawInputData, true);
        
                // Check if decoding was successful
                if ($jsonData === null) {
                    echo json_encode(['success' => false, 'message' => 'Error decoding JSON data']);
                    exit;
                }
        
                $dbHost = isset($jsonData['dbHost']) ? $jsonData['dbHost'] : '';
                $dbName = isset($jsonData['dbName']) ? $jsonData['dbName'] : '';
                $dbUser = isset($jsonData['dbUser']) ? $jsonData['dbUser'] : '';
                $dbPassword = isset($jsonData['dbPassword']) ? $jsonData['dbPassword'] : '';
        
                try {
                    // Attempt to create a new mysqli connection
                    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        
                    // Rest of your code
                } catch (mysqli_sql_exception $e) {
                    // If an exception occurs, capture the error message
                    $errorMessage = $e->getMessage();
        
                    // Send the error message as part of the JSON response
                    $response = [
                        'success' => false,
                        'message' => $errorMessage
                    ];
        
                    echo json_encode($response);
                    exit;
                }
        
                // If the code reaches this point, the connection was successful
        
                // Create a temporary configuration file
                $tempConfigFile = 'temp_config.php';
        
                $configContent = <<<EOD
                <?php
                // DB_ADDR - The address of the database server, in host:port format.
                //           (You might also try setting this to e.g. ":/tmp/mysql.sock" to
                //           use a Unix domain socket, if your mysqld is on the same box as
                //           your web server.)
                define("DB_ADDR", '$dbHost');
        
                // DB_USER - The username to connect to the database as
                define("DB_USER", '$dbUser');
        
                // DB_PASS - The password for DB_USER
                define("DB_PASS", '$dbPassword');
        
                // DB_NAME - The name of the database
                define("DB_NAME", '$dbName');
        
                ?>
                EOD;
        
                file_put_contents($tempConfigFile, $configContent);
        
                // Read the SQL file content
                $sqlFile = 'install.sql';
                $sql = file_get_contents($sqlFile);
        
                // Execute the SQL queries
                if ($conn->multi_query($sql)) {
                    do {
                        // Store the result set
                        if ($result = $conn->store_result()) {
                            $result->free();
                        }
                    } while ($conn->more_results() && $conn->next_result());
        
                    if (!file_exists($tempConfigFile)) {
                        echo json_encode(['success' => false, 'message' => 'Database created successfully, but temp config file creation failed.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error executing SQL queries: ' . $conn->error]);
                }
        
                // Close the connection
                $conn->close();
            }
        
            $success = true; // Replace with your logic
            $message = $success ? 'Step 2 completed successfully. Database populated and temp config created.' : 'Step 2 failed';
            break;
        case 'step3':
            $rawInputData = file_get_contents('php://input');
        
            // Decode the JSON data
            $jsonData = json_decode($rawInputData, true);
    
            // Check if decoding was successful
            if ($jsonData === null) {
                echo json_encode(['success' => false, 'message' => 'Error decoding JSON data']);
                exit;
            }
            // Retrieve form data
            $username = isset($jsonData['username']) ? $jsonData['username'] : '';
            $password = isset($jsonData['password']) ? $jsonData['password'] : '';

            // Initialize $conn outside the try-catch block
            $conn = null;

            try {
                // Include the database connection code here
                require('temp_config.php');

                // Use prepared statements to prevent SQL injection
                $conn = new mysqli(DB_ADDR, DB_USER, DB_PASS, DB_NAME);
                $stmt = $conn->prepare("INSERT INTO hlstats_Users (username, password, acclevel, playerId) VALUES (?, ?, ?, ?)");
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $acclevel = 100;
                $playerId = 0;
                $stmt->bind_param("ssii", $username, $hashedPassword, $acclevel, $playerId);


                // Execute the statement
                if ($stmt->execute()) {
                    // User account inserted successfully, respond with success
                    $succmessage = "User account created";
                } else {
                    // Error inserting user account into the database
                    echo json_encode(['success' => false, 'message' => 'Error inserting user account.']);
                    exit;
                }

                // Close the statement
                $stmt->close();
            } catch (Exception $e) {
                // Handle exceptions, log or respond appropriately
                echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
                exit;
            } finally {
                // Close the connection in the finally block to ensure it happens
                if ($conn !== null) {
                    $conn->close();
                }
            }

            $success = true; // Replace with your logic
            $message = $success ? 'Step 3 completed successfully' . $succmessage : 'Step 3 failed';
            break;
        case 'step4':
            $rawInputData = file_get_contents('php://input');
        
            // Decode the JSON data
            $jsonData = json_decode($rawInputData, true);
    
            // Check if decoding was successful
            if ($jsonData === null) {
                echo json_encode(['success' => false, 'message' => 'Error decoding JSON data']);
                exit;
            }
            // Retrieve form data
            $sitename = isset($jsonData['sitename']) ? $jsonData['sitename'] : '';
            $siteurl = isset($jsonData['siteurl']) ? $jsonData['siteurl'] : '';
            $contactemail = isset($jsonData['contacturl']) ? $jsonData['contacturl'] : '';
            $sourcebans = isset($jsonData['sourcebans']) ? $jsonData['sourcebans'] : '';
            $forum = isset($jsonData['forum']) ? $jsonData['forum'] : '';
            $discord = isset($jsonData['discord']) ? $jsonData['discord'] : '';

            try {
                // Include the database connection code here
                include 'temp_config.php';

                // Use prepared statements to prevent SQL injection
                $conn = new mysqli(DB_ADDR, DB_USER, DB_PASS, DB_NAME);

                // Update hlstats_Options records for step 4 settings
                updateOption($conn, 'sitename', $sitename);
                updateOption($conn, 'siteurl', $siteurl);
                updateOption($conn, 'contact', $contactemail);
                updateOption($conn, 'sourcebans_address', $sourcebans);
                updateOption($conn, 'forum_address', $forum);
                updateOption($conn, 'discord_address', $discord);

            } catch (Exception $e) {
                // Handle exceptions, log or respond appropriately
                echo json_encode(['success' => false, 'message' => 'Exception: ' . $e->getMessage()]);
                exit;
            } finally {
                // Close the connection in the finally block to ensure it happens
                if ($conn !== null) {
                    $conn->close();
                }
            }
            $success = true; // Replace with your logic
            $message = $success ? 'Step 4 completed successfully' : 'Step 4 failed';
            break;
        case 'step5':
            // Include the content of temp_config.php
            $tempConfigPath = 'temp_config.php'; // Adjust the path accordingly
            $tempConfigContent = file_get_contents($tempConfigPath);
            $tempConfigContent = substr($tempConfigContent, 5, -2);

            // Add dynamic configuration information
            $Config1 = <<<EOD
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

            EOD;

            $Config2 = <<<EOD
            // DB_TYPE - The database server type. Only "mysql" is supported currently
            define("DB_TYPE", 'mysql');

            // default 'utf8mb4'
            define("DB_CHARSET", 'utf8mb4');

            // default 'utf8mb4_unicode_ci'
            define("DB_COLLATE", 'utf8mb4_unicode_ci');

            // DB_PCONNECT - Set to 1 to use persistent database connections. Persistent
            //               connections can give better performance, but may overload
            //               the database server. Set to 0 to use non-persistent
            //               connections.
            define("DB_PCONNECT", 0);

            // INCLUDE_PATH - Filesystem path to the includes directory, relative to hlstats.php. This must be specified
            //		as a relative path.
            //
            //                Under Windows, make sure you use forward slash (/) instead
            //                of back slash (\) and use absolute paths if you are having any issue.
            define("INCLUDE_PATH", './includes');

            // INCLUDE_PATH - Filesystem path to the includes directory, relative to hlstats.php. This must be specified
            //		as a relative path.
            //
            //                Under Windows, make sure you use forward slash (/) instead
            //                of back slash (\) and use absolute paths if you are having any issue.
            define("ASSETS_PATH", './assets');

            // PAGE_PATH - Filesystem path to the pages directory, relative to hlstats.php. This must be specified
            //		as a relative path.
            //
            //                Under Windows, make sure you use forward slash (/) instead
            //                of back slash (\) and use absolute paths if you are having any issue.
            define("PAGE_PATH", './pages');


            // CONFIG_PATH - Filesystem path to the configs directory, relative to hlstats.php. This must be specified
            //		as a relative path.
            //
            //                Under Windows, make sure you use forward slash (/) instead
            //                of back slash (\) and use absolute paths if you are having any issue.
            define("CONFIG_PATH", './configs');


            // PAGE_PATH - Filesystem path to the hlstatsimg directory, relative to hlstats.php. This must be specified
            //		as a relative path.
            //
            //                Under Windows, make sure you use forward slash (/) instead
            //                of back slash (\) and use absolute paths if you are having any issue.
            //
            // 		Note: the progress directory under hlstatsimg must be writable!!
            define("IMAGE_PATH", './assets/images/hlstatsimg');

            // How often dynamicly generated images are updated (in seconds)
            define("IMAGE_UPDATE_INTERVAL", 300);

            // Does the site use SSL?
            define('SITE_SSL', true);

            define('GOOGLE_MAPS_API_KEY', "");

            //define("DB_DEBUG", true);

            ?>
            EOD;

            // Combine the contents
            $configContent = $Config1 . $tempConfigContent . $Config2;

            // Write the combined content to config.php
            $configFile = '../configs/config.php';
            file_put_contents($configFile, $configContent);

            $success = true; // Replace with your logic
            $message = $success ? 'Step 4 completed successfully' : 'Step 4 failed';
            break;
        default:
            $success = false;
            $message = 'Invalid step provided';
            break;
    }

    // Return a JSON response
    echo json_encode(['success' => $success, 'message' => $message]);
}

// Check if the step parameter is set
if (isset($_GET['step'])) {
    $currentStep = $_GET['step'];

    // Check the request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assume that the data is sent as JSON
        $postData = json_decode(file_get_contents('php://input'), true);

        // Handle step-specific actions
        handleStep($currentStep, $postData);
    } else {
        // Invalid request method
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
} else {
    // Step parameter not set
    echo json_encode(['success' => false, 'message' => 'Step parameter not provided']);
}

// Function to update a specific option in hlstats_Options
function updateOption($conn, $keyname, $value) {
    $stmt = $conn->prepare("UPDATE hlstats_Options SET value = ? WHERE keyname = ?");
    $stmt->bind_param("ss", $value, $keyname);
    $stmt->execute();
    $stmt->close();
}

?>