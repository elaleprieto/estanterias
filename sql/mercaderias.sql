-- Table: mercaderias

-- DROP TABLE mercaderias;

CREATE TABLE mercaderias
(
  id SERIAL NOT NULL PRIMARY KEY,
  cantidad INTEGER NOT NULL,
  cantidad_anterior INTEGER NOT NULL,
  observaciones text,
  movimiento INTEGER NOT NULL DEFAULT 0,
  created TIMESTAMP DEFAULT NULL,
  modified TIMESTAMP DEFAULT NULL,
  articulo_id INTEGER NOT NULL
);
