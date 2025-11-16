
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
