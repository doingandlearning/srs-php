USE srs;

CREATE TABLE IF NOT EXISTS progress (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	card_id INT NOT NULL,
	reviews INT DEFAULT 0,
	correct_answers INT DEFAULT 0,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (card_id) REFERENCES cards(id)
);