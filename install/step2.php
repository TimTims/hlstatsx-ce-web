<div id="step2" class="install-script">
    <!-- Display any error messages here if needed -->
    <div id="errorMessages"></div>
    <p class="text-center ms-4 me-4">Fill in your database details below.</br>When clicking next, the installation will write the tables to your chosen database, if the details are correct.</p>
    <!-- MySQL Database Information Form -->
    <form id="step2Form" class="col-6 mx-auto" >
        <div class="mb-3">
            <label for="dbHost" class="form-label">Database Host</label>
            <input type="text" class="form-control" id="dbHost" name="dbHost" required>
        </div>
        <div class="mb-3">
            <label for="dbName" class="form-label">Database Name</label>
            <input type="text" class="form-control" id="dbName" name="dbName" required>
        </div>
        <div class="mb-3">
            <label for="dbUser" class="form-label">Database User</label>
            <input type="text" class="form-control" id="dbUser" name="dbUser" required>
        </div>
        <div class="mb-3">
            <label for="dbPassword" class="form-label">Database Password</label>
            <input type="password" class="form-control" id="dbPassword" name="dbPassword">
        </div>
        <input type="hidden" id="step2" name="step2">

        <!-- Next and Previous buttons -->
        <div class="text-center">
            <button type="button" class="btn btn-info" id="prevButton1">Previous</button>
            <button type="submit" class="btn btn-info">Next</button>
        </div>
    </form>
</div>
