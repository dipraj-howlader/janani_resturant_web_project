<!DOCTYPE html>
<html>
<head>
  <title>Restaurant Management - Modify Items</title>
  <style>
    body {
  font-family: Arial, sans-serif;
  background-color: #f1f1f1;
}

nav {
  background-color: #333;
  color: #fff;
  text-align:center;
}

nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

nav ul li {
  display: inline-block;
}

nav ul li a {
  display: block;
  padding: 15px;
  color: #fff;
  text-decoration: none;
}

.food-list-container {
  width: 800px;
  margin: 0 auto;
  margin-top: 50px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
  padding: 20px;
}

h2 {
  text-align: center;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 8px;
  text-align: left;
}

th {
  background-color: #333;
  color: #fff;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

.edit-button, .delete-button {
  padding: 6px 12px;
  background-color: #4CAF50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.delete-button {
  background-color: #f44336;
  margin-left: 10px;
}


.popup {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
}

.popup-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 400px;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 20px;
  color: #aaa;
  cursor: pointer;
}

textarea {
  height: 100px;
  resize: vertical;
}


.popup {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
}

.popup-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 400px;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 20px;
  color: #aaa;
  cursor: pointer;
}

h2 {
  margin-top: 0;
}

form {
  margin-top: 20px;
}

input[type="text"],
input[type="number"],
textarea {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}

input[type="file"] {
  margin-bottom: 10px;
}

button[type="submit"] {
  background-color: #4CAF50;
  color: #fff;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button[type="submit"]:hover {
  background-color: #45a049;
}


  </style>
</head>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Management - Modify Items</title>
    <style>
    </style>
</head>
<body>
<nav>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="dashboard.php">Add Item</a></li>
        <li><a href="modify.php">Modify Item</a></li>
    </ul>
</nav>

<?php
$conn = mysqli_connect("localhost", "root", "", "restaurant");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];
        $deleteSql = "DELETE FROM food_items WHERE id = $deleteId";
        if ($conn->query($deleteSql) === true) {
            header("Location: modify.php");
            exit;
        } else {
            echo "Error deleting item: " . $conn->error;
        }
    } elseif (isset($_POST['foodId'])) {
        $foodId = $_POST['foodId'];
        $foodName = $_POST['foodName'];
        $foodDescription = $_POST['description'];
        $foodPrice = $_POST['price'];

        $stmt = $conn->prepare("UPDATE food_items SET food_name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $foodName, $foodDescription, $foodPrice, $foodId);

        if ($stmt->execute()) {
            header("Location: modify.php");
            exit;
        } else {
            echo "Error updating item: " . $stmt->error;
        }

        $stmt->close();
    }
}

$sql = "SELECT * FROM food_items";
$result = $conn->query($sql);

$foodItems = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foodItems[] = $row;
    }
}

$conn->close();
?>

<div class="food-list-container">
    <h2>Food Items</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($foodItems as $foodItem): ?>
            <tr>
                <td><?php echo $foodItem["id"]; ?></td>
                <td><?php echo $foodItem["food_name"]; ?></td>
                <td><?php echo $foodItem["price"]; ?></td>
                <td><button class="edit-button" onclick="editFoodItem(<?php echo $foodItem["id"]; ?>, '<?php echo $foodItem["food_name"]; ?>', '<?php echo $foodItem["description"]; ?>', <?php echo $foodItem["price"]; ?>)">Edit</button></td>
                <td>
                    <form method="POST" action="modify.php">
                        <input type="hidden" name="delete_id" value="<?php echo $foodItem["id"]; ?>">
                        <button class="delete-button" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="edit-item-popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closeEditItemPopup()">&times;</span>
        <h2>Edit Item</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="food-id" name="foodId" value="">
            <input type="text" id="food-name" name="foodName" placeholder="Food Name" required>
            <input type="file" id="food-image" name="foodImage" accept="image/*">
            <textarea id="food-description" name="description" placeholder="Description" required></textarea>
            <input type="number" id="food-price" name="price" placeholder="Price" step="0.01" required>
            <button type="submit">Update</button>
        </form>
    </div>
</div>

<script>
    function editFoodItem(foodId, foodName, foodDescription, foodPrice) {
        document.getElementById("food-id").value = foodId;
        document.getElementById("food-name").value = foodName;
        document.getElementById("food-description").value = foodDescription;
        document.getElementById("food-price").value = foodPrice;
        document.getElementById("edit-item-popup").style.display = "block";
    }

    function closeEditItemPopup() {
        document.getElementById("edit-item-popup").style.display = "none";
    }

    function deleteFoodItem(foodId) {
        console.log("Delete food item with ID: " + foodId);
    }
</script>

</body>
</html>
