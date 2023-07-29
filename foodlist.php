<?php

$conn =mysqli_connect("localhost","root","","restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM food_items";
$result = $conn->query($sql);


$foodCards = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foodName = $row['food_name'];
        $foodImage = $row['food_image'];
        $description = $row['description'];
        $price = $row['price'];
        $foodId = $row['id'];

        $foodCards .= '<div class="card">';
        $foodCards .= '<img src="./uploads/' . $foodImage . '" alt="' . $foodName . '">';
        $foodCards .= '<h3>' . $foodName . '</h3>';
        $foodCards .= '<p>' . $description . '</p>';
        $foodCards .= '<p>Price: $' . $price . '</p>';
        $foodCards .= '<button class="add-to-cart-btn" data-food-id="' . $foodId . '" data-food-name="' . $foodName . '" data-food-price="' . $price . '">Add to Cart</button>';
        $foodCards .= '</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Page</title>
    <style>
      .navbar {
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

.navbar ul li {
    display: inline;
    margin-right: 10px;
}

.navbar ul li a {
    color: #fff;
    text-decoration: none;
}

.food-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.card {
    width: 300px;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    margin: 10px;
    text-align: center;
}

.card img {
    width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 10px;
}

.card h3 {
    margin-bottom: 5px;
}

.card p {
    margin-bottom: 10px;
}

.card button {
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

.card button:hover {
    background-color: #555;
}
.cart {
            width: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            text-align: center;
            background-color: #B0C4DE;
            position: fixed;
            top: 50px;
            right: 1130px;
        }

        .cart h2 {
            margin-bottom: 10px;
        }

        .cart ul {
            list-style-type: none;
            padding: 0;
        }

        .cart li {
            margin-bottom: 5px;
            font-size: 15px;
        }

        .cart button {
            background-color: #333;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
        }

        .cart button:hover {
            background-color: #555;
        }

    </style>
</head>
<body>

    <nav class="navbar">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="foodlist.php">Food List</a></li>
            <li><a href="login.php">Dashboard</a></li>
            <li><a href="#">Reservation</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>


    <div class="food-cards">
        <?php echo $foodCards; ?>

    </div>
    <div class="cart">
        <h2>Cart</h2>
        <ul id="cart-items"></ul>
        <button id="checkout-btn">Checkout</button>
    </div>
    <script>
        const cartItems = {};

        function addToCart(foodId, foodName, price) {
            if (cartItems[foodId]) {
                cartItems[foodId].quantity += 1;
            } else {
                cartItems[foodId] = {
                    name: foodName,
                    price: price,
                    quantity: 1
                };
            }

            updateCartUI();
        }

        function updateCartUI() {
            const cartElement = document.getElementById('cart-items');
            cartElement.innerHTML = '';

            let totalAmount = 0;
            for (const foodId in cartItems) {
                const { name, price, quantity } = cartItems[foodId];
                totalAmount += price * quantity;

                const liElement = document.createElement('li');
                liElement.textContent = `${name} (${quantity}) - $${price.toFixed(2)}`;
                cartElement.appendChild(liElement);
            }

            const checkoutBtn = document.getElementById('checkout-btn');
            checkoutBtn.textContent = `Checkout - Total: $${totalAmount.toFixed(2)}`;
        }
        const checkoutBtn = document.getElementById('checkout-btn');
        checkoutBtn.addEventListener('click', () => {
        // Create a URL with cart data as query parameters
        const cartData = encodeURIComponent(JSON.stringify(cartItems));
        const checkoutUrl = `checkout.php?cart=${cartData}`;

        // Navigate to the checkout.php page
        window.location.href = checkoutUrl;
    });

        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const foodId = button.dataset.foodId;
                const foodName = button.dataset.foodName;
                const foodPrice = parseFloat(button.dataset.foodPrice);

                addToCart(foodId, foodName, foodPrice);
            });
        });
    </script>
</body>

</html>
