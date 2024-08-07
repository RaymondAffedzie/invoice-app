<?php
include_once('includes/head.php');
include_once('includes/navbar.php');

?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 mb-5">
                            <h4 class="card-title float-left">Invoices</h4>
                            <h4 class="float-right"><a href="make-invoice.php" class="text-decoration-none">Add invoice</a></h4>
                        </div>
                        <div class="table-responsive">
                            <table id="invoices-table" class="table invoice-column table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Sales Person</th>
                                        <th>Amount</th>
                                        <th>View Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
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

        fetchinvoices();

        // Fetch invoices
        function fetchinvoices() {
            $.ajax({
                url: "../logic/get-invoices-logic.php",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {

                        // Check if DataTable is already initialized
                        if ($.fn.DataTable.isDataTable('#invoices-table')) {
                            $('#invoices-table').DataTable().clear().rows.add(response.message).draw();
                        } else {
                            $('#invoices-table').DataTable({
                                data: response.message,
                                columns: [{
                                        data: 'date',
                                        render: function(data, type, row) {
                                            if (type === 'display') {
                                                return formatDate(data);
                                            }
                                            return data;
                                        }
                                    },
                                    {
                                        data: 'user'
                                    },
                                    {
                                        data: 'amount'
                                    },
                                    {
                                        data: 'invoice_id',
                                        render: function(data, type, row) {
                                            return '<a href="invoice-details.php?invoice=' + data + '">View</a>';
                                        }
                                    },
                                ],
                                paging: true,
                                searching: true,
                                invoiceing: true,
                                pageLength: 20,
                                lengthMenu: [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, 'All']
                                ],
                            });
                        }
                    } else {
                        swal("Caution", response.message, "warnign");
                    }
                },
                error: function(xhr, status, error) {
                    swal("Error", status + ": " + error, "error");
                }
            });
        }
    });
</script>
</body>

</html>