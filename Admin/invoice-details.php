<?php
include_once('includes/head.php');
include_once('includes/navbar.php');

// Create a invoice instance
$invoices = new Invoice($dbConnection);
$details = $invoices->getSingleInvoiceDetails($_GET['invoice']);

$invoiceId = htmlspecialchars($details['message'][0]['invoice_id'], ENT_QUOTES, 'UTF-8');
$title = htmlspecialchars($details['message'][0]['title'], ENT_QUOTES, 'UTF-8');
$name = htmlspecialchars($details['message'][0]['product_name'], ENT_QUOTES, 'UTF-8');
$customer = htmlspecialchars($details['message'][0]['customer'], ENT_QUOTES, 'UTF-8');
$contact = htmlspecialchars($details['message'][0]['contact'], ENT_QUOTES, 'UTF-8');
$quaunity = htmlspecialchars($details['message'][0]['quantity'], ENT_QUOTES, 'UTF-8');
$price = htmlspecialchars($details['message'][0]['price'], ENT_QUOTES, 'UTF-8');
$amount = htmlspecialchars($details['message'][0]['amount'], ENT_QUOTES, 'UTF-8');
$total = htmlspecialchars($details['message'][0]['total'], ENT_QUOTES, 'UTF-8');
$transportation = htmlspecialchars($details['message'][0]['transportation'], ENT_QUOTES, 'UTF-8');
$workmanship = htmlspecialchars($details['message'][0]['workmanship'], ENT_QUOTES, 'UTF-8');
$date = htmlspecialchars(date('l, jS  F, Y.', strtotime($details['message'][0]['date'])), ENT_QUOTES, 'UTF-8');

?>

