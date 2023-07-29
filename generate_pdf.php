<?php
// Include the TCPDF library
require_once('tcpdf/tcpdf.php');

// Function to generate the PDF
function generatePDF($orderData) {
    $pdf = new TCPDF();
    $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
    $pdf->AddPage();

    // Set restaurant details
    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(0, 15, 'Janani Restaurant', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Address: Bhola', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Phone: 01780118936', 0, 1, 'C');

    // Set order details in a table
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 15, 'Order Summary', 0, 1, 'C');

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(60, 10, 'Food Name', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Price', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Total', 1, 1, 'C');

    $pdf->SetFont('helvetica', '', 12);
    $totalAmount = 0;
    foreach ($orderData as $foodId => $cartItem) {
        $foodName = $cartItem['name'];
        $quantity = $cartItem['quantity'];
        $price = $cartItem['price'];
        $total = $price * $quantity;
        $totalAmount += $total;

        $pdf->Cell(60, 10, $foodName, 1, 0, 'L');
        $pdf->Cell(30, 10, $quantity, 1, 0, 'C');
        $pdf->Cell(30, 10, '$'.$price, 1, 0, 'R');
        $pdf->Cell(40, 10, '$'.$total, 1, 1, 'R');
    }

    // Set total amount
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(120, 10, 'Total Amount:', 1, 0, 'R');
    $pdf->Cell(40, 10, '$'.$totalAmount, 1, 1, 'R');

    // Output the PDF and force download
    $pdf->Output('order_summary.pdf', 'D');
    echo '<script>window.location.href = "Home.php";</script>';
}

// Check if the cart data is available
if (isset($_GET['cart'])) {
    $orderData = json_decode(urldecode($_GET['cart']), true);
    generatePDF($orderData);
} else {
    // Handle the case when cart data is not available
    echo "No order data found!";
}
?>
