-- 
-- Table structure for table `tc_pages`
-- 

CREATE TABLE tc_pages (
  id int(11) NOT NULL auto_increment,
  title varchar(50) NOT NULL,
  content text,
  updated_at datetime NOT NULL,
  user_id int(11),
  PRIMARY KEY (id)
);

-- 
-- Table structure for table `tc_pages_tags`
-- 

CREATE TABLE tc_pages_tags (
  id int(11) NOT NULL auto_increment,
  page_id int(11) NOT NULL,
  tag_id int(11) NOT NULL,
  PRIMARY KEY (id)
);

-- 
-- Table structure for table `tc_tags`
-- 

CREATE TABLE tc_tags (
  id int(11) NOT NULL auto_increment,
  tag varchar(50) NOT NULL,
  PRIMARY KEY  (id)
);

-- 
-- Table structure for table `tc_users`
-- 

CREATE TABLE tc_users (
  id int(11) NOT NULL auto_increment,
  user_name varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  full_name varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  PRIMARY KEY  (id)
);

--
-- Table structure for table `tc_discussion`
--

CREATE TABLE `tc_discussion` (
  id int(11) NOT NULL auto_increment,
  page_id int(11) NOT NULL ,
  author varchar(100) NOT NULL ,
  email varchar(100) NULL ,
  url varchar(100) NULL ,
  comment text NOT NULL,
  PRIMARY KEY (id)
);