<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row" id="invoice-data" style="font-size: 1.2rem; margin: 12px 0;">
            <div class="col-md-12 col-sm-12 col-lg-12 col-12 grid-margin stretch-card">
                <div class="card p-5">
                    <div class="card-header bg-white text-primary text-center">
                            <h2 class="text-uppercase m-0">
                                <img src="../uploads/window.png" alt="">
                                Permanent Aluminium Fabrication
                            </h2>
                            <h3 class="mt-3">High Quality of Services</h3>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-right text-primary" style="font-size: 1rem; margin: 4px 0;">Serial #: <?= $invoiceId; ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-7">
                                        <h4 class="pb-3" style="font-size: 1rem; margin: 12px 0;"><?= $date; ?></h4>
                                        <address>
                                            <p style=" margin: 12px 0;"><strong style="font-size: 1.2rem;"><i class="ti ti-map-alt text-info"></i> Beach Road, Shama, Ghana</strong><br></p>
                                            <p style=" margin: 12px 0;"><a style="font-size: 1.2rem;"><i class="ti ti-location-pin text-info"></i> WR-013-3840</a><br></p>
                                            <p style=" margin: 12px 0;"><a href="tel:+233277100022" style="text-decoration: none; color:#000; font-size: 1.2rem;"><i class="mdi mdi-whatsapp text-success"></i> +233 277 100 022</a><br></p>
                                            <p style=" margin: 12px 0;"><a href="tel:+233207926588" style="text-decoration: none; color:#000; font-size: 1.2rem;"><i class="ti ti-mobile text-primary"></i> +233 207 926 588</a><br></p>
                                            <p style=" margin: 12px 0;"><a href="tel:+233312299068" style="text-decoration: none; color:#000; font-size: 1.2rem;"><i class="ti ti-mobile text-primary"></i> +233 312 299 068</a><br></p>
                                            <p style=" margin: 12px 0;"><a href="mailto:affedziejheyjoseph@gmail.com" style="text-decoration: none; color:#000; font-size: 1.2rem;"><i class="ti ti-email text-danger"></i> affedziejheyjoseph@gmail.com</a><br></p>
                                        </address>
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <h3 style="font-size: 1.2rem; margin: 12px 0;"><b>Client</b></h3>
                                        <address>
                                            <p class="py-2" style="font-size: 1.2rem; margin: 12px 0;"><?= ucwords($customer); ?></p>
                                            <p href="tel:<?= $contact; ?>" style="text-decoration: none; color:#000; font-size:1.2rem;"><i class="ti ti-mobile text-primary"></i> <?= $contact; ?></p><br>
                                            <h4 style="font-size: 1rem; margin: 12px 0;"><?= ucwords($title); ?></h4>
                                        </address>
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="invoice-items-table" class="table invoice-column table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left" style="font-size: 1.2rem; margin: 6px 0;">Product Name</th>
                                                        <th class="text-left" style="font-size: 1.2rem; margin: 6px 0;">Unit Price</th>
                                                        <th class="text-left" style="font-size: 1.2rem; margin: 6px 0;">Quantity</th>
                                                        <th class="text-left" style="font-size: 1.2rem; margin: 6px 0;">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-left">
                                                    <?php
                                                    $materialTotal = 0;
                                                    foreach ($details['message'] as $invoice) {
                                                        $materialTotal += $invoice['amount'];
                                                    ?>
                                                        <tr>
                                                            <td style="font-size: 1rem;"><?= ucwords(htmlspecialchars($invoice['product_name'])); ?></td>
                                                            <td style="font-size: 1rem;">&#8373;<?= htmlspecialchars($invoice['price']); ?></td>
                                                            <td style="font-size: 1rem;"><?= htmlspecialchars($invoice['quantity']); ?></td>
                                                            <td style="font-size: 1rem;">&#8373;<?= htmlspecialchars($invoice['amount']); ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <h4 style="font-size: 1rem;">Material cost</h4>
                                                        </td>
                                                        <td class="text-left">
                                                            <h4 style="font-size: 1rem;" class="text-primary"> &#8373;<?= htmlspecialchars($materialTotal); ?></h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <h4 style="font-size: 1rem;">Workmanship</h4>
                                                        </td>
                                                        <td class="text-left">
                                                            <h4 style="font-size: 1rem;" class="text-primary"> &#8373;<?= htmlspecialchars($workmanship); ?></h4>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <h4 style="font-size: 1rem;">Transportation</h4>
                                                        </td>
                                                        <td class="text-left">
                                                            <h4 style="font-size: 1rem;" class="text-primary"> &#8373;<?= htmlspecialchars($transportation); ?></span></h4>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <h4 style="font-size: 1rem;">Total</h4>
                                                        </td>
                                                        <td class="text-left">
                                                            <h4 style="font-size: 1rem;" class="text-primary"> &#8373;<?= htmlspecialchars($total); ?></span></h4>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white ">
                                    <h5 class="text-primary text-center">Thank you for doing business with us.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Print button -->
        <button class="print-button btn btn-primary">Print Invoice</button>

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
<script src="../vendors/sweetalert/sweetalert.min.js"></script>
<script src="../js/off-canvas.js"></script>
<script src="../js/hoverable-collapse.js"></script>
<script src="../js/template.js"></script>
<script src="../js/settings.js"></script>
<script src="../js/todolist.js"></script>
<script src="../js/dashboard.js"></script>
<script src="../js/main.js"></script>

<!-- Ensures PDF generation -->
<script src="../js/jsPDF.js"></script>
<script src="../js/html2canvas.min.js"></script>
<script>
    document.querySelector('.print-button').addEventListener('click', function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();
        const invoiceData = document.getElementById('invoice-data');

        html2canvas(invoiceData).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 210; // A4 width in mm
            const pageHeight = 295; // A4 height in mm
            const imgHeight = canvas.height * imgWidth / canvas.width;
            let heightLeft = imgHeight;
            let position = 0;

            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                doc.addPage();
                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            doc.save('Permanent Aluminium Fabrication Invoice.pdf');
        });
    });
</script>
</body>

</html>