<?php

class UserCard
{
	private $conn;

	public function __construct()
	{
		$this->conn = Database::getInstance()->getConnection();
	}

	public function addUserCard($user_id, $card_id)
	{
		if (empty($user_id)) {
			throw new InvalidArgumentException('User ID cannot be empty');
		}
		if (empty($card_id)) {
			throw new InvalidArgumentException('Card ID cannot be empty');
		}

		$stmt = $this->conn->prepare("INSERT INTO user_cards (user_id, card_id) VALUES (:user_id, :card_id)");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':card_id', $card_id);
		$stmt->execute();
	}

	public function isCardInUserList($user_id, $card_id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM user_cards WHERE user_id = :user_id AND card_id = :card_id");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':card_id', $card_id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getNextUserCard($userId)
	{
		$stmt = $this->conn->prepare("SELECT uc.*, c.question, c.answer 
																	FROM user_cards uc
																	JOIN cards c ON uc.card_id = c.id
																	WHERE uc.user_id = :user_id AND uc.next_review <= CURRENT_DATE
																	ORDER BY uc.next_review ASC LIMIT 1");
		$stmt->bindParam(':user_id', $userId);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getRandomUserCard($userId)
	{
		$stmt = $this->conn->prepare("SELECT uc.*, c.question, c.answer 
                                  FROM user_cards uc
                                  JOIN cards c ON uc.card_id = c.id
                                  WHERE uc.user_id = :user_id
                                  ORDER BY RAND() LIMIT 1");
		$stmt->bindParam(':user_id', $userId);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function calculateNextReview($userCard, $quality)
	{
		$interval = $userCard['interval'];
		$easeFactor = $userCard['ease_factor'];
		$repetitions = $userCard['repetitions'];

		if ($quality >= 3) {
			if ($repetitions == 0) {
				$interval = 1;
			} elseif ($repetitions == 1) {
				$interval = 6;
			} else {
				$interval = ceil($interval * $easeFactor);
			}
			$repetitions += 1;
		} else {
			$interval = 1;
			$repetitions = 0;
		}

		$easeFactor += (0.1 - (5 - $quality) * (0.08 + (5 - $quality) * 0.02));
		if ($easeFactor < 1.3) {
			$easeFactor = 1.3;
		}

		return [
			'interval' => $interval,
			'ease_factor' => $easeFactor,
			'repetitions' => $repetitions,
			'next_review' => date('Y-m-d', strtotime("+$interval days")),
		];
	}

	public function updateUserCard($id, $interval, $next_review, $repetitions, $ease_factor, $last_review_result)
	{
		$stmt = $this->conn->prepare("UPDATE user_cards 
                                  SET `interval` = :interval, next_review = :next_review, last_reviewed = CURRENT_DATE, repetitions = :repetitions, ease_factor = :ease_factor, last_review_result = :last_review_result 
                                  WHERE id = :id");
		$stmt->bindParam(':interval', $interval);
		$stmt->bindParam(':next_review', $next_review);
		$stmt->bindParam(':repetitions', $repetitions);
		$stmt->bindParam(':ease_factor', $ease_factor);
		$stmt->bindParam(':last_review_result', $last_review_result);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	}


}