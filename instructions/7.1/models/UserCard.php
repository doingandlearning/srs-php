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
}