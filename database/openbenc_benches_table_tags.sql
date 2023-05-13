
-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tagID` bigint(20) NOT NULL,
  `tagText` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagID`, `tagText`) VALUES
(15, '18th century'),
(14, '19th century'),
(18, 'beach'),
(1, 'cat'),
(17, 'colleague'),
(2, 'dog'),
(13, 'emoji'),
(42, 'famous'),
(8, 'funny'),
(19, 'graveyard'),
(22, 'illegible'),
(9, 'indoors'),
(6, 'metal'),
(11, 'multiple plaques'),
(20, 'park'),
(16, 'picture'),
(10, 'poem'),
(12, 'quote'),
(43, 'statue'),
(7, 'stone'),
(21, 'train station'),
(44, 'twinned'),
(5, 'wooden');
