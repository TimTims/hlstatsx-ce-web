<div class="install-script" id="step4">
    <div class="alert alert-success mx-auto text-center col-8" role="alert">User successfully created!</div>
    <form id="step4Form" class="col-6 mx-auto">
        <div class="mb-3">
            <label for="sitename" class="form-label">Site Name</label>
            <input type="text" class="form-control" id="sitename" name="sitename" required>
        </div>
        <div class="mb-3">
            <label for="siteurl" class="form-label">Site URL</label>
            <input type="text" class="form-control" id="siteurl" name="siteurl" required>
        </div>
        <div class="mb-3">
            <label for="contactemail" class="form-label">Contact E-mail</label>
            <input type="email" class="form-control" id="contactemail" name="contactemail" required>
        </div>
        <div class="mb-3">
            <label for="sourcebans" class="form-label">Sourcebans URL (including http:// or https://)</label>
            <input type="text" class="form-control" id="sourcebans" name="sourcebans">
        </div>
        <div class="mb-3">
            <label for="forum" class="form-label">Forum URL (including http:// or https://)</label>
            <input type="text" class="form-control" id="forum" name="forum">
        </div>
        <div class="mb-3">
            <label for="discord" class="form-label">Discord Invite URL (including http:// or https://)</label>
            <input type="text" class="form-control" id="discord" name="discord">
        </div>
        <!-- Add more fields for other settings as needed -->

        <input type="hidden" id="step4" name="step4">
        <!-- Submit button-->
        <div class="text-center">
            <button type="submit" class="btn btn-info">Next</button>
        </div>
    </form>
</div>
