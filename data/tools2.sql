SELECT * FROM projet.airports;
SELECT * FROM villes;
SELECT * FROM routes;

UPDATE airports SET id_pays = "VI" WHERE id_pays = "Virgin Islands";
UPDATE airports SET id_pays = "MK" WHERE id_pays = "Macedonia";
UPDATE airports SET id_pays = "CI" WHERE id_pays = "Cote d'Ivoire";
UPDATE airports SET id_pays = "MM" WHERE id_pays = "Burma";
UPDATE airports SET id_pays = "CG" WHERE id_pays = "Congo (Brazzaville)";
UPDATE airports SET id_pays = "CD" WHERE id_pays = "Congo (Kinshasa)";
DELETE FROM airports WHERE id_pays = "Cocos (Keeling) Islands" OR id_pays = "Midway Islands" OR id_pays = "Palestine" OR id_pays = "West Bank";
DELETE FROM airports WHERE id_pays = "East Timor" OR id_pays = "Falkland Islands" OR id_pays = "Johnston Atoll" OR id_pays = "Svalbard" OR id_pays = "Wake Island";

DELETE FROM airports WHERE CONCAT(city,id_pays) NOT IN (SELECT CONCAT(nom,id_pays) FROM villes);
DELETE FROM routes WHERE dst_apid NOT IN (SELECT apid FROM airports) OR src_apid NOT IN (SELECT apid FROM airports);
DELETE FROM routes WHERE dst_apid IS NULL OR src_apid IS NULL;

SELECT * FROM planes WHERE `check` IS NULL AND `alter` IS NOT NULL;
SELECT * FROM planes;
SELECT *, length(equipment) FROM routes;
DELETE FROM routes WHERE length(equipment) = 0;
SELECT routes.*, airports.name FROM routes,airports WHERE routes.src_apid = apid AND routes.equipment LIKE "%736%";
SELECT * FROM planes WHERE iata = '77W';
DELETE FROM planes WHERE iata NOT IN ('SWM','MA6','M80','L4T','DH8','D1C','CRA','CR2', 'A81', '142', '141', '143', '146', 'CRJ', 'E70', 'M87', '319', '320', '100', 'SF3', 'ATP', '330', '737', 'DH3', 'SH6', '734', 'SU9', '73G', '321', 'AT7', '313', 'DH8', '777', '763', 'DHT', 'CNA', '73M', '733', 'EMB', 'BEH', '738', 'YK2', 'E90', 'M83', '735', '752', 'MA6', 'D28', 'F50', 'EM2', 'M82', '332', 'CNC', 'ER4', 'AT5', 'BE1', '732', '73H', '739', '73W', '333', '77W', '772', '736', 'DH4', 'SWM', 'CR7', 'CRK', '744', '340', 'E75', '767', '757', '787', 'M80', 'L4T', 'ERD', '380', '773', 'CR9', '32B', '762', '318', '346', '', '345', '73C', 'CRA', 'DH1', 'ERJ', '77L', 'E95', '788', 'M88', 'F70', '32A', 'AR8', 'M90', '717', '76W', '74Y', '764', '343', '388', '32S', '753', 'ATR', 'EMJ', 'AR1', '75W', 'J31', '747', 'S20', '310', 'F28', 'FRJ', '74N', '342', 'D1C', 'ER3', 'T20', 'A58', 'IL9', 'AT4', '74M', 'AB6', '73J', 'M11', '75T', 'BH2', 'J41', 'J32', 'I14', 'AN4', 'BNI', '74L', 'AB4', 'DHP', 'ARJ', 'DH2', 'M1F', '76F', '74E', 'D38', 'CN2', 'D93', '77X', 'DC9', '33X', '32C');
SELECT * FROM planes WHERE iata IN ('SWM','MA6','M80','L4T','DH8','D1C','CRA','CR2', 'A81', '142', '141', '143', '146', 'CRJ', 'E70', 'M87', '319', '320', '100', 'SF3', 'ATP', '330', '737', 'DH3', 'SH6', '734', 'SU9', '73G', '321', 'AT7', '313', 'DH8', '777', '763', 'DHT', 'CNA', '73M', '733', 'EMB', 'BEH', '738', 'YK2', 'E90', 'M83', '735', '752', 'MA6', 'D28', 'F50', 'EM2', 'M82', '332', 'CNC', 'ER4', 'AT5', 'BE1', '732', '73H', '739', '73W', '333', '77W', '772', '736', 'DH4', 'SWM', 'CR7', 'CRK', '744', '340', 'E75', '767', '757', '787', 'M80', 'L4T', 'ERD', '380', '773', 'CR9', '32B', '762', '318', '346', '', '345', '73C', 'CRA', 'DH1', 'ERJ', '77L', 'E95', '788', 'M88', 'F70', '32A', 'AR8', 'M90', '717', '76W', '74Y', '764', '343', '388', '32S', '753', 'ATR', 'EMJ', 'AR1', '75W', 'J31', '747', 'S20', '310', 'F28', 'FRJ', '74N', '342', 'D1C', 'ER3', 'T20', 'A58', 'IL9', 'AT4', '74M', 'AB6', '73J', 'M11', '75T', 'BH2', 'J41', 'J32', 'I14', 'AN4', 'BNI', '74L', 'AB4', 'DHP', 'ARJ', 'DH2', 'M1F', '76F', '74E', 'D38', 'CN2', 'D93', '77X', 'DC9', '33X', '32C');

