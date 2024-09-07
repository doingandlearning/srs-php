--

## Task 1: Create a Folder for SQL Files

### Explanation

We’ll first create a directory in the file tree where we’ll store our SQL files. This will help organize the project and make it easier to migrate the database later.

### Instructions

1. Open the built-in code editor.
2. Create a new folder in the file tree named `srs_database_setup`.

---

## Task 2: Create the `cards` Table

### Explanation

The `cards` table will store the actual flashcards, including the question, answer, and review scheduling information. Each flashcard will have a unique ID, a question, an answer, and fields to track when the card was last reviewed and when it should be reviewed next.

### Instructions

1. In the `srs_database_setup` folder, create a new file named `1_create_cards_table.sql`.
2. Add the following content to the file:

    ```sql
    -- 1_create_cards_table.sql

    USE srs;

    CREATE TABLE IF NOT EXISTS cards (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        last_reviewed DATE,
        next_review DATE,
        `interval` INT DEFAULT 1
    );
    ```

3. Save the file.
4. Open the **SQL Viewer** tab and run the file by executing the following command:

    ```bash
    mysql -u root -p < srs_database_setup/1_create_cards_table.sql
    ```

---

## Task 3: Create the `users` Table

### Explanation

The `users` table will store the credentials for each user. It will contain a unique `id` for each user, a `username`, and a `password`. The password will be stored as a hashed value to ensure security.

### Instructions

1. In the `srs_database_setup` folder, create a new file named `2_create_users_table.sql`.
2. Add the following content to the file:

    ```sql
    -- 2_create_users_table.sql

    USE srs;

    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    );
    ```

3. Save the file.
4. Run the file using the **SQL Viewer**:

    ```bash
    mysql -u root -p < srs_database_setup/2_create_users_table.sql
    ```

---

## Task 4: Create the `progress` Table

### Explanation

The `progress` table will track how each user interacts with each flashcard. It will record the number of times a card has been reviewed and how many times the user answered correctly. This table links `user_id` and `card_id` to track progress for individual users on individual flashcards.

### Instructions

1. In the `srs_database_setup` folder, create a new file named `3_create_progress_table.sql`.
2. Add the following content to the file:

    ```sql
    -- 3_create_progress_table.sql

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
    ```

3. Save the file.
4. Run the file using the **SQL Viewer**:

    ```bash
    mysql -u root -p < srs_database_setup/3_create_progress_table.sql
    ```

---

## Task 5: Verify the Tables

### Explanation

After creating the tables, we’ll check that they have been created correctly and verify the structure to ensure everything is set up as expected.

### Instructions

1. Open the **SQL Viewer** tab and log into MySQL:

    ```bash
    mysql -u root -p
    ```

2. Execute the following commands to verify the tables:

    ```sql
    USE srs;
    SHOW TABLES;
    ```

3. You should see the following output:

    ```
    +------------------+
    | Tables_in_srs    |
    +------------------+
    | cards            |
    | progress         |
    | users            |
    +------------------+
    ```

4. To check the structure of each table, execute the following commands:

    ```sql
    DESCRIBE cards;
    DESCRIBE users;
    DESCRIBE progress;
    ```

---

## Summary

In this part, we have:
- Created a new folder to store the SQL files.
- Created the `cards`, `users`, and `progress` tables.
- Verified that the tables were successfully created.

Each task was designed to help students both understand the process and easily execute the SQL commands using the built-in code editor and MySQL viewer.

---

This format breaks the tasks into clear **Explanation** and **Instructions** blocks, making it easy for students to follow along with both understanding and executing the steps. Let me know if this looks good!