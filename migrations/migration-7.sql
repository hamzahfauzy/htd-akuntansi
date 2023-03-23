ALTER TABLE transaction_items DROP FOREIGN KEY fk_transaction_items_module_id;
ALTER TABLE transaction_items DROP COLUMN module_id;
ALTER TABLE transaction_items ADD COLUMN merchant_id INT DEFAULT NULL;
ALTER TABLE transaction_items ADD CONSTRAINT fk_transaction_items_merchant_id FOREIGN KEY (merchant_id) REFERENCES merchants(id) ON DELETE CASCADE;

ALTER TABLE journals ADD COLUMN transaction_code VARCHAR(100) DEFAULT NULL;

ALTER TABLE transaction_items DROP FOREIGN KEY fk_transaction_items_cash_flow_id;
ALTER TABLE transaction_items DROP COLUMN cash_flow_id;

ALTER TABLE bills ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT "BELUM LUNAS";
ALTER TABLE bills ADD COLUMN remaining_payment DOUBLE(11,2) DEFAULT NULL;