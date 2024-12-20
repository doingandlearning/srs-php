<?php $title = 'Login'; ?>

<h1>Login</h1>

<?php if (!empty($error)): ?>
	<div style="color: red;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/login.php" method="POST">
	<div>
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" required>
	</div>
	<div>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required>
	</div>
	<div>
		<button type="submit">Login</button>
	</div>
</form>