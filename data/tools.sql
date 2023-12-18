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

