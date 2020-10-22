
-- --------------------------------------------------------

--
-- Table structure for table `media_types`
--

CREATE TABLE `media_types` (
  `shortName` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longName` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `displayOrder` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media_types`
--

INSERT INTO `media_types` (`shortName`, `longName`, `displayOrder`) VALUES
('360', 'VR 360 panoramic ', 4),
('bench', 'Long shot of the bench', 1),
('inscription', 'Close-up of the inscription', 0),
('pano', 'Panoramic view', 3),
('video', 'Video of the bench ', 5),
('view', 'View from the bench', 2);
