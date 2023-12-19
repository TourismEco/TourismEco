-- Normaliser des colonnes
UPDATE ecologienorm SET co2 = (co2 - MIN(co2)over()) / (MAX(co2)over() - MIN(co2)over());

-- Croissance
SELECT id_pays, annee, arriveesTotal,
    IF(@last_entry = 0, 0, round(((arriveesTotal - @last_entry) / @last_entry) * 100,2)) "growth rate",
    @last_entry := arriveesTotal AS tmp
    FROM
    (SELECT @last_entry := 0) x,
    (SELECT id_pays, annee, sum(arriveesTotal) arriveesTotal
    FROM tourisme
    WHERE id_pays = "FR"
    GROUP BY annee) y;

-- Création de la table continents
CREATE TABLE continents (id INT PRIMARY KEY, nom VARCHAR(20));
INSERT INTO continents VALUES (1,"Afrique"),(2,"Amérique du Nord"),(3,"Amérique du Sud"),
(4,"Asie"),(5,"Europe"),(6,"Océanie")

-- Création de la table guerre
CREATE TABLE guerre (id INT PRIMARY KEY, statut VARCHAR(25));
INSERT INTO guerre VALUES (0,'En paix'), (1,'En guerre'), (2,'Guerre de la drogue'), (3,'Guerre civile'), (4,'Terrorisme'), (5,'Guerre ethnique');

-- Test jointures globales
SELECT CASE WHEN ecologie.id_pays IS NULL THEN surete.id_pays ELSE ecologie.id_pays END AS id_pays,
CASE WHEN ecologie.annee IS NULL THEN surete.annee ELSE ecologie.annee END AS annee, 
co2, ges, pibParHab, cpi, gpi, arriveesTotal, departs
FROM ecologie
LEFT JOIN economie ON ecologie.id_pays = economie.id_pays AND ecologie.annee = economie.annee
LEFT JOIN tourisme ON economie.id_pays = tourisme.id_pays AND economie.annee = tourisme.annee
LEFT JOIN surete ON tourisme.id_pays = surete.id_pays AND tourisme.annee = surete.annee 
WHERE ecologie.id_pays = 'FR'

UNION ALL 
SELECT CASE WHEN ecologie.id_pays IS NULL THEN surete.id_pays ELSE ecologie.id_pays END AS id_pays,
CASE WHEN ecologie.annee IS NULL THEN surete.annee ELSE ecologie.annee END AS annee, 
co2, ges, pibParHab, cpi, gpi, arriveesTotal, departs
FROM ecologie
RIGHT JOIN economie ON ecologie.id_pays = economie.id_pays AND ecologie.annee = economie.annee
RIGHT JOIN tourisme ON economie.id_pays = tourisme.id_pays AND economie.annee = tourisme.annee
RIGHT JOIN surete ON tourisme.id_pays = surete.id_pays AND tourisme.annee = surete.annee
WHERE (ecologie.id IS NULL XOR tourisme.id IS NULL XOR surete.id IS NULL XOR economie.id) AND ecologie.id_pays = 'FR'

ORDER BY annee;

-- Solution jointures globales
SELECT allk.id_pays, allk.annee, co2, ges, pibParHab, cpi, gpi, arriveesTotal, departs
FROM (SELECT id_pays, annee FROM economie UNION 
      SELECT id_pays, annee FROM tourisme UNION
      SELECT id_pays, annee FROM surete UNION
      SELECT id_pays, annee FROM ecologie
     ) allk 
LEFT OUTER JOIN economie ON allk.id_pays = economie.id_pays AND allk.annee = economie.annee 
LEFT OUTER JOIN ecologie ON allk.id_pays = ecologie.id_pays AND allk.annee = ecologie.annee 
LEFT OUTER JOIN surete ON allk.id_pays = surete.id_pays AND allk.annee = surete.annee 
LEFT OUTER JOIN tourisme ON allk.id_pays = tourisme.id_pays AND allk.annee = tourisme.annee
WHERE allk.id_pays = 'FR'
ORDER BY allk.annee;

-- Passage des capitales en booléen
ALTER TABLE `villes` CHANGE COLUMN `capital` `capital` BOOLEAN NOT NULL ;
UPDATE villes SET capital = '1' WHERE capital = 'TRUE';
UPDATE villes SET capital = '0' WHERE capital = 'FALSE';

-- Suppression des aéroports qui n'ont pas de ville dans la table ville
DELETE FROM airports WHERE CONCAT(city,id_pays) NOT IN (SELECT CONCAT(nom,id_pays) FROM villes);

-- Suppression des routes qui n'ont pas d'aéroport dans la table aéroport
DELETE FROM routes WHERE dst_apid NOT IN (SELECT apid FROM airports) OR src_apid NOT IN (SELECT apid FROM airports);