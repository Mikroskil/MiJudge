CREATE DATABASE mikroskil_oj DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE USER
	'mijudge'@'%' IDENTIFIED BY 'MikroskilOnlineJudge',
	'mijudge'@'localhost' IDENTIFIED BY 'MikroskilOnlineJudge';

GRANT SELECT, INSERT, UPDATE, DELETE ON mikroskil_oj.* TO 'mijudge';

FLUSH PRIVILEGES;