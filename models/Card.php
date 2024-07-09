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

	public function getCardById($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM cards WHERE id = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function listCards()
	{
		$stmt = $this->conn->prepare("SELECT * FROM cards");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLastInsertId()
	{
		return $this->conn->lastInsertId();
	}
}
