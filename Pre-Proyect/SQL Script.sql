-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-02-2025 a las 20:33:55
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `vgord`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agerating`
--

CREATE TABLE `agerating` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ageratinggame`
--

CREATE TABLE `ageratinggame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `AgeRating_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `collection`
--

CREATE TABLE `collection` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `collectiongame`
--

CREATE TABLE `collectiongame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Collection_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `developer`
--

CREATE TABLE `developer` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `developergame`
--

CREATE TABLE `developergame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Developer_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `engine`
--

CREATE TABLE `engine` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `franchice`
--

CREATE TABLE `franchice` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `franchicegame`
--

CREATE TABLE `franchicegame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Franchice_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `game`
--

CREATE TABLE `game` (
  `IGDB_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `cover` varchar(50) NOT NULL,
  `isSpinOff` int(11) DEFAULT NULL,
  `IGDB_url` varchar(50) DEFAULT NULL,
  `Engine_IGDB_id` int(11) DEFAULT NULL,
  `GameType_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gametype`
--

CREATE TABLE `gametype` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genre`
--

CREATE TABLE `genre` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genregame`
--

CREATE TABLE `genregame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Genre_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `link`
--

CREATE TABLE `link` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `icon` varchar(45) NOT NULL,
  `url` varchar(45) NOT NULL,
  `Game_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

CREATE TABLE `media` (
  `IGDB_id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `url` varchar(45) NOT NULL,
  `Game_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perspective`
--

CREATE TABLE `perspective` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perspectivegame`
--

CREATE TABLE `perspectivegame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Perspective_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platform`
--

CREATE TABLE `platform` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platformgame`
--

CREATE TABLE `platformgame` (
  `id` int(11) NOT NULL,
  `Platform_IGDB_id` int(11) NOT NULL,
  `Game_IGDB_id` int(11) NOT NULL,
  `releasedDate` date NOT NULL,
  `region` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playing`
--

CREATE TABLE `playing` (
  `id` int(11) NOT NULL,
  `DateStart` datetime NOT NULL,
  `DateEnd` datetime DEFAULT NULL,
  `UserGame_id` int(11) NOT NULL,
  `Status_id` int(11) NOT NULL,
  `Death` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publisher`
--

CREATE TABLE `publisher` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publishergame`
--

CREATE TABLE `publishergame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Publisher_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `region`
--

CREATE TABLE `region` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relatedgame`
--

CREATE TABLE `relatedgame` (
  `GamePrincipal_IGDB_id` int(11) NOT NULL,
  `GameRelated_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `icon` varchar(45) NOT NULL,
  `textColor` varchar(45) DEFAULT NULL,
  `bgColor` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `store`
--

CREATE TABLE `store` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `idSuscription` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `storeplatform`
--

CREATE TABLE `storeplatform` (
  `Platform_IGDB_id` int(11) NOT NULL,
  `Store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `theme`
--

CREATE TABLE `theme` (
  `IGDB_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `themegame`
--

CREATE TABLE `themegame` (
  `Game_IGDB_id` int(11) NOT NULL,
  `Theme_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `timeplayed`
--

CREATE TABLE `timeplayed` (
  `id` int(11) NOT NULL,
  `DateStart` datetime NOT NULL,
  `DateEnd` datetime DEFAULT NULL,
  `Playing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `timesbeat`
--

CREATE TABLE `timesbeat` (
  `IGDB_id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `time` varchar(45) NOT NULL,
  `Game_IGDB_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` varchar(45) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `darkmode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usergame`
--

CREATE TABLE `usergame` (
  `id` int(11) NOT NULL,
  `cost` float NOT NULL,
  `adquireDate` date DEFAULT NULL,
  `allAchivements` int(11) DEFAULT NULL,
  `PlatformGame_id` int(11) NOT NULL,
  `UserPlatform_id` int(11) NOT NULL,
  `Store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `userplatform`
--

CREATE TABLE `userplatform` (
  `id` int(11) NOT NULL,
  `Platform_IGDB_id` int(11) NOT NULL,
  `User_id` varchar(45) NOT NULL,
  `emulator` int(11) DEFAULT NULL,
  `retrocompatible` int(11) DEFAULT NULL,
  `FisicPlatform_IGDB_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agerating`
--
ALTER TABLE `agerating`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `ageratinggame`
--
ALTER TABLE `ageratinggame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`AgeRating_IGDB_id`),
  ADD KEY `fk_Game_has_AgeRating_AgeRating1_idx` (`AgeRating_IGDB_id`),
  ADD KEY `fk_Game_has_AgeRating_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `collectiongame`
--
ALTER TABLE `collectiongame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Collection_IGDB_id`),
  ADD KEY `fk_Game_has_Collection_Collection1_idx` (`Collection_IGDB_id`),
  ADD KEY `fk_Game_has_Collection_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `developergame`
--
ALTER TABLE `developergame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Developer_IGDB_id`),
  ADD KEY `fk_Game_has_Developer_Developer1_idx` (`Developer_IGDB_id`),
  ADD KEY `fk_Game_has_Developer_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `engine`
--
ALTER TABLE `engine`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `franchice`
--
ALTER TABLE `franchice`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `franchicegame`
--
ALTER TABLE `franchicegame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Franchice_IGDB_id`),
  ADD KEY `fk_Game_has_Franchice_Franchice1_idx` (`Franchice_IGDB_id`),
  ADD KEY `fk_Game_has_Franchice_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`IGDB_id`),
  ADD KEY `fk_Game_Engine1_idx` (`Engine_IGDB_id`),
  ADD KEY `fk_Game_GameType1_idx` (`GameType_IGDB_id`);

--
-- Indices de la tabla `gametype`
--
ALTER TABLE `gametype`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `genregame`
--
ALTER TABLE `genregame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Genre_IGDB_id`),
  ADD KEY `fk_Game_has_Genre_Genre1_idx` (`Genre_IGDB_id`),
  ADD KEY `fk_Game_has_Genre_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`IGDB_id`),
  ADD KEY `fk_Link_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`IGDB_id`),
  ADD KEY `fk_Media_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `perspective`
--
ALTER TABLE `perspective`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `perspectivegame`
--
ALTER TABLE `perspectivegame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Perspective_IGDB_id`),
  ADD KEY `fk_Game_has_Perspective_Perspective1_idx` (`Perspective_IGDB_id`),
  ADD KEY `fk_Game_has_Perspective_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `platform`
--
ALTER TABLE `platform`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `platformgame`
--
ALTER TABLE `platformgame`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Platform_has_Game_Game1_idx` (`Game_IGDB_id`),
  ADD KEY `fk_Platform_has_Game_Platform1_idx` (`Platform_IGDB_id`);

--
-- Indices de la tabla `playing`
--
ALTER TABLE `playing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Playing_UserGame1_idx` (`UserGame_id`),
  ADD KEY `fk_Playing_Status1_idx` (`Status_id`);

--
-- Indices de la tabla `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `publishergame`
--
ALTER TABLE `publishergame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Publisher_IGDB_id`),
  ADD KEY `fk_Game_has_Publisher_Publisher1_idx` (`Publisher_IGDB_id`),
  ADD KEY `fk_Game_has_Publisher_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `relatedgame`
--
ALTER TABLE `relatedgame`
  ADD PRIMARY KEY (`GamePrincipal_IGDB_id`,`GameRelated_IGDB_id`),
  ADD KEY `fk_Game_has_Game_Game2_idx` (`GameRelated_IGDB_id`),
  ADD KEY `fk_Game_has_Game_Game1_idx` (`GamePrincipal_IGDB_id`);

--
-- Indices de la tabla `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `storeplatform`
--
ALTER TABLE `storeplatform`
  ADD PRIMARY KEY (`Platform_IGDB_id`,`Store_id`),
  ADD KEY `fk_Platform_has_Store_Store1_idx` (`Store_id`),
  ADD KEY `fk_Platform_has_Store_Platform1_idx` (`Platform_IGDB_id`);

