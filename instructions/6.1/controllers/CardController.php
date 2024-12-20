<?php

require_once __DIR__ . '/../bootstrap.php';

class CardController
{
	private $cardModel;

	public function __construct($cardModel)
	{
		$this->cardModel = $cardModel;
	}

	public function add()
	{
		if (empty($_POST['question']) || empty($_POST['answer'])) {
			throw new InvalidArgumentException('Question and answer are required');
		}

		$question = trim($_POST['question']);
		$answer = trim($_POST['answer']);

		if ($question === '' || $answer === '') {
			throw new InvalidArgumentException('Question and answer are required');
		}

		$this->cardModel->addCard($question, $answer);

		return $this->redirect('list.php');
	}

	protected function redirect($location)
	{
		header("Location: " . $location);
		exit();
	}

	public function list()
	{
		return $this->cardModel->listCards();
	}
}

