CREATE TABLE `user` (
`user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`username` varchar(50) NOT NULL,
`password` varchar(255) NOT NULL,
`email` varchar(50) NOT NULL,
`balance` double(11,2) NOT NULL DEFAULT "0",
`last_balance_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`is_active` bit(2) NOT NULL,
PRIMARY KEY (`user_id`) ,
UNIQUE INDEX `uq_username` (`username` ASC) USING BTREE,
UNIQUE INDEX `uq_email` (`email` ASC) USING BTREE
);

CREATE TABLE `transaction` (
`transaction_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`transaction_type_id` int(11) UNSIGNED NOT NULL,
`user_id` int(11) UNSIGNED NOT NULL,
`amount` double(11,2) NOT NULL,
`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`transaction_id`) 
);

CREATE TRIGGER `trigger_transaction_after` After INSERT ON `transaction` FOR EACH ROW UPDATE user SET user.balance = user.balance + NEW.amount, user.last_balance_update = CURRENT_TIMESTAMP WHERE NEW.user_id = user.user_id;
CREATE TRIGGER `trigger_transaction_after2` After UPDATE ON `transaction` FOR EACH ROW UPDATE user SET user.balance = user.balance + NEW.amount, user.last_balance_update = CURRENT_TIMESTAMP WHERE NEW.user_id = user.user_id;

CREATE TABLE `transaction_type` (
`transaction_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`name` varchar(50) NOT NULL,
`type` enum('income', 'outcome') NOT NULL,
PRIMARY KEY (`transaction_type_id`) ,
UNIQUE INDEX `uq_name` (`name` ASC) USING BTREE
);


ALTER TABLE `transaction` ADD CONSTRAINT `transaction_transaction_type_transaction_type_id` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_type` (`transaction_type_id`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `transaction` ADD CONSTRAINT `transaction_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

