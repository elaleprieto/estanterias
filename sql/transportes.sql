-- Table: transportes

-- DROP TABLE transportes;

CREATE TABLE transportes
(
  id SERIAL NOT NULL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  created TIMESTAMP DEFAULT NULL,
  modified TIMESTAMP DEFAULT NULL
);