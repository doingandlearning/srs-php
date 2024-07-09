<?php $title = 'Review card'; ?>
<?php include '../partials/header.php'; ?>
<style>
	.answer-section,
	.feedback-section {
		display: none;
	}
</style>
<script>
	function showAnswer() {
		document.getElementById('answer-section').style.display = 'block';
		document.getElementById('feedback-section').style.display = 'block';
	}
</script>

<h1>Review Card</h1>
<?php if ($userCard) : ?>
	<p><?= htmlspecialchars($userCard['question']) ?></p>
	<form id="review-form" action="/controllers/CardController.php?action=review&id=<?= $userCard['id'] ?>" method="POST">
		<label for="user-answer">Your Answer:</label>
		<input type="text" name="user-answer" id="user-answer" required>
		<button type="button" onclick="showAnswer()">Show Answer</button>
		<div id="answer-section" class="answer-section">
			<p><strong>Correct Answer:</strong> <?= htmlspecialchars($userCard['answer']) ?></p>
		</div>
		<div id="feedback-section" class="feedback-section">
			<button type="submit" name="response" value="correct">Correct</button>
			<button type="submit" name="response" value="incorrect">Incorrect</button>
		</div>
	</form>
	<p><strong>Last Reviewed:</strong> <?= htmlspecialchars($userCard['last_reviewed'] ?? 'Never') ?></p>
	<p><strong>Last Review Result:</strong> <?= htmlspecialchars($userCard['last_review_result'] ?? 'N/A') ?></p>
<?php else : ?>
	<p>No cards to review.</p>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>