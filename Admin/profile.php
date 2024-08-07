<?php
include_once('includes/head.php');
include_once('includes/navbar.php');

// Create a user instance
$user = new User($dbConnection);
$details = $user->getSingleUserDetails($_SESSION['user']['id']);
$firstname = htmlspecialchars($details['message']['first_name'], ENT_QUOTES, 'UTF-8');
$lastname = htmlspecialchars($details['message']['last_name'], ENT_QUOTES, 'UTF-8');
$contact = htmlspecialchars($details['message']['contact'], ENT_QUOTES, 'UTF-8');
$role = htmlspecialchars($details['message']['role'], ENT_QUOTES, 'UTF-8');
$user_id = htmlspecialchars($details['message']['user_id'], ENT_QUOTES, 'UTF-8');

?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="ti-user text-primary" style="font-size: 2rem;"></i>
                        <h3 class="text-primary mt-3"><?= $firstname . " " . $lastname; ?></h3>
                        <h4 class="text-secondary mt-5"><?= $contact; ?></h4>
                        <h4 class="text-secondary mb-5"><?= ucwords($role); ?></h4>

                        <button class="btn btn-outline-primary" data-toggle="modal" data-target="#updateProfileModal">
                            Update profile
                        </button>
                    </div>
                    <div class="card-footer bg-primary text-center">
                        <button class="btn btn-light" data-toggle="modal" data-target="#changePasswordModal">
                            Change password
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="updateProfileModalLabel">Update profile</h3>
                    </div>
                    <div class="modal-body">
                        <form id="update-profile-form" action="../logic/update-profile-logic.php" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="update-profile-first-name">First name</label>
                                        <input type="text" class="form-control" id="update-profile-first-name" name="first_name" value="<?= $firstname; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update-profile-last-name">Last name</label>
                                        <input type="text" class="form-control" id="update-profile-last-name" name="last_name" value="<?= $lastname; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="update-profile-contact">Contact</label>
                                        <input type="tel" id="update-profile-contact" class="form-control" name="contact" value="<?= $contact; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="update-profile-role">Role</label>
                                        <select id="update-profile-role" class="form-control" name="role" required>
                                            <?php
                                            if ($role == 'manager') {
                                            ?>
                                                <option value="<?= $role ?>"><?= $role ?></option>
                                                <option value="sales person">sales person</option>
                                            <?php
                                            } else {
                                            ?>
                                                <option value="<?= $role ?>"><?= $role ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button id="update-profile-btn" class="btn btn-block btn-outline-primary" name="update-profile-btn">Update</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="changePasswordModalLabel">Change password</h3>
                    </div>
                    <div class="modal-body">
                        <form id="update-password-form" action="../logic/update-password-logic.php" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="update-old-password">Old Password</label>
                                        <input type="password" class="form-control" id="update-old-password" name="update-old-password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update-new-password">New Password</label>
                                        <input type="password" class="form-control" id="update-new-password" name="update-new-password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="update-confirm-password">Confirm password</label>
                                        <input type="password" class="form-control" id="update-confirm-password" name="update-confirm-password" required">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button id="change-password-btn" class="btn btn-block btn-outline-info" name="change-password-update-btn">Change Password</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                &copy 2024 - POS
            </span>
        </div>
    </footer>
</div>
</div>
</div>

<script src="../vendors/js/vendor.bundle.base.js"></script>
<script src="../js/jquery.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="../vendors/sweetalert/sweetalert.min.js"></script>
<script src="../js/off-canvas.js"></script>
<script src="../js/hoverable-collapse.js"></script>
<script src="../js/template.js"></script>
<script src="../js/settings.js"></script>
<script src="../js/todolist.js"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/main.js"></script>
<script>
    $(document).ready(function() {

        // Change password script
        $("#change-password-btn").click(function(e) {
            e.preventDefault();

            // Get form inputs
            var oldPassword = $("#update-old-password").val().trim();
            var newPassword = $("#update-new-password").val().trim();
            var conPassword = $("#update-confirm-password").val().trim();

            // Perform validation
            if (oldPassword === "") {
                swal("Caution", "Please enter your password.", "Caution");
                return;
            }
            if (newPassword === "") {
                swal("Caution", "Please enter your new password.", "Caution");
                return;
            }
            if (conPassword === "") {
                swal("Caution", "Please confirm your new password.", "Caution");
                return;
            }

            var formData = new FormData();
            formData.append('old_password', oldPassword);
            formData.append('new_password', newPassword);
            formData.append('con_password', conPassword);

            var url = '../logic/change-password-logic.php';
            postReq(url, formData);
        });

        // Update user details
        $("#update-profile-btn").click(function(e) {
            e.preventDefault();

            var firstname = $("#update-profile-first-name").val().trim();
            var lastname = $("#update-profile-last-name").val().trim();
            var role = $("#update-profile-role").val().trim();
            var contact = $("#update-profile-contact").val().trim();

            if (firstname === "") {
                swal("Caution", "Please enter your first name.", "warning");
                return;
            }
            if (lastname === "") {
                swal("Caution", "Please enter your last name.", "warning");
                return;
            }
            if (contact === "") {
                swal("Caution", "Please enter your contact.", "warning");
                return;
            }

            var formData = new FormData();
            formData.append('firstname', firstname);
            formData.append('lastname', lastname);
            formData.append('role', role);
            formData.append('contact', contact);

            var url = '../logic/update-profile-logic.php';
            postReq(url, formData);
        });

    });
</script>
</body>

</html>