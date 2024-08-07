<?php
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../error.log');

require_once realpath(dirname(__FILE__) . '/../../models/DBConnection.php');
require_once realpath(dirname(__FILE__) . '/../../controllers/Invoice.php');
require_once realpath(dirname(__FILE__) . '/../../controllers/User.php');
require_once realpath(dirname(__FILE__) . '/../../controllers/Report.php');

// Create a DBConnection instance
$dbConnection = new DBConnection();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
	header("Location: ./login.php");
	exit;
}

?>

<body>
	<div class="container-scroller">

		<!-- partial:partials/_navbar.html -->
		<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
			<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
				<a class="navbar-brand brand-logo mr-5" href="./index.php">
					PAF
					<img src="../uploads/window.png" alt="PAF logo">
					<!-- <i class="mdi mdi-sale text-secondary" style="font-size: 30px"></i> -->
				</a>
				<a class="navbar-brand brand-logo-mini" href="./index.php">
					<!-- <i class="mdi mdi-sale text-secondary" style="font-size: 36px"></i> -->
					<img src="../uploads/window.png" alt="PAF logo">
				</a>
			</div>
			<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
				<button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
					<span class="icon-menu"></span>
				</button>
				<ul class="navbar-nav navbar-nav-right">
					<li class="nav-item nav-profile dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
							<i class="ti-user mx-0 text-secondary" style="font-size: 24px;"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
							<?php
							if (isset($_SESSION['user']['id'])) {
							?>
								<a class="btn btn-secondary dropdown-item text-secondary" href="./profile.php">
									<i class="ti-user"></i> Profile
								</a>
								<a id="logout" class="btn btn-danger dropdown-item text-danger">
									<i class="ti-power-off"></i> Logout
								</a>
							<?php
							}
							?>
						</div>
					</li>

				</ul>
				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
					<span class="icon-menu"></span>
				</button>
			</div>
		</nav>

		<!-- partial -->
		<div class="container-fluid page-body-wrapper">

			<!-- partial:partials/_sidebar.html -->
			<nav class="sidebar sidebar-offcanvas" id="sidebar">
				<ul class="nav">
					<li class="nav-item">
						<a class="nav-link" href="./index.php">
							<i class="icon-grid menu-icon"></i>
							<span class="menu-title">Dashboard</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="collapse" href="#invoice-elements" aria-expanded="false" aria-controls="form-elements">
							<i class="ti-shopping-cart menu-icon"></i>
							<span class="menu-title">Invoices</span>
							<i class="menu-arrow"></i>
						</a>
						<div class="collapse" id="invoice-elements">
							<ul class="nav flex-column sub-menu">
								<li class="nav-item"><a class="nav-link" href="make-invoice.php">Make invoice</a></li>
								<li class="nav-item"><a class="nav-link" href="view-invoices.php">View invoices</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</nav>