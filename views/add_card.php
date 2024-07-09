<?php $title = 'Add a new card'; ?>
<?php include '../partials/header.php'; ?>
<h1>Add a New Card</h1>
<form action="/controllers/CardController.php?action=add" method="POST">
	<label for="question">Question:</label>
	<textarea name="question" id="question" required></textarea>
	<label for="answer">Answer:</label>
	<textarea name="answer" id="answer" required></textarea>
	<button type="submit">Add Card</button>
</form>

<?php include '../partials/footer.php'; ?>