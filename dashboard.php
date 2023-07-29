<!DOCTYPE html>
<html>
<head>
  <title>Restaurant Management Dashboard</title>
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

.add-item-container {
  width: 500px;
  margin: 0 auto;
  margin-top: 50px;
  padding: 20px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

h2 {
  text-align: center;
}

input[type="text"],
input[type="number"],
textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

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

  <div class="add-item-container">
    <h2>Add Item</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="foodName" placeholder="Food Name" required>
      <input type="file" name="foodImage" accept="image/*" required>
      <textarea name="description" placeholder="Description" required></textarea>
      <input type="number" name="price" placeholder="Price" step="0.01" required>
      <button type="submit">Add</button>
    </form>
  </div>
</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$foodName = $_POST['foodName'];
$foodImage = $_FILES['foodImage']['name'];
$foodImageTmp = $_FILES['foodImage']['tmp_name'];
$description = $_POST['description'];
$price = $_POST['price'];

$conn = mysqli_connect("localhost","root","","restaurant");

if(!$conn){
  die("Connection Failed: " . mysqli_connect_error());
}

$stmt = $conn->prepare("INSERT INTO food_items (food_name, food_image, description, price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $foodName, $foodImage, $description, $price);
$stmt->execute();

$targetDirectory = "uploads/"; 
$targetFile = $targetDirectory . basename($foodImage);
move_uploaded_file($foodImageTmp, $targetFile);

if ($stmt->affected_rows > 0) {
    echo "New food item added successfully.";
} else {
    echo "Error adding food item.";
}

$stmt->close();
$conn->close();
}
?>
