<?php

class User
{
	private $conn;

	public function __construct()
	{
		$this->conn = Database::getInstance()->getConnection();
	}

	public function register($username, $password)
	{
		$error = '';

		// Validate username
		if (empty($username) || strlen($username) < 3) {
			$error = 'Username must be at least 3 characters long.';
		}
		// Validate password
		elseif (empty($password) || strlen($password) < 6) {
			$error = 'Password must be at least 6 characters long.';
		}

		if (!empty($error)) {
			return ['success' => false, 'error' => $error];
		}

		// Hash the password
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Insert user into the database
		$stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $hashedPassword);
		$stmt->execute();

		return ['success' => true];
	}

	public function login($username, $password)
	{
		// Fetch the user by username
		$stmt = $this->conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Verify the password
		if ($user && password_verify($password, $user['password'])) {
			return ['success' => true, 'user' => $user];
		}

		return ['success' => false, 'error' => 'Invalid username or password.'];
	}
}
<?php

class User
{
	private $conn;

	public function __construct()
	{
		$this->conn = Database::getInstance()->getConnection();
	}

	public function register($username, $password)
	{
		$error = '';

		// Validate username
		if (empty($username) || strlen($username) < 3) {
			$error = 'Username must be at least 3 characters long.';
		}
		// Validate password
		elseif (empty($password) || strlen($password) < 6) {
			$error = 'Password must be at least 6 characters long.';
		}

		if (!empty($error)) {
			return ['success' => false, 'error' => $error];
		}

		// Hash the password
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Insert user into the database
		$stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $hashedPassword);
		$stmt->execute();

		return ['success' => true];
	}

	public function login($username, $password)
	{
		// Fetch the user by username
		$stmt = $this->conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Verify the password
		if ($user && password_verify($password, $user['password'])) {
			return ['success' => true, 'user' => $user];
		}

		return ['success' => false, 'error' => 'Invalid username or password.'];
	}
}
