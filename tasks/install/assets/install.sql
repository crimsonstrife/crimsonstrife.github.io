--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(45) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_desc` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_name`, `page_title`, `page_desc`) VALUES
(1, 'dashboard_view', 'Home | Taskfeed', 'Manage All Your Tasks Here'),
(2, 'account_view', 'Account | Taskfeed', 'Change Password'),
(3, 'new_project_wizard/step1_view', 'Step1 : Create New Project | Taskfeed', 'Create A New Project'),
(4, 'new_project_wizard/step2_view', 'Step2 : Create New Project | Taskfeed', 'Create A New Project'),
(5, 'new_project_wizard/step3_view', 'Step3 : Create New Project | Taskfeed', 'Create A New Project'),
(6, 'new_project_wizard/completed_view', 'Project Created Successfully | Taskfeed', 'Project Created Successfully'),
(7, 'project/project_dashboard_view', 'Project Detail | Taskfeed', 'Project Detail'),
(8, 'members/manage_projects_view', 'Manage Your Projects | Taskfeed', 'Manage Your Projects '),
(9, 'members/manage_tasks_view', 'Manage Your Tasks | Taskfeed', 'Manage Your Tasks'),
(10, 'project/project_edit_view', 'Edit Project', 'Edit Project'),
(11, 'project/join_error_view', 'Error Joining Project', 'Error Joining Project'),
(12, 'project/project_discussion_view', 'Discussion Thread', 'Discussion Thread'),
(13, 'project/project_todo_view', 'Project To-Do', 'Project To-Do'),
(14, 'project/project_milestones_view', 'Milestones', 'Milestones'),
(15, 'members/all_feeds_view', 'All Taskfeeds', 'All Taskfeeds'),
(16, 'priority/priority_shared_view', 'Priority Queue Shared With You', 'Priority Queue Shared With You'),
(17, 'priority/priority_manage_view', 'Priority Queue Created By You', 'Priority Queue Created By You'),
(18, 'priority/priority_view', 'Priority Queue', 'Priority Queue'),
(19, 'members/profile_view', 'Public Business Card', 'Public Business Card'),
(20, 'members/profile_edit_view', 'Update Your Profile Information', 'Update Your Profile Information'),
(21, 'team/manage_team_view', 'Manage Your Taskfeed Team', 'Your Taskfeed Team'),
(22, 'team/team_invites_view', 'Invitations to join Teams', 'Invitations to join Teams'),
(23, 'team/team_joined_view', 'Teams you have already Joined', 'Teams you have already Joined'),
(24, 'general/about_view', 'About | Taskfeed', 'About | Taskfeed'),
(25, 'general/terms_view', 'Terms Of Services | Taskfeed', 'Terms Of Services | Taskfeed'),
(26, 'general/privacy_view', 'Privacy Policy | Taskfeed', 'Privacy Policy | Taskfeed');

-- --------------------------------------------------------

--
-- Table structure for table `tf_discussion`
--

