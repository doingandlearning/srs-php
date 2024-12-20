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
		$stmt = $this->conn->prepare("INSERT INTO cards (question, answer) VALUES (:question, :answer)");
		$stmt->bindParam(':question', $question);
		$stmt->bindParam(':answer', $answer);
		$stmt->execute();
	}
}