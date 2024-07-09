<?php $title = 'User Progress'; ?>
<?php include '../partials/header.php'; ?>

<h1>Your Progress</h1>
<?php if ($progress) : ?>
	<table>
		<tr>
			<th>Question</th>
			<th>Answer</th>
			<th>Reviews</th>
			<th>Correct Answers</th>
		</tr>
		<?php foreach ($progress as $p) : ?>
			<tr>
				<td><?= htmlspecialchars($p['question']) ?></td>
				<td><?= htmlspecialchars($p['answer']) ?></td>
				<td><?= htmlspecialchars($p['reviews']) ?></td>
				<td><?= htmlspecialchars($p['correct_answers']) ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else : ?>
	<p>You have not reviewed any cards yet.</p>
<?php endif; ?>
<p><a href="/index.php">Back to Home</a></p>

<?php include '../partials/footer.php'; ?>