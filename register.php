<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

	try {
		$stmt = Database::getInstance()->getConnection()->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		header('Location: login.php');
		exit();
	} catch (PDOException $e) {
		$error = $e->getMessage();
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title>Register</title>
</head>

<body>
	<h1>Register</h1>
	<?php if (isset($error)) : ?>
		<p style="color: red;"><?= htmlspecialchars($error) ?></p>
	<?php endif; ?>
	<form action="register.php" method="POST">
		<label for="username">Username:</label>
		<input type="text" name="username" id="username" required>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" required>
		<button type="submit">Register</button>
	</form>
	<p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>

</html>