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

				<td>
					<!-- Button to add to the user's list -->
					<a href="/add_to_user_list.php?card_id=<?= $card['id'] ?>">
						Add to My List
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>