use wideworldimporters;

-- alter de customers tabel om een niet unieke voor en achternaam toe te voegen

ALTER TABLE `customers` ADD `Email` VARCHAR(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL AFTER `ValidTo`, ADD UNIQUE (`Email`);
ALTER TABLE `customers` ADD `HashedPassword` LONGBLOB NULL DEFAULT NULL AFTER `Email`;

-- customer category goed zetten

DROP TABLE IF EXISTS `connectusers`;

INSERT INTO customercategories VALUES(10, 'Web shopper', 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59");

-- INSERTS voor shit fixen met database details.

INSERT INTO stateprovinces (StateProvinceID, StateProvinceCode, StateProvinceName, CountryID, SalesTerritory, LastEditedBy, ValidFrom, ValidTo) VALUES(54, "GR" , "Groningen", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),
(55, "FR" , "Friesland", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"), (56, "DR" , "Drenthe", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),(57, "OV" , "Overijssel", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),
(58, "FL" , "Flevoland", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"), (59, "GE" , "Gelderland", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),(60, "UT" , "Utrecht", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),
(61, "NH" , "Noord-Holland", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),(62, "ZH" , "Zuid-Holland", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),(63, "ZL" , "Zeeland", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),
(64, "NB" , "Noord-Braband", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59"),(65, "LI" , "Limburg", 153, "Plains", 1, "2014-01-01 00:00:00", "9999-12-31 23:59:59");


#bij een Where Like %% gebruik: '#--' UNION (SELECT * FROM account);  #
#bij een where like kan ook deze gebruk: '#--' UNION (SELECT TABLE_NAME, TABLE_SCHEMA, 1 FROM information_schema.tables);  #

insert into paymentmethods values (6,'PayPal',1,current_date(),'9999-12-31 23:59:59');
insert into paymentmethods values (5,'IDeal',1,current_date(),'9999-12-31 23:59:59');
