--
-- Base de datos ejemplo 2
--

DROP DATABASE IF EXISTS ejemplophp2;

CREATE DATABASE IF NOT EXISTS ejemplophp2
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_spanish_ci;

use ejemplophp2;

CREATE TABLE Clientes
(
   DNI VARCHAR(9) NOT NULL PRIMARY KEY,
   Nome VARCHAR(30) NOT NULL,
   Apelido1 VARCHAR(30) NOT NULL,
   Apelido2 VARCHAR(30) NOT NULL DEFAULT 'nonexiste',
   Nacemento DATE NOT NULL,
   Enderezo VARCHAR(100) NOT NULL,
   Telefono VARCHAR(9) NOT NULL
) ENGINE=INNODB;