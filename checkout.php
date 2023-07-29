<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .confirm-btn {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .confirm-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Order Details</h1>
    <table>
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['cart'])) {
                $cartData = json_decode(urldecode($_GET['cart']), true);
                $totalAmount = 0;
                foreach ($cartData as $foodId => $cartItem) {
                    $foodName = $cartItem['name'];
                    $quantity = $cartItem['quantity'];
                    $price = $cartItem['price'];
                    $total = $price * $quantity;
                    $totalAmount += $total;
                    echo '<tr>';
                    echo "<td>$foodName</td>";
                    echo "<td>$quantity</td>";
                    echo "<td>$price</td>";
                    echo "<td>$total</td>";
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td colspan="3"><strong>Total Amount:</strong></td>';
                echo "<td><strong>$totalAmount</strong></td>";
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <button class="confirm-btn">Confirm Order</button>

    <script>
        const confirmBtn = document.querySelector('.confirm-btn');
        confirmBtn.addEventListener('click', () => {
            const orderData = <?php echo json_encode($cartData); ?>;

            // Send AJAX request to generate_pdf.php
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'generate_pdf.php?cart=' + encodeURIComponent(JSON.stringify(orderData)), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Redirect to the generated PDF for download
                        window.location.href = 'generate_pdf.php?cart=' + encodeURIComponent(JSON.stringify(orderData));
                        window.location.href = 'Home.php';
                    } else {
                        console.error('Failed to generate PDF');
                        window.location.href = 'Home.php';
                    }
                }
            };
            xhr.send();
        });
    </script>
</body>
</html>
