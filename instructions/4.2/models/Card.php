<?php

class Card
{
	private $conn;

	public function __construct()
	{
		$this->conn = Database::getInstance()->getConnection();
	}

	public function addCard($question, $answer)
	{
		if (empty($question)) {
			throw new InvalidArgumentException('Question cannot be empty');
		}
		if (empty($answer)) {
			throw new InvalidArgumentException('Answer cannot be empty');
		}

		$question = trim($question);
		$answer = trim($answer);

		$stmt = $this->conn->prepare("INSERT INTO cards (question, answer) VALUES (:question, :answer)");
		$stmt->bindParam(':question', $question);
		$stmt->bindParam(':answer', $answer);
		$stmt->execute();
	}

}
