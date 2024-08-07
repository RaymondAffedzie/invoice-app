<?php
include_once('includes/head.php');
include_once('includes/navbar.php');

?>
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Make invoice</h4>
                                <form class="pt-3" id="add-invoice-form" method="post">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" id="title" class="form-control" name="title">
                                    </div>
                                    <div class="form-group">
                                        <label for="contact">Customer's contact</label>
                                        <input type="tel" id="contact" class="form-control" name="customer-contact" autocomplete="on" placeholder="024 111 1112">
                                    </div>
                                    <div class="form-group">
                                        <label for="first-name">Customer's first name</label>
                                        <input type="text" id="first-name" class="form-control" name="customer-first-name" autocomplete="on" placeholder="John">
                                    </div>
                                    <div class="form-group">
                                        <label for="last-name">Customer's last name</label>
                                        <input type="text" id="last-name" class="form-control" name="customer-last-name" autocomplete="on" placeholder="Essien">
                                    </div>
                                    <div class="form-group">
                                        <label for="transportation">Transportation</label>
                                        <input type="number" id="transportation" class="form-control" name="transportation">
                                    </div>
                                    <div class="form-group">
                                        <label for="workmanship">Workmanship</label>
                                        <input type="number" id="workmanship" class="form-control" name="workmanship">
                                    </div>
                                    <div class="paste-new-forms"></div>
                                    <a type="button" class="add-more-btn btn btn-warning rounded-0" href="javascript:void(0)">
                                        Add product
                                    </a>

                                    <div class="mt-3">
                                        <button id="add-to-invoice-btn" class="btn btn-primary rounded-0" name="add-to-invoice-btn">
                                            Add to invoice
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <h4>Invoice list</h4>
                                <div class="table-responsive">
                                    <table id="invoice_table" class="table invoice-column table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-left">Product name</th>
                                                <th class="text-left">Price</th>
                                                <th class="text-left">Quantity</th>
                                                <th class="text-left">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3">
                                                    <h3 class="text-primary">
                                                        Total: &#8373;<span class="text-primary" id="invoice_total"></span>
                                                    </h3>
                                                </td>
                                                <td>
                                                    <button id="create-invoice-btn" class="btn btn-outline-danger rounded-0 m-3" name="add-to-invoice-btn">
                                                        Create invoice
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
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
        // Function to handle the dynamic form addition
        $(".add-more-btn").click(function(e) {
            const timestamp = new Date().getTime();
            const datalistId = `product-list-${timestamp}`;
            const productNameInputId = `invoice-product-name-${timestamp}`;
            const productPriceInputId = `invoice-product-price-${timestamp}`;

            $(".paste-new-forms").append(
                `<div class="main-form">
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <label for="${productNameInputId}">Product</label>
                            <input type="text" id="${productNameInputId}" class="form-control" name="invoice-product-name" autocapitalize="on" autocomplete="on" placeholder="Enter product here...">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="${productPriceInputId}">Price</label>
                            <input type="number" id="${productPriceInputId}" class="form-control" name="invoice-product-price">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-8">
                            <label for="invoice-product-quantity-${timestamp}">Quantity</label>
                            <input type="number" id="invoice-product-quantity-${timestamp}" class="form-control" name="invoice-product-quantity" min="1" step="1" value="1" required>
                        </div>
                        <div class="col-sm-4">
                            <a type="button" class="remove-btn btn btn-outline-danger rounded-0" title="Remove product"><i class="settings-close ti-close text-danger"></i></a>
                        </div>
                    </div>
            </div>`
            );
        });

        // Event delegation to handle dynamically added remove buttons
        $(document).on('click', '.remove-btn', function(e) {
            $(this).closest('.main-form').remove();
        });

        // Save invoice button event
        $("#add-to-invoice-btn").click(function(e) {
            e.preventDefault();

            let invoiceData = [];
            let contact = $("#contact").val().trim();
            let lastName = $("#last-name").val().trim();
            let firstName = $("#first-name").val().trim();
            let transportation = $("#transportation").val().trim();
            let workmanship = $("#workmanship").val().trim();
            let title = $("#title").val().trim();

            // Iterate over each main-form and collect data
            $(".main-form").each(function() {
                let product = $(this).find("input[name='invoice-product-name']").val();
                let quantity = $(this).find("input[name='invoice-product-quantity']").val();
                let price = $(this).find("input[name='invoice-product-price']").val();

                if (product && quantity && price) {
                    invoiceData.push({
                        product: product,
                        quantity: parseInt(quantity),
                        price: parseFloat(price),
                        contact: contact,
                        lastName: lastName,
                        firstName: firstName,
                        transportation: transportation,
                        workmanship: workmanship,
                        title: title
                    });
                }
            });

            var url = '../logic/save-invoice.php';

            postJSON(url, invoiceData);

            fetchInvoice();
        });

        $("#create-invoice-btn").click(function(e) {
            e.preventDefault();

            var url = '../logic/create-invoice-logic.php';

            postReq(url, null);
        });

        // Fetch invoice details
        function fetchInvoice() {
            $.ajax({
                url: "../logic/get-cart-logic.php",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        console.log(response.message);
                        $("#invoice_total").text(response.total);
                        // Check if DataTable is already initialized
                        if ($.fn.DataTable.isDataTable('#invoice_table')) {
                            $('#invoice_table').DataTable().clear().rows.add(response.message).draw();
                        } else {
                            $('#invoice_table').DataTable({
                                data: response.message,
                                columns: [{
                                        data: 'product'
                                    },
                                    {
                                        data: 'price'
                                    },
                                    {
                                        data: 'quantity'
                                    },
                                    {
                                        data: 'amount'
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
                    }
                },
                error: function(xhr, status, error) {
                    swal("Error", status + ": " + error, "error");
                }
            });
        }

        function postJSON(url, data) {
            $.ajax({
                url: url,
                type: "POST",
                data: JSON.stringify(data),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        // console.log(response);
                        swal("Success", response.message, "success").then(function() {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        });
                    } else if (response.status == false) {
                        swal("Caution", response.message, "warning").then(function() {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        });
                    } else {
                        swal("Danger", response.message, "error").then(function() {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    swal(status, error, "error");
                },
            });
        }
    });
</script>
</body>

</html>