<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title>Spaced Repetition System</title>

	<style>
		.list {
			display: flex;
			flex-wrap: wrap;
			grid-template-columns: 2;
		}

		.list li {
			display: block;
			list-style: none;
			border: 1px black solid;
			width: 40%;
			margin: 12px;
			padding: 12px;
			text-align: center;
		}

		.logout {
			border: 1px black solid;
			width: 80%;
			justify-self: center;
			margin: 12px;
			padding: 12px;
			text-align: center;
		}
	</style>
</head>

<body>
	<h1>Welcome to the Spaced Repetition System</h1>
	<nav>
		<ul class="list">
			<li><a href="/add.php">Add New Card</a></li>
			<li><a href="/list.php">List All Cards</a></li>
			<li><a href="/review.php">Review Next Card</a></li>
			<li><a href="/progress.php">Track Progress</a></li>
		</ul>
		<div class="logout"><a href="logout.php">Logout</a></div>
	</nav>
</body>

</html>