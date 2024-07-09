<?php $title = 'Review card'; ?>
<?php include '../partials/header.php'; ?>
<style>
	.answer-section,
	.feedback-section {
		display: none;
	}

	.question {
		font-size: 24px;
		color: blueviolet;
		margin-left: 12px;
	}

	#review-form {
		margin-left: 16px;
	}

	textarea {
		width: 50%;
	}

	label {
		margin-bottom: 4px;
		font-weight: bold;
	}

	#show-answer {
		width: 50%
	}

	.previous {
		margin: 16px;
		border: 1px solid black;
		border-radius: 4px;
		width: 30%;
		padding: 8px;
		text-align: center;
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
	<p class="question"><?= htmlspecialchars($userCard['question']) ?></p>
	<div class="previous">

		<p><strong>Last Reviewed:</strong> <?= htmlspecialchars($userCard['last_reviewed'] ?? 'Never') ?></p>
		<p><strong>Last Review Result:</strong> <?= htmlspecialchars($userCard['last_review_result'] ?? 'N/A') ?></p>
	</div>
	<form id="review-form" action="/controllers/CardController.php?action=review&id=<?= $userCard['id'] ?>" method="POST">
		<div>
			<label for="user-answer">Your Answer:</label>
		</div>
		<div>
			<textarea rows=4 name="user-answer" id="user-answer" required></textarea>
		</div>
		<div>
			<button id="show-answer" type="button" onclick="showAnswer()">Show Answer</button>
		</div>
		<div id="answer-section" class="answer-section">
			<p><strong>Correct Answer:</strong> <?= htmlspecialchars($userCard['answer']) ?></p>
		</div>
		<div id="feedback-section" class="feedback-section">
			<button type="submit" name="response" value="correct">Correct</button>
			<button type="submit" name="response" value="incorrect">Incorrect</button>
		</div>
	</form>
<?php else : ?>
	<p>No cards to review.</p>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>