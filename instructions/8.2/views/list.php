<?php $title = 'Card List'; ?>

<h1>Available Cards</h1>

<table>
	<thead>
		<tr>
			<th>Question</th>
			<th>Answer</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cards as $card): ?>
			<tr>
				<td><?= htmlspecialchars($card['question']) ?></td>
				<td><?= htmlspecialchars($card['answer']) ?></td>
				<?php if (isset($_SESSION['user_id'])): ?>
					<?php if (isset($card['user_id'])): ?>
						<td>Added to your list</td>
					<?php else: ?>

						<td>
							<!-- Button to add to the user's list -->
							<a href="/add_to_user_list.php?card_id=<?= $card['id'] ?>">
								Add to My List
							</a>
						</td>
					<?php endif; ?>
				<?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>