<?php $title = 'List of cards'; ?>
<h1>List of Cards</h1>
<table>
	<tr>
		<th>Question</th>
		<th>Answer</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php foreach ($cards as $card): ?>
		<?php $userCard = $userCardModel->isCardInUserList($_SESSION['user_id'], $card['id']); ?>
		<tr>
			<td><?= htmlspecialchars($card['question']) ?></td>
			<td><?= htmlspecialchars($card['answer']) ?></td>
			<td>
				<?php if ($userCard): ?>
					Last Reviewed:
					<?= $userCard['last_reviewed'] ? htmlspecialchars($userCard['last_reviewed']) : 'Not yet reviewed' ?><br>
					Next Review: <?= $userCard['next_review'] ? htmlspecialchars($userCard['next_review']) : 'Not scheduled' ?>
				<?php else: ?>
					Not in your list
				<?php endif; ?>
			</td>
			<td>
				<?php if (!$userCard): ?>
					<a href="/controllers/CardController.php?action=addToUserList&card_id=<?= $card['id'] ?>">Add to my
						list</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>