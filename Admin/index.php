<?php
include_once('includes/head.php');
include_once('includes/navbar.php');

?>
<!-- partial -->
<div class="main-panel">
	<div class="content-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-body">
					<h3 class="text-center my-3">My report</h3>
					<div class="row">
						<div class="col-md-12">
							<div class="row" hidden>
								<div class="col-md-4">
									<div class="form-group">
										<label for="report-from">From</label>
										<input type="date" class="form-control" name="from" id="report-from">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="report-to">To</label>
										<input type="date" class="form-control" name="to" id="report-to">
									</div>
								</div>
								<div class="col-md-4">
									<button type="button" class="btn btn-outline-primary btn-block mt-4" id="report-btn"><i class="ti ti-search"></i> Search</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 grid-margin stretch-card" id="report-data">
							<div class="card">
								<div class="card-body">
									<p class="card-title">invoice Details</p>
									
									<div style="overflow-x:auto;">
										<table class="table table-hover">
											<thead>
												<tr>
													<th>Customer's Name</th>
													<th>Date</th>
													<th>Amount</th>
													<th>Invoice Details</th>
												</tr>
											</thead>
											<tbody id="report-table-body">
												<!-- Rows will be inserted here dynamically -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="../vendors/js/vendor.bundle.base.js"></script>
	<script src="../js/jquery.js"></script>
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

			$.ajax({
				url: "../logic/get-my-report-logic.php",
				type: "GET",
				dataType: "json",
				success: function(response) {
					if (response.status === true) {
						var reportTableBody = $("#report-table-body");
						reportTableBody.empty();

						if (response.message !== null && response.message.length > 0) {
							response.message.forEach(function(invoice) {
								var date = new Date(invoice.date).toLocaleDateString();
								date = formatDateNoTime(date);
								var row = `<tr>
                                <td>${invoice.customer_first_name} ${invoice.customer_last_name}</td>
                                <td>${date}</td>
                                <td>${invoice.amount}</td>
                                <td><a href="invoice-details.php?invoice=${invoice.invoice_id}">View details</a></a></td>
                            </tr>`;
								reportTableBody.append(row);
							});
						} else {
							swal("No Data", "No invoices found.", "info");
						}
					} else {
						swal("Error", response.message || "An error occurred.", "error");
					}
				},
				error: function(xhr, status, error) {
					swal("Error", status + ": " + error, "error");
				}
			});
		});

		function formatDateNoTime(date) {
			date = new Date(date);
			const day = date.getDate();
			const month = date.toLocaleDateString("en-GB", {
				month: "long",
			});
			const year = date.getFullYear();

			const formattedDate = `${day}${daySuffix(day)} ${month}, ${year}`;
			return formattedDate;
		}

		function printReport() {
			var printContents = document.getElementById('report-data').innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}

		function printOthersReport() {
			var printContents = document.getElementById('others-report-data').innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
	</script>

	</body>

	</html>