UPDATE planes SET `check`=NULL WHERE `check` = 1;
UPDATE planes SET `alter` = '737' WHERE plid = 73;
UPDATE planes SET icao = 'A388' WHERE plid = 33;
DELETE FROM planes WHERE plid = 34;
DELETE FROM planes WHERE plid = 19;
DELETE FROM planes WHERE plid = 25;
DELETE FROM planes WHERE plid = 87;
DELETE FROM planes WHERE plid = 70;
DELETE FROM planes WHERE plid = 90;
DELETE FROM planes WHERE plid = 94;
DELETE FROM planes WHERE plid = 101;
DELETE FROM planes WHERE iata IS NULL;
DELETE FROM planes WHERE `check` IS NULL;

DELETE FROM routes WHERE rid = 52843;
DELETE FROM routes WHERE rid = 35838;
DELETE FROM routes WHERE rid = 9714;
DELETE FROM routes WHERE rid = 47520;
DELETE FROM routes WHERE rid = 38507;
DELETE FROM routes WHERE equipment = "BE1\r";
DELETE FROM routes WHERE equipment = "CN2\r";
DELETE FROM routes WHERE equipment = "CNA\r";
DELETE FROM routes WHERE equipment = "CNC\r";
SELECT equipment, substring(equipment,5,40) FROM routes WHERE equipment LIKE "CRJ%";
INSERT INTO planes VALUES (247,"Canadair Regioanl Jet 905","CRA","CRJ",NULL);
INSERT INTO planes VALUES (248,"Douglas DC-10-30","D1C","D10",NULL);
INSERT INTO planes VALUES (249,"Bombardier DHC-8 Q400","DH8","DH8",NULL);
INSERT INTO planes VALUES (250,"Aircraft Industries (LET) 410","L4T","T410",NULL);
INSERT INTO planes VALUES (251,"Douglas MD-80","M80","MD80",NULL);
INSERT INTO planes VALUES (252,"Xi'an MA60","MA6","MA6",NULL);
INSERT INTO planes VALUES (253,"Fairchild SA26","SWM","SWM",NULL);

CREATE TABLE planes_conso (id INT PRIMARY KEY AUTO_INCREMENT, id_plane INT, distance DOUBLE, fuel_burn DOUBLE, fuel_eff DOUBLE);
