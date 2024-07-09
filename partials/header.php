<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title><?= isset($title) ? htmlspecialchars($title) : 'Spaced Repetition System' ?></title>
	<style>
		nav ul li {
			list-style: none;
			margin-left: 4px;
			margin-right: 4px;
		}

		nav ul {
			display: flex;
			justify-content: center;
		}

		.title-h1 {
			text-align: center;
		}
	</style>
</head>

<body>
	<header>
		<h1 class="title-h1">Spaced Repetition System</h1>
		<nav>
			<ul>
				<li><a href="/views/add_card.php">Add New Card</a></li>
				<li><a href="/controllers/CardController.php?action=list">List All Cards</a></li>
				<li><a href="/controllers/CardController.php?action=getNext">Review Next Card</a></li>
				<li><a href="/controllers/CardController.php?action=progress">Track Progress</a></li>
				<li><a href="/logout.php">Logout</a></li>
			</ul>
		</nav>
	</header>
	<main>