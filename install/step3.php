<div class="install-script" id="step3">
    <div class="alert alert-success mx-auto text-center col-8" role="alert">Database and temporary config successfully written!</div>
    <div id="errorMessages"></div>
    <form id="step3Form" class="col-6 mx-auto">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirmpassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
        </div>
        <input type="hidden" id="step3" name="step3">
        <!-- Next button-->
        <div class="text-center">
            <button type="submit" class="btn btn-info" onclick="return validatePassword()">Next</button>
        </div>
    </form>
</div>