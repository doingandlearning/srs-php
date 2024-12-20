<?php $title = 'Review Card'; ?>
<h1>Review Card</h1>
<?php if (!empty($userCard)): ?>
	<p class="question"><?= htmlspecialchars($userCard['question']) ?></p>
	<div class="previous">
		<p><strong>Last Reviewed:</strong> <?= htmlspecialchars($userCard['last_reviewed'] ?? 'Never') ?></p>
		<p><strong>Last Review Result:</strong> <?= htmlspecialchars($userCard['last_review_result'] ?? 'N/A') ?></p>
	</div>
	<form id="review-form" action="/review.php?id=<?= htmlspecialchars($userCard['id']) ?>" method="POST">
		<div>
			<label for="user-answer">Your Answer:</label>
		</div>
		<div>
			<textarea rows=4 name="user-answer" id="user-answer" required></textarea>
		</div>
		<div>
			<button id="show-answer" type="button" onclick="showAnswer()">Show Answer</button>
		</div>
		<div id="answer-section" style="display: none;">
			<p><strong>Correct Answer:</strong> <?= htmlspecialchars($userCard['answer']) ?></p>
		</div>
		<div id="feedback-section" class="feedback-section">
			<button type="submit" name="response" value="correct">Correct</button>
			<button type="submit" name="response" value="incorrect">Incorrect</button>
		</div>
	</form>

<?php else: ?>
	<p>No cards to review.</p>
<?php endif; ?>

<script>
	function showAnswer() {
		document.getElementById('answer-section').style.display = 'block';
		document.getElementById('feedback-section').style.display = 'block';
	}
</script>