ALTER TABLE bills DROP FOREIGN KEY fk_bills_module_id;
ALTER TABLE bills DROP COLUMN module_id;
ALTER TABLE bills ADD COLUMN merchant_id INT DEFAULT NULL;
ALTER TABLE bills ADD CONSTRAINT fk_bills_merchant_id FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE CASCADE;
