<?php $title = 'User Registration'; ?>

<h1>Create an Account</h1>

<?php if (!empty($error)): ?>
	<div style="color: red;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/register.php" method="POST">
	<div>
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" required minlength="3">
	</div>
	<div>
		<label for="password">Password:</label>
		<input type="password" id="password" name="password" required minlength="6">
	</div>
	<div>
		<button type="submit">Register</button>
	</div>
</form>