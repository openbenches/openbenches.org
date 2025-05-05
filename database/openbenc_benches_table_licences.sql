
-- --------------------------------------------------------

--
-- Table structure for table `licences`
--

CREATE TABLE `licences` (
  `shortName` varchar(32) NOT NULL DEFAULT 'CC BY-SA 4.0',
  `longName` varchar(512) NOT NULL DEFAULT 'Creative Commons Attribution-ShareAlike 4.0 International',
  `url` varchar(1024) NOT NULL DEFAULT 'https://creativecommons.org/licenses/by-sa/4.0/'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `licences`
--

INSERT INTO `licences` (`shortName`, `longName`, `url`) VALUES
('CC BY 1.0', 'Creative Commons Attribution 1.0', 'https://creativecommons.org/licenses/by/1.0/'),
('CC BY 2.0', 'Creative Commons Attribution 2.0 Generic', 'https://creativecommons.org/licenses/by/2.0/'),
('CC BY 3.0', 'Creative Commons Attribution 3.0', 'https://creativecommons.org/licenses/by/3.0/'),
('CC BY 4.0', 'Creative Commons Attribution 4.0', 'https://creativecommons.org/licenses/by/4.0/'),
('CC BY-NC 2.0', 'Creative Commons Attribution-NonCommercial 2.0 Generic', 'https://creativecommons.org/licenses/by-nc/2.0/'),
('CC BY-NC-ND 2.0', 'Creative Commons Attribution-NonCommercial-NoDerivs 2.0 Generic', 'https://creativecommons.org/licenses/by-nc-nd/2.0/'),
('CC BY-NC-SA 2.0', 'Attribution-NonCommercial-ShareAlike 2.0 Generic', 'https://creativecommons.org/licenses/by-nc-sa/2.0/'),
('CC BY-ND 2.0', 'Attribution-NoDerivs 2.0 Generic', 'https://creativecommons.org/licenses/by-nd/2.0/'),
('CC BY-SA 1.0', 'Creative Commons Attribution-ShareAlike 1.0', 'https://creativecommons.org/licenses/by-sa/1.0/'),
('CC BY-SA 2.0', 'Creative Commons Attribution-ShareAlike 2.0 Generic ', 'https://creativecommons.org/licenses/by-sa/2.0/'),
('CC BY-SA 3.0', 'Creative Commons Attribution-ShareAlike 3.0', 'https://creativecommons.org/licenses/by-sa/3.0/'),
('CC BY-SA 4.0', 'Creative Commons Attribution-ShareAlike 4.0 International', 'https://creativecommons.org/licenses/by-sa/4.0/'),
('CC Zero', 'Public Domain Dedication', 'https://creativecommons.org/publicdomain/zero/1.0/');
