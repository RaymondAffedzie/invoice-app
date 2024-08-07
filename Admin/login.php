<?php
include_once('includes/head.php');

if (isset($_SESSION['user']) || isset($_SESSION['user']['id'])) {
    header("Location: index.php");
}
?>

<body>
    <!--Body Content-->
    <div id="page-content">

        <div class="container mt-5">
            <div class="page-title">
                <div class="wrapper">
                    <h2 class="page-width text-center">Login</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-6 mx-auto">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="login_contact">Contact</label>
                                    <input type="tel" name="contact" id="login_contact" class="form-control form-control-lg" autofocus="on">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="login_password">Password</label>
                                    <input type="password" name="password" id="login_password" class="form-control form-control-lg">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mt-3">
                                    <button id="login-btn" class="btn btn-block btn-outline-primary"">
                                        Save
                                    </button>
                                </div> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src=" ../js/jquery.js"></script>
                                        <script src="../vendors/sweetalert/sweetalert.min.js"></script>
                                        <script src="../js/main.js"></script>
                                        <script>
                                            $(document).ready(function() {

                                                $("#login-btn").click(function(e) {
                                                    e.preventDefault();

                                                    var contact = $("#login_contact").val().trim();
                                                    var password = $("#login_password").val().trim();

                                                    if (contact === "") {
                                                        swal("Caution", "Please enter a valid contact number.", "warning");
                                                        return;
                                                    }
                                                    if (password === "") {
                                                        swal("Caution", "Please enter your password.", "warning");
                                                        return;
                                                    }

                                                    var formData = new FormData();
                                                    formData.append('contact', contact);
                                                    formData.append('password', password);

                                                    var url = '../logic/login-logic.php';
                                                    postReq(url, formData);
                                                });
                                            });
                                        </script>
</body>

</html>