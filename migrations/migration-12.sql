CREATE TABLE bill_master (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    merchant_id INT NOT NULL,
    base_amount DOUBLE(11, 2) NOT NULL,
    total_amount DOUBLE(11, 2) NOT NULL,
    num_of_bills INT NOT NULL,
    start_at DATE NOT NULL,
    end_at DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bill_master_subject_id FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    CONSTRAINT fk_bill_master_merchant_id FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE CASCADE
);

ALTER TABLE bills ADD COLUMN bill_master_id INT DEFAULT NULL;
ALTER TABLE bills ADD CONSTRAINT fk_bills_bill_master_id FOREIGN KEY (bill_master_id) REFERENCES bill_master(id) ON DELETE CASCADE;
