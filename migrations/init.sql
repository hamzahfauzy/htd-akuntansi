CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE role_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    route_path VARCHAR(100) NOT NULL,
    CONSTRAINT fk_role_routes_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    CONSTRAINT fk_user_roles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE application (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(100) NOT NULL,
    execute_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ref_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    is_open VARCHAR(100) NOT NULL,
    is_active VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reports_ref_id FOREIGN KEY (ref_id) REFERENCES reports(id) ON DELETE SET NULL
);

CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    code VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    balance_position VARCHAR(100) NOT NULL,
    balance_amount DOUBLE(10, 2) NOT NULL,
    report_position VARCHAR(100) NOT NULL,
    budget_amount DOUBLE(10, 2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_accounts_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE,
    CONSTRAINT fk_accounts_parent_id FOREIGN KEY (parent_id) REFERENCES accounts(id) ON DELETE CASCADE
);

CREATE TABLE cash_flows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    account_id INT NOT NULL,
    cash_type VARCHAR(100) DEFAULT NULL,
    amount DOUBLE(10, 2) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cash_flows_account_id FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_cash_flows_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_groups_parent_id FOREIGN KEY (parent_id) REFERENCES groups(id) ON DELETE CASCADE,
    CONSTRAINT fk_groups_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    role_id INT NOT NULL,
    account_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    cash_type VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_modules_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_modules_account_id FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_modules_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);


CREATE TABLE module_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    group_id INT NOT NULL,
    CONSTRAINT fk_module_groups_module_id FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    CONSTRAINT fk_module_groups_group_id FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    group_id INT NOT NULL,
    report_id INT NOT NULL,
    code VARCHAR(100) DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_subjects_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_subjects_group_id FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    CONSTRAINT fk_subjects_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    module_id INT DEFAULT NULL,
    report_id INT NOT NULL,
    bill_code VARCHAR(100) DEFAULT NULL,
    description TEXT NOT NULL,
    amount DOUBLE(11,2) NOT NULL,
    notification_to VARCHAR(100) DEFAULT NULL,
    notification_date DATE DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bills_subject_id FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    CONSTRAINT fk_bills_module_id FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE SET NULL,
    CONSTRAINT fk_bills_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    report_id INT NOT NULL,
    transaction_code VARCHAR(100) NOT NULL,
    total DOUBLE(11,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_subject_id FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    CONSTRAINT fk_transactions_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

CREATE TABLE transaction_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    cash_flow_id INT NOT NULL,
    bill_id INT DEFAULT NULL,
    module_id INT DEFAULT NULL,
    amount DOUBLE(11,2) NOT NULL,
    description TEXT NOT NULL,
    CONSTRAINT fk_transaction_items_transaction_id FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    CONSTRAINT fk_transaction_items_bill_id FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE SET NULL,
    CONSTRAINT fk_transaction_items_module_id FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE SET NULL,
    CONSTRAINT fk_transaction_items_cash_flow_id FOREIGN KEY (cash_flow_id) REFERENCES cash_flows(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    target VARCHAR(100) NOT NULL,
    via TEXT NOT NULL,
    sent_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(100) DEFAULT NULL
);

CREATE TABLE account_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_id INT NOT NULL,
    account_source INT NOT NULL,
    account_target INT NOT NULL,
    cash_source VARCHAR(100) DEFAULT "Mutasi Kas",
    CONSTRAINT fk_account_settings_account_source FOREIGN KEY (account_source) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_account_settings_account_target FOREIGN KEY (account_target) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_account_settings_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);