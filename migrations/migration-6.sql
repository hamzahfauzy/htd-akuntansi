ALTER TABLE merchants ADD COLUMN debt_bill_account_id INT DEFAULT NULL;
ALTER TABLE merchants ADD COLUMN credit_bill_account_id INT DEFAULT NULL;
ALTER TABLE merchants ADD CONSTRAINT fk_merchants_debt_bill_account_id FOREIGN KEY (debt_bill_account_id) REFERENCES accounts(id) ON DELETE CASCADE;
ALTER TABLE merchants ADD CONSTRAINT fk_merchants_credit_bill_account_id FOREIGN KEY (credit_bill_account_id) REFERENCES accounts(id) ON DELETE CASCADE;