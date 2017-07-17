SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `benches` (
  `benchID` bigint(20) NOT NULL,
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `inscription` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `present` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `licences` (
  `shortName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'CC BY-SA 4.0',
  `longName` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Creative Commons Attribution-ShareAlike 4.0 International',
  `url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'https://creativecommons.org/licenses/by-sa/4.0/'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `licences` (`shortName`, `longName`, `url`) VALUES
('CC BY 2.0', 'Creative Commons Attribution 2.0 Generic', 'https://creativecommons.org/licenses/by/2.0/'),
('CC BY-NC 2.0', 'Creative Commons Attribution-NonCommercial 2.0 Generic', 'https://creativecommons.org/licenses/by-nc/2.0/'),
('CC BY-NC-ND 2.0', 'Creative Commons Attribution-NonCommercial-NoDerivs 2.0 Generic', 'https://creativecommons.org/licenses/by-nc-nd/2.0/'),
('CC BY-NC-SA 2.0', 'Attribution-NonCommercial-ShareAlike 2.0 Generic', 'https://creativecommons.org/licenses/by-nc-sa/2.0/'),
('CC BY-ND 2.0', 'Attribution-NoDerivs 2.0 Generic', 'https://creativecommons.org/licenses/by-nd/2.0/'),
('CC BY-SA 2.0', 'Creative Commons Attribution-ShareAlike 2.0 Generic ', 'https://creativecommons.org/licenses/by-sa/2.0/'),
('CC BY-SA 4.0', 'Creative Commons Attribution-ShareAlike 4.0 International', 'https://creativecommons.org/licenses/by-sa/4.0/');

CREATE TABLE `media` (
  `mediaID` bigint(20) NOT NULL,
  `benchID` bigint(20) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importURL` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licence` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'CC BY-SA 4.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `userID` bigint(20) NOT NULL,
  `provider` varchar(64) NOT NULL,
  `providerID` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `benches`
  ADD PRIMARY KEY (`benchID`),
  ADD KEY `contributor` (`userID`);

ALTER TABLE `licences`
  ADD PRIMARY KEY (`shortName`);

ALTER TABLE `media`
  ADD PRIMARY KEY (`mediaID`),
  ADD KEY `contributorID` (`userID`),
  ADD KEY `licence` (`licence`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);


ALTER TABLE `benches`
  MODIFY `benchID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;
ALTER TABLE `media`
  MODIFY `mediaID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;
ALTER TABLE `users`
  MODIFY `userID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

ALTER TABLE `benches`
  ADD CONSTRAINT `benches_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`licence`) REFERENCES `licences` (`shortName`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
