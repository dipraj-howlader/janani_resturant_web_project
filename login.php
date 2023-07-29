<!DOCTYPE html>
<html>
<head>
  <title>Restaurant Management Login</title>
<style>
    body {
  font-family: Arial, sans-serif;
  background-color: #f1f1f1;
}

.login-container {
  width: 300px;
  margin: 0 auto;
  margin-top: 100px;
  padding: 20px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
}

h1 {
  text-align: center;
}

input[type="text"],
input[type="password"] {
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
  <div class="login-container">
    <h1>Restaurant Management Dashboard</h1>
    <form action="" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Log In</button>
    </form>
  </div>
</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  

$username = $_POST['username'];
$password = $_POST['password'];
$conn = mysqli_connect("localhost","root","","restaurant");

if(!$conn){
  die("Connection Failed: " . mysqli_connect_error());
}


$sql = "SELECT * from login where username='$username' and password = '$password' ";

$result = $conn->query($sql);

if ($result && $result->num_rows === 1) {
  $_SESSION['username'] = $username;
  $conn->close();
  echo '<script>window.location.href = "dashboard.php";</script>';
  exit;
} else {
  // Invalid username or password
  $conn->close();
  echo "Invalid username or password.";
}

    }

?>
