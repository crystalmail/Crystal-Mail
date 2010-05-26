DROP SEQUENCE accounts_id_seq;
CREATE SEQUENCE accounts_id_seq;
DROP TABLE accounts;
CREATE TABLE accounts (
  aid integer DEFAULT nextval('accounts_id_seq'::text),
  account_dn varchar(128) NOT NULL,  
  account_id varchar(128) NOT NULL,
  account_pw varchar(128) NOT NULL,
  account_host varchar(128) NOT NULL,
  preferences text,
  user_id integer NOT NULL default '0',
  PRIMARY KEY (aid)
);

CREATE INDEX user_id_fk_accounts ON accounts (user_id);

ALTER TABLE accounts
  ADD CONSTRAINT accounts_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE CASCADE;
