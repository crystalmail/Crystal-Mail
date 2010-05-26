DROP TABLE accounts;
CREATE TABLE 'accounts' (
   'aid' INTEGER PRIMARY KEY ASC,
   'account_dn' VARCHAR(128) NOT NULL,
   'account_id' VARCHAR(128) NOT NULL,
   'account_pw' VARCHAR(128) NOT NULL,
   'account_host' VARCHAR(128) NOT NULL,
   'preferences' TEXT,
   'user_id' UNSIGNED INTEGER(10) NOT NULL default '0',
   CONSTRAINT 'accounts_ibfk_1' FOREIGN KEY ('user_id') REFERENCES 'users'  
('user_id') ON DELETE
CASCADE ON UPDATE CASCADE
);