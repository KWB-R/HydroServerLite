-- phpMyAdmin SQL Dump
-- version 3.4.10.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2012 at 09:44 AM
-- Server version: 5.1.63
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `advenup1_odm`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `VariableID` int(11) NOT NULL,
  `DataValue` double NOT NULL,
  `CategoryDescription` text NOT NULL,
  KEY `VariableID` (`VariableID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `censorcodecv`
--

DROP TABLE IF EXISTS `censorcodecv`;
CREATE TABLE IF NOT EXISTS `censorcodecv` (
  `Term` varchar(50) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `censorcodecv`
--

INSERT INTO `censorcodecv` (`Term`, `Definition`) VALUES
('gt', 'mayor que'),
('lt', 'menor que'),
('nc', 'no censurada'),
('nd', 'non-detect'),
('pnq', 'presente pero no cuantificado');

-- --------------------------------------------------------

--
-- Table structure for table `datatypecv`
--

DROP TABLE IF EXISTS `datatypecv`;
CREATE TABLE IF NOT EXISTS `datatypecv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `datatypecv`
--

INSERT INTO `datatypecv` (`Term`, `Definition`) VALUES
('Average', 'Los valores representan el promedio durante un intervalo de tiempo, tal como un caudal medio diario o la temperatura media diaria.'),
('Best Easy Systematic Estimator', 'Best Easy Systematic Estimator BES = (Q1 +2Q2 +Q3)/4.  Q1, Q2, y Q3 son el primer, segundo, y tercer cantiles. Ver Woodcock, F. and Engel, C., 2005: Operational Consensus Forecasts.Weather and Forecasting, 20, 101-111. (http://www.bom.gov.au/nmoc/bulletins/60/article_by_Woodcock_in_Weather_and_Forecasting.pdf) and Wonnacott, T. H., and R. J. Wonnacott, 1972: Introductory Statistics. Wiley, 510 pp.'),
('Categorical', 'Los valores son categóricos mas que cantidades valoradas como continuas. El mapeo de Valor valores a categorías se hace a traves de la tabla CategoryDefinitions.'),
('Constant Over Interval', 'Los valores son cantidades que pueden ser interpretadas como constantes todo el tiempo, o durante el intervalo de tiempo hasta una medida subsecuente de la misma variable en el mismo sitio.'),
('Continuous', 'Una cantidad especificada en un instante particular en el tiempo medido con suficiente frecuencia (pequeño espaciamiento) para ser interpretado como registro continuo del fenomeno.'),
('Cumulative', 'Los valores representan el valor acumulado de una variable medida o calculada hasta un instante dado de tiempo, tal como el volumen de flujo acumulado o la precipitación acumulada.'),
('Incremental', 'Los valores representan el valor incremental de una variable a lo largo de un intervalo de tiempo, tal como el volumen de flujo acumulado o la precipitación acumulada.'),
('Maximum', 'Los valores son los valores máximos que occurren en un tiempo durante un intervalo de tiempo, tal como el caudal máximo anual o la temperatura máxima diaria.'),
('Median', 'Los valores representan  la mediana a lo largo de un intervalo de tiempo, tal como la mediana del caudal diario o la mediana d ela temperatura diaria.'),
('Minimum', 'Los valores son los valores mínimos que ocurren en algun tiempo durante un intervalo de tiempo, tal como el flujo bajo de 7-días para un año o la temperatura mínima diaria.'),
('Mode', 'Los valores son los valores que mas frecuentemente ocurren en algun tiempo durante un intervalo de tiempo, tal como la direccion del viento mas frecuente anualmente.'),
('Sporadic', 'El fenomeno es muestreado en un instante particular en el tiempo pero con una frecuencia que es muy gruesa para que se interprete el registro como continuo. Este seria el caso cuando el espaciamiento es significativamente mas largo que el soporte y la escala de tiempo de la fluctuación del fénomeno, tal como por ejemplo los muestreos infrecuentes (no frecuentes) de calidad de agua.'),
('StandardDeviation', 'Los valores representan la desviación estándar dde un conjunto de observaciones realizadas  a lo largo de un intervalo de tiempo. Se prefiere la desviación estándar calculada usando la fórmula no sesgada SQRT(SUM((Xi-media)^2)/(n-1)). La fórmula específica usada para calcular la varianza puede ser anotada en la descripción de los  métodos.'),
('Unknown', 'El tipo de datos es desconocido'),
('Variance', 'Los valores representan la varianza de un conjunto de observaciones realizadas a lo largo de un intervalo de tiempo. Se prefiere la varianza es calculada usando la fórmula no sesgada SUM((Xi-mean)^2)/(n-1).  La fórmula específica usada para calcular la varianza pude ser anotada en la descripción de los métodos.');

-- --------------------------------------------------------

--
-- Table structure for table `datavalues`
--

DROP TABLE IF EXISTS `datavalues`;
CREATE TABLE IF NOT EXISTS `datavalues` (
  `ValueID` int(11) NOT NULL AUTO_INCREMENT,
  `DataValue` double NOT NULL,
  `ValueAccuracy` double DEFAULT NULL,
  `LocalDateTime` datetime NOT NULL,
  `UTCOffset` double NOT NULL,
  `DateTimeUTC` datetime NOT NULL,
  `SiteID` int(11) NOT NULL,
  `VariableID` int(11) NOT NULL,
  `OffsetValue` double DEFAULT NULL,
  `OffsetTypeID` int(11) DEFAULT NULL,
  `CensorCode` varchar(50) NOT NULL DEFAULT 'nc',
  `QualifierID` int(11) DEFAULT NULL,
  `MethodID` int(11) NOT NULL DEFAULT '0',
  `SourceID` int(11) NOT NULL,
  `SampleID` int(11) DEFAULT NULL,
  `DerivedFromID` int(11) DEFAULT NULL,
  `QualityControlLevelID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ValueID`),
  UNIQUE KEY `DataValues_UNIQUE_DataValues` (`DataValue`,`ValueAccuracy`,`LocalDateTime`,`UTCOffset`,`DateTimeUTC`,`SiteID`,`VariableID`,`OffsetValue`,`OffsetTypeID`,`CensorCode`,`QualifierID`,`MethodID`,`SourceID`,`SampleID`,`DerivedFromID`,`QualityControlLevelID`),
  KEY `SiteID` (`SiteID`),
  KEY `SourceID` (`SourceID`),
  KEY `QualityControlLevelID` (`QualityControlLevelID`),
  KEY `OffsetTypeID` (`OffsetTypeID`),
  KEY `CensorCode` (`CensorCode`),
  KEY `VariableID` (`VariableID`),
  KEY `MethodID` (`MethodID`),
  KEY `QualifierID` (`QualifierID`),
  KEY `SampleID` (`SampleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



--
-- Table structure for table `derivedfrom`
--

DROP TABLE IF EXISTS `derivedfrom`;
CREATE TABLE IF NOT EXISTS `derivedfrom` (
  `DerivedFromID` int(11) NOT NULL,
  `ValueID` int(11) NOT NULL,
  KEY `ValueID` (`ValueID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `generalcategorycv`
--

DROP TABLE IF EXISTS `generalcategorycv`;
CREATE TABLE IF NOT EXISTS `generalcategorycv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `generalcategorycv`
--

INSERT INTO `generalcategorycv` (`Term`, `Definition`) VALUES
('Biota', 'Datos asociados con organismos biológicos'),
('Climate', 'Datos asociados con el clima, el tiempo, o procesos atmosféricos'),
('Geology', 'Datos asociados con la geología o los procesos geológicos'),
('Hydrology', 'Datos asociados con varibles o procesos hidrológicos'),
('Instrumentación', 'Datos asociados con instrumentación y propiedades de los instrumentos tales como voltaje de batería, temperaturas del data logger (registrador de datos), a menudo util para diagnóstco.'),
('Unknown', 'La categoría general es desconocida'),
('Water Quality', 'Datos asociados con variables o procesos  de calidad de agua');

-- --------------------------------------------------------

--
-- Table structure for table `groupdescriptions`
--

DROP TABLE IF EXISTS `groupdescriptions`;
CREATE TABLE IF NOT EXISTS `groupdescriptions` (
  `GroupID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupDescription` text,
  PRIMARY KEY (`GroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `GroupID` int(11) NOT NULL,
  `ValueID` int(11) NOT NULL,
  KEY `GroupID` (`GroupID`),
  KEY `ValueID` (`ValueID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `isometadata`
--

DROP TABLE IF EXISTS `isometadata`;
CREATE TABLE IF NOT EXISTS `isometadata` (
  `MetadataID` int(11) NOT NULL AUTO_INCREMENT,
  `TopicCategory` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Title` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Abstract` text NOT NULL,
  `ProfileVersion` varchar(255) NOT NULL DEFAULT 'Unknown',
  `MetadataLink` text,
  PRIMARY KEY (`MetadataID`),
  KEY `TopicCategory` (`TopicCategory`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `isometadata`
--

INSERT INTO `isometadata` (`MetadataID`, `TopicCategory`, `Title`, `Abstract`, `ProfileVersion`, `MetadataLink`) VALUES
(1, 'Unknown', 'Unknown', 'Unknown', 'Unknown', NULL);


-- --------------------------------------------------------

--
-- Table structure for table `labmethods`
--

DROP TABLE IF EXISTS `labmethods`;
CREATE TABLE IF NOT EXISTS `labmethods` (
  `LabMethodID` int(11) NOT NULL,
  `LabName` varchar(255) NOT NULL DEFAULT 'Unknown',
  `LabOrganization` varchar(255) NOT NULL DEFAULT 'Unknown',
  `LabMethodName` varchar(255) NOT NULL DEFAULT 'Unknown',
  `LabMethodDescription` text NOT NULL,
  `LabMethodLink` text,
  PRIMARY KEY (`LabMethodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `labmethods`
--

INSERT INTO `labmethods` (`LabMethodID`, `LabName`, `LabOrganization`, `LabMethodName`, `LabMethodDescription`, `LabMethodLink`) VALUES
(0, 'Unknown', 'Unknown', 'Unknown', 'Unknown', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `methods`
--

DROP TABLE IF EXISTS `methods`;
CREATE TABLE IF NOT EXISTS `methods` (
  `MethodID` int(11) NOT NULL AUTO_INCREMENT,
  `MethodDescription` text NOT NULL,
  `MethodLink` text,
  PRIMARY KEY (`MethodID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `methods`
--

INSERT INTO `methods` (`MethodID`, `MethodDescription`, `MethodLink`) VALUES
(1, 'No method specified', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `moss_users`
--

DROP TABLE IF EXISTS `moss_users`;
CREATE TABLE IF NOT EXISTS `moss_users` (
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `authority` enum('admin','teacher','student') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `moss_user_tokens`
--

DROP TABLE IF EXISTS `moss_user_tokens`;
CREATE TABLE IF NOT EXISTS `moss_user_tokens` (
  `TokenID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `token` varchar(100) NOT NULL,
  `issued` datetime NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`TokenID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Table structure for table `odmversion`
--

DROP TABLE IF EXISTS `odmversion`;
CREATE TABLE IF NOT EXISTS `odmversion` (
  `VersionNumber` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `odmversion`
--

INSERT INTO `odmversion` (`VersionNumber`) VALUES
('1.1.1');

-- --------------------------------------------------------

--
-- Table structure for table `offsettypes`
--

DROP TABLE IF EXISTS `offsettypes`;
CREATE TABLE IF NOT EXISTS `offsettypes` (
  `OffsetTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `OffsetunitsID` int(11) NOT NULL,
  `OffsetDescription` text NOT NULL,
  PRIMARY KEY (`OffsetTypeID`),
  KEY `OffsetunitsID` (`OffsetunitsID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qualifiers`
--

DROP TABLE IF EXISTS `qualifiers`;
CREATE TABLE IF NOT EXISTS `qualifiers` (
  `QualifierID` int(11) NOT NULL AUTO_INCREMENT,
  `QualifierCode` varchar(50) DEFAULT NULL,
  `QualifierDescription` text NOT NULL,
  PRIMARY KEY (`QualifierID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `qualifiers`
--

INSERT INTO `qualifiers` (`QualifierID`, `QualifierCode`, `QualifierDescription`) VALUES
(1, 'cs', 'Citizen Science');

-- --------------------------------------------------------

--
-- Table structure for table `qualitycontrollevels`
--

DROP TABLE IF EXISTS `qualitycontrollevels`;
CREATE TABLE IF NOT EXISTS `qualitycontrollevels` (
  `QualityControlLevelID` int(11) NOT NULL,
  `QualityControlLevelCode` varchar(50) NOT NULL,
  `Definition` varchar(255) NOT NULL,
  `Explanation` text NOT NULL,
  PRIMARY KEY (`QualityControlLevelID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qualitycontrollevels`
--

INSERT INTO `qualitycontrollevels` (`QualityControlLevelID`, `QualityControlLevelCode`, `Definition`, `Explanation`) VALUES
(-9999, '-9999', 'Unknown', 'El nivel de control de calidad es desconocido'),
(0, '0', 'Raw data', 'Datos crudos y no procesados y productos de datos que no han pasado por control de calidad. Dependiendo de la variable, tipo de dato, y sistema de transmisión de los datos, la data cruda puede estar disponible dentro los segundos o minutos despues que se ha realizado la medida. Ejemplos incluyen medidas en tiempo real de precipitación, caudal y calidad de agua.'),
(1, '1', 'Quality controlled data', 'Data con control de calidad que han pasado los procedimientos de seguridad de calidad tales como las rutinas de estimación del tiempo y la calibración del sensor o la inspección visual y la eliminación de errores obvios. Un ejemplo de esto son los registros de caudales publicados por el USGS siguiendo la desagregación a traves de los procedimientos de control de calidad del USGS.'),
(2, '2', 'Derived products', 'Productos derivados que requieren interpretación científica y técnical y puede incluir datos de sensores múltiples. Un ejemplo de esto es la precipitación promediada sobre la cuenca derivada de los pluviometros usando un procedimiento de interpolación.'),
(3, '3', 'Interpreted products', 'Productos interpretados que requieren un analisis e interpretación conducido por un investigador, interpretación basada en modelos usando otros datos y/o fuertes supuestos previos. Un ejemplo de esto es la precipitación media de la cuenca  derivada de la combinación de pluviómetros y data retornada de radares.'),
(4, '4', 'Knowledge products', 'Productos de Conocimiento que requiere interpretacion cientifica conducidad por un investigador y la integración multidisciplinaria de data e incluye interpretación basa en modelos usando otros datos y/o fuertes supuestos previos . Un ejemplo de esto es el porcentaje de agua vieja o nueva en un hidrograma inferido de un análisis de isotopos.');

-- --------------------------------------------------------

--
-- Table structure for table `samplemediumcv`
--

DROP TABLE IF EXISTS `samplemediumcv`;
CREATE TABLE IF NOT EXISTS `samplemediumcv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `samplemediumcv`
--

INSERT INTO `samplemediumcv` (`Term`, `Definition`) VALUES
('Air', 'Muestra tomada de la atmósfera'),
('Flowback water', 'Una mezcla de formación de agua e hidráulica fracturando inyectados derivandose desde aceite y pozos de gas previo a colocar pozos en producción'),
('Groundwater', 'Muestra tomada de agua localizada por debajo de la superficie de la tierra, tales como desde un pozo o manatial'),
('Municipal waste water', 'Muestras tomadas desde cruda municipal waste water stream.'),
('Not Relevante', 'Muestra medio no relevante en el contexto de las medidas'),
('Other', 'Muestra medio otra que aquellas contenida en el CV'),
('Precipitation', 'Muestra tomadas desde solida o precipitación liquida'),
('Production water', 'Fluidos producidos desde pozos durante oil o gas production which may include formation water, injected fluids, oil and gas.'),
('Sediment', 'Muestra tomada desde el sedimento por debajo la columna de agua'),
('Snow', 'Observación en, de o muestra tomada desde nieve'),
('Soil', 'Sample tomada desde el suelo'),
('So il air', 'Aire contenenida en los poros del suelo'),
('Soil water', 'el agua contenida en los poros del suelo'),
('Surface Water', 'Observación o muestra de superficie del agua tal como un cauce, rio, lago, laguna, embalse, oceano, etc.'),
('Tissue', 'Muestra tomada desde el tejido de un organismo biologico'),
('Unknown', 'El medio de muestra es desconocido');

-- --------------------------------------------------------

--
-- Table structure for table `samples`
--

DROP TABLE IF EXISTS `samples`;
CREATE TABLE IF NOT EXISTS `samples` (
  `SampleID` int(11) NOT NULL AUTO_INCREMENT,
  `SampleType` varchar(255) NOT NULL,
  `LabSampleCode` varchar(50) NOT NULL,
  `LabMethodID` int(11) NOT NULL,
  PRIMARY KEY (`SampleID`),
  KEY `LabMethodID` (`LabMethodID`),
  KEY `SampleType` (`SampleType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sampletypecv`
--

DROP TABLE IF EXISTS `sampletypecv`;
CREATE TABLE IF NOT EXISTS `sampletypecv` (
  `Term` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sampletypecv`
--

INSERT INTO `sampletypecv` (`Term`, `Definition`) VALUES
('Automated', 'Muestra recolectada usando un muestreador automatizado'),
('FD ', ' Digestión de Follaje'),
('FF ', ' Digestión de Piso de Bosque'),
('FL ', ' Lixiviado de Follaje'),
('Grab', 'Agarrar muestra'),
('GW ', ' Agua subterranea'),
('LF ', ' Digestión de basura de Otoño'),
('meteorological', 'tipo de muestra puede incluir un numeor de tipos de muestra medidas incluyendo temperatura, HR, radiación solar, precipitación, velocidad de viento, dirección de viento.'),
('No Sample', 'No hay muestra de laboratorio asociada con este medida'),
('PB  ', ' Volumen grueso de Precipitación'),
('PD ', ' Petri Dish (Deposición Seca)'),
('PE ', ' Evento de Precipitación'),
('PI ', ' Incremento de la Precipitación'),
('PW ', ' Precipitación semanal'),
('RE ', ' Extracción de roca'),
('SE ', ' Evento de escurrimiento'),
('SR ', ' Referencia estandar'),
('SS ', ' Sedimento suspendido del cauce'),
('SW ', ' Agua del cauce'),
('TE ', ' Evento de escurrimiento'),
('TI ', ' Incremento de escurrimiento'),
('TW ', ' Escurrimiento Semanal'),
('Unknown', 'El tipo de muestra es desconcido'),
('VE ', ' Evento de agua Vadosa'),
('VI ', ' Incremento de agua vadosa'),
('VW ', ' Agua Vadosa Semanal');

-- --------------------------------------------------------

--
-- Table structure for table `seriescatalog`
--

DROP TABLE IF EXISTS `seriescatalog`;
CREATE TABLE IF NOT EXISTS `seriescatalog` (
  `SeriesID` int(11) NOT NULL AUTO_INCREMENT,
  `SiteID` int(11) DEFAULT NULL,
  `SiteCode` varchar(50) DEFAULT NULL,
  `SiteName` varchar(255) DEFAULT NULL,
  `SiteType` varchar(255) DEFAULT NULL,
  `VariableID` int(11) DEFAULT NULL,
  `VariableCode` varchar(50) DEFAULT NULL,
  `VariableName` varchar(255) DEFAULT NULL,
  `Speciation` varchar(255) DEFAULT NULL,
  `VariableunitsID` int(11) DEFAULT NULL,
  `VariableunitsName` varchar(255) DEFAULT NULL,
  `SampleMedium` varchar(255) DEFAULT NULL,
  `ValueType` varchar(255) DEFAULT NULL,
  `TimeSupport` double DEFAULT NULL,
  `TimeunitsID` int(11) DEFAULT NULL,
  `TimeunitsName` varchar(255) DEFAULT NULL,
  `DataType` varchar(255) DEFAULT NULL,
  `GeneralCategory` varchar(255) DEFAULT NULL,
  `MethodID` int(11) DEFAULT NULL,
  `MethodDescription` text,
  `SourceID` int(11) DEFAULT NULL,
  `Organization` varchar(255) DEFAULT NULL,
  `SourceDescription` text,
  `Citation` text,
  `QualityControlLevelID` int(11) DEFAULT NULL,
  `QualityControlLevelCode` varchar(50) DEFAULT NULL,
  `BeginDateTime` datetime DEFAULT NULL,
  `EndDateTime` datetime DEFAULT NULL,
  `BeginDateTimeUTC` datetime DEFAULT NULL,
  `EndDateTimeUTC` datetime DEFAULT NULL,
  `ValueCount` int(11) DEFAULT NULL,
  PRIMARY KEY (`SeriesID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=207 ;


--
-- Table structure for table `sitepic`
--

DROP TABLE IF EXISTS `sitepic`;
CREATE TABLE IF NOT EXISTS `sitepic` (
  `siteid` varchar(50) NOT NULL,
  `picname` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`siteid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores different picture for various sites';


--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
CREATE TABLE IF NOT EXISTS `sites` (
  `SiteID` int(11) NOT NULL AUTO_INCREMENT,
  `SiteCode` varchar(50) NOT NULL,
  `SiteName` varchar(255) NOT NULL,
  `Latitude` double NOT NULL,
  `Longitude` double NOT NULL,
  `LatLongDatumID` int(11) NOT NULL DEFAULT '0',
  `SiteType` varchar(255) DEFAULT NULL,
  `Elevation_m` double DEFAULT NULL,
  `VerticalDatum` varchar(255) DEFAULT NULL,
  `LocalX` double DEFAULT NULL,
  `LocalY` double DEFAULT NULL,
  `LocalProjectionID` int(11) DEFAULT NULL,
  `PosAccuracy_m` double DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `County` varchar(255) DEFAULT NULL,
  `Comments` text,
  PRIMARY KEY (`SiteID`),
  UNIQUE KEY `AK_Sites_SiteCode` (`SiteCode`),
  KEY `VerticalDatum` (`VerticalDatum`),
  KEY `LatLongDatumID` (`LatLongDatumID`),
  KEY `LocalProjectionID` (`LocalProjectionID`),
  KEY `SiteType` (`SiteType`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Table structure for table `sitetypecv`
--

DROP TABLE IF EXISTS `sitetypecv`;
CREATE TABLE IF NOT EXISTS `sitetypecv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sitetypecv`
--

INSERT INTO `sitetypecv` (`Term`, `Definition`) VALUES
('Aggregate groundwater use', 'Un sitio agregado de Derivación/Retorno de agua subterranea representa un agregado de sitios específicos donde se extrae o se retorna agua subterranea, el cual esta definido por un area geografica o alguna otra caracteristica común. Un tipo de sitio agregado de agua subterranea es usado cuando no es posible o practico describir los sitios especificos como manantiales o como cualquier tipo de pozo incluyendo ''pozos múltiples'', o cuando la información de uso de agua esta solamente disponible para el agregado. Los sitios de agregados que cubren varios municipios deben ser codificados con 000 como codigo de municipio, o se puede crear un sitio de agregado para cada municipio.'),
('Aggregate surface-water-use', 'Un sitio agregado de Derivación/Retorno de agua superficial Diversion/Return representa un agregado de sitios específicos donde se deriva o se retorna agua superficial , el cual esta definido por un area geografica o alguna otra caracteristica común. Un tipo de sitio agregado de agua superficial es usado cuando no es posible o practico describir los sitios especificos como derivaciones, caídas de eagua, o sitios de aplicación de terreno, o cuando la información de uso de agua esta solamente disponible para el agregado. Los sitios de agregados que cubren varios municipios deben ser codificados con 000 como codigo de municipio, o se puede crear un sitio de agregado para cada municipio.'),
('Aggregate water-use establishment', 'El Uso de Agua Establecimiento agregada representa una clase de agregado de agua que utilizan los establecimientos o personas que se asocian con una ubicación geográfica específica y categoría de uso de agua, como todos los usuarios industriales ubicados dentro de un condado o de todos los usuarios domésticos de autoabastecimiento en un condado. La clase de agregado de agua que utilizan los establecimientos se identifica mediante el uso de agua de código nacional categoría y, opcionalmente, clasificados de acuerdo al Código de Clasificación Industrial Estándar System (código SIC) o norteamericano Código de Clasificación del sistema (código NAICS). Un establecimiento del uso del agua tipo de sitio agregada se utiliza cuando la información específica que se necesita para crear sitios para las instalaciones individuales o usuarios no está disponible o cuando no es conveniente almacenar la información específica de la base de datos. Reglas de entrada de datos que se aplican a los establecimientos de consumo de agua también se aplican a los establecimientos totales del uso del agua. Sitios agregados que abarcan varios condados deben codificarse con 000 como el código de condado, o un sitio de agregado se pueden crear para cada condado.'),
('Animal waste lagoon', 'Una instalación para el almacenamiento y / o el tratamiento biológico de los desechos de las operaciones ganaderas. Lagunas de desechos animales son estructuras de tierra que van desde pozos a grandes estanques, y contienen estiércol que se ha diluido con el agua de lavado de construcción, las precipitaciones y la escorrentía superficial. En lagunas de tratamiento, los residuos se convierte parcialmente licuado y estabilizado por la acción bacteriana antes de los residuos se eliminan en la tierra y el agua se descarga o reutilización.'),
('Atmosphere', 'Un sitio creado principalmente para medir las propiedades meteorológicas o la deposición atmosférica.'),
('Canal', 'Un curso de agua artificial para la navegación, drenaje o de riego mediante la conexión de dos o más cuerpos de agua, sino que es más grande que una zanja.'),
('Cave', 'Un espacio abierto natural dentro de una formación de roca lo suficientemente grande como para dar cabida a un ser humano. Una cueva puede tener una abertura hacia el exterior, es siempre bajo tierra, y, a veces sumergido. Cuevas comúnmente se producen por la disolución de rocas solubles, generalmente de piedra caliza, pero también pueden ser creados dentro de los huecos de las agregaciones de gran roca, en aberturas a lo largo de fallas sísmicas, y en formaciones de lava.'),
('Cistern', 'Una, depósito no presurizado artificial llenado por flujo por gravedad y se utiliza para el almacenamiento de agua. El depósito puede estar situado por encima de, sobre o bajo el nivel del suelo. El agua puede ser suministrada a partir de la desviación de precipitación, superficie, aguas subterráneas o fuentes.'),
('Coastal', 'Un sitio oceánico que se encuentra en alta mar más allá de la zona de mezcla de marea (estuario), pero lo suficientemente cerca de la costa que el investigador considera que la presencia de la costa para ser importante. Los sitios costeros son típicamente menos de tres millas náuticas de la costa.'),
('Collector or Ranney type well', 'Una galería de infiltración que consiste en uno o más laterales subterráneas a través de los que se recoge el agua subterránea y un cajón vertical desde el cual se elimina el agua subterránea. También conocido como "pozo horizontal". Estos pozos producen gran rendimiento con una pequeña reducción.'),
('Combined sewer', 'Un conducto subterráneo creado para transmitir drenaje y residuos en una planta de tratamiento de aguas residuales, corriente, depósito o vertedero.'),
('Ditch', 'Una excavación excavado artificialmente en el suelo, ya sea alineado o sin forro, para el transporte de agua para el drenaje o el riego, que es más pequeño que un canal.'),
('Diversion', 'Un sitio donde se retira o se desvía el agua de un cuerpo de agua superficial (por ejemplo, el punto en el que el extremo corriente arriba de un canal se cruza una corriente, o el punto donde el agua se extrae de un depósito). Incluye sitios donde el agua es bombeada para su uso en otros lugares.'),
('Estuary', 'Un entrante de la costa del mar o el océano, esp. la desembocadura de un río, donde el agua corriente se mezcla normalmente con agua corriente (modificado, Webster). La salinidad en los estuarios suele oscilar de 1 a 25 unidades prácticas de salinidad (ups), como valores oceánicas frente alrededor de 35 ups. Ver también: corriente de marea y costeras.'),
('Excavation', 'Una cavidad construido artificialmente en la tierra que es más profundo que el suelo (ver el orificio de suelo), más grande que un diámetro pocillos (ver bien y el agujero de prueba), y sustancialmente abiertas a la atmósfera. El diámetro de una excavación es típicamente similar o mayor que la profundidad. Las excavaciones incluyen excavaciones edificio de la fundación, los cortes de carretera y minas de superficie.'),
('Extensometer well', 'Un bien equipado para medir pequeños cambios en el espesor de los sedimentos penetradas, tales como los causados ​​por la extracción de aguas subterráneas o recarga.'),
('Facility', 'Un lugar que no ambiente donde se espera que las medidas ambientales a ser fuertemente influenciado por las actividades actuales o anteriores de los seres humanos. * Sitios identificados con una "instalación" tipo de sitio primario deben ser clasificados con uno de los tipos de sitios secundarios aplicables.'),
('Field, Pasture, Orchard, or Nursery', 'Una instalación-utilizando agua que se caracteriza por un área donde se cultivan las plantas para trasplantar, para su uso como poblaciones para las gemación e injerto, o para la venta. El agua de riego puede o no se puede aplicar.'),
('Glacier', 'Cuerpo de hielo terrestre que consiste en la nieve recristalizada acumulada en la superficie de la tierra y se mueve lentamente cuesta abajo (WSP-1541A) durante un período de años o siglos. Desde sitios glacial movimiento, el lat-larga de precisión de estos sitios suele ser grueso.'),
('Golf course', 'Un lugar de uso, ya sea pública o privada, donde se juega el juego del golf. Un campo de golf por lo general utiliza el agua para el riego. No se debe utilizar si el sitio es una característica o servicio hidrológico específico, pero puede ser utilizado sobre todo para los sitios de uso del agua.'),
('Groundwater drain', 'Una tubería subterránea o túnel a través del cual se desvía el agua subterránea artificialmente a las aguas superficiales con el fin de reducir la erosión o la reducción de la capa freática. Un drenaje está típicamente abierto a la atmósfera en la elevación más baja, en contraste con un pozo que está abierto en el punto más alto.'),
('Hydroelectric plant', 'Una instalación que genera energía eléctrica mediante la conversión de energía potencial del agua en energía cinética. Por lo general, los generadores de turbinas se encienden por el agua que cae.'),
('Hyporheic-zone well', 'Un bien permanente, el punto de accionamiento, o cualquier otro dispositivo destinado a probar una zona saturada en estrecha proximidad a una corriente.'),
('Interconnected wells', 'Colector o drenaje pozos conectados por un lateral subterráneo.'),
('Laboratory or sample-preparation area', 'Un sitio en el que se recogen algunos tipos de muestras de control de calidad, y donde se preparan los equipos y suministros para el muestreo ambiental. Los blancos de equipo se reúnen habitualmente en este tipo de sitio, al igual que las muestras de agua desionizada de producción local. Este tipo de sitio se utiliza cuando los datos están o no asociados a un sitio de recopilación de datos del medio ambiente único, o donde el abastecimiento de agua en blanco son designados por las oficinas del Centro con ID de estación única.'),
('Lake, Reservoir, Impoundment', 'El cuerpo interior de agua estancada dulce o salina que generalmente es demasiado profundo para permitir que la vegetación acuática sumergida a echar raíces en todo el cuerpo (cf: humedales). Este tipo de sitio incluye una parte expandida de un río, un embalse detrás de una presa, y una depresión natural o excavado que contiene un cuerpo de agua sin entrada de agua superficial y / o salida.'),
('Land', 'Una localización en la superficie de la tierra que no está normalmente saturado con agua. Sitios de la tierra son apropiados para muestreo de la vegetación, el flujo superficial de agua, o que miden las propiedades de la superficie terrestre, como la temperatura. (Ver también: Humedal).'),
('Landfill', 'Una ubicación normalmente seco en la superficie de la tierra donde los residuos sólidos son principalmente actualmente o anteriormente han sido agregada y, a veces cubierto de una capa de suelo. Ver también: Disposición de aguas residuales y de los residuos de la inyección así.'),
('Multiple wells', 'Un grupo de pozos que son bombeados a través de un único encabezado y para el que pocos o ningún dato acerca de los pocillos individuales están disponibles.'),
('Ocean', 'Sitio en el océano abierto, golfo, o al mar. (Ver también: Costa, estuario, y las corrientes de marea).'),
('Outcrop', 'La parte de una formación rocosa que aparece en la superficie de la tierra circundante.'),
('Outfall', 'Un sitio donde el agua o agua residual se devuelve a un cuerpo de agua superficial, por ejemplo, el punto en que las aguas residuales se devuelve a un arroyo. Típicamente, el extremo de descarga de un tubo de efluente.'),
('Pavement', 'Un sitio de superficie, donde la superficie de la tierra está cubierta por un material relativamente impermeable, tal como hormigón o asfalto. Sitios pavimento son típicamente parte de la infraestructura de transporte, como carreteras, aparcamientos, o pistas de aterrizaje.'),
('Playa', 'Una vegetación zona libre con suelo llano reseco,, compuesto de hojas finas, uniformemente estratificadas de arcilla fina, limo o arena, y representa la parte inferior de un desierto cuenca del lago de poca profundidad, completamente cerrada o sin escurrir en que el agua se acumula y es se evaporó rápidamente, por lo general dejando depósitos de sales solubles.'),
('Septic system', 'Un sitio dentro o en estrecha proximidad a un sistema de eliminación de aguas residuales del subsuelo que por lo general consiste en: (1) un tanque séptico donde la sedimentación de material sólido se produce, (2) un sistema de distribución que transfiere el fluido desde el tanque hasta (3) un sistema de lixiviación que se dispersa el efluente en el suelo.'),
('Shore', 'La tierra a lo largo de la orilla del mar, un lago o un río ancho que el investigador considera que la proximidad de la masa de agua que sea importante. Terreno adyacente a un depósito, lago, embalse o oceánica tipo de sitio se considera parte de la orilla cuando se incluye una playa o un banco entre las marcas superior e inferior de agua.'),
('Sinkhole', 'Un cráter se formó cuando el techo de una cueva se derrumba, por lo general se encuentra en zonas de piedra caliza. El agua superficial y la precipitación que entra en un sumidero por lo general se evapora o se infiltra en el suelo, en lugar de drenaje en una corriente.'),
('Soil hole', 'Una pequeña excavación en el suelo en la parte superior unos metros de la superficie de la tierra. Suelo generalmente incluye alguna materia orgánica de origen vegetal. Agujeros del suelo se crean para medir la composición y las propiedades del suelo. A veces sondas electrónicas se insertan en los agujeros del suelo para medir las propiedades físicas, y (o) la tierra extraída se analiza.'),
('Spring', 'Una ubicación en la que la tabla de agua se cruza con la superficie de la tierra, lo que resulta en un flujo natural de las aguas subterráneas a la superficie. Los manantiales pueden ser perennes, intermitentes o efímeros.'),
('Storm sewer', 'Un conducto subterráneo creado para transmitir de drenaje en un curso de agua o reservorio. Si la red de alcantarillado también transmite los productos de desecho líquidos, a continuación, se debe utilizar el "combinado de alcantarillas" tipo de sitio secundario.'),
('Stream', 'Un cuerpo de agua corriente en movimiento bajo flujo por gravedad en un canal definido. El canal puede ser totalmente natural, o alterado por prácticas de ingeniería a través de enderezamiento, dragado y (o) del revestimiento. Un canal totalmente artificial debe ser calificada con "canales" o "zanja" tipo de sitio secundario.'),
('Subsurface', 'Una ubicación por debajo de la superficie de la tierra, pero no un pozo, agujero del suelo, o excavación.'),
('Test hole not completed as a well', 'Un agujero sin entubar (o un entubado sólo temporalmente) que fue perforado por el agua, o para las pruebas geológicas o hidrogeológicas. Podrá estar equipado temporalmente con una bomba con el fin de hacer una prueba de bombeo, pero si el agujero se destruye después de finalizar el análisis, es todavía un agujero de prueba. Un agujero central perforado como parte de la minería o canteras trabajos de exploración debería estar en esta clase.'),
('Thermoelectric plant', 'Una instalación que utiliza el agua en la generación de electricidad a partir del calor. Típicamente generadores de turbinas son accionadas por vapor de agua. El calor puede ser causada por diversos medios, incluyendo la combustión, las reacciones nucleares, y procesos geotérmicos.'),
('Tidal stream', 'Una corriente de llegar donde el flujo está influenciada por la marea, pero donde la química del agua no está normalmente influenciada. Un sitio donde el agua del océano normalmente se mezcla con agua corriente debe ser codificado como un estuario.'),
('Tunnel, shaft, or mine', 'Un construida espacio abierto debajo de la superficie lo suficientemente grande como para dar cabida a un ser humano que no está abierto sustancialmente a la atmósfera y no es un pozo. La excavación puede haber sido para los minerales, transporte, u otros propósitos. Ver también: Excavación.'),
('Unsaturated zone', 'Un sitio equipado para medir las condiciones en el subsuelo más profundo que un agujero del suelo, pero por encima de la mesa de agua u otra zona de saturación.'),
('Volcanic vent', 'Vent de la que los gases volcánicos se escapan a la atmósfera. También conocido como fumarola.'),
('Waste injection well', 'Una instalación utilizada para transmitir los residuos industriales, aguas residuales domésticas, salmuera, drenaje de la mina, los residuos radiactivos, u otro fluido en una zona subterránea. Una prueba de aceite o de aguas profundas y convertido a la eliminación de residuos deben estar en esta categoría. Un pozo en el que se inyecta el agua dulce para recargar artificialmente el suministro thegroundwaterr o para presionar a una zona de producción de petróleo o gas mediante la inyección de un fluido que debe ser clasificado como un bien (no es una instalación de inyección pocillos), con la información adicional registrada bajo uso del Sitio.'),
('Wastewater land application', 'Un sitio donde se produce el vertido de aguas residuales en la tierra. Utilice "así los residuos de la inyección" para los sitios de eliminación de residuos subterráneos.'),
('Wastewater sewer', 'Un conducto subterráneo creado para transmitir los residuos domésticos, comerciales o industriales líquidos y semisólidos en una planta de tratamiento, corriente, depósito o vertedero. Si la red de alcantarillado de aguas pluviales también transmite, a continuación, se debe utilizar la "cloaca combinada" tipo de sitio secundario.'),
('Wastewater-treatment plant', 'Una instalación donde las aguas residuales es tratada para reducir las concentraciones de materiales suspendidos y disueltos (o) antes de la descarga o reutilización.'),
('Water-distribution system', 'Un sitio ubicado en algún lugar de una infraestructura de red que distribuye el agua tratada o no tratada para múltiples usuarios domésticos, industriales, institucionales, y (o) comerciales. Puede ser propiedad de un municipio o comunidad, un distrito de agua, o una empresa privada.'),
('Water-supply treatment plant', 'Una instalación donde el agua es tratada antes de su uso para el consumo o para otros fines.'),
('Water-use establishment', 'Un lugar de uso (un agua utilizando instalaciones que se asocia con la ubicación de un punto geográfico específico, como un negocio o un usuario industrial) que no se puede especificar con cualquier otro tipo secundario instalación. El uso del agua sitios lugar de uso son establecimientos como una fábrica, molino, tienda, almacén, granja, rancho, o el banco. Un sitio de lugar de uso se clasifica utilizando el código de categoría de uso de aguas nacionales (C39) y, opcionalmente, clasificados de acuerdo al Código de Clasificación Industrial Estándar System (código SIC) o norteamericano Código de Clasificación del sistema (código NAICS). Ver también: Agregado el uso del agua al establecimiento.'),
('Well', 'Un taladro o el eje construido en la tierra destinados a ser utilizados para localizar, de la muestra, o el desarrollo de las aguas subterráneas, petróleo, gas, o algún otro material subsuperficial. El diámetro de un pozo es típicamente mucho menor que la profundidad. Wells también se utilizan para recargar artificialmente aguas subterráneas o para presionar a las zonas de producción de petróleo y gas. Información adicional sobre los tipos específicos de pozos deben registrarse en los tipos de sitios secundarios o el uso de terreno del sitio. Pozos de eliminación de residuos subterráneos deben ser clasificados como residuos pozos de inyección.'),
('Wetland', 'Tierra donde la saturación de agua es el factor dominante en la determinación de la naturaleza del desarrollo del suelo y los tipos de comunidades vegetales y animales que viven en el suelo y en su superficie (Cowardin, diciembre de 1979). Los humedales se encuentran desde la tundra hasta los trópicos y en todos los continentes excepto la Antártida. Los humedales son áreas que están inundadas o saturadas por agua superficial o subterránea en una frecuencia y duración suficientes para apoyar, y que en circunstancias normales lo hacen de soporte, una prevalencia de vegetación típicamente adaptada a la vida en condiciones de saturación del suelo. Los humedales generalmente incluyen pantanos, ciénagas, pantanos y áreas similares. Los humedales pueden ser forestadas o deforestadas, y crearon natural o artificialmente.');

-- --------------------------------------------------------

--
-- Table structure for table `sources`
--

DROP TABLE IF EXISTS `sources`;
CREATE TABLE IF NOT EXISTS `sources` (
  `SourceID` int(11) NOT NULL AUTO_INCREMENT,
  `Organization` varchar(255) NOT NULL,
  `SourceDescription` text NOT NULL,
  `SourceLink` text,
  `ContactName` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Phone` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Email` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Address` varchar(255) NOT NULL DEFAULT 'Unknown',
  `City` varchar(255) NOT NULL DEFAULT 'Unknown',
  `State` varchar(255) NOT NULL DEFAULT 'Unknown',
  `ZipCode` varchar(255) NOT NULL DEFAULT 'Unknown',
  `Citation` text NOT NULL,
  `MetadataID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`SourceID`),
  KEY `MetadataID` (`MetadataID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Table structure for table `spatialreferences`
--

DROP TABLE IF EXISTS `spatialreferences`;
CREATE TABLE IF NOT EXISTS `spatialreferences` (
  `SpatialReferenceID` int(11) NOT NULL,
  `SRSID` int(11) DEFAULT NULL,
  `SRSName` varchar(255) NOT NULL,
  `IsGeographic` tinyint(1) DEFAULT NULL,
  `Notes` text,
  PRIMARY KEY (`SpatialReferenceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `spatialreferences`
--

INSERT INTO `spatialreferences` (`SpatialReferenceID`, `SRSID`, `SRSName`, `IsGeographic`, `Notes`) VALUES
(0, NULL, 'Unknown', 0, 'The spatial reference system is unknown'),
(1, 4267, 'NAD27', 1, NULL),
(2, 4269, 'NAD83', 1, NULL),
(3, 4326, 'WGS84', 1, NULL),
(4, 26703, 'NAD27 / UTM zone 3N', 0, NULL),
(5, 26704, 'NAD27 / UTM zone 4N', 0, NULL),
(6, 26705, 'NAD27 / UTM zone 5N', 0, NULL),
(7, 26706, 'NAD27 / UTM zone 6N', 0, NULL),
(8, 26707, 'NAD27 / UTM zone 7N', 0, NULL),
(9, 26708, 'NAD27 / UTM zone 8N', 0, NULL),
(10, 26709, 'NAD27 / UTM zone 9N', 0, NULL),
(11, 26710, 'NAD27 / UTM zone 10N', 0, NULL),
(12, 26711, 'NAD27 / UTM zone 11N', 0, NULL),
(13, 26712, 'NAD27 / UTM zone 12N', 0, NULL),
(14, 26713, 'NAD27 / UTM zone 13N', 0, NULL),
(15, 26714, 'NAD27 / UTM zone 14N', 0, NULL),
(16, 26715, 'NAD27 / UTM zone 15N', 0, NULL),
(17, 26716, 'NAD27 / UTM zone 16N', 0, NULL),
(18, 26717, 'NAD27 / UTM zone 17N', 0, NULL),
(19, 26718, 'NAD27 / UTM zone 18N', 0, NULL),
(20, 26719, 'NAD27 / UTM zone 19N', 0, NULL),
(21, 26720, 'NAD27 / UTM zone 20N', 0, NULL),
(22, 26721, 'NAD27 / UTM zone 21N', 0, NULL),
(23, 26722, 'NAD27 / UTM zone 22N', 0, NULL),
(24, 26729, 'NAD27 / Alabama East', 0, NULL),
(25, 26730, 'NAD27 / Alabama West', 0, NULL),
(26, 26732, 'NAD27 / Alaska zone 2', 0, NULL),
(27, 26733, 'NAD27 / Alaska zone 3', 0, NULL),
(28, 26734, 'NAD27 / Alaska zone 4', 0, NULL),
(29, 26735, 'NAD27 / Alaska zone 5', 0, NULL),
(30, 26736, 'NAD27 / Alaska zone 6', 0, NULL),
(31, 26737, 'NAD27 / Alaska zone 7', 0, NULL),
(32, 26738, 'NAD27 / Alaska zone 8', 0, NULL),
(33, 26739, 'NAD27 / Alaska zone 9', 0, NULL),
(34, 26740, 'NAD27 / Alaska zone 10', 0, NULL),
(35, 26741, 'NAD27 / California zone I', 0, NULL),
(36, 26742, 'NAD27 / California zone II', 0, NULL),
(37, 26743, 'NAD27 / California zone III', 0, NULL),
(38, 26744, 'NAD27 / California zone IV', 0, NULL),
(39, 26745, 'NAD27 / California zone V', 0, NULL),
(40, 26746, 'NAD27 / California zone VI', 0, NULL),
(41, 26747, 'NAD27 / California zone VII', 0, NULL),
(42, 26748, 'NAD27 / Arizona East', 0, NULL),
(43, 26749, 'NAD27 / Arizona Central', 0, NULL),
(44, 26750, 'NAD27 / Arizona West', 0, NULL),
(45, 26751, 'NAD27 / Arkansas North', 0, NULL),
(46, 26752, 'NAD27 / Arkansas South', 0, NULL),
(47, 26753, 'NAD27 / Colorado North', 0, NULL),
(48, 26754, 'NAD27 / Colorado Central', 0, NULL),
(49, 26755, 'NAD27 / Colorado South', 0, NULL),
(50, 26756, 'NAD27 / Connecticut', 0, NULL),
(51, 26757, 'NAD27 / Delaware', 0, NULL),
(52, 26758, 'NAD27 / Florida East', 0, NULL),
(53, 26759, 'NAD27 / Florida West', 0, NULL),
(54, 26760, 'NAD27 / Florida North', 0, NULL),
(55, 26761, 'NAD27 / Hawaii zone 1', 0, NULL),
(56, 26762, 'NAD27 / Hawaii zone 2', 0, NULL),
(57, 26763, 'NAD27 / Hawaii zone 3', 0, NULL),
(58, 26764, 'NAD27 / Hawaii zone 4', 0, NULL),
(59, 26765, 'NAD27 / Hawaii zone 5', 0, NULL),
(60, 26766, 'NAD27 / Georgia East', 0, NULL),
(61, 26767, 'NAD27 / Georgia West', 0, NULL),
(62, 26768, 'NAD27 / Idaho East', 0, NULL),
(63, 26769, 'NAD27 / Idaho Central', 0, NULL),
(64, 26770, 'NAD27 / Idaho West', 0, NULL),
(65, 26771, 'NAD27 / Illinois East', 0, NULL),
(66, 26772, 'NAD27 / Illinois West', 0, NULL),
(67, 26773, 'NAD27 / Indiana East', 0, NULL),
(68, 26774, 'NAD27 / Indiana West', 0, NULL),
(69, 26775, 'NAD27 / Iowa North', 0, NULL),
(70, 26776, 'NAD27 / Iowa South', 0, NULL),
(71, 26777, 'NAD27 / Kansas North', 0, NULL),
(72, 26778, 'NAD27 / Kansas South', 0, NULL),
(73, 26779, 'NAD27 / Kentucky North', 0, NULL),
(74, 26780, 'NAD27 / Kentucky South', 0, NULL),
(75, 26781, 'NAD27 / Louisiana North', 0, NULL),
(76, 26782, 'NAD27 / Louisiana South', 0, NULL),
(77, 26783, 'NAD27 / Maine East', 0, NULL),
(78, 26784, 'NAD27 / Maine West', 0, NULL),
(79, 26785, 'NAD27 / Maryland', 0, NULL),
(80, 26786, 'NAD27 / Massachusetts Mainland', 0, NULL),
(81, 26787, 'NAD27 / Massachusetts Island', 0, NULL),
(82, 26791, 'NAD27 / Minnesota North', 0, NULL),
(83, 26792, 'NAD27 / Minnesota Central', 0, NULL),
(84, 26793, 'NAD27 / Minnesota South', 0, NULL),
(85, 26794, 'NAD27 / Mississippi East', 0, NULL),
(86, 26795, 'NAD27 / Mississippi West', 0, NULL),
(87, 26796, 'NAD27 / Missouri East', 0, NULL),
(88, 26797, 'NAD27 / Missouri Central', 0, NULL),
(89, 26798, 'NAD27 / Missouri West', 0, NULL),
(90, 26801, 'NAD Michigan / Michigan East', 0, NULL),
(91, 26802, 'NAD Michigan / Michigan Old Central', 0, NULL),
(92, 26803, 'NAD Michigan / Michigan West', 0, NULL),
(93, 26811, 'NAD Michigan / Michigan North', 0, NULL),
(94, 26812, 'NAD Michigan / Michigan Central', 0, NULL),
(95, 26813, 'NAD Michigan / Michigan South', 0, NULL),
(96, 26903, 'NAD83 / UTM zone 3N', 0, NULL),
(97, 26904, 'NAD83 / UTM zone 4N', 0, NULL),
(98, 26905, 'NAD83 / UTM zone 5N', 0, NULL),
(99, 26906, 'NAD83 / UTM zone 6N', 0, NULL),
(100, 26907, 'NAD83 / UTM zone 7N', 0, NULL),
(101, 26908, 'NAD83 / UTM zone 8N', 0, NULL),
(102, 26909, 'NAD83 / UTM zone 9N', 0, NULL),
(103, 26910, 'NAD83 / UTM zone 10N', 0, NULL),
(104, 26911, 'NAD83 / UTM zone 11N', 0, NULL),
(105, 26912, 'NAD83 / UTM zone 12N', 0, NULL),
(106, 26913, 'NAD83 / UTM zone 13N', 0, NULL),
(107, 26914, 'NAD83 / UTM zone 14N', 0, NULL),
(108, 26915, 'NAD83 / UTM zone 15N', 0, NULL),
(109, 26916, 'NAD83 / UTM zone 16N', 0, NULL),
(110, 26917, 'NAD83 / UTM zone 17N', 0, NULL),
(111, 26918, 'NAD83 / UTM zone 18N', 0, NULL),
(112, 26919, 'NAD83 / UTM zone 19N', 0, NULL),
(113, 26920, 'NAD83 / UTM zone 20N', 0, NULL),
(114, 26921, 'NAD83 / UTM zone 21N', 0, NULL),
(115, 26922, 'NAD83 / UTM zone 22N', 0, NULL),
(116, 26923, 'NAD83 / UTM zone 23N', 0, NULL),
(117, 26929, 'NAD83 / Alabama East', 0, NULL),
(118, 26930, 'NAD83 / Alabama West', 0, NULL),
(119, 26932, 'NAD83 / Alaska zone 2', 0, NULL),
(120, 26933, 'NAD83 / Alaska zone 3', 0, NULL),
(121, 26934, 'NAD83 / Alaska zone 4', 0, NULL),
(122, 26935, 'NAD83 / Alaska zone 5', 0, NULL),
(123, 26936, 'NAD83 / Alaska zone 6', 0, NULL),
(124, 26937, 'NAD83 / Alaska zone 7', 0, NULL),
(125, 26938, 'NAD83 / Alaska zone 8', 0, NULL),
(126, 26939, 'NAD83 / Alaska zone 9', 0, NULL),
(127, 26940, 'NAD83 / Alaska zone 10', 0, NULL),
(128, 26941, 'NAD83 / California zone 1', 0, NULL),
(129, 26942, 'NAD83 / California zone 2', 0, NULL),
(130, 26943, 'NAD83 / California zone 3', 0, NULL),
(131, 26944, 'NAD83 / California zone 4', 0, NULL),
(132, 26945, 'NAD83 / California zone 5', 0, NULL),
(133, 26946, 'NAD83 / California zone 6', 0, NULL),
(134, 26948, 'NAD83 / Arizona East', 0, NULL),
(135, 26949, 'NAD83 / Arizona Central', 0, NULL),
(136, 26950, 'NAD83 / Arizona West', 0, NULL),
(137, 26951, 'NAD83 / Arkansas North', 0, NULL),
(138, 26952, 'NAD83 / Arkansas South', 0, NULL),
(139, 26953, 'NAD83 / Colorado North', 0, NULL),
(140, 26954, 'NAD83 / Colorado Central', 0, NULL),
(141, 26955, 'NAD83 / Colorado South', 0, NULL),
(142, 26956, 'NAD83 / Connecticut', 0, NULL),
(143, 26957, 'NAD83 / Delaware', 0, NULL),
(144, 26958, 'NAD83 / Florida East', 0, NULL),
(145, 26959, 'NAD83 / Florida West', 0, NULL),
(146, 26960, 'NAD83 / Florida North', 0, NULL),
(147, 26961, 'NAD83 / Hawaii zone 1', 0, NULL),
(148, 26962, 'NAD83 / Hawaii zone 2', 0, NULL),
(149, 26963, 'NAD83 / Hawaii zone 3', 0, NULL),
(150, 26964, 'NAD83 / Hawaii zone 4', 0, NULL),
(151, 26965, 'NAD83 / Hawaii zone 5', 0, NULL),
(152, 26966, 'NAD83 / Georgia East', 0, NULL),
(153, 26967, 'NAD83 / Georgia West', 0, NULL),
(154, 26968, 'NAD83 / Idaho East', 0, NULL),
(155, 26969, 'NAD83 / Idaho Central', 0, NULL),
(156, 26970, 'NAD83 / Idaho West', 0, NULL),
(157, 26971, 'NAD83 / Illinois East', 0, NULL),
(158, 26972, 'NAD83 / Illinois West', 0, NULL),
(159, 26973, 'NAD83 / Indiana East', 0, NULL),
(160, 26974, 'NAD83 / Indiana West', 0, NULL),
(161, 26975, 'NAD83 / Iowa North', 0, NULL),
(162, 26976, 'NAD83 / Iowa South', 0, NULL),
(163, 26977, 'NAD83 / Kansas North', 0, NULL),
(164, 26978, 'NAD83 / Kansas South', 0, NULL),
(165, 26979, 'NAD83 / Kentucky North', 0, NULL),
(166, 26980, 'NAD83 / Kentucky South', 0, NULL),
(167, 26981, 'NAD83 / Louisiana North', 0, NULL),
(168, 26982, 'NAD83 / Louisiana South', 0, NULL),
(169, 26983, 'NAD83 / Maine East', 0, NULL),
(170, 26984, 'NAD83 / Maine West', 0, NULL),
(171, 26985, 'NAD83 / Maryland', 0, NULL),
(172, 26986, 'NAD83 / Massachusetts Mainland', 0, NULL),
(173, 26987, 'NAD83 / Massachusetts Island', 0, NULL),
(174, 26988, 'NAD83 / Michigan North', 0, NULL),
(175, 26989, 'NAD83 / Michigan Central', 0, NULL),
(176, 26990, 'NAD83 / Michigan South', 0, NULL),
(177, 26991, 'NAD83 / Minnesota North', 0, NULL),
(178, 26992, 'NAD83 / Minnesota Central', 0, NULL),
(179, 26993, 'NAD83 / Minnesota South', 0, NULL),
(180, 26994, 'NAD83 / Mississippi East', 0, NULL),
(181, 26995, 'NAD83 / Mississippi West', 0, NULL),
(182, 26996, 'NAD83 / Missouri East', 0, NULL),
(183, 26997, 'NAD83 / Missouri Central', 0, NULL),
(184, 26998, 'NAD83 / Missouri West  ', 0, NULL),
(185, 4176, 'Australian Antarctic', 1, 'Datum Name: Australian Antarctic Datum 1998    Area of Use: Antarctica - Australian sector.    Datum Origin: No Data Available    Coord System: ellipsoidal    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(186, 4203, 'AGD84', 1, 'Datum Name: Australian Geodetic Datum 1984    Area of Use: Australia - Queensland (Qld), South Australia (SA), Western Australia (WA).    Datum Origin: Fundamental point: Johnson Memorial Cairn. Latitude: 25 deg 56 min 54.5515 sec S; Longitude: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: ellipsoidal    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(187, 4283, 'GDA94', 1, 'Datum Name: Geocentric Datum of Australia 1994    Area of Use: Australia - Australian Capital Territory (ACT); New South Wales (NSW); Northern Territory (NT); Queensland (Qld); South Australia (SA); Tasmania (Tas); Western Australia (WA); Victoria (Vic).    Datum Origin: ITRF92 at epoch 1994.0    Coord System: ellipsoidal    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(188, 5711, 'Australian Height Datum', 0, 'Datum Name: Australian Height Datum    Area of Use: Australia - Australian Capital Territory (ACT); New South Wales (NSW); Northern Territory (NT); Queensland; South Australia (SA); Western Australia (WA); Victoria.    Datum Origin: MSL 1966-68 at 30 gauges around coast.    Coord System: vertical    Ellipsoid Name: No Data Available    Data Source: EPSG'),
(189, 5712, 'Australian Height Datum (Tasmania)', 0, 'Datum Name: Australian Height Datum (Tasmania)    Area of Use: Australia - Tasmania (Tas).    Datum Origin: MSL 1972 at Hobart and Burnie.    Coord System: vertical    Ellipsoid Name: No Data Available    Data Source: EPSG'),
(190, 5714, 'Mean Sea Level Height', 0, 'Nombre del datum: Nivel medio del mar    Area de Uso: World.    Datum Origin: No Data Available    Coord System: vertical    Ellipsoid Name: No Data Available    Data Source: EPSG'),
(191, 5715, 'Mean Sea Level Depth', 0, 'Nombre del datum: Nivel medio del mar    Area de Uso: World.    Datum Origin: No Data Available    Coord System: vertical    Ellipsoid Name: No Data Available    Data Source: EPSG'),
(192, 20348, 'AGD84 / AMG zone 48', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 102 y 108 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(193, 20349, 'AGD84 / AMG zone 49', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 108 y 114 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(194, 20350, 'AGD84 / AMG zone 50', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 114 y 120 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(195, 20351, 'AGD84 / AMG zone 51', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 120 y 126 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(196, 20352, 'AGD84 / AMG zone 52', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 126 y 132 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(197, 20353, 'AGD84 / AMG zone 53', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 132 y 138 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(198, 20354, 'AGD84 / AMG zone 54', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 138 y 144 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(199, 20355, 'AGD84 / AMG zone 55', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 144 y 150 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(200, 20356, 'AGD84 / AMG zone 56', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 150 y 156 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(201, 20357, 'AGD84 / AMG zone 57', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 156 y 162 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(202, 20358, 'AGD84 / AMG zone 58', 0, 'Nombre del datum: datum geodésico Australiano 1984    Area de Uso: Australia - entre 162 y 168 grados Este.    Origen del datum: Fundamental point: Johnson Memorial Cairn. Latitud: 25 deg 56 min 54.5515 sec S; Longitud: 133 deg 12 min 30.0771 sec E (of Greenwich).    Coord System: Cartesian    Ellipsoid Name: Australian National Spheroid    Data Source: EPSG'),
(203, 28348, 'GDA94 / MGA zone 48', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 102 y 108 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(204, 28349, 'GDA94 / MGA zone 49', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 108 y 114 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(205, 28350, 'GDA94 / MGA zone 50', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 114 y 120 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(206, 28351, 'GDA94 / MGA zone 51', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 120 y 126 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(207, 28352, 'GDA94 / MGA zone 52', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 126 y 132 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(208, 28353, 'GDA94 / MGA zone 53', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 132 y 138 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(209, 28354, 'GDA94 / MGA zone 54', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 138 y 144 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(210, 28355, 'GDA94 / MGA zone 55', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 144 y 150 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(211, 28356, 'GDA94 / MGA zone 56', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 150 y 156 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(212, 28357, 'GDA94 / MGA zone 57', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 156 y 162 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(213, 28358, 'GDA94 / MGA zone 58', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994    Area de Uso: Australia - entre 162 y 168 grados Este.    Origen del datum: ITRF92 at epoch 1994.0    Coord System: Cartesian    Ellipsoid Name: GRS 1980    Data Source: EPSG'),
(214, 32748, 'WGS 84 / UTM zone 48S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 102 y 108 grados Este; hemisferio Sur. Indonesia.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(215, 32749, 'WGS 84 / UTM zone 49S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 108 y 114 grados Este; hemisferio Sur. Australia. Indonesia.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(216, 32750, 'WGS 84 / UTM zone 50S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 114 y 120 grados Este; hemisferio Sur. Australia. Indonesia.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(217, 32751, 'WGS 84 / UTM zone 51S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 120 y 126 grados Este; hemisferio Sur. Australia. East Timor. Indonesia.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(218, 32752, 'WGS 84 / UTM zone 52S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 126 y 132 grados Este; hemisferio Sur. Australia. East Timor. Indonesia.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(219, 32753, 'WGS 84 / UTM zone 53S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 132 y 138 grados Este; shemisferio Sur. Australia.  Indonesia.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(220, 32754, 'WGS 84 / UTM zone 54S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 138 y 144 grados Este; hemisferio Sur. Australia. Indonesia. Papua New Guinea.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(221, 32755, 'WGS 84 / UTM zone 55S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 144 y 150 grados Este; hemisferio Sur. Australia. Papua New Guinea.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(222, 32756, 'WGS 84 / UTM zone 56S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 150 y 156 grados Este; hemisferio Sur. Australia. Papua New Guinea.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(223, 32757, 'WGS 84 / UTM zone 57S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 156 y 162 grados Este; hemisferio Sur.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(224, 32758, 'WGS 84 / UTM zone 58S', 0, 'Nombre del datum: Sistema Geodésico Mundial 1984    Area de Uso: entre 162 y 168 grados Este; hemisferio Sur.    Origen del datum: Defined through a consistent set of station coordinates. These have changed with time: by 0.7m on 29/6/1994 [WGS 84 (G730)], a further 0.2m on 29/1/1997 [WGS 84 (G873)] and a further 0.06m on 20/1/2002 [WGS 84 (G1150)].    Coord System: Cartesian    Ellipsoid Name: WGS 84    Data Source: EPSG'),
(225, 3308, 'GDA94 / NSW Lambert', 0, 'Nombre del datum: Datum geocéntrico de Australia 1994 Area of Uso: Australia - New South Wales (NSW). Origen del datum: ITRF92 at epoch 1994.0  Ellipsoid Name: GRS 1980 Data Source: EPSG'),
(226, 2914, 'NAD_1983_HARN_StatePlane_Oregon_South_FIPS_3602_Feet_Intl', 0, 'I wonder if we can''t just load the entire list at:\nhttp://www.arcwebservices.com/v2006/help/index_Left.htm#StartTopic=support/pcs_name.htm#|SkinName=ArcWeb \ninto the CV??'),
(227, 2276, 'NAD83 / Texas North Central (ftUS)', 0, 'ESRI Name: NAD_1983_StatePlane_Texas_North_Central_FIPS_4202_Feet\nArea of Use: United States (USA) - Texas - counties of: Andrews; Archer; Bailey; Baylor; Borden; Bowie; Callahan; Camp; Cass; Clay; Cochran; Collin; Cooke; Cottle; Crosby; Dallas; Dawson; Delta; Denton; Dickens; Eastland; Ellis; Erath; Fannin; Fisher; Floyd; Foard; Franklin; Gaines; Garza; Grayson; Gregg; Hale; Hardeman; Harrison; Haskell; Henderson; Hill; Hockley; Hood; Hopkins; Howard; Hunt; Jack; Johnson; Jones; Kaufman; Kent; King; Knox; Lamar; Lamb; Lubbock; Lynn; Marion; Martin; Mitchell; Montague; Morris; Motley; Navarro; Nolan; Palo Pinto; Panola; Parker; Rains; Red River; Rockwall; Rusk; Scurry; Shackelford; Smith; Somervell; Stephens; Stonewall; Tarrant; Taylor; Terry; Throckmorton; Titus; Upshur; Van Zandt; Wichita; Wilbarger; Wise; Wood; Yoakum; Young.'),
(228, 0, 'HRAP Grid Coordinate System', 0, 'Datum Name: Hydrologic Rainfall Analysis Project (HRAP) grid coordinate system  Information: a polar stereographic projection true at 60');

-- --------------------------------------------------------

--
-- Table structure for table `speciationcv`
--

DROP TABLE IF EXISTS `speciationcv`;
CREATE TABLE IF NOT EXISTS `speciationcv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `speciationcv`
--

INSERT INTO `speciationcv` (`Term`, `Definition`) VALUES
('Al', 'Expresado como aluminio'),
('As', 'Expresado como arsénico'),
('B', 'Expresado como boro'),
('Ba', 'Expresado como bario'),
('Br', 'Expresado como bromo'),
('C', 'Expresado como carbon'),
('C2H6', 'Expresado como etano'),
('Ca', 'Expresado como calcio'),
('CaCO3', 'Expresado como carbonato de calcio'),
('Cd', 'Expresado como cadmio'),
('CH4', 'Expresado como metano'),
('Cl', 'Expresado como cloro'),
('Co', 'Expresado como cobalto'),
('CO2', 'Expresado como dioxido de carbon'),
('CO3', 'Expresado como carbonato'),
('Cr', 'Expresado como cromo'),
('Cu', 'Expresado como cobre'),
('delta 2H', 'Expresado como deuterio'),
('delta N15', 'Expresado como nitrogeno-15'),
('delta O18 ', 'Expresado como oxigeno-18'),
('EC', 'Expresado como conductividad electrica'),
('F', 'Expresado como fluor'),
('Fe', 'Expresado como hierro'),
('H2O', 'Expresado como agua'),
('HCO3', 'Expresado como carbonato de hidrogeno'),
('Hg', 'Expresado como mercurio'),
('K', 'Expresado como potasio'),
('Mg', 'Expresado como magnesio'),
('Mn', 'Expresado como manganeso'),
('Mo', 'Expresado como molibdeno'),
('N', 'Expresado como nitrogeno'),
('Na', 'Expresado como sodio'),
('NH4', 'Expresado como amonio'),
('Ni', 'Expresado como niquel'),
('NO2', 'Expresado como nitrito'),
('NO3', 'Expresado como nitrato'),
('Not Applicable', 'La especiation no es aplicable'),
('P', 'Expresado como fosforo'),
('Pb', 'Expresado como plomo'),
('pH', 'Expresado como pH'),
('PO4', 'Expresado como fosfato'),
('S', 'Expresado como Sulfuro'),
('Sb', 'Expresado como antimonio'),
('Se', 'Expresado como selenio'),
('Si', 'Expresado como silicio'),
('SiO2', 'Expresado como silicato'),
('SN', 'Expresado como estaño'),
('SO4', 'Expresado como Sulfato'),
('Sr', 'Expresado como estroncio'),
('TA', 'Expresado como alacalinidad total'),
('Ti', 'Expresado como titanio'),
('Tl', 'Expresado como talio'),
('U', 'Expresado como uranio'),
('Unknown', 'La especiación es desconocida'),
('V', 'Expresado como vanadio'),
('Zn', 'Expresado como zinc'),
('Zr', 'Expresado como zirconio');

-- --------------------------------------------------------

--
-- Table structure for table `topiccategorycv`
--

DROP TABLE IF EXISTS `topiccategorycv`;
CREATE TABLE IF NOT EXISTS `topiccategorycv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `topiccategorycv`
--

INSERT INTO `topiccategorycv` (`Term`, `Definition`) VALUES
('biota', 'Data asociada con biological organisms'),
('boundaries', 'Data asociada con frontera'),
('climatology/meteorology/atmosphere', 'Data asociada con climatologia, meteorologia, o la atmosfera'),
('economy', 'Data asociada con la economía'),
('elevation', 'Data asociada con elevación'),
('environment', 'Data asociada con el medio ambiente'),
('farming', 'Data asociada con la producción agricola'),
('geoscientificInformation', 'Data asociada con información geo-cientifica'),
('health', 'Data asociada con la salud'),
('imageryBaseMapsEarthCover', 'Data asociada con imagenes, mapas de base, o cobertura de la tierra'),
('inlandWaters', 'Data asociada con aguas continentales o dentro de tierra'),
('intelligenceMilitary', 'Data asociada con inteligencia o la milicia'),
('location', 'Data asociada con localización'),
('oceans', 'Data asociada con los oceanos'),
('planningCadastre', 'Data asociada con planificación o catastro'),
('society', 'Data asociada con la sociedad'),
('structure', 'Data asociada con estructura'),
('transportation', 'Data asociada con transportación'),
('Unknown', 'La categoria del topico es desconcida'),
('utilitiesCommunication', 'Data asociada con servicios o comunicación');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `unitsID` int(11) NOT NULL AUTO_INCREMENT,
  `unitsName` varchar(255) NOT NULL,
  `unitsType` varchar(255) NOT NULL,
  `unitsAbbreviation` varchar(255) NOT NULL,
  PRIMARY KEY (`unitsID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=349 ;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unitsID`, `unitsName`, `unitsType`, `unitsAbbreviation`) VALUES
(1, 'porcentaje', 'Adimensional', '%'),
(2, 'grado', 'Angulo', 'grados'),
(3, 'grados', 'Angulo', 'grados'),
(4, 'radianes', 'Angulo', 'radianes'),
(5, 'grados norte', 'Angulo', 'gradN'),
(6, 'grados sur', 'Angulo', 'gradS'),
(7, 'grados oeste', 'Angulo', 'gradW'),
(8, 'grados este', 'Angulo', 'gradE'),
(9, 'arcominuto', 'Angulo', 'arcmin'),
(10, 'arcosegundo', 'Angulo', 'arcsec'),
(11, 'estereorradián', 'Angulo', 'sr'),
(12, 'acre', 'Area', 'ac'),
(13, 'hectarea', 'Area', 'ha'),
(14, 'centimetros cuadrados', 'Area', 'cm^2'),
(15, 'pies cuadrados', 'Area', 'ft^2'),
(16, 'kilometros cuadrados', 'Area', 'km^2'),
(17, 'metros cuadrados', 'Area', 'm^2'),
(18, 'millas cuadradas', 'Area', 'mi^2'),
(19, 'hertz', 'Frecuencia', 'Hz'),
(20, 'darcy', 'Permeabilidad', 'D'),
(21, 'unidades termicas británicas', 'Energia', 'BTU'),
(22, 'caloría', 'Energía', 'cal'),
(23, 'erg', 'Energía', 'erg'),
(24, 'fuerza pie libra', 'Energy', 'lbf ft'),
(25, 'joule', 'Energía', 'J'),
(26, 'kilowatt hora', 'Energía', 'kW hr'),
(27, 'electronvolt', 'Energía', 'eV'),
(28, 'langleys por día', 'Flujo de Energía', 'Ly/d'),
(29, 'langleys por minuto', 'Flujo de Energía', 'Ly/min'),
(30, 'langleys por segundo', 'Flujo de Energía', 'Ly/s'),
(31, 'megajoules por square metro por día', 'Flujo de Energía', 'MJ/m^2 d'),
(32, 'watts por centimetro cuadrado', 'Flujo de Energía', 'W/cm^2'),
(33, 'watts por metros cuadrados', 'Flujo de Energía', 'W/m^2'),
(34, 'acre pies por año', 'Flujo', 'ac ft/yr'),
(35, 'pies cubico por segundo', 'Flujo', 'cfs'),
(36, 'metros cubicos por segundo', 'Flujo', 'm^3/s'),
(37, 'metros cubicos por día', 'Flujo', 'm^3/d'),
(38, 'galones por minuto', 'Flljo', 'gpm'),
(39, 'litros por segundo', 'Flujo', 'L/s'),
(40, 'millones galones por día', 'Flujo', 'MGD'),
(41, 'dina', 'Fuerza', 'dyn'),
(42, 'kilogramo de fuerza', 'Fuerza', 'kgf'),
(43, 'newton', 'Fuerza', 'N'),
(44, 'libra de fuerza', 'Fuerza', 'lbf'),
(45, 'kilo libra de fuerza', 'Fuerza', 'kip'),
(46, 'onza de fuerza', 'Fuerza', 'ozf'),
(47, 'centimetro   ', 'Longitud', 'cm'),
(48, 'pie internacional', 'Longitud', 'ft'),
(49, 'pulgada internacional', 'Longitud', 'in'),
(50, 'yarda internacional', 'Longitud', 'yd'),
(51, 'kilometro', 'Longitud', 'km'),
(52, 'metro', 'Longitud', 'm'),
(53, 'milla internacional', 'Longitud', 'mi'),
(54, 'milimetro', 'Longitud', 'mm'),
(55, 'micrón', 'Longitud', 'um'),
(56, 'angstrom', 'Longitud', ''),
(57, 'femtometer', 'Longitud', 'fm'),
(58, 'milla nautica', 'Longitud', 'nmi'),
(59, 'lumen', 'Luz', 'lm'),
(60, 'lux', 'Luz', 'lx'),
(61, 'lambert', 'Luz', 'La'),
(62, 'stilb', 'Luz', 'sb'),
(63, 'fot', 'Luz', 'ph'),
(64, 'langley', 'Luz', 'Ly'),
(65, 'gramo', 'Masa', 'g'),
(66, 'kilogramo', 'Masa', 'kg'),
(67, 'miligramo', 'Masa', 'mg'),
(68, 'microgramo', 'Masa', 'ug'),
(69, 'libra masa (avoirdupois)', 'Masa', 'lb'),
(70, 'slug', 'Masa', 'slug'),
(71, 'tonelada metrica', 'Masa', 'tonne'),
(72, 'grano', 'Masa', 'grano'),
(73, 'carat', 'Masa', 'car'),
(74, 'unidad de masa atomica', 'Masa', 'amu'),
(75, 'tonelada corta', 'Masa', 'ton'),
(76, 'BTU por hora', 'Potencia', 'BTU/hr'),
(77, 'pie libra de fuerza por segundo', 'Potencia', 'lbf/s'),
(78, 'caballo de potencia (shaft)', 'Potencia', 'hp'),
(79, 'kilowatt', 'Potencia', 'kW'),
(80, 'watt', 'Potencia', 'W'),
(81, 'voltamperio', 'Potencia', 'VA'),
(82, 'atmosferas', 'Presión/tensión', 'atm'),
(83, 'pascal', 'Presión/tensión', 'Pa'),
(84, 'pulgada de mercurio', 'Presión/tensión', 'pulg Hg'),
(85, 'pulgada de agua', 'Presión/tensión', 'pulg H2O'),
(86, 'milimetro de mercurio', 'Presión/tensión', 'mm Hg'),
(87, 'milimetro de agua', 'Presión/tensión', 'mm H2O'),
(88, 'centimetro de mercurio', 'Presión/tensión', 'cm Hg'),
(89, 'centimetro de agua', 'Presión/tensión', 'cm H2O'),
(90, 'milibar', 'Presió/Stress', 'mbar'),
(91, 'libra de fuerza por pulgada cuadrada', 'Presión/tensión', 'psi'),
(92, 'torr', 'Presión/tensión', 'torr'),
(93, 'barie', 'Presión/tensión', 'barie'),
(94, 'metros por pixel', 'Resolución', 'metros por pixel'),
(95, 'meters por meter', 'Escala', '-'),
(96, 'grados celsius', 'Temperatura', 'degC'),
(97, 'grados fahrenheit', 'Temperatura', 'degF'),
(98, 'grados rankine', 'Temperatura', 'degR'),
(99, 'grados kelvin', 'Temperatura', 'degK'),
(100, 'segundo', 'Time', 's'),
(101, 'milisegundo', 'Tiempo', 'millisec'),
(102, 'minuto', 'Tiempo', 'min'),
(103, 'hora', 'Tiempo', 'hr'),
(104, 'día', 'Tiempo', 'd'),
(105, 'semana', 'Tiempo', 'semana'),
(106, 'mes', 'Tiempo', 'mes'),
(107, 'año comun (365 días)', 'Tiempo', 'año'),
(108, 'año bisiesto (366 días)', 'Tiempo', 'año bis'),
(109, 'año Juliano (365.25 días)', 'Tiempo', 'año jul'),
(110, 'año Gregoriano (365.2425 días)', 'Tiempo', 'año greg'),
(111, 'centimetros por hour', 'Velocidad', 'cm/hr'),
(112, 'centimetros por second', 'Velocidad', 'cm/s'),
(113, 'pies por second', 'Velocidad', 'ft/s'),
(114, 'galones por day por pie cuadrado', 'Velocidad', 'gpd/ft^2'),
(115, 'pulgadas por hora', 'Velocidad', 'in/hr'),
(116, 'kilometros por hora', 'Velocidad', 'km/h'),
(117, 'metros por day', 'Velocidad', 'm/d'),
(118, 'metros per hora', 'Velocidad', 'm/hr'),
(119, 'metros por segundo', 'Velocidad', 'm/s'),
(120, 'millas por hora', 'Velocidad', 'mph'),
(121, 'millimetros por hora', 'Velocidad', 'mm/hr'),
(122, 'milla nautica por hora', 'Velocidad', 'nudos'),
(123, 'acre pie', 'Volumen', 'ac ft'),
(124, 'centimetro cubico', 'Volume', 'cc'),
(125, 'pies cubico', 'Volumen', 'ft^3'),
(126, 'metros cubicos', 'Volume', 'm^3'),
(127, 'hectarea metro', 'Volumen', 'hec m'),
(128, 'litro', 'Volumen', 'L'),
(129, 'US galon', 'Volumen', 'gal'),
(130, 'barril', 'Volumen', 'bbl'),
(131, 'pinta', 'Volumen', 'pt'),
(132, 'bushel', 'Volumen', 'bu'),
(133, 'cucharadita', 'Volumen', 'tsp'),
(134, 'cucharada', 'Volumen', 'tbsp'),
(135, 'cuarto de galón', 'Volumen', 'qrt'),
(136, 'onza', 'Volumen', 'oz'),
(137, 'adimensional', 'Adimensional', '-'),
(138, 'mega joule', 'Energía', 'MJ'),
(139, 'grados minutos segundos', 'Angulo', 'dddmmss'),
(140, 'calorías por centimetro cuadrado por día', 'Flujo de Energía', 'cal/cm^2 d'),
(141, 'calorías por square centimetro por minuto', 'Flujo de Energía', 'cal/cm^2 min'),
(142, 'mililitros por square centimetro por día', 'Hyporheic flux', 'ml/cm^2 d'),
(144, 'megajoules por metro cuadradado', 'Energía por Area', 'MJ/m^2'),
(145, 'galones por día', 'Flujo', 'gpd'),
(146, 'millones de galones por mes', 'Flujo', 'MGM'),
(147, 'millones de galones por año', 'Flujo', 'MGY'),
(148, 'toneladas cortas por día por pie', 'Flujo de Masa por unidad de ancho', 'ton/d ft'),
(149, 'lumens por pie cuadrado', 'Intensidad de Luz', 'lm/ft^2'),
(150, 'microeinsteins por square metro por segundo', 'Intensidad de Luz', 'uE/m^2 s'),
(151, 'alfas por metro', 'Luz', 'a/m'),
(152, 'microeinsteins por metro cuadrado', 'Luzt', 'uE/m^2'),
(153, 'milimoles de fotones por metro cuadrado', 'Luz', 'mmol/m^2'),
(154, 'absorbancia por centimetro', 'Extincción/Absorbancia', 'A/cm'),
(155, 'nanogram', 'Masa', 'ng'),
(156, 'picogram', 'Masa', 'pg'),
(157, 'miliequivalentes', 'Masa', 'meq'),
(158, 'gramos por metro cuadrado', 'Densidad Aereal', 'g/m^2'),
(159, 'miligramos por metro cuadrado', 'Densidad Aereal', 'mg/m^2'),
(160, 'microgramos por metro cuadrado', 'Densidad Aereal', 'ug/m^2'),
(161, 'gramos por metro cuadrado por día', 'Carga Aereal', 'g/m^2 d'),
(162, 'gramos por day', 'Carga', 'g/d'),
(163, 'libras por day', 'Carga', 'lb/d'),
(164, 'libras por milla', 'Carga', 'lb/mi'),
(165, 'short toneladas por día', 'Carga', 'ton/d'),
(166, 'miligramos por metro cubico por día', 'Productividad', 'mg/m^3 d'),
(167, 'miligramos por meter cuadrado por día', 'Productividad', 'mg/m^2 d'),
(168, 'volts', 'Diferencia Potencial', 'V'),
(169, 'millivolts', 'Diferencia Potencial', 'mV'),
(170, 'kilopascal', 'Presión/tensión', 'kPa'),
(171, 'megapascal', 'Presión/tensión', 'MPa'),
(172, 'becquerel', 'Radioactividad', 'Bq'),
(173, 'becquerels por gramo', 'Radioactividad', 'Bq/g'),
(174, 'curie', 'Radioactividad', 'Ci'),
(175, 'picocurie', 'Radioactividad', 'pCi'),
(176, 'ohm', 'Resistancia', 'ohm'),
(177, 'ohm metro', 'Resistividad', 'ohm m'),
(178, 'picocuries por gramo', 'Actividad Especifica', 'pCi/g'),
(179, 'picocuries por litro', 'Actividad Especifica', 'pCi/L'),
(180, 'picocuries por mililitro', 'Actividad Especifica', 'pCi/ml'),
(181, 'hora minuto', 'Tiempo', 'hhmm'),
(182, 'ayear mes day', 'Tiempo', 'yymmdd'),
(183, 'year dia (Julian)', 'Tiempo', 'yyddd'),
(184, 'pulgadas por dia', 'Velocidad', 'in/d'),
(185, 'pulgadas por semana', 'Velocidad', 'in/week'),
(186, 'pulgadas por tormenta', 'Precipitacion', 'in/storm'),
(187, 'miles de acre pies', 'Volumen', '10^3 ac ft'),
(188, 'mililitro', 'Volume', 'ml'),
(189, 'pies cubicos por segundo dias', 'Volumen', 'cfs d'),
(190, 'miles de galones', 'Volumen', '10^3 gal'),
(191, 'millones galones', 'Volumen', '10^6 gal'),
(192, 'microsiemens por centimetro', 'Conductividad Electrica', 'uS/cm'),
(193, 'practica salinidad unidades ', 'Salinidad', 'psu'),
(194, 'decibeles', 'Sonido', 'dB'),
(195, 'centimetros cubicos por gramo', 'Specific Volume', 'cm^3/g'),
(196, 'metros cuadrados por gramo', 'Specific Surface Area ', 'm^2/g'),
(197, 'short toneladas per acre foot', 'Concentracion', 'ton/ac ft'),
(198, 'grams por centimetro cubico', 'Concentracion', 'g/cm^3'),
(199, 'milligramos por litro', 'Concentracion', 'mg/L'),
(200, 'nanogramos por metro cubico', 'Concentracion', 'ng/m^3'),
(201, 'nanogramos por litro', 'Concentracion', 'ng/L'),
(202, 'gramos por litro', 'Concentracion', 'g/L'),
(203, 'microgramos por cubic meter', 'Concentracion', 'ug/m^3'),
(204, 'microgramos por litro', 'Concentracion', 'ug/L'),
(205, 'partes por millon', 'Concentracion', 'ppm'),
(206, 'partes por billon', 'Concentracion', 'ppb'),
(207, 'partes por trillon', 'Concentracion', 'ppt'),
(208, 'partes por quintillon', 'Concentracion', 'ppqt'),
(209, 'partes por quadrillon', 'Concentracion', 'ppq'),
(210, 'por milla', 'Concentracion', '%o'),
(211, 'microequivalentes por litro', 'Concentracion', 'ueq/L'),
(212, 'milliequivalentes por litro', 'Concentracion', 'meq/L'),
(213, 'milliequivalentes por 100 gram', 'Concentracion', 'meq/100 g'),
(214, 'milliosmoles por kilogramo', 'Concentracion', 'mOsm/kg'),
(215, 'nanomoles per litro', 'Concentracion', 'nmol/L'),
(216, 'picogramos per metro cubico', 'Concentracion', 'pg/m^3'),
(217, 'picogramos per litro', 'Concentracion', 'pg/L'),
(218, 'picogramos per millilitro', 'Concentracion', 'pg/ml'),
(219, 'tritium unidades', 'Concentracion', 'TU'),
(220, 'jackson turbidez unidades', 'Turbidez', 'JTU'),
(221, 'nefelometrico turbidez unidades', 'Turbidez', 'NTU'),
(222, 'nefelometrico turbidez multirayo unidad', 'Turbidez', 'NTMU'),
(223, 'nefelometrico turbidez tasa unidad', 'Turbidez', 'NTRU'),
(224, 'formazin nefelometrica multirayo unidad', 'Turbidez', 'FNMU'),
(225, 'formazin nefelometrica tasa unidad', 'Turbidez', 'FNRU'),
(226, 'formazin nefelometrica unidad', 'Turbidez', 'FNU'),
(227, 'formazin atenuación unidad', 'Turbidez', 'FAU'),
(228, 'formazin backscatter unidad ', 'Turbidez', 'FBU'),
(229, 'backscatter units', 'Turbidez', 'BU'),
(230, 'atenuacion units', 'Turbidez', 'AU'),
(231, 'platinum cobalto unidades', 'Color', 'PCU'),
(232, 'la tasa entre la absorbancia UV e 254 nm y nivel DOC', 'Absorbancia UV Especifica', 'L/(mg DOC/cm)'),
(233, 'billones colonias por day', 'Organism Loading', '10^9 colonias/d'),
(234, 'numero de organismos por metro cuadrado', 'Concentración de Organismos', '#/m^2'),
(235, 'numero de organismos por litro', 'Concentración de Organismos', '#/L'),
(236, 'numero de organismos por metros cubicos', 'Concentración de Organismos', '#/m^3'),
(237, 'celdas por mililitro', 'Concentración de Organismos', 'cells/ml'),
(238, 'celdas por milimetro cuadrado', 'Concentración de Organismos', 'celdas/mm^2'),
(239, 'colonias por 100 mililitro', 'Concentración de Organismos', 'colonias/100 ml'),
(240, 'colonias por mililitro', 'Concentración de Organismos', 'colonias/ml'),
(241, 'colonias por gramo', 'Concentración de Organismos', 'colonias/g'),
(242, 'colonyiaformando unidades por mililitro', 'Concentración de Organismos', 'CFU/ml'),
(243, 'quistes por 10 litros', 'Concentración de Organismos', 'quistes/10 L'),
(244, 'quistes por 100 litros', 'Concentración de Organismos', 'quistes/100 L'),
(245, 'oocysts por 10 litros', 'Concentración de Organismos', 'oocysts/10 L'),
(246, 'oocysts por 100 litros', 'Concentración de Organismos', 'oocysts/100 L'),
(247, 'número mas probable', 'Concentración de Organismos', 'MPN'),
(248, 'número mas probable por 100 litros', 'Concentración de Organismos', 'MPN/100 L'),
(249, 'número mas probable por 100 mililitros', 'Concentración de Organismos', 'MPN/100 ml'),
(250, 'número mas probable por gramo', 'Concentración de Organismos', 'MPN/g'),
(251, 'placas-formantes unidades por 100 litros', 'Concentración de Organismos', 'PFU/100 L'),
(252, 'placas por 100 mililitros', 'Concentración de Organismos', 'placas/100 ml'),
(253, 'conteo por segundo', 'Rate', '#/s'),
(254, 'por dia', 'Tasa', '1/d'),
(255, 'nanogramos por metro cuadrado por hora', 'Tasa de Volatilización', 'ng/m^2 hr'),
(256, 'nanogramos por metro cuadrado por semana', 'Tasa de Volatilización', 'ng/m^2 week'),
(257, 'conteo', 'Adimensional', '#'),
(258, 'categorica', 'Adimensional', 'code'),
(259, 'absorbancia por centimetro por mg/L de acido dado', 'Absorbancia', '100/cm mg/L'),
(260, 'por litro', 'Tasa de Concentracion', '1/L'),
(261, 'por milla por hora', 'Tasa de Sedimentación', '%o/hr'),
(262, 'galones por batch', 'Flujo', 'gpb'),
(263, 'pies cubicos por barril', 'Concentracion', 'ft^3/bbl'),
(264, 'por milla por volumen', 'Concentracion', '%o by vol'),
(265, 'por milla por hora por volumen', 'Tasa de Sedimentación', '%o/hr by vol'),
(266, 'micromoles', 'Monto', 'umol'),
(267, 'toneladas de carbonato de calcio por kiloton', 'Net Neutralizacion Potencial', 'tCaCO3/Kt'),
(268, 'siemens por metro', 'Conductividad Eléctrica', 'S/m'),
(269, 'milisiemens por centimetro', 'Conductividad Eléctrica', 'mS/cm'),
(270, 'siemens por centimetro', 'Conductividad Eléctrica', 'S/cm'),
(271, 'escala practica de salinidad', 'Salinidad', 'pss'),
(272, 'por metro', 'Extincción de Luz', '1/m'),
(273, 'normal', 'Normalidad', 'N'),
(274, 'nanomoles por kilogramo', 'Concentration', 'nmol/kg'),
(275, 'millimoles por kilogramo', 'Concentration', 'mmol/kg'),
(276, 'millimoles por metro cuadrado por hora', 'Areal Flux', 'mmol/m^2 hr'),
(277, 'milligramos por metro cubico por hora', 'Productivity', 'mg/m^3 hr'),
(278, 'milligramos por dia', 'Carga', 'mg/d'),
(279, 'litros por minuto', 'Flujo', 'L/min'),
(280, 'litros por dia', 'Flujo', 'L/d'),
(281, 'jackson unidades de velas', 'Turbidez', 'JCU'),
(282, 'granos por gallon', 'Concentración', 'gpg'),
(283, 'galones por segundo', 'Flujo', 'gps'),
(284, 'galones por hora', 'Flujo', 'gph'),
(285, 'pies velas', 'Iluminancia', 'ftc'),
(286, 'fibras por litro', 'Concentration', 'fibers/L'),
(287, 'drips por minuto', 'Flow', 'drips/min'),
(288, 'centimetros cubicos por segundo', 'Flow', 'cm^3/sec'),
(289, 'colonia formantes unidades', 'Concentración de Organismos', 'CFU'),
(290, 'colonia formantes unidades por 100 mililitros', 'Concentración de Organismos', 'CFU/100 ml'),
(291, 'cubic feet por minuto', 'Flow', 'cfm'),
(292, 'ADMI color unit', 'Color', 'ADMI'),
(293, 'percent por volumen', 'Concentración', '% by vol'),
(294, 'numero de organismos por 500 mililitros', 'Concentración de Organismos', '#/500 ml'),
(295, 'numero de organismos por 100 galones', 'Concentración de Organismos', '#/100 gal'),
(296, 'grams por cubic metros por hour', 'Productivity', 'g/m^3 hr'),
(297, 'grams por minuto', 'Carga', 'g/min'),
(298, 'grams por segundo', 'Carga', 'g/s'),
(299, 'millones cubic feet', 'Volumen', '10^6 ft^3'),
(300, 'mes año', 'Tiempo', 'mmyy'),
(301, 'bar', 'Presión', 'bar'),
(302, 'decisiemens por centimetro', 'Conductividad Eléctrica', 'dS/cm'),
(303, 'micromoles por litro', 'Concentracion', 'umol/L'),
(304, 'Joules por centimetro cuadrado', 'Energía por Area', 'J/cm^2'),
(305, 'milimetros por dia', 'velocidad', 'mm/dia'),
(306, 'partes por miles', 'Concentración', 'ppth'),
(307, 'megalitro', 'Volumen', 'ML'),
(308, 'Porcentaje de Saturación', 'Concentración', '% Sat'),
(309, 'pH Unit', 'Adimensional', 'pH'),
(310, 'milimetros por segundo', 'Velocidad', 'mm/s'),
(311, 'litros por hora', 'Flujo', 'L/hr'),
(312, 'hecto metro cubico', 'Volumen', '(hm)^3'),
(313, 'moles por metro cubico', 'Concentracion o Concentración de Organismos', 'mol/m^3'),
(314, 'kilogramos por mes', 'Loading', 'kg/mes'),
(315, 'Hecto Pascal', 'Presión/tensión', 'hPa'),
(316, 'kilogramos por metro cubico', 'Concentration', 'kg/m^3'),
(317, 'toneladas  cortas por mes', 'Loading', 'ton/mes'),
(318, 'micromoles por metro cuadrado por segundo', 'Aereal Flux', 'umol/m^2 s'),
(319, 'gramos por metro cuadrado por hora', 'Aereal Flux', 'g/m^2 hr'),
(320, 'miligramos por metro cubico', 'Concentración', 'mg/m^3'),
(321, 'metros squared por segundo cuadrado', 'Velocidad', 'm^2/s^2'),
(322, 'grados Celsius cuadrados', 'Temperatura', '(DegC)^2'),
(323, 'miligramos por metro cubico', 'Concentration', '(mg/m^3)^2'),
(324, 'metros per segundo grados Celsius', 'Temperatura', 'm/s DegC'),
(325, 'milimoles por metro cuadrado por segundo', 'Aereal Flux', 'mmol/m^2 s'),
(326, 'grados Celsius milimoles por cubico metro', 'Concentracion', 'DegC mmol/m^3'),
(327, 'milimoles por cubic meter', 'Concentracion', 'mmol/m^3'),
(328, 'milimoles por cubic meter squared', 'Concentracion', '(mmol/m^3)^2'),
(329, 'Langleys por hora', 'Energy Flux', 'Ly/hr'),
(330, 'hits por centimetros cuadrados', 'Precipitacion', 'hits/cm^2'),
(331, 'hits por centimetro cuadradopor hora', 'Velocidad', 'hits/cm^2 hr'),
(332, 'fluorescencia relativa unidades', 'Fluorescencia', 'RFU'),
(333, 'kilogramos por hectarea por dia', 'Aereal Flujo', 'kg/ha d'),
(334, 'kilowatts por metro cuadrado', 'Energy Flux', 'kW/m^2'),
(335, 'kilogramos por metro cuadrado', 'Densidad  Aereal', 'kg/m^2'),
(336, 'microeinsteins por metro cuadrado por dia', 'Light Intensity', 'uE/m^2 d'),
(337, 'microgramo por mililitro', 'Concentración', 'ug/mL'),
(338, 'Newton por metro cuadrado', 'Presión/Tensión', 'Newton/m^2'),
(339, 'micromoles por litro por hora', 'Presión/tensión', 'umol/L hr'),
(340, 'decisiemens por metro', 'Conductividad Eléctrica', 'dS/m'),
(341, 'miligramos por kilogramo', 'Masa Fracción', 'mg/Kg'),
(342, 'numero de organisms per 100 mililitro', 'Concentración de Organismos', '#/100 mL'),
(343, 'microgramos por kilogramo', 'Masa Fracción', 'ug/Kg'),
(344, 'gramos por kilogramo', 'Masa Fracción', 'g/Kg'),
(345, 'acre pies por mes', 'Flujo', 'ac ft/mo'),
(346, 'acre pies por half mes', 'Flujo', 'ac ft/0.5 mo'),
(347, 'metros cubicos por minuto', 'Flujo', 'm^3/min'),
(348, 'conteo por medo pies cubico', 'Concentración', '#/((ft^3)/2)');

-- --------------------------------------------------------

--
-- Table structure for table `valuetypecv`
--

DROP TABLE IF EXISTS `valuetypecv`;
CREATE TABLE IF NOT EXISTS `valuetypecv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `valuetypecv`
--

INSERT INTO `valuetypecv` (`Term`, `Definition`) VALUES
('Calibration Value', 'Un valor usado como parte de la calibración de un instrumento en un tiempo particular.'),
('Derived Value', 'Valor que esta directament derivado de una observación o conjunto de observaciones'),
('Field Observation', 'Observación de una variable usando un instrumento de campo'),
('Model Simulation Result', 'Valores generados por un modelo de simulación'),
('Sample', 'Observación que es el resultado de analizar una muestra en un laboratorio'),
('Unknown', 'El tipo de valor es desconocido');

-- --------------------------------------------------------

--
-- Table structure for table `variablenamecv`
--

DROP TABLE IF EXISTS `variablenamecv`;
CREATE TABLE IF NOT EXISTS `variablenamecv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `variablenamecv`
--

INSERT INTO `variablenamecv` (`Term`, `Definition`) VALUES
('19-Hexanoyloxyfucoxanthin', 'El pigmento de fitoplancton 19-hexanoiloxifucoxantina'),
('9 cis-Neoxanthin', 'El pigmento de fitoplancton '),
('Acid neutralizing capacity', 'Capcidad de neutralización del ácido'),
('Agency code', 'Código para la agencia que analizó la muestra'),
('Albedo', 'EL cociente o tasa de la luz incidente reflejada.'),
('Alkalinity, bicarbonate', 'Alcalinidad de Bicarbonato '),
('Alkalinity, carbonate ', 'Alcalinidad de carbonato '),
('Alkalinity, carbonate plus bicarbonate', 'Alcalinidad, de carbonato mas bicarbonato'),
('Alkalinity, hydroxide ', 'Alcalinidad de hidroxido'),
('Alkalinity, total ', 'Alcalinidad Total'),
('Alloxanthin', 'El pigmento de fitoplancton Alloxanthin'),
('Aluminium', 'Aluminio (Al)'),
('Aluminum, dissolved', 'Aluminio Disuelto (Al)'),
('Ammonium flux', 'Amonio (NH4) flujo'),
('Antimony', 'Antimonio (Sb)'),
('Area', 'Area de localización de una medida'),
('Arsenic', 'Arsénico (As)'),
('Asteridae coverage', 'Cobertura aereal de la planta Asteridae'),
('Barium, dissolved', 'Bario Disuelto (Ba)'),
('Barium, total', 'Bario Total (Ba)'),
('Barometric pressure', 'Presión Barométrica'),
('Baseflow', 'La porción del flujo del cauce (caudal) que es suministrada por fuentes de aga subterranea.'),
('Batis maritima Coverage', 'Cobertura aereal de la planta Batis maritima'),
('Battery Temperature', 'La temperatura de la bateria de un datalogger o sistema de sensores'),
('Battery voltage', 'El voltaje de la bateria de un datalogger o sistema de sensores, frecuentemente registrado como un indicador de confiabilidad de los datos'),
('Benthos', 'Especies bentónicas'),
('Bicarbonate', 'Bicarbonato (HCO3-)'),
('Biogenic silica', 'sílice hidratada (SiO2 nH20)'),
('Biomass, phytoplankton', 'Masa Total de fitoplancton, por unidad de área o volumen'),
('Biomass, total', 'Biomasa Total'),
('Blue-green algae (cyanobacteria), phycocyanin', 'Las algas verdiazules (cianobacteria) con pigmentos ficocianina'),
('BOD1', 'Demanda bioquimica de oxigeno 1-día'),
('BOD2, carbonaceous', '2-día Demanda Carbonosa Bioquímica de Oxígeno'),
('BOD20', '20-día Demanda Bioquímica de Oxígeno'),
('BOD20, carbonaceous', '20-día Demanda Carbonosa Bioquímica de Oxígeno'),
('BOD20, nitrogenous', '20-día Demanda Nitrogenosa Bioquímica de Oxígeno'),
('BOD3, carbonaceous', '3-día Demanda Carbonosa Bioquímica de Oxígeno'),
('BOD4, carbonaceous', '4-día Demanda Carbonosa Biológica de Oxígeno'),
('BOD5', '5-day Demanda Bioquimica de Oxigeno'),
('BOD5, carbonaceous', '5-día Demanda Carbonosa Bioquímica de Oxígeno'),
('BOD5, nitrogenous', '5-día Demanda Nitrogenosa Bioquímica de Oxígeno'),
('BOD6, carbonaceous', '6-día Demanda Carbonosa Biological de Oxígeno'),
('BOD7, carbonaceous', '7-día Demanda Carbonosa Bioquímica de Oxígeno'),
('BODu', 'Demanda Ultima Bioquímica de Oxígeno'),
('BODu, carbonaceous', 'Demanda Ultima Carbonosa Bioquímica de Oxígeno'),
('BODu, nitrogenous', 'Demanda Ultima Nitrogenosa Bioquímica de Oxígeno'),
('Borehole log material classification', 'Clasificación de material encontrado por una perforadora a varias profundidades durante la perforación de un pozo y registrada en el registro de pozo.'),
('Boron', 'Boro (B)'),
('Borrichia frutescens Coverage', 'Cobertura áereal de la planta Borrichia frutescens'),
('Bromide', 'Bromo'),
('Bromine', 'Bromo (Br)'),
('Bromine, dissolved', 'Bromo disuelto (Br)'),
('Bulk electrical conductivity', 'Conductividad eléctrica en volumen de un medio medida usando un sensor tal como uno de tiempo de reflectometría en el dominio o time domain reflectometry (TDR), como una respuesta cruda del sensor en la medida de una cantidad como la humedad del suelo.'),
('Cadmium', 'Cadmio (Cd)'),
('Calcium ', 'Calcio (Ca) '),
('Calcium, dissolved', 'Calcio Disuelto (Ca)'),
('Canthaxanthin', 'El pigmento de fitoplancton Canthaxanthin'),
('Carbon dioxide', 'Dioxido de Carbono'),
('Carbon dioxide Flux', 'Flujo de Dioxido de Carbono(CO2)'),
('Carbon dioxide Storage Flux', 'Flujo de Almacenamiento de Dioxido de Carbono(CO2)'),
('Carbon dioxide, transducer signal', 'Carbon dioxide (CO2), data cruda del sensor'),
('Carbon disulfide', 'disulfuro de carbono (CS2)'),
('Carbon tetrachloride', 'tetracloruro de carbono (CCl4)'),
('Carbon to Nitrogen molar ratio', 'C:N (molar)'),
('Carbon, dissolved inorganic', 'Carbon Inorgánico Disuelto'),
('Carbon, dissolved organic', 'Carbon orgánico Disuelto'),
('Carbon, dissolved total', 'Carbon Disuelto Total (Orgánico+Inorgánico)'),
('Carbon, particulate organic', 'Partículado de carbono orgánico en suspensión'),
('Carbon, suspended inorganic', 'Carbon Inorgánico Suspendido'),
('Carbon, suspended organic', 'DEPRECATED -- El uso de este término es desalentado en favor del uso del término sinónimo "Carbono, partículas orgánicas".'),
('Carbon, suspened total', 'Carbon Suspendido Total (Orgánico+Inorgánico)'),
('Carbon, total', 'Total (Disuelto+Particulado) Carbon'),
('Carbon, total inorganic', 'Total (Disuelto+Particulado) Carbon Inorgánico'),
('Carbon, total organic', 'Total (Disuelto+Particulado) Carbon Orgánico'),
('Carbon, total solid phase', 'Total de carbono fase sólida'),
('Carbonate', 'Concentración de ion de Carbonato (CO3-2)'),
('Chloride', 'Cloruro (Cl-)'),
('Chloride, total', 'Cloruro Total (Cl-)'),
('Chlorine', 'Cloruro o Cloro (Cl)'),
('Chlorine, dissolved', 'Cloruro Disuelto (Cl)'),
('Chlorophyll (a+b+c)', 'Clorofila (a+b+c)'),
('Chlorophyll a', 'Clorofila a'),
('Chlorophyll a allomer', 'El pigmento de fitoplancton Clorofila un alomero'),
('Chlorophyll a, corrected for pheophytin', 'Clorofila a corregida para feofitina'),
('Chlorophyll a, uncorrected for pheophytin', 'Clorofila a sin corregir para feofitina'),
('Chlorophyll b', 'Clorofila b'),
('Chlorophyll c', 'Clorofila c'),
('Chlorophyll c1 and c2', 'Clorofila c1 and c2'),
('Chlorophyll Fluorescence', 'Clorofila Fluorescencia'),
('Chromium (III)', 'Cromo Trivalente'),
('Chromium (VI)', 'Cromo Hexavalente'),
('Chromium, dissolved', 'Cromo Disuelto'),
('Chromium, total', 'Cromo, en todas sus formas'),
('Cobalt', 'Cobalto (Co)'),
('Cobalt, dissolved', 'Cobalto Disuelto (Co)'),
('COD', 'Demanda quimica de oxigeno'),
('Coliform, fecal', 'Coliforme fecal'),
('Coliform, total', 'Coliforme total'),
('Color', 'Color en unidades de color cuantificadas'),
('Colored Dissolved Organic Matter', 'La concentración de materia organica coloreada disuelta (sustancias húmicas )'),
('Container number', 'El número identificador para un contenedor de muestreador de agua.'),
('Copper', 'Cobre (Cu)'),
('Copper, dissolved', 'Cobre Disuelto (Cu)'),
('Cryptophytes', 'La concentración de clorofila contribuida por criptofitas'),
('Cuscuta spp. coverage', 'Cobertura aereal de la planta Cuscuta spp.'),
('DeleteThisVar', 'Una variable falsa para ser eliminada'),
('Density', 'Densidad'),
('Deuterium', 'Deuterio (2H), Delta D'),
('Diadinoxanthin', 'El pigmento de fitoplancto Diadinoxanthin'),
('Diatoxanthin', 'El pigmento de fitoplancton Diatoxanthin'),
('Dinoflagellates', 'La concentración de clorofila contribuida por dinoflagelados'),
('Discharge', 'Caudal'),
('Distance', 'Distancia medida desde un sensor a un objeto blanco tal como la suerficie de un cuerpo de agua o superficie de nieve.'),
('Distichlis spicata Coverage', 'Cobertura áereal de la planta Distichlis spicata'),
('E-coli', 'Escherichi coli'),
('Electric Energy', 'Energía Eléctrica'),
('Electric Power', 'Potencia Eléctrica'),
('Electrical conductivity', 'Conductividad Electrica'),
('Enterococci', 'Bacterias enterococos'),
('Ethane, dissolved', 'Etano Disuelto (C2H6)'),
('Evaporation', 'Evaporación'),
('Evapotranspiration', 'Evapotranspiración'),
('Evapotranspiration, potential', 'La cantidad de agua que podria evaporarse y transpirarse si hubiese suficiente agua disponible.'),
('Fish detections', 'El número de peces identificados por equipo de detección'),
('Flash memory error count', 'Un contador que cuenta el numero de  '),
('Fluoride', 'Fluor - F-'),
('Fluorine', 'Fluor (F-)'),
('Fluorine, dissolved', 'Fluor Disuelto (Fl)'),
('Friction velocity', 'Velocidad de Fricción'),
('Gage height', 'Nivel de agua con relación a un datum de medicion arbitrario (ver tambien Profundidad de Agua para comparación)'),
('Global Radiation', 'Radiación Solar, directa y difusa, recibida desde un angulo sólido de 2p esterorradianes sobre una superficie horizontal. \nSource: World Meteorological Organization, Meteoterm'),
('Ground heat flux', 'Flujo de Calor del suelo'),
('Groundwater Depth', 'Profundidad de agua subterranea es la distancia entre la superficie del agua y la superficie del terreno en una localización especifica especificada por la localización del sitio y el offset.'),
('Hardness, carbonate', 'Dureza de Carbonatos tambien conocida como dureza temporal'),
('Hardness, non-carbonate', 'Dureza No-carbonatada'),
('Hardness, total', 'Dureza Total'),
('Heat index', 'El efecto combinado del calor y la humedad sobre la temperatura sentida por las personas.'),
('Hydrogen sulfide', 'Sulfuro de Hidrogeno (H2S)'),
('Hydrogen-2, stable isotope ratio delta', 'Diferencia en la tasa 2H:1H entre la muestra y el estandar'),
('Imaginary dielectric constant', 'Respuesta del suelo de una onda electromagnética permanente reflejada de una frecuencia particular la cual esta relacionada a la disipación (o pérdida) de energía dentro de medio. Esta es la porción imaginaria de la ocnstante dielectrica compleja.'),
('Iron sulfide', 'Sulfuro e Hierro (FeS2)'),
('Iron, dissolved', 'Hierro Disueto (Fe)'),
('Iron, ferric', 'Hierro Férrico (Fe+3)'),
('Iron, ferrous', 'Hierro Ferroso (Fe+2)'),
('Iva frutescens coverage', 'Cobertura aereal de la planta Iva frutescens'),
('Latent Heat Flux', 'Flujo de Calor Latente'),
('Latitude', 'Latitud como una medida de variable u observación (referencia espacial a ser designada en metodos). '),
('Lead', 'Plomo (Pb)'),
('Leaf wetness', 'El efecto de la humedad asentandose sobre la superficie de una hoja como un resultado de ya sea la condensación o la lluvia.'),
('Light attenuation coefficient', 'Coeficiente de atenuación de la luz'),
('Limonium nashii Coverage', 'Cobertura aereal de la planta Limonium nashii'),
('Lithium', 'Litio (Li)'),
('Longitude', 'Longitud como una medida de variable u observación (referencia espacial a ser designada en metodos. Esto es distinto a la longitud de un sitio la cual which es un atributo del sitio.'),
('Low battery count', 'Un contador del numero de veces que el voltage de la bateria cae por debajo de un umbral minimo'),
('LSI', 'El Indice de Saturación de Langelier es un indicador del grado de saturación del agua con respecto al carbonato de calcio'),
('Lycium carolinianum Coverage', 'Cobertura aereal de la planta Lycium carolinianum'),
('Magnesium', 'Magnesio (Mg)'),
('Magnesium, dissolved', 'Magnesio disuelto (Mg)'),
('Manganese', 'Manganeso (Mn)'),
('Manganese, dissolved', 'Manganeso disuelto (Mn)'),
('Mercury', 'Mercurio (Hg)'),
('Methane, dissolved', 'Metano disuelto(CH4)'),
('Methylmercury', 'Methylmercurio (CH3Hg)'),
('Molybdenum', 'Molibdeno (Mo)'),
('Momentum flux', 'Flujo de Momentum'),
('Monanthochloe littoralis Coverage', 'Cobertura aereal de la planta Monanthochloe littoralis'),
('N, albuminoid', 'Nitrogen Albuminoide'),
('Net heat flux', 'Tasa saliente de energia de calor transferida menos la tasa entrante de energía de calor transferida a traves de un área dada'),
('Nickel', 'Níquel (Ni)'),
('Nickel, dissolved', 'Níquel disuelto (Ni)'),
('Nitrogen, Dissolved Inorganic', 'Nitrogeno inorgánico disuelto '),
('Nitrogen, dissolved Kjeldahl', 'Kjeldahl disuelto(nitrogeno organico + amoniaco (NH3) + nitrogeno amonio (NH4))'),
('Nitrogen, dissolved nitrate (NO3)', 'Nitrato (NO3) nitrogeno disuelto '),
('Nitrogen, dissolved nitrite (NO2)', 'Nitrito NO2) nitrogeno disuelto '),
('Nitrogen, dissolved nitrite (NO2) + nitrate (NO3)', 'nitrito (NO2) + nitrato (NO3) nitrogeno disuelto '),
('Nitrogen, Dissolved Organic', 'Nitrogeno Organico disuelto '),
('Nitrogen, gas', 'Nitrogeno gasesoso (N2)'),
('Nitrogen, inorganic', 'Nitrogeno inorganico Total'),
('Nitrogen, NH3', 'Amoniaco libre (NH3)'),
('Nitrogen, NH3 + NH4', '(libre+ionizado) Amoniaco Total'),
('Nitrogen, NH4', 'Amonio (NH4)'),
('Nitrogen, nitrate (NO3)', 'Nitrato (NO3) Nitrogeno'),
('Nitrogen, nitrite (NO2)', 'Nitrito (NO2) Nitrogeno'),
('Nitrogen, nitrite (NO2) + nitrato (NO3)', 'Nitrito (NO2) + Nitrato (NO3) Nitrogeno'),
('Nitrogen, organic', 'Nitrogeno Organico'),
('Nitrogen, organic kjeldahl', 'Kjeldahl Orgánico (nitrogeno organico + amoniaco (NH3) + amonio (NH4)) nitrogeno'),
('Nitrogen, particulate organic', 'Nitrogeno Organico particulado'),
('Nitrogen, total', 'Nitrogeno Total (NO3+NO2+NH4+NH3+Organico)'),
('Nitrogen, total dissolved', 'Total nitrogeno disuelto '),
('Nitrogen, total kjeldahl', 'Total Kjeldahl Nitrogeno (Amoniaco+Organico) '),
('Nitrogen, total organic', 'Total (disuelto + particulado) nitrogeno organico'),
('Nitrogen-15', '15 Nitrogeno, Delta Nitrogeno'),
('Nitrogen-15, stable isotope ratio delta', 'Diferencia en la tasa 15N:14N entre la muestra y el estandar'),
('No vegetation coverage', 'Cobertura aereal de no vegetación'),
('Odor', 'Odor'),
('Oxygen flux', 'Flujo de Oxigeno (O2)'),
('Oxygen, dissolved', 'Oxigeno disuelto'),
('Oxygen, dissolved percent of saturation', 'Oxigeno disuelto, porcentaje de saturación'),
('Oxygen, dissolved, transducer signal', 'oxigen disuelto, data cruda desde sensor'),
('Oxygen-18', '18 O, Delta O'),
('Oxygen-18, stable isotope ratio delta', 'Diferencia en la tasa 18O:16O entre la muestra y el estandar'),
('Ozone', 'Ozono (O3)'),
('Parameter', 'Parametro relacionado a un proceso hidrologico. Un ejemplo de uso seria el del parametro de la relación elevación-caudal.'),
('Peridinin', 'El pigmento de fitoplancton Peridinin'),
('Permittivity', 'La Permitividad es una cantidad fisica que describe como un campo eléctrico afecta, y es afectado por un medio dielectrico, y se determina por la abilidad de un material de polarizar en respuesta al campo, y reduciendo asi el campo total electrico dentro del material. De esta manera, la permitividad se relaciona a la abilidad del material de transmitir (o "permitir") un campo electrico.'),
('Petroleum hydrocarbon, total', 'Petroleo hidrocarburo total'),
('pH', 'El pH es la medida de la acidez o alcalinidad de una solución. El pH es formalmente una medida de la actividad de los iones de hidrogeno disuelto (H+). Las soluciones en la cual la concentración de H+ excede aquella de OH- tienen un valor de pH menor que 7.0 y son conocidas como acidos. '),
('Pheophytin', 'Pheophytin (Clorofila que ha perdido el ion central de Mg ion) es un producto de la degradación de la clorofila'),
('Phosphorus, dissolved', 'Fosforo disuelto(P)'),
('Phosphorus, dissolved organic', 'Fosforo orgánico disuelto '),
('Phosphorus, inorganic ', 'Fosforo Inorgánico'),
('Phosphorus, organic', 'Fosforo Orgánico'),
('Phosphorus, orthophosphate', 'Fosforo Ortfosfatado'),
('Phosphorus, orthophosphate dissolved', 'Fósforo orthofosfatado disuelto'),
('Phosphorus, particulate', 'Fosforo Particulado'),
('Phosphorus, particulate organic', 'Fósforo orgánico particulado en suspensión'),
('Phosphorus, phosphate (PO4)', 'Fósforo fosfatado'),
('Phosphorus, phosphate flux', 'Flujo Fosfatado (PO4)'),
('Phosphorus, polyphosphate', 'Fosforo Polyfosfatado'),
('Phosphorus, total', 'Fosforo Total'),
('Phosphorus, total dissolved', 'Total de fosforo disuelto'),
('Phytoplankton', 'Medida de fitoplancton sin diferentiación entre especies'),
('Position', 'Posición de un elemento que interactua con el agua tal como compuertas de embalse'),
('Potassium', 'Potasio (K)'),
('Potassium, dissolved', 'Potasio Disuelto (K)'),
('Precipitation', 'Precipitación tal como lluvia. No debe ser confundida con asentamiento de una sustancia.'),
('Pressure, absolute', 'Presión absoluta'),
('Pressure, gauge', 'Presion relativa a la presion local atmosferica o la presion ambiental'),
('Primary Productivity', 'Productividad primaria'),
('Program signature', 'Un identificador unico del progama registrador de datos que es util para conocer cuando el codigo fuente en el registtrador de datos ha sido modificado.'),
('Radiation, incoming longwave', 'Radiación entrante de onda larga'),
('Radiation, incoming PAR', 'Radiación entrante Fotosintheticamente-Activa'),
('Radiation, incoming shortwave', 'Radiación entrante de onda corta'),
('Radiation, incoming UV-A', 'Radiación entrante Ultravioleta A'),
('Radiation, incoming UV-B', 'Radiacion entrante Ultravioleta B'),
('Radiation, net', 'Radiación Neta'),
('Radiation, net longwave', 'Radiación Neta de Onda Larga'),
('Radiation, net PAR', 'Radiación Neta Fotosinteticamente-Activa'),
('Radiation, net shortwave', 'Radiación Neta de Onda Corta'),
('Radiation, outgoing longwave', 'Radiación Saliente de Onda Larga'),
('Radiation, outgoing PAR', 'Radiación Saliente Fotosinteticamente-Activa'),
('Radiation, outgoing shortwave', 'Radiación Saliente de Onda Corta'),
('Radiation, total incoming', 'Cantidad Total de radiación entrante de todas las frecuencias'),
('Radiation, total outgoing', 'Cantidad Total de radiación saliente de todas las frecuencias'),
('Radiation, total shortwave', 'Radiación Total de Onda Corta'),
('Rainfall rate', 'Una medida de la intensidad de la lluvia, calculada como la profundidad del agua sobre un periodo de tiempo dado si la intensidad fuera a permenecer constante a lo largo del intervalo de tiempo (pulg/hr, mm/hr, etc)'),
('Real dielectric constant', 'Respuesta del Suelo a la onda estacionaria electromagnetica reflejada de una frecuencia particular la cual esta relacionada a la energia almacenada dentro el medio.  Esta es la porcion real de la constante compleja dielectrica.'),
('Recorder code', 'Un código usado para identificar un registraddor de datos.'),
('Reduction potential', 'Potencial de Oxidación-reducción'),
('Relative humidity', 'Humedad Relativa'),
('Reservoir storage', 'Volumen de agua de un embalse'),
('Respiration, net', 'Respiración Neta'),
('Salicornia bigelovii coverage', 'Cobertura aereal de la planta Salicornia bigelovii'),
('Salicornia virginica coverage', 'Cobertura aereal de la planta Salicornia virginica'),
('Salinity', 'Salinidad'),
('Secchi depth', 'Profundiad del disco Secchi'),
('Selenium', 'Selenio (Se)'),
('Sensible Heat Flux', 'Flujo de Calor Sensible'),
('Sequence number', 'Un contador de eventos en una secuencia'),
('Signal-to-noise ratio', 'Tasa Senal-a-ruido (suele abreviarse como SNR o S/N) es definida como la tasa de una senal de poder a la senal corrompida de poder de ruido. Mientras mas alta es la tasa, menor obtrusivo sera el ruido de fondo.'),
('Silica', 'Sílice (SiO2)'),
('Silicate', 'Silicato.  Compuesto quimico que contiene silicio, oxigeno, y uno o mas metales, por ejemplo, aluminio, bario, berilio, calcio, hierro, magnesio, manganeso, potasio, sodio, o circonio.'),
('Silicic acid', 'Silice hidrata disuelta en agua'),
('Silicic acid flux', 'Flujo de Acido Silicato (H4SiO4)'),
('Silicon', 'Silicio (Si)  '),
('Silicon, dissolved', 'Silicio disuelto (Si)'),
('Snow depth', 'Profundidad de la Nieve'),
('Snow Water Equivalent', 'La profundidad de agua si una cobertura de nieve se derritiera por completo, expresada en unidades de profundidad, sobre un área de superficie correspondiente horizontal.'),
('Sodium', 'Sodio (Na)'),
('Sodium adsorption ratio', 'Tasa de de absorcion de Sodio'),
('Sodium plus potassium', 'Sodio mas potasio'),
('Sodium, dissolved', 'Sodio (Na)disuelto'),
('Sodium, fraction of cations', 'Sodio, fracción de cationes'),
('Solids, fixed Dissolved', 'Solidos Fijos disueltos'),
('Solids, fixed Suspended', 'Solidos Fijos Suspendidos'),
('Solids, total', 'Solidos Totales'),
('Solids, total Dissolved', 'Total de Sólidos disueltos'),
('Solids, total Fixed', 'Total de Solids Fijos'),
('Solids, total Suspended', 'Total de Solidos Suspendidos'),
('Solids, total Volatile', 'Total de Solidos Volatiles'),
('Solids, volatile Dissolved', 'Solidos volatiles disueltos'),
('Solids, volatile Suspended', 'Solidos Suspendidos Volatiles disuelto'),
('Spartina alterniflora coverage', 'Cobertura áereal de la planta Spartina alterniflora'),
('Spartina spartinea coverage', 'Cobertura áereal de la planta Spartina spartinea'),
('Specific conductance', 'Conductancia específica'),
('Streamflow', 'El volumen de agua fluyendo que pasa por un punto fijo. '),
('Streptococci, fecal', 'estreptococos fecales'),
('Strontium', 'Estroncio (Sr)'),
('Strontium, dissolved', 'Estroncio (Sr)disuelto '),
('Strontium, total', 'Estroncio Total(Sr)'),
('Suaeda linearis coverage', 'Cobertura áereal de la planta Suaeda linearis'),
('Suaeda maritima coverage', 'Cobertura áereal de la planta Suaeda maritima'),
('Sulfate', 'Sulfate (SO4)'),
('Sulfate, dissolved', 'Sulfato (SO4)disuelto '),
('Sulfur', 'Sulfur (S)'),
('Sulfur dioxide', 'Dioxido de Sulfuro (SO2)disuelto '),
('Sulfur, organic', 'Sulfuro Organico'),
('Sulfur, pyritic', 'azufre pirítica'),
('Sunshine duration', 'Duración de horas de sol'),
('Table overrun error count', 'Un contador que cuenta el numero de errores de saturación de tabla de datalogger'),
('TDR waveform relative length', 'Dominio de tiempo de reflextometria, longitud aparente dividida por la longitud de probeta. Raiz cuadrada de dielectrico'),
('Temperature', 'Temperatura'),
('Temperature, dew point', 'Temperatura de Punto de Rocio'),
('Temperature, transducer signal', 'Temperatura, data cruda del sensor'),
('TestingAddVar', 'Cualquier cosa que querramos'),
('Thallium', 'Thalio (Tl)'),
('THSW Index', 'El indice THSW usa temperatura, humidad, radiación solar, y velocidad del viento para calcular una temperatura aparente.'),
('THW Index', 'El indice THSW usa temperatura, humidad, radiación solar, y velocidad del viento para calcular una temperatura aparente.'),
('Tide stage', 'Fase de Marea'),
('Tin', 'Estaño (Sn)'),
('Titanium', 'Titanio (Ti)'),
('Transient species coverage', 'Cobertura áereal de transient species'),
('Transpiration', 'Transpiración'),
('TSI', 'Indice de EStado Trofico de Carlson es una medida de eutroficazión basada en la profundidad del disco Secchi'),
('Turbidity', 'Turbididez'),
('Uranium', 'Uranio (U)'),
('Urea', 'Urea ((NH2)2CO)'),
('Urea flux', 'Flujo de Urea ((NH2)2CO)'),
('Vanadium', 'Vanadio (V)'),
('Vapor pressure', 'La presion de un vapor en equilibrio con sus fases de no-vapor'),
('Vapor pressure deficit', 'La diferencia entre la presión de vapor de agua actual y la presión de saturación de vapor de agua a una temperatura particular.'),
('Velocity', 'La velocidad de una sustancia, fluido u objeto'),
('Visibility', 'Visibilidad'),
('Voltage', 'Voltaje o Potencial Electrico'),
('Volume', 'Volumen. Para cuantificar caudal o volumen de hidrograma o alguna otra medida de volumen.'),
('Volumetric water content', 'Volumen de agua liquida relativa al volumen total. Usado por ejemplo para cuantificar humedad del suelo'),
('Watchdog error count', 'Un contador que cuenta el numero total de errores de vigilancia de datalogger'),
('Water depth', 'Profundidad de agua es la distancia entre la superficie del agua y el fondo del cuerpo de agua en una localización especifica especificada por la localización del sitio y el offset.'),
('Water depth, averaged', 'Profundidad de agua promediada a lo largo de una sección transversal de canal o cuerpo de agua. '),
('Water flux', 'Flujo de Agua'),
('Water level', 'Nivel del agua relativo a un datum. El datum puede ser local o global tal como NGVD 1929 y debe ser especificado en la descripción del método para valores de datos asociados.'),
('Water potential', 'Potencial de agua es la energia potencial del agua relativa a agua libre pura (por ejemplo, agua desionizada) en condiciones de referencia. Cuantifica la tendencia del agua de moverse de un área a otra debido a osmosis, gravedad, presión mecanica, o efectos matriciales incluyendo presión de tensión superficial.'),
('Water vapor density', 'Densidad de vapor de agua'),
('Wave height', 'La altura de una onda de agua, medida como la diferencia en elevación entre la cresta de la onda y la depresión adyacente.'),
('Weather conditions', 'Condiciones del tiempo'),
('Well flow rate', 'Tasa de flujo de un pozo mientras esta bombeando'),
('Wellhead pressure', 'La presión ejercida por el fluido en la cabeza del pozo o la cabeza del encamisado despues que el pozo ha estado cerrado durante un periodo de tiempo, tipicamente 24 horas'),
('Wind chill', 'El efecto del viento sobre la temperatura sentida sobre la piel humana.'),
('Wind direction', 'Direccion del viento'),
('Wind gust direction', 'Dirección of rafagas de viento'),
('Wind gust speed', 'Velocidad de las rafagas de viento'),
('Wind Run', 'La longitud del flujo de aire que pasa un punto a lo largo de un intervalo de tiempo. Velocidad del viento multiplicada por el intervalo de tiempo.'),
('Wind speed', 'Velocidad del viento'),
('Wind stress', 'Arrastre o fuerza tangencial por unidad de area ejercida sobre una superficie por la capa adyacente de aire en movimiento'),
('Wrack coverage', 'Cobertura areal de vegetación muerta'),
('Zeaxanthin', 'El pigmento de fitoplancton Zeaxanthin'),
('Zinc', 'Zinc (Zn)'),
('Zinc, dissolved', 'Zinc (Zn) disuelto'),
('Zircon, dissolved', 'Zirconio (Zr)disuelto '),
('Zooplankton', 'Organismos Zooplanctonicos, no-especificos');

-- --------------------------------------------------------

--
-- Table structure for table `variables`
--

DROP TABLE IF EXISTS `variables`;
CREATE TABLE IF NOT EXISTS `variables` (
  `VariableID` int(11) NOT NULL AUTO_INCREMENT,
  `VariableCode` varchar(50) NOT NULL,
  `VariableName` varchar(255) NOT NULL,
  `Speciation` varchar(255) NOT NULL DEFAULT 'Not Applicable',
  `VariableunitsID` int(11) NOT NULL,
  `SampleMedium` varchar(255) NOT NULL DEFAULT 'Unknown',
  `ValueType` varchar(255) NOT NULL DEFAULT 'Unknown',
  `IsRegular` tinyint(1) NOT NULL DEFAULT '0',
  `TimeSupport` double NOT NULL DEFAULT '0',
  `TimeunitsID` int(11) NOT NULL DEFAULT '0',
  `DataType` varchar(255) NOT NULL DEFAULT 'Unknown',
  `GeneralCategory` varchar(255) NOT NULL DEFAULT 'Unknown',
  `NoDataValue` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`VariableID`),
  UNIQUE KEY `AK_Variables_VariableCode` (`VariableCode`),
  KEY `VariableunitsID` (`VariableunitsID`),
  KEY `TimeunitsID` (`TimeunitsID`),
  KEY `DataType` (`DataType`),
  KEY `GeneralCategory` (`GeneralCategory`),
  KEY `SampleMedium` (`SampleMedium`),
  KEY `ValueType` (`ValueType`),
  KEY `VariableName` (`VariableName`),
  KEY `Speciation` (`Speciation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



--
-- Table structure for table `varmeth`
--

DROP TABLE IF EXISTS `varmeth`;
CREATE TABLE IF NOT EXISTS `varmeth` (
  `VariableID` varchar(50) NOT NULL,
  `VariableCode` varchar(25) NOT NULL,
  `VariableName` varchar(50) NOT NULL,
  `DataType` varchar(50) NOT NULL,
  `MethodID` varchar(50) DEFAULT NULL,
  KEY `VariableID` (`VariableID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Index to connect variables to specific methods for narrowing';



--
-- Table structure for table `verticaldatumcv`
--

DROP TABLE IF EXISTS `verticaldatumcv`;
CREATE TABLE IF NOT EXISTS `verticaldatumcv` (
  `Term` varchar(255) NOT NULL,
  `Definition` text,
  PRIMARY KEY (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `verticaldatumcv`
--

INSERT INTO `verticaldatumcv` (`Term`, `Definition`) VALUES
('MSL', 'Mean Sea Level'),
('NAVD88', 'Datum Vertical de Norte America del 1988'),
('NGVD29', 'Datum Vertical Nacional Geodésico del 1929'),
('Something', 'El Datum Vertical es desconocido.'),
('Unknown', 'El Datum Vertical es desconocido.');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `FK_Categories_Variables` FOREIGN KEY (`VariableID`) REFERENCES `variables` (`VariableID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `datavalues`
--
ALTER TABLE `datavalues`
  ADD CONSTRAINT `FK_DataValues_censorcodecv` FOREIGN KEY (`CensorCode`) REFERENCES `censorcodecv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_methods` FOREIGN KEY (`MethodID`) REFERENCES `methods` (`MethodID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_OffsetTypes` FOREIGN KEY (`OffsetTypeID`) REFERENCES `offsettypes` (`OffsetTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_Qualifiers` FOREIGN KEY (`QualifierID`) REFERENCES `qualifiers` (`QualifierID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_qualitycontrollevels` FOREIGN KEY (`QualityControlLevelID`) REFERENCES `qualitycontrollevels` (`QualityControlLevelID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_Samples` FOREIGN KEY (`SampleID`) REFERENCES `samples` (`SampleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_Sites` FOREIGN KEY (`SiteID`) REFERENCES `sites` (`SiteID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DataValues_Sources` FOREIGN KEY (`SourceID`) REFERENCES `sources` (`SourceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DataValues_Variables` FOREIGN KEY (`VariableID`) REFERENCES `variables` (`VariableID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `derivedfrom`
--
ALTER TABLE `derivedfrom`
  ADD CONSTRAINT `FK_DerivedFrom_DataValues` FOREIGN KEY (`ValueID`) REFERENCES `datavalues` (`ValueID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `FK_Groups_DataValues` FOREIGN KEY (`ValueID`) REFERENCES `datavalues` (`ValueID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Groups_GroupDescriptions` FOREIGN KEY (`GroupID`) REFERENCES `groupdescriptions` (`GroupID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `isometadata`
--
ALTER TABLE `isometadata`
  ADD CONSTRAINT `FK_isometadata_topiccategorycv` FOREIGN KEY (`TopicCategory`) REFERENCES `topiccategorycv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `offsettypes`
--
ALTER TABLE `offsettypes`
  ADD CONSTRAINT `FK_OffsetTypes_units` FOREIGN KEY (`OffsetunitsID`) REFERENCES `units` (`unitsID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `samples`
--
ALTER TABLE `samples`
  ADD CONSTRAINT `FK_Samples_labmethods` FOREIGN KEY (`LabMethodID`) REFERENCES `labmethods` (`LabMethodID`),
  ADD CONSTRAINT `FK_Samples_sampletypecv` FOREIGN KEY (`SampleType`) REFERENCES `sampletypecv` (`Term`);

--
-- Constraints for table `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `FK_Sites_sitetypecv` FOREIGN KEY (`SiteType`) REFERENCES `sitetypecv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Sites_spatialreferences` FOREIGN KEY (`LatLongDatumID`) REFERENCES `spatialreferences` (`SpatialReferenceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Sites_spatialreferences1` FOREIGN KEY (`LocalProjectionID`) REFERENCES `spatialreferences` (`SpatialReferenceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Sites_verticaldatumcv` FOREIGN KEY (`VerticalDatum`) REFERENCES `verticaldatumcv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sources`
--
ALTER TABLE `sources`
  ADD CONSTRAINT `FK_Sources_ISOMetaData` FOREIGN KEY (`MetadataID`) REFERENCES `isometadata` (`MetadataID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `variables`
--
ALTER TABLE `variables`
  ADD CONSTRAINT `FK_Variables_datatypecv` FOREIGN KEY (`DataType`) REFERENCES `datatypecv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Variables_GeneralCategoryCV` FOREIGN KEY (`GeneralCategory`) REFERENCES `generalcategorycv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Variables_samplemediumcv` FOREIGN KEY (`SampleMedium`) REFERENCES `samplemediumcv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Variables_speciationcv` FOREIGN KEY (`Speciation`) REFERENCES `speciationcv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Variables_units` FOREIGN KEY (`VariableunitsID`) REFERENCES `units` (`unitsID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Variables_units1` FOREIGN KEY (`TimeunitsID`) REFERENCES `units` (`unitsID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Variables_valuetypecv` FOREIGN KEY (`ValueType`) REFERENCES `valuetypecv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Variables_variablenamecv` FOREIGN KEY (`VariableName`) REFERENCES `variablenamecv` (`Term`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