CREATE TABLE IF NOT EXISTS `tf_discussion` (
  `pd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pd_comment` longtext,
  `pd_creator` tinyint(3) unsigned DEFAULT NULL,
  `pd_time` varchar(255) DEFAULT NULL,
  `pd_pid` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`pd_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_discussion_files`
--

CREATE TABLE IF NOT EXISTS `tf_discussion_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tfile_pid` int(10) unsigned DEFAULT NULL,
  `tfile_real_name` varchar(255) DEFAULT NULL,
  `tfile_server_name` varchar(255) DEFAULT NULL,
  `tfile_ext` varchar(5) DEFAULT NULL,
  `tfile_created_by` tinyint(3) unsigned DEFAULT NULL,
  `tfile_created` varchar(45) DEFAULT NULL,
  `tfile_pdid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_milestones`
--

CREATE TABLE IF NOT EXISTS `tf_milestones` (
  `pm_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pm_desc` longtext,
  `pm_due` varchar(45) DEFAULT NULL,
  `pm_time` varchar(45) DEFAULT NULL,
  `pm_pid` int(10) unsigned DEFAULT NULL,
  `pm_creator` int(10) unsigned DEFAULT NULL,
  `pm_title` text,
  PRIMARY KEY (`pm_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_notification`
--

CREATE TABLE IF NOT EXISTS `tf_notification` (
  `pn_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pn_to` tinyint(3) unsigned DEFAULT NULL,
  `pn_from` tinyint(3) unsigned DEFAULT NULL,
  `pn_created` varchar(255) DEFAULT NULL,
  `pn_content` longtext,
  `pn_link` text,
  `pn_title` text,
  `pn_unread` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`pn_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_priority_data`
--

CREATE TABLE IF NOT EXISTS `tf_priority_data` (
  `pdata_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pdata_title` text NOT NULL,
  `pdata_order` bigint(20) unsigned NOT NULL,
  `pdata_progress` varchar(45) DEFAULT NULL,
  `pdata_last_modified` varchar(255) NOT NULL,
  `prio_id` bigint(20) unsigned NOT NULL,
  `pdata_requested_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`pdata_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_priority_queue`
--

CREATE TABLE IF NOT EXISTS `tf_priority_queue` (
  `prio_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prio_owner` varchar(45) DEFAULT NULL,
  `prio_name` text,
  `prio_created` varchar(255) DEFAULT NULL,
  `prio_last_modified` varchar(255) DEFAULT NULL,
  `prio_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`prio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_priority_queue_share`
--

CREATE TABLE IF NOT EXISTS `tf_priority_queue_share` (
  `share_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prio_id` bigint(20) unsigned NOT NULL,
  `prio_share_id` bigint(20) unsigned NOT NULL,
  `prio_owner_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`share_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_projects`
--

CREATE TABLE IF NOT EXISTS `tf_projects` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_title` text,
  `p_created_by` int(10) unsigned DEFAULT NULL,
  `p_created_date` varchar(255) DEFAULT NULL,
  `p_deadline` varchar(255) DEFAULT NULL,
  `p_active` tinyint(3) unsigned DEFAULT NULL,
  `p_status` varchar(25) DEFAULT NULL,
  `p_group` tinyint(3) unsigned DEFAULT NULL,
  `p_desc` longtext,
  `p_discussion` varchar(25) DEFAULT NULL,
  `p_milestone` varchar(25) DEFAULT NULL,
  `p_todo` varchar(25) DEFAULT NULL,
  `p_join_token` varchar(255) DEFAULT NULL,
  `p_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`pid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_project_group`
--

CREATE TABLE IF NOT EXISTS `tf_project_group` (
  `pg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pg_name` text,
  `pg_active` tinyint(3) unsigned DEFAULT NULL,
  `pg_owner` int(10) unsigned DEFAULT NULL,
  `pg_created` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`pg_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_project_todo`
--

CREATE TABLE IF NOT EXISTS `tf_project_todo` (
  `pt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pt_priority` varchar(45) DEFAULT NULL,
  `pt_pid` int(10) unsigned DEFAULT NULL,
  `pt_comment` text,
  `pt_time` varchar(255) DEFAULT NULL,
  `pt_creator` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`pt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_project_user`
--

CREATE TABLE IF NOT EXISTS `tf_project_user` (
  `pu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pu_uid` varchar(45) DEFAULT NULL,
  `pu_last_viewed` varchar(45) DEFAULT NULL,
  `pu_pid` int(10) unsigned DEFAULT NULL,
  `pu_joined` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`pu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tf_team_invite`
--

CREATE TABLE IF NOT EXISTS `tf_team_invite` (
  `tf_tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `to_email` varchar(255) DEFAULT NULL,
  `from_member` varchar(255) DEFAULT NULL,
  `created` varchar(255) DEFAULT NULL,
  `accepted` varchar(255) DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`tf_tid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_pass` varchar(45) DEFAULT NULL,
  `user_fname` varchar(255) DEFAULT NULL,
  `user_lname` varchar(255) DEFAULT NULL,
  `user_status` tinyint(3) unsigned DEFAULT NULL,
  `user_online` tinyint(3) unsigned DEFAULT NULL,
  `user_avatar_img` varchar(45) DEFAULT NULL,
  `user_avatar_ext` varchar(5) DEFAULT NULL,
  `user_created` varchar(45) DEFAULT NULL,
  `user_role` varchar(45) DEFAULT NULL,
  `user_crypt` varchar(255) DEFAULT NULL,
  `crypt_activated` varchar(255) DEFAULT NULL,
  `user_phone` varchar(255) DEFAULT NULL,
  `user_address_1` varchar(255) DEFAULT NULL,
  `user_address_2` varchar(255) DEFAULT NULL,
  `user_designation` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
