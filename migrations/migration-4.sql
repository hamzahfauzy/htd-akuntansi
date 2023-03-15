CREATE TABLE merchants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    debt_account_id INT NOT NULL,
    credit_account_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_merchants_debt_account_id FOREIGN KEY (debt_account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_merchants_credit_account_id FOREIGN KEY (credit_account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_merchants_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);