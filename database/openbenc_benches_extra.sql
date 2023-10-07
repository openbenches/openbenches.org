
--
-- Indexes for dumped tables
--

--
-- Indexes for table `benches`
--
ALTER TABLE `benches`
  ADD PRIMARY KEY (`benchID`),
  ADD KEY `contributor` (`userID`);

--
-- Indexes for table `benches_history`
--
ALTER TABLE `benches_history`
  ADD KEY `benchID` (`benchID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `licences`
--
ALTER TABLE `licences`
  ADD PRIMARY KEY (`shortName`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`mediaID`),
  ADD KEY `contributorID` (`userID`),
  ADD KEY `licence` (`licence`),
  ADD KEY `media_type` (`media_type`),
  ADD KEY `benchID` (`benchID`);

--
-- Indexes for table `media_types`
--
ALTER TABLE `media_types`
  ADD PRIMARY KEY (`shortName`);

--
-- Indexes for table `merged_benches`
--
ALTER TABLE `merged_benches`
  ADD UNIQUE KEY `benchID` (`benchID`),
  ADD KEY `mergedID` (`mergedID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagID`),
  ADD UNIQUE KEY `tagText` (`tagText`);

--
-- Indexes for table `tag_map`
--
ALTER TABLE `tag_map`
  ADD PRIMARY KEY (`mapID`),
  ADD KEY `tag_map_ibfk_1` (`benchID`),
  ADD KEY `tag_map_ibfk_2` (`tagID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `benches`
--
ALTER TABLE `benches`
  MODIFY `benchID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30357;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `mediaID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72427;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tagID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tag_map`
--
ALTER TABLE `tag_map`
  MODIFY `mapID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17518;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8163;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `benches`
--
ALTER TABLE `benches`
  ADD CONSTRAINT `benches_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `benches_history`
--
ALTER TABLE `benches_history`
  ADD CONSTRAINT `benches_history_ibfk_1` FOREIGN KEY (`benchID`) REFERENCES `benches` (`benchID`),
  ADD CONSTRAINT `benches_history_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`licence`) REFERENCES `licences` (`shortName`),
  ADD CONSTRAINT `media_ibfk_3` FOREIGN KEY (`media_type`) REFERENCES `media_types` (`shortName`),
  ADD CONSTRAINT `media_ibfk_4` FOREIGN KEY (`benchID`) REFERENCES `benches` (`benchID`);

--
-- Constraints for table `merged_benches`
--
ALTER TABLE `merged_benches`
  ADD CONSTRAINT `merged_benches_ibfk_1` FOREIGN KEY (`benchID`) REFERENCES `benches` (`benchID`),
  ADD CONSTRAINT `merged_benches_ibfk_2` FOREIGN KEY (`mergedID`) REFERENCES `benches` (`benchID`);

--
-- Constraints for table `tag_map`
--
ALTER TABLE `tag_map`
  ADD CONSTRAINT `tag_map_ibfk_1` FOREIGN KEY (`benchID`) REFERENCES `benches` (`benchID`),
  ADD CONSTRAINT `tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `tags` (`tagID`);
