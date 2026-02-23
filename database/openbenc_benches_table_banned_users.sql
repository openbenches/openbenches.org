
-- --------------------------------------------------------

--
-- Table structure for table `banned_users`
--

CREATE TABLE `banned_users` (
  `bannedID` bigint(20) NOT NULL,
  `provider` varchar(64) NOT NULL,
  `providerID` varchar(64) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='List of banned users. Doesn''t use UserID due to anon IP';

--
-- Dumping data for table `banned_users`
--

INSERT INTO `banned_users` (`bannedID`, `provider`, `providerID`, `reason`) VALUES
(1, 'anon', '2.218.226.214', 'Repeated uploading of ineligible benches.'),
(3, 'wordpress', '4658229', 'This test user is banned.'),
(5, 'facebook', '1092852669552896', 'Vandalism.'),
(6, 'anon', '31.94.0.214', 'Vandalism'),
(7, 'github', '162350019', 'Vandalism.');
