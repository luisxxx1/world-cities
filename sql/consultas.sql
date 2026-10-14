-- Pregunta 2 - Consultas en la base World


-- 1. Ciudades con 100.000 habitantes o menos
-- Resultado: 521 ciudades

SELECT ID, Name, CountryCode, District, Population
FROM world.city
WHERE Population <= 100000
ORDER BY Population DESC;


-- 2. Ciudades que empiezan con R
-- Resultado: 110 ciudades

SELECT COUNT(*) AS total_ciudades_con_r
FROM world.city
WHERE Name LIKE 'R%';


-- 3. Top 10 de ciudades donde se habla español

-- Versión A: todos los países donde se habla español
-- Aparecen ciudades de Estados Unidos, donde el español no es oficial

SELECT ci.Name AS ciudad, co.Name AS pais, ci.Population AS poblacion,
       cl.Percentage AS porcentaje_espanol, cl.IsOfficial AS es_oficial
FROM world.city ci
JOIN world.country co ON co.Code = ci.CountryCode
JOIN world.countrylanguage cl ON cl.CountryCode = co.Code
WHERE cl.Language = 'Spanish'
ORDER BY ci.Population DESC
LIMIT 10;

-- Versión B: solo países donde el español es oficial
-- Esta es la que responde mejor la pregunta

SELECT ci.Name AS ciudad, co.Name AS pais, ci.Population AS poblacion,
       cl.Percentage AS porcentaje_espanol, cl.IsOfficial AS es_oficial
FROM world.city ci
JOIN world.country co ON co.Code = ci.CountryCode
JOIN world.countrylanguage cl ON cl.CountryCode = co.Code
WHERE cl.Language = 'Spanish'
  AND cl.IsOfficial = 'T'
ORDER BY ci.Population DESC
LIMIT 10;