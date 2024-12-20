USE srs;

CREATE TABLE IF NOT EXISTS cards (
	id INT AUTO_INCREMENT PRIMARY KEY,
	question TEXT NOT NULL,
	answer TEXT NOT NULL,
	last_reviewed DATE,
	next_review DATE,
	`interval` INT DEFAULT 1
);