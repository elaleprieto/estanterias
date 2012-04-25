-- Table: pedidos

--DROP TABLE pedidos;

CREATE TABLE pedidos
(
  id SERIAL NOT NULL PRIMARY KEY,
  -- numero INTEGER NOT NULL,
  estado BOOLEAN NOT NULL DEFAULT FALSE,
  b integer NOT NULL DEFAULT 0,
  contrarrembolso boolean NOT NULL DEFAULT false,
  cobinpro boolean NOT NULL DEFAULT false,
  created TIMESTAMP DEFAULT NULL,
  modified TIMESTAMP DEFAULT NULL,
  finalizado TIMESTAMP DEFAULT NULL,
  transporte_id integer NOT NULL DEFAULT 0,
  cliente_id INTEGER NOT NULL,
  observaciones text,
  iniciado timestamp without time zone,
  tiempo_preparacion real NOT NULL DEFAULT 0,
  controlado timestamp without time zone,
  tiempo_control real NOT NULL DEFAULT 0,
  prioridad integer NOT NULL DEFAULT 0,
  facturado timestamp without time zone,
  tiempo_facturacion real NOT NULL DEFAULT 0,
  embalado timestamp without time zone,
  despachado timestamp without time zone,
  tiempo_embalado real NOT NULL DEFAULT 0,
  tiempo_despacho real NOT NULL DEFAULT 0
);


-- SETEO DE CONTADOR EN 2
--SELECT pg_catalog.setval('pedidos_id_seq', 2, true);


-- INSERTS
--INSERT INTO pedidos(id, created, modified, cliente_id)
	VALUES (1, '2010-10-23 11:06:05', '2010-10-23 12:36:11', 1);



-- Agregar una columna (cliente_id) con DEFAULT NOT NULL
--ALTER TABLE pedidos ADD COLUMN cliente_id INTEGER;
-- UPDATE pedidos SET cliente_id=1 WHERE cliente_id=65;
--ALTER TABLE pedidos ALTER COLUMN cliente_id SET NOT NULL;


-- Eliminar una columna (numero)
--ALTER TABLE pedidos DROP COLUMN numero;
