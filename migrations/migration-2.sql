CREATE TABLE journals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    account_id INT NOT NULL,
    transaction_type VARCHAR(100) DEFAULT NULL,
    amount DOUBLE(11, 2) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_journals_account_id FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_journals_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);