--
-- Indices de la tabla `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`IGDB_id`);

--
-- Indices de la tabla `themegame`
--
ALTER TABLE `themegame`
  ADD PRIMARY KEY (`Game_IGDB_id`,`Theme_IGDB_id`),
  ADD KEY `fk_Game_has_Theme_Theme1_idx` (`Theme_IGDB_id`),
  ADD KEY `fk_Game_has_Theme_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `timeplayed`
--
ALTER TABLE `timeplayed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_TimePlayed_Playing1_idx` (`Playing_id`);

--
-- Indices de la tabla `timesbeat`
--
ALTER TABLE `timesbeat`
  ADD PRIMARY KEY (`IGDB_id`),
  ADD KEY `fk_Timesbeat_Game1_idx` (`Game_IGDB_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usergame`
--
ALTER TABLE `usergame`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_UserGame_PlatformGame1_idx` (`PlatformGame_id`),
  ADD KEY `fk_UserGame_UserPlatform1_idx` (`UserPlatform_id`),
  ADD KEY `fk_UserGame_Store1_idx` (`Store_id`);

--
-- Indices de la tabla `userplatform`
--
ALTER TABLE `userplatform`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Platform_has_User_User1_idx` (`User_id`),
  ADD KEY `fk_Platform_has_User_Platform1_idx` (`Platform_IGDB_id`),
  ADD KEY `fk_UserPlatform_Platform1_idx` (`FisicPlatform_IGDB_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `platformgame`
--
ALTER TABLE `platformgame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `playing`
--
ALTER TABLE `playing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `timeplayed`
--
ALTER TABLE `timeplayed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usergame`
--
ALTER TABLE `usergame`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `userplatform`
--
ALTER TABLE `userplatform`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ageratinggame`
--
ALTER TABLE `ageratinggame`
  ADD CONSTRAINT `fk_Game_has_AgeRating_AgeRating1` FOREIGN KEY (`AgeRating_IGDB_id`) REFERENCES `agerating` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_AgeRating_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `collectiongame`
--
ALTER TABLE `collectiongame`
  ADD CONSTRAINT `fk_Game_has_Collection_Collection1` FOREIGN KEY (`Collection_IGDB_id`) REFERENCES `collection` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Collection_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `developergame`
--
ALTER TABLE `developergame`
  ADD CONSTRAINT `fk_Game_has_Developer_Developer1` FOREIGN KEY (`Developer_IGDB_id`) REFERENCES `developer` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Developer_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `franchicegame`
--
ALTER TABLE `franchicegame`
  ADD CONSTRAINT `fk_Game_has_Franchice_Franchice1` FOREIGN KEY (`Franchice_IGDB_id`) REFERENCES `franchice` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Franchice_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `fk_Game_Engine1` FOREIGN KEY (`Engine_IGDB_id`) REFERENCES `engine` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_GameType1` FOREIGN KEY (`GameType_IGDB_id`) REFERENCES `gametype` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `genregame`
--
ALTER TABLE `genregame`
  ADD CONSTRAINT `fk_Game_has_Genre_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Genre_Genre1` FOREIGN KEY (`Genre_IGDB_id`) REFERENCES `genre` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `link`
--
ALTER TABLE `link`
  ADD CONSTRAINT `fk_Link_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_Media_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `perspectivegame`
--
ALTER TABLE `perspectivegame`
  ADD CONSTRAINT `fk_Game_has_Perspective_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Perspective_Perspective1` FOREIGN KEY (`Perspective_IGDB_id`) REFERENCES `perspective` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `platformgame`
--
ALTER TABLE `platformgame`
  ADD CONSTRAINT `fk_Platform_has_Game_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Platform_has_Game_Platform1` FOREIGN KEY (`Platform_IGDB_id`) REFERENCES `platform` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `playing`
--
ALTER TABLE `playing`
  ADD CONSTRAINT `fk_Playing_Status1` FOREIGN KEY (`Status_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Playing_UserGame1` FOREIGN KEY (`UserGame_id`) REFERENCES `usergame` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `publishergame`
--
ALTER TABLE `publishergame`
  ADD CONSTRAINT `fk_Game_has_Publisher_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Publisher_Publisher1` FOREIGN KEY (`Publisher_IGDB_id`) REFERENCES `publisher` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `relatedgame`
--
ALTER TABLE `relatedgame`
  ADD CONSTRAINT `fk_Game_has_Game_Game1` FOREIGN KEY (`GamePrincipal_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Game_Game2` FOREIGN KEY (`GameRelated_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `storeplatform`
--
ALTER TABLE `storeplatform`
  ADD CONSTRAINT `fk_Platform_has_Store_Platform1` FOREIGN KEY (`Platform_IGDB_id`) REFERENCES `platform` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Platform_has_Store_Store1` FOREIGN KEY (`Store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `themegame`
--
ALTER TABLE `themegame`
  ADD CONSTRAINT `fk_Game_has_Theme_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Game_has_Theme_Theme1` FOREIGN KEY (`Theme_IGDB_id`) REFERENCES `theme` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `timeplayed`
--
ALTER TABLE `timeplayed`
  ADD CONSTRAINT `fk_TimePlayed_Playing1` FOREIGN KEY (`Playing_id`) REFERENCES `playing` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `timesbeat`
--
ALTER TABLE `timesbeat`
  ADD CONSTRAINT `fk_Timesbeat_Game1` FOREIGN KEY (`Game_IGDB_id`) REFERENCES `game` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usergame`
--
ALTER TABLE `usergame`
  ADD CONSTRAINT `fk_UserGame_PlatformGame1` FOREIGN KEY (`PlatformGame_id`) REFERENCES `platformgame` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UserGame_Store1` FOREIGN KEY (`Store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UserGame_UserPlatform1` FOREIGN KEY (`UserPlatform_id`) REFERENCES `userplatform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `userplatform`
--
ALTER TABLE `userplatform`
  ADD CONSTRAINT `fk_Platform_has_User_Platform1` FOREIGN KEY (`Platform_IGDB_id`) REFERENCES `platform` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Platform_has_User_User1` FOREIGN KEY (`User_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_UserPlatform_Platform1` FOREIGN KEY (`FisicPlatform_IGDB_id`) REFERENCES `platform` (`IGDB_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;
