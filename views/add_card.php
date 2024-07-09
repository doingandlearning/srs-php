<?php $title = 'Add a new card'; ?>
<?php include '../partials/header.php'; ?>

<style>
	form {
		width: 50%;
	}
</style>
<h1>Add a New Card</h1>
<form action="/controllers/CardController.php?action=add" method="POST">
	<div>

		<label for="question">Question:</label>
		<textarea name="question" id="question" required></textarea>
	</div>
	<div>

		<label for="answer">Answer:</label>
		<textarea name="answer" id="answer" required></textarea>
	</div>
	<div>

		<button type="submit">Add Card</button>
	</div>
</form>

<?php include '../partials/footer.php'; ?>