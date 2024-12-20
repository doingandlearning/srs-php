USE srs;

CREATE TABLE IF NOT EXISTS user_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    card_id INT NOT NULL,
    `interval` INT DEFAULT 1,          -- Spaced repetition interval in days
    next_review DATE,                  -- Date of the next review
    last_reviewed DATE,                -- Date of the last review
    repetitions INT DEFAULT 0,         -- Count of repetitions for spaced repetition
    ease_factor FLOAT DEFAULT 2.5,     -- Ease factor for spaced repetition algorithm
    last_review_result ENUM('correct', 'incorrect') DEFAULT NULL,  -- Result of last review

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (card_id) REFERENCES cards(id)
);