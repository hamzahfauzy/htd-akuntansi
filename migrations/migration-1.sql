ALTER TABLE subjects DROP FOREIGN KEY fk_subjects_group_id;
ALTER TABLE subjects DROP FOREIGN KEY fk_subjects_report_id;
ALTER TABLE subjects DROP COLUMN group_id;
ALTER TABLE subjects DROP COLUMN report_id;

CREATE TABLE subject_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    group_id INT NOT NULL,
    report_id INT NOT NULL,
    CONSTRAINT fk_subject_groups_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_subject_groups_group_id FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    CONSTRAINT fk_subject_groups_report_id FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);