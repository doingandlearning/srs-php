<?php $title = 'Card List'; ?>

<h1>Flashcard List</h1>

<table border="1" cellpadding="10" cellspacing="0">
	<thead>
		<tr>
			<th>ID</th>
			<th>Question</th>
			<th>Answer</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!empty($cards)): ?>
			<?php foreach ($cards as $card): ?>
				<tr>
					<td><?= htmlspecialchars($card['id']) ?></td>
					<td><?= htmlspecialchars($card['question']) ?></td>
					<td><?= htmlspecialchars($card['answer']) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="3">No flashcards available.</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>