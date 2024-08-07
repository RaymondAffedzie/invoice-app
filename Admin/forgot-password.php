<?php
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

include_once('includes/head.php');
?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card mx-auto">
                <div class="card" id="change-password-card">
                    <div class="card-body">
                        <h1 class="card-title">Change Password</h1>
                        <form class="pt-3" id="change-password-form" action="../logic/change-password-logic.php" method="post">
                            <div class="form-group">
                                <input type="text" id="old-password" class="form-control form-control-lg" name="old-password" placeholder="Old password">
                            </div>
                            <div class="form-group">
                                <input type="text" id="new-password" class="form-control form-control-lg" name="new-password" placeholder="New password">
                            </div>
                            <div class="form-group">
                                <input type="text" id="con-new-password" class="form-control form-control-lg" name="con-new-password" placeholder="Confirm new password">
                            </div>

                            <div class="mt-3">
                                <button id="save-password-btn" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="save">SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
