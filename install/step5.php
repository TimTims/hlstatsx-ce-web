<?php 
include("temp_config.php");
$conn = new mysqli(DB_ADDR, DB_USER, DB_PASS, DB_NAME);
// Fetch username from hlstats_Users
$userQuery = "SELECT username FROM hlstats_Users LIMIT 1";
$userResult = $conn->query($userQuery);

// Initialize variables
$username = '';

// Check if the query was successful
if ($userResult) {
    $user = $userResult->fetch_assoc();

    // Assign the username to a variable
    if ($user) {
        $username = $user['username'];
    }

    // Free the result set
    $userResult->free();
} else {
    // Handle the error
    echo "Error executing username query: " . $conn->error;
}

// Fetch values from hlstats_Options for specific keynames
$keynames = ["sitename", "siteurl", "sourcebans_address", "forum_address", "discord_address"];
$optionsQuery = "SELECT keyname, value FROM hlstats_Options WHERE keyname IN ('" . implode("','", $keynames) . "')";
$optionsResult = $conn->query($optionsQuery);

// Initialize variables
$sitename = $siteurl = $sourcebans_address = $forum_address = $discord_address = '';

// Check if the query was successful
if ($optionsResult) {
    $options = $optionsResult->fetch_all(MYSQLI_ASSOC);

    // Assign values to variables
    foreach ($options as $option) {
        switch ($option['keyname']) {
            case 'sitename':
                $sitename = $option['value'];
                break;
            case 'siteurl':
                $siteurl = $option['value'];
                break;
            case 'sourcebans_address':
                $sourcebans_address = $option['value'];
                break;
            case 'forum_address':
                $forum_address = $option['value'];
                break;
            case 'discord_address':
                $discord_address = $option['value'];
                break;
        }
    }

    // Free the result set
    $optionsResult->free();
} else {
    // Handle the error
    echo "Error executing options query: " . $conn->error;
}

// Close the connection
$conn->close();
?>
<div class="install-script" id="step5">
    <p class="ms-4 me-4 text-center">Congratulations, you have successfully configured your HLstatsX: CE instance!<br/>By clicking the "Configure" button below, you will write the config file and be finished with the setup process.</p>
        <div class="table-responsive col-md-10 mx-auto">
            <table class="table table-striped table-bordered">
                <tr>
                    <td><strong>Site Name:</strong></td>
                    <td><?php echo $sitename; ?></td>
                </tr>
                <tr>
                    <td><strong>Site URL:</strong></td>
                    <td><?php echo $siteurl; ?></td>
                </tr>
                <tr>
                    <td><strong>Sourcebans Address:</strong></td>
                    <td><?php echo $sourcebans_address; ?></td>
                </tr>
                <tr>
                    <td><strong>Forum Address:</strong></td>
                    <td><?php echo $forum_address; ?></td>
                </tr>
                <tr>
                    <td><strong>Discord Address:</strong></td>
                    <td><?php echo $discord_address; ?></td>
                </tr>
                <tr class="border-top border-2 border-black">
                    <td><strong>Admin Username:</strong></td>
                    <td><?php echo $username; ?></td>
                </tr>
                <tr>
                    <td><strong>Admin Password:</strong></td>
                    <td><strong><strong><i>encrypted</i></strong</td>
                </tr>
            </table>
        </div>
    <form id="step5Form" class="col-6 mx-auto">
        <input type="hidden" id="step5">
        <div class="text-center">
            <button type="submit" class="btn btn-info">Configure</button>
        </div>
    </form>
</form>