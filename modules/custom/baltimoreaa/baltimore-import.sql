DROP TABLE IF EXISTS meeting_location;
SOURCE modules/custom/baltimoreaa/baltimore.sql;

DROP TABLE IF EXISTS anon_location;
CREATE TABLE anon_location
  SELECT DISTINCT
    mAdd1 AS name,
    mAdd2 AS street,
    mCity AS city,
    'MD' AS state,
    mZip AS zip,
    LEFT(mSpecial, INSTR(mSpecial, ',') - 1) AS latitude,
    SUBSTRING(mSpecial, INSTR(mSpecial, ',') + 1) AS longitude
  FROM meeting_directory;

DROP TABLE IF EXISTS anon_group;

DROP TABLE IF EXISTS anon_meeting;
