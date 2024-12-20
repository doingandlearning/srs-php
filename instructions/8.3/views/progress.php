<?php $title = 'User Progress'; ?>

<h1>Your Progress</h1>
<?php if ($progress): ?>
	<table>
		<tr>
			<th>Question</th>
			<th>Answer</th>
			<th>Reviews</th>
			<th>Correct Answers</th>
		</tr>
		<?php foreach ($progress as $p): ?>
			<tr>
				<td><?= htmlspecialchars($p['question'] ?? 'N/A') ?></td>
				<td><?= htmlspecialchars($p['answer'] ?? 'N/A') ?></td>
				<td><?= htmlspecialchars($p['total_reviews'] ?? 0) ?></td>
				<td><?= htmlspecialchars($p['total_correct_answers'] ?? 0) ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>You have not reviewed any cards yet.</p>
<?php endif; ?>
<p><a href="/index.php">Back to Home</a></p>