<div id="step1" class="install-script">
<?php if(!file_exists('../configs/config.php.1')){ ?>
    <p class="ms-4 me-4 text-center">Welcome to the installation script of HLstatsX: CE!</p>
    <p class="ms-4 me-4 text-center">Make sure to follow the instructions carefully. The following pages will help you set up your instance of HLstatsX: CE correctly.</p>
    <div class="text-center">
        <button type="button" class="btn btn-info nextButton">Next</button></div>
    </div>
<?php } else { ?>
    <div class="alert alert-danger mx-auto text-center col-10" role="alert"><strong>Config already exists.</br>Please delete "install" folder to use your HLstatsX: CE instance.</strong></div>
<?php } ?>
