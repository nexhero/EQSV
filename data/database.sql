
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;



-- Volcando estructura de base de datos para sismos
DROP DATABASE IF EXISTS `sismos`;
CREATE DATABASE IF NOT EXISTS `sismos` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `sismos`;



drop table if exists `epicentre`;
create table `epicentre`(
id int(11) not null auto_increment,
datetime datetime not null,
`latitude` double not null,
`longitude` double not null,
`description` varchar(250) not null,
`depth` double not null,
`richter` double not null,
`u_id` int(11),
primary key(`id`),
constraint `fk_uid` foreign key(`u_id`) references `places` (`ID`) on delete no action on update no action
);
-- create table if not exists `epicentre`(
-- `ID` int(11) not null auto_increment,
-- `datetime` datetime not null,

-- );

-- Volcando estructura para tabla sismos.depsv
DROP TABLE IF EXISTS `places`;
CREATE TABLE IF NOT EXISTS `places` (
`ID` int(11) NOT NULL AUTO_INCREMENT,
`placeName` varchar(30) NOT NULL COMMENT 'Nombre del departamento',
`countryID` int(11) not null comment 'belong to the country',
PRIMARY KEY (`ID`),
key `fk_countryID` (`countryID`),
constraint `fk_countryID` foreign key (`countryID`) references `countries` (`ID`) on delete no action on update no action
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='Departamentos de El Salvador';

-- Volcando datos para la tabla sismos.places: ~14 rows (aproximadamente)
DELETE FROM `places`;
/*!40000 ALTER TABLE `places` DISABLE KEYS */;
-- TODO: add the correct latitude and longitude area
INSERT INTO `places` (`ID`,`placeName`, `countryID`) VALUES
(1,'Ahuachapán',  1),
(2,'Santa Ana',   1),
(3,'Sonsonate',   1),
(4,'La Libertad', 1),
(5,'Chalatenango',1),
(6,'San Salvador',1),
(7,'Cuscatlán',   1),
(8,'La Paz',      1),
(9,'Cabañas',     1),
(10,'San Vicente', 1),
(11,'Usulután',    1),
(12,'Morazán',     1),
(13,'San Miguel',  1),
(14,'La Unión',   1),
(15,'Guatemala',   2),
(16,'Honduras',    3),
(17,'Nicaragua',4),
(18,'Panama',5),
(19,'Costa Rica',   6),
(20,'Mexico',   7);



-- create structure for countries table
drop table if exists `countries`;
create table if not exists `countries`(
`ID` int(11) not null auto_increment,
`countryName` varchar(45) not null comment 'Country Name',
primary key (`ID`)
) engine=InnoDB auto_increment=8 default charset=utf8 comment='Countries near to El Salvador';
delete from `countries`;
insert into `countries` (`ID`,`countryName`) values
(1, 'El Salvador'),
(2, 'Guatemala'),
(3, 'Honduras'),
(4, 'Nicaragua'),
(5, 'Panama'),
(6, 'Costa Rica'),
(7, 'Mexico');

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
