<?php

class UserCard
{
	private $conn;

	public function __construct()
	{
		$this->conn = Database::getInstance()->getConnection();
	}

	public function addUserCard($userId, $cardId)
	{
		$stmt = $this->conn->prepare("INSERT INTO user_cards (user_id, card_id) VALUES (:user_id, :card_id)");
		$stmt->bindParam(':user_id', $userId);
		$stmt->bindParam(':card_id', $cardId);
		$stmt->execute();
	}

	public function isCardInUserList($userId, $cardId)
	{
		$stmt = $this->conn->prepare("SELECT * FROM user_cards WHERE user_id = :user_id AND card_id = :card_id");
		$stmt->bindParam(':user_id', $userId);
		$stmt->bindParam(':card_id', $cardId);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getUserCardById($userId, $userCardId)
	{
		$stmt = $this->conn->prepare("SELECT uc.*, c.question, c.answer 
																		FROM user_cards uc
																		JOIN cards c ON uc.card_id = c.id
																		WHERE uc.user_id = :user_id AND uc.id = :id");
		$stmt->bindParam(':user_id', $userId);
		$stmt->bindParam(':id', $userCardId);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function updateUserCard($id, $interval, $next_review, $repetitions, $ease_factor, $last_review_result)
	{
		$stmt = $this->conn->prepare("UPDATE user_cards 
                                  SET interval = :interval, next_review = :next_review, last_reviewed = CURRENT_DATE, repetitions = :repetitions, ease_factor = :ease_factor, last_review_result = :last_review_result 
                                  WHERE id = :id");
		$stmt->bindParam(':interval', $interval);
		$stmt->bindParam(':next_review', $next_review);
		$stmt->bindParam(':repetitions', $repetitions);
		$stmt->bindParam(':ease_factor', $ease_factor);
		$stmt->bindParam(':last_review_result', $last_review_result);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
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
                                  ORDER BY RANDOM() LIMIT 1");
		$stmt->bindParam(':user_id', $userId);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	public function logProgress($userId, $cardId, $response)
	{
		$stmt = $this->conn->prepare("
				INSERT INTO progress (user_id, card_id, reviews, correct_answers)
				VALUES (:user_id, :card_id, 1, CASE WHEN :response = 'correct' THEN 1 ELSE 0 END)
				ON CONFLICT (user_id, card_id)
				DO UPDATE SET 
						reviews = progress.reviews + 1,
						correct_answers = progress.correct_answers + CASE WHEN EXCLUDED.correct_answers = 1 THEN 1 ELSE 0 END
		");
		$stmt->bindParam(':user_id', $userId);
		$stmt->bindParam(':card_id', $cardId);
		$stmt->bindParam(':response', $response);
		$stmt->execute();
	}
	public function getUserProgress($userId)
	{
		$stmt = $this->conn->prepare("SELECT c.question, c.answer, p.reviews, p.correct_answers
                                  FROM progress p
                                  JOIN cards c ON p.card_id = c.id
                                  WHERE p.user_id = :user_id");
		$stmt->bindParam(':user_id', $userId);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
