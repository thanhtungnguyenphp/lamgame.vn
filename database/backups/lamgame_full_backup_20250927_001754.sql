-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: localhost    Database: lamgame
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_address_id` int unsigned DEFAULT NULL,
  `customer_id` int unsigned DEFAULT NULL COMMENT 'null if guest checkout',
  `cart_id` int unsigned DEFAULT NULL COMMENT 'only for cart_addresses',
  `order_id` int unsigned DEFAULT NULL COMMENT 'only for order_addresses',
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_address` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'only for customer_addresses',
  `use_for_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_customer_id_foreign` (`customer_id`),
  KEY `addresses_cart_id_foreign` (`cart_id`),
  KEY `addresses_order_id_foreign` (`order_id`),
  KEY `addresses_parent_address_id_foreign` (`parent_address_id`),
  CONSTRAINT `addresses_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  CONSTRAINT `addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `addresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `addresses_parent_address_id_foreign` FOREIGN KEY (`parent_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_password_resets`
--

DROP TABLE IF EXISTS `admin_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `admin_password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_password_resets`
--

LOCK TABLES `admin_password_resets` WRITE;
/*!40000 ALTER TABLE `admin_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `role_id` int unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  UNIQUE KEY `admins_api_token_unique` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Example','admin@example.com','$2y$10$yGuwQfe9xS4Ht03IxbsLiuAaNWIKl7QgFWwAD3mwlxSu9J5tFJwB6','5qU2nicRwfOXw21ycOtoguKLeBwytPSkoW8Y5oKZEQ2FeqN3U0LfGhHycBsQz89rMKewVYsO8IXbaCmb',1,1,NULL,NULL,'2025-09-05 17:10:08','2025-09-05 17:10:08');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_families`
--

DROP TABLE IF EXISTS `attribute_families`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_families` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_user_defined` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_families`
--

LOCK TABLES `attribute_families` WRITE;
/*!40000 ALTER TABLE `attribute_families` DISABLE KEYS */;
INSERT INTO `attribute_families` VALUES (1,'default','Default 1',1,1);
/*!40000 ALTER TABLE `attribute_families` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_group_mappings`
--

DROP TABLE IF EXISTS `attribute_group_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_group_mappings` (
  `attribute_id` int unsigned NOT NULL,
  `attribute_group_id` int unsigned NOT NULL,
  `position` int DEFAULT NULL,
  PRIMARY KEY (`attribute_id`,`attribute_group_id`),
  KEY `attribute_group_mappings_attribute_group_id_foreign` (`attribute_group_id`),
  CONSTRAINT `attribute_group_mappings_attribute_group_id_foreign` FOREIGN KEY (`attribute_group_id`) REFERENCES `attribute_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attribute_group_mappings_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_group_mappings`
--

LOCK TABLES `attribute_group_mappings` WRITE;
/*!40000 ALTER TABLE `attribute_group_mappings` DISABLE KEYS */;
INSERT INTO `attribute_group_mappings` VALUES (1,1,1),(2,1,3),(3,1,4),(4,1,5),(5,6,1),(6,6,2),(7,6,3),(8,6,4),(9,2,1),(10,2,2),(11,4,1),(12,4,2),(13,4,3),(14,4,4),(15,4,5),(16,3,1),(17,3,2),(18,3,3),(19,5,1),(20,5,2),(21,5,3),(22,5,4),(23,1,6),(24,1,7),(25,1,8),(26,6,5),(27,1,2),(28,7,1),(29,8,1),(30,8,2),(31,9,3),(32,9,4),(33,8,5),(34,9,6),(35,9,7),(36,9,8),(37,8,9),(38,9,10),(40,10,1),(41,10,2),(42,10,3),(43,10,4),(44,10,5),(45,11,6),(46,11,7),(47,11,8),(48,12,9),(49,12,10),(50,12,11),(51,12,12),(52,12,13),(53,10,14),(54,10,15),(55,12,16),(56,1,999),(57,1,999);
/*!40000 ALTER TABLE `attribute_group_mappings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_groups`
--

DROP TABLE IF EXISTS `attribute_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_family_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column` int NOT NULL DEFAULT '1',
  `position` int NOT NULL,
  `is_user_defined` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_groups_attribute_family_id_name_unique` (`attribute_family_id`,`name`),
  CONSTRAINT `attribute_groups_attribute_family_id_foreign` FOREIGN KEY (`attribute_family_id`) REFERENCES `attribute_families` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_groups`
--

LOCK TABLES `attribute_groups` WRITE;
/*!40000 ALTER TABLE `attribute_groups` DISABLE KEYS */;
INSERT INTO `attribute_groups` VALUES (1,'general',1,'General',1,1,0),(2,'description',1,'Description',1,2,0),(3,'meta_description',1,'Meta Description',1,3,0),(4,'price',1,'Price',2,1,0),(5,'shipping',1,'Shipping',2,2,0),(6,'settings',1,'Settings',2,3,0),(7,'inventories',1,'Inventories',2,4,0),(8,'game_info',1,'Game Information',1,10,1),(9,'technical_details',1,'Technical Details',2,11,1),(10,'job_info',1,'Job Information',1,20,1),(11,'job_requirements',1,'Job Requirements',2,21,1),(12,'job_benefits',1,'Benefits & Application',3,22,1),(13,NULL,1,'Source Game Info',1,10,1);
/*!40000 ALTER TABLE `attribute_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_option_translations`
--

DROP TABLE IF EXISTS `attribute_option_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_option_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `attribute_option_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_option_locale_unique` (`attribute_option_id`,`locale`),
  CONSTRAINT `attribute_option_translations_attribute_option_id_foreign` FOREIGN KEY (`attribute_option_id`) REFERENCES `attribute_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_option_translations`
--

LOCK TABLES `attribute_option_translations` WRITE;
/*!40000 ALTER TABLE `attribute_option_translations` DISABLE KEYS */;
INSERT INTO `attribute_option_translations` VALUES (1,1,'vi','Red'),(2,2,'vi','Green'),(3,3,'vi','Yellow'),(4,4,'vi','Black'),(5,5,'vi','White'),(6,6,'vi','S'),(7,7,'vi','M'),(8,8,'vi','L'),(9,9,'vi','XL'),(10,10,'vi','Action - Hành động'),(11,11,'vi','Adventure - Phiêu lưu'),(12,12,'vi','RPG - Nhập vai'),(13,13,'vi','Strategy - Chiến thuật'),(14,14,'vi','Puzzle - Giải đố'),(15,15,'vi','Racing - Đua xe'),(16,16,'vi','Sports - Thể thao'),(17,17,'vi','Simulation - Mô phỏng'),(18,18,'vi','Casual - Giải trí'),(19,19,'vi','Educational - Giáo dục'),(20,20,'vi','Android'),(21,21,'vi','iOS'),(22,22,'vi','Windows PC'),(23,23,'vi','Mac OS'),(24,24,'vi','Linux'),(25,25,'vi','Web Browser'),(26,26,'vi','Nintendo Switch'),(27,27,'vi','PlayStation'),(28,28,'vi','Xbox'),(29,29,'vi','Unity'),(30,30,'vi','Unreal Engine'),(31,31,'vi','Godot'),(32,32,'vi','Construct 3'),(33,33,'vi','GameMaker Studio'),(34,34,'vi','Cocos2d'),(35,35,'vi','HTML5/JavaScript'),(36,36,'vi','Flutter'),(37,37,'vi','React Native'),(38,38,'vi','Native (Java/Kotlin)'),(39,39,'vi','Native (Swift/Objective-C)'),(40,40,'vi','C#'),(41,41,'vi','JavaScript'),(42,42,'vi','Python'),(43,43,'vi','Java'),(44,44,'vi','Kotlin'),(45,45,'vi','Swift'),(46,46,'vi','C++'),(47,47,'vi','HTML5/CSS3'),(48,48,'vi','GDScript'),(49,49,'vi','Lua'),(50,50,'vi','Người mới bắt đầu'),(51,51,'vi','Trung bình'),(52,52,'vi','Nâng cao'),(53,53,'vi','Chuyên gia'),(54,54,'vi','Không hỗ trợ'),(55,55,'vi','Email hỗ trợ'),(56,56,'vi','Hỗ trợ 1-1'),(57,57,'vi','Hỗ trợ trọn đời'),(58,58,'vi','Sử dụng cá nhân'),(59,59,'vi','Thương mại'),(60,60,'vi','Mở rộng không giới hạn'),(61,61,'vi','Độc quyền'),(62,62,'vi','Full-time'),(63,63,'vi','Part-time'),(64,64,'vi','Contract'),(65,65,'vi','Freelance'),(66,66,'vi','Internship'),(67,67,'vi','Remote'),(68,68,'vi','Hybrid'),(69,69,'vi','Fresher (0-1 năm)'),(70,70,'vi','Junior (1-3 năm)'),(71,71,'vi','Middle (3-5 năm)'),(72,72,'vi','Senior (5+ năm)'),(73,73,'vi','Lead/Manager (7+ năm)'),(74,74,'vi','Director (10+ năm)'),(75,75,'vi','Dưới 10 triệu'),(76,76,'vi','10-20 triệu'),(77,77,'vi','20-30 triệu'),(78,78,'vi','30-50 triệu'),(79,79,'vi','50-80 triệu'),(80,80,'vi','Trên 80 triệu'),(81,81,'vi','Thỏa thuận'),(82,82,'vi','Hồ Chí Minh'),(83,83,'vi','Hà Nội'),(84,84,'vi','Đà Nẵng'),(85,85,'vi','Cần Thơ'),(86,86,'vi','Biên Hòa'),(87,87,'vi','Nha Trang'),(88,88,'vi','Remote'),(89,89,'vi','Toàn Quốc'),(90,90,'vi','Startup (1-10 người)'),(91,91,'vi','Nhỏ (10-50 người)'),(92,92,'vi','Trung bình (50-200 người)'),(93,93,'vi','Lớn (200-1000 người)'),(94,94,'vi','Tập đoàn (1000+ người)'),(95,95,'vi','Unity'),(96,96,'vi','Unreal Engine'),(97,97,'vi','C#'),(98,98,'vi','C++'),(99,99,'vi','JavaScript'),(100,100,'vi','Python'),(101,101,'vi','Java'),(102,102,'vi','Swift'),(103,103,'vi','Kotlin'),(104,104,'vi','HTML5/CSS3'),(105,105,'vi','React Native'),(106,106,'vi','Flutter'),(107,107,'vi','Photoshop'),(108,108,'vi','3ds Max'),(109,109,'vi','Maya'),(110,110,'vi','Blender'),(111,111,'vi','Git'),(112,112,'vi','Agile/Scrum'),(113,113,'vi','Game Design'),(114,114,'vi','Level Design'),(115,115,'vi','Không yêu cầu'),(116,116,'vi','Trung cấp/Cao đẳng'),(117,117,'vi','Đại học'),(118,118,'vi','Thạc sĩ'),(119,119,'vi','Tiến sĩ'),(120,120,'vi','Không yêu cầu'),(121,121,'vi','Cơ bản'),(122,122,'vi','Giao tiếp tốt'),(123,123,'vi','Thành thạo'),(124,124,'vi','Bản ngữ'),(125,125,'vi','Bảo hiểm sức khỏe'),(126,126,'vi','Bảo hiểm xã hội'),(127,127,'vi','Thưởng hiệu suất'),(128,128,'vi','Du lịch hàng năm'),(129,129,'vi','Nghỉ phép có lương'),(130,130,'vi','Đào tạo & phát triển'),(131,131,'vi','Làm việc từ xa'),(132,132,'vi','Giờ làm việc linh hoạt'),(133,133,'vi','Máy tính/laptop công ty'),(134,134,'vi','Phụ cấp ăn trua'),(135,135,'vi','Team building'),(136,136,'vi','Game room'),(137,137,'vi','Gửi email'),(138,138,'vi','Ứng tuyển online'),(139,139,'vi','Liên hệ trực tiếp'),(140,140,'vi','Qua website công ty');
/*!40000 ALTER TABLE `attribute_option_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_options`
--

DROP TABLE IF EXISTS `attribute_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` int unsigned NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT NULL,
  `swatch_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_options_attribute_id_foreign` (`attribute_id`),
  CONSTRAINT `attribute_options_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_options`
--

LOCK TABLES `attribute_options` WRITE;
/*!40000 ALTER TABLE `attribute_options` DISABLE KEYS */;
INSERT INTO `attribute_options` VALUES (1,23,'Red',1,NULL),(2,23,'Green',2,NULL),(3,23,'Yellow',3,NULL),(4,23,'Black',4,NULL),(5,23,'White',5,NULL),(6,24,'S',1,NULL),(7,24,'M',2,NULL),(8,24,'L',3,NULL),(9,24,'XL',4,NULL),(10,29,'Action',1,NULL),(11,29,'Adventure',2,NULL),(12,29,'RPG',3,NULL),(13,29,'Strategy',4,NULL),(14,29,'Simulation',5,NULL),(15,29,'Sports',6,NULL),(16,29,'Racing',7,NULL),(17,29,'Puzzle',8,NULL),(18,29,'Platform',9,NULL),(19,29,'Fighting',10,NULL),(20,30,'PC (Windows)',1,NULL),(21,30,'Mac',2,NULL),(22,30,'Linux',3,NULL),(23,30,'PlayStation',4,NULL),(24,30,'Xbox',5,NULL),(25,30,'Nintendo Switch',6,NULL),(26,30,'Mobile (iOS)',7,NULL),(27,30,'Mobile (Android)',8,NULL),(28,30,'Web Browser',9,NULL),(29,31,'Unity',1,NULL),(30,31,'Unreal Engine',2,NULL),(31,31,'Godot',3,NULL),(32,31,'Construct 3',4,NULL),(33,31,'GameMaker Studio',5,NULL),(34,31,'RPG Maker',6,NULL),(35,31,'Defold',7,NULL),(36,31,'Cocos2d',8,NULL),(37,31,'Custom Engine',9,NULL),(38,31,'Phaser',10,NULL),(39,31,'Other',11,NULL),(40,32,'C#',1,NULL),(41,32,'C++',2,NULL),(42,32,'JavaScript',3,NULL),(43,32,'Python',4,NULL),(44,32,'Java',5,NULL),(45,32,'GDScript',6,NULL),(46,32,'Blueprint',7,NULL),(47,32,'Lua',8,NULL),(48,32,'Swift',9,NULL),(49,32,'Kotlin',10,NULL),(50,33,'Beginner',1,NULL),(51,33,'Intermediate',2,NULL),(52,33,'Advanced',3,NULL),(53,33,'Expert',4,NULL),(54,37,'Documentation',1,NULL),(55,37,'Email Support',2,NULL),(56,37,'Community Forum',3,NULL),(57,37,'Source Code',4,NULL),(58,38,'Commercial',1,NULL),(59,38,'Royalty Free',2,NULL),(60,38,'Creative Commons',3,NULL),(61,38,'Open Source',4,NULL),(62,40,'Full-time',1,NULL),(63,40,'Part-time',2,NULL),(64,40,'Contract',3,NULL),(65,40,'Freelance',4,NULL),(66,40,'Internship',5,NULL),(67,40,'Remote',6,NULL),(68,40,'Hybrid',7,NULL),(69,41,'Entry Level (0-1 years)',1,NULL),(70,41,'Junior (1-3 years)',2,NULL),(71,41,'Mid-level (3-5 years)',3,NULL),(72,41,'Senior (5-8 years)',4,NULL),(73,41,'Lead (8+ years)',5,NULL),(74,41,'Director/Executive',6,NULL),(75,42,'Under k',1,NULL),(76,42,'k - k',2,NULL),(77,42,'k - k',3,NULL),(78,42,'k - k',4,NULL),(79,42,'k - k',5,NULL),(80,42,'k - k',6,NULL),(81,42,'k+',7,NULL),(82,43,'Ho Chi Minh City',1,NULL),(83,43,'Ha Noi',2,NULL),(84,43,'Da Nang',3,NULL),(85,43,'Can Tho',4,NULL),(86,43,'Remote (Vietnam)',5,NULL),(87,43,'Asia Pacific',6,NULL),(88,43,'United States',7,NULL),(89,43,'Europe',8,NULL),(90,44,'Startup (1-10 employees)',1,NULL),(91,44,'Small (11-50 employees)',2,NULL),(92,44,'Medium (51-200 employees)',3,NULL),(93,44,'Large (201-1000 employees)',4,NULL),(94,44,'Enterprise (1000+ employees)',5,NULL),(95,45,'Unity Development',1,NULL),(96,45,'Unreal Engine',2,NULL),(97,45,'C# Programming',3,NULL),(98,45,'C++ Programming',4,NULL),(99,45,'JavaScript',5,NULL),(100,45,'Python',6,NULL),(101,45,'3D Modeling',7,NULL),(102,45,'2D Art',8,NULL),(103,45,'UI/UX Design',9,NULL),(104,45,'Game Design',10,NULL),(105,45,'Project Management',11,NULL),(106,45,'Quality Assurance',12,NULL),(107,45,'Audio Engineering',13,NULL),(108,45,'Animation',14,NULL),(109,45,'Level Design',15,NULL),(110,45,'Mobile Development',16,NULL),(111,45,'Web Development',17,NULL),(112,45,'DevOps',18,NULL),(113,45,'Marketing',19,NULL),(114,45,'Community Management',20,NULL),(115,46,'High School',1,NULL),(116,46,'Associate Degree',2,NULL),(117,46,'Bachelor Degree',3,NULL),(118,46,'Master Degree',4,NULL),(119,46,'PhD/Doctorate',5,NULL),(120,47,'Basic',1,NULL),(121,47,'Intermediate',2,NULL),(122,47,'Advanced',3,NULL),(123,47,'Fluent',4,NULL),(124,47,'Native',5,NULL),(125,48,'Health Insurance',1,NULL),(126,48,'Dental Insurance',2,NULL),(127,48,'Vision Insurance',3,NULL),(128,48,'Flexible Working Hours',4,NULL),(129,48,'Remote Work Options',5,NULL),(130,48,'Professional Development',6,NULL),(131,48,'Training Budget',7,NULL),(132,48,'Conference Attendance',8,NULL),(133,48,'Stock Options',9,NULL),(134,48,'Bonus System',10,NULL),(135,48,'Game Development Tools',11,NULL),(136,48,'Free Lunch',12,NULL),(137,55,'Online Application',1,NULL),(138,55,'Email Resume',2,NULL),(139,55,'Company Website',3,NULL),(140,55,'LinkedIn',4,NULL);
/*!40000 ALTER TABLE `attribute_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_translations`
--

DROP TABLE IF EXISTS `attribute_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attribute_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attribute_translations_attribute_id_locale_unique` (`attribute_id`,`locale`),
  CONSTRAINT `attribute_translations_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_translations`
--

LOCK TABLES `attribute_translations` WRITE;
/*!40000 ALTER TABLE `attribute_translations` DISABLE KEYS */;
INSERT INTO `attribute_translations` VALUES (1,1,'vi','SKU'),(2,2,'vi','Name'),(3,3,'vi','URL Key'),(4,4,'vi','Tax Category'),(5,5,'vi','New'),(6,6,'vi','Featured'),(7,7,'vi','Visible Individually'),(8,8,'vi','Status'),(9,9,'vi','Short Description'),(10,10,'vi','Description'),(11,11,'vi','Price'),(12,12,'vi','Cost'),(13,13,'vi','Special Price'),(14,14,'vi','Special Price From'),(15,15,'vi','Special Price To'),(16,16,'vi','Meta Title'),(17,17,'vi','Meta Keywords'),(18,18,'vi','Meta Description'),(19,19,'vi','Length'),(20,20,'vi','Width'),(21,21,'vi','Height'),(22,22,'vi','Weight'),(23,23,'vi','Color'),(24,24,'vi','Size'),(25,25,'vi','Brand'),(26,26,'vi','Guest Checkout'),(27,27,'vi','Product Number'),(28,28,'vi','Manage Stock'),(29,29,'vi','Thể Loại Game'),(30,30,'vi','Nền Tảng'),(31,31,'vi','Game Engine'),(32,32,'vi','Ngôn Ngữ Lập Trình'),(33,33,'vi','Độ Khó'),(34,34,'vi','Bao Gồm Source Code'),(35,35,'vi','Bao Gồm Assets'),(36,36,'vi','Có Tài Liệu'),(37,37,'vi','Hỗ Trợ'),(38,38,'vi','Loại Bản Quyền'),(39,40,'vi','Loại Hình Công Việc'),(40,41,'vi','Cấp Độ Kinh Nghiệm'),(41,42,'vi','Mức Lương'),(42,43,'vi','Địa Điểm Làm Việc'),(43,44,'vi','Quy Mô Công Ty'),(44,45,'vi','Kỹ Năng Yêu Cầu'),(45,46,'vi','Trình Độ Học Vấn'),(46,47,'vi','Trình Độ Tiếng Anh'),(47,48,'vi','Phúc Lợi'),(48,49,'vi','Hạn Nộp Hồ Sơ'),(49,50,'vi','Email Liên Hệ'),(50,51,'vi','Số Điện Thoại'),(51,52,'vi','Website Công Ty'),(52,53,'vi','Tuyển Gấp'),(53,54,'vi','Tin Nổi Bật'),(54,55,'vi','Cách Thức Ứng Tuyển');
/*!40000 ALTER TABLE `attribute_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attributes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `swatch_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regex` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_unique` tinyint(1) NOT NULL DEFAULT '0',
  `is_filterable` tinyint(1) NOT NULL DEFAULT '0',
  `is_comparable` tinyint(1) NOT NULL DEFAULT '0',
  `is_configurable` tinyint(1) NOT NULL DEFAULT '0',
  `is_user_defined` tinyint(1) NOT NULL DEFAULT '1',
  `is_visible_on_front` tinyint(1) NOT NULL DEFAULT '0',
  `value_per_locale` tinyint(1) NOT NULL DEFAULT '0',
  `value_per_channel` tinyint(1) NOT NULL DEFAULT '0',
  `default_value` int DEFAULT NULL,
  `enable_wysiwyg` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attributes_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attributes`
--

LOCK TABLES `attributes` WRITE;
/*!40000 ALTER TABLE `attributes` DISABLE KEYS */;
INSERT INTO `attributes` VALUES (1,'sku','SKU','text',NULL,NULL,NULL,1,1,1,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(2,'name','Name','text',NULL,NULL,NULL,3,1,0,0,1,0,0,0,1,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(3,'url_key','URL Key','text',NULL,NULL,NULL,4,1,1,0,0,0,0,0,1,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(4,'tax_category_id','Tax Category','select',NULL,NULL,NULL,5,0,0,0,0,0,0,0,0,1,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(5,'new','New','boolean',NULL,NULL,NULL,6,0,0,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(6,'featured','Featured','boolean',NULL,NULL,NULL,7,0,0,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(7,'visible_individually','Visible Individually','boolean',NULL,NULL,NULL,9,1,0,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(8,'status','Status','boolean',NULL,NULL,NULL,10,1,0,0,0,0,0,0,0,1,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(9,'short_description','Short Description','textarea',NULL,NULL,NULL,11,1,0,0,0,0,0,0,1,0,NULL,1,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(10,'description','Description','textarea',NULL,NULL,NULL,12,1,0,0,1,0,0,0,1,0,NULL,1,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(11,'price','Price','price',NULL,'decimal',NULL,13,1,0,1,1,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(12,'cost','Cost','price',NULL,'decimal',NULL,14,0,0,0,0,0,1,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(13,'special_price','Special Price','price',NULL,'decimal',NULL,15,0,0,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(14,'special_price_from','Special Price From','date',NULL,NULL,NULL,16,0,0,0,0,0,0,0,0,1,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(15,'special_price_to','Special Price To','date',NULL,NULL,NULL,17,0,0,0,0,0,0,0,0,1,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(16,'meta_title','Meta Title','textarea',NULL,NULL,NULL,18,0,0,0,0,0,0,0,1,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(17,'meta_keywords','Meta Keywords','textarea',NULL,NULL,NULL,20,0,0,0,0,0,0,0,1,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(18,'meta_description','Meta Description','textarea',NULL,NULL,NULL,21,0,0,0,0,0,1,0,1,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(19,'length','Length','text',NULL,'decimal',NULL,22,0,0,0,0,0,1,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(20,'width','Width','text',NULL,'decimal',NULL,23,0,0,0,0,0,1,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(21,'height','Height','text',NULL,'decimal',NULL,24,0,0,0,0,0,1,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(22,'weight','Weight','text',NULL,'decimal',NULL,25,1,0,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(23,'color','Color','select',NULL,NULL,NULL,26,0,0,1,0,1,1,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(24,'size','Size','select',NULL,NULL,NULL,27,0,0,1,0,1,1,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(25,'brand','Brand','select',NULL,NULL,NULL,28,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(26,'guest_checkout','Guest Checkout','boolean',NULL,NULL,NULL,8,1,0,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(27,'product_number','Product Number','text',NULL,NULL,NULL,2,0,1,0,0,0,0,0,0,0,NULL,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(28,'manage_stock','Manage Stock','boolean',NULL,NULL,NULL,1,0,0,0,0,0,0,0,0,1,1,0,'2025-09-05 17:10:06','2025-09-05 17:10:06'),(29,'game_genre','Game Genre','select',NULL,NULL,NULL,1,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:12:23','2025-09-05 17:12:23'),(30,'game_platform','Platform','multiselect',NULL,NULL,NULL,2,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:12:31','2025-09-05 17:12:31'),(31,'game_engine','Game Engine','select',NULL,NULL,NULL,3,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:12:49','2025-09-05 17:12:49'),(32,'programming_language','Programming Language','multiselect',NULL,NULL,NULL,4,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:00','2025-09-05 17:13:00'),(33,'difficulty_level','Difficulty Level','select',NULL,NULL,NULL,5,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:19','2025-09-05 17:13:19'),(34,'includes_source','Includes Source Code','boolean',NULL,NULL,NULL,6,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:22','2025-09-05 17:13:22'),(35,'includes_assets','Includes Assets','boolean',NULL,NULL,NULL,7,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:24','2025-09-05 17:13:24'),(36,'documentation_included','Documentation Included','boolean',NULL,NULL,NULL,8,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:25','2025-09-05 17:13:25'),(37,'support_included','Support Included','select',NULL,NULL,NULL,9,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:30','2025-09-05 17:13:30'),(38,'license_type','License Type','select',NULL,NULL,NULL,10,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:13:40','2025-09-05 17:13:40'),(40,'job_type','Job Type','select',NULL,NULL,NULL,1,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:32:57','2025-09-05 17:32:57'),(41,'experience_level','Experience Level','select',NULL,NULL,NULL,2,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:01','2025-09-05 17:33:01'),(42,'salary_range','Salary Range','select',NULL,NULL,NULL,3,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:04','2025-09-05 17:33:04'),(43,'job_location','Job Location','select',NULL,NULL,NULL,4,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:13','2025-09-05 17:33:13'),(44,'company_size','Company Size','select',NULL,NULL,NULL,5,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:23','2025-09-05 17:33:23'),(45,'required_skills','Required Skills','multiselect',NULL,NULL,NULL,6,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:31','2025-09-05 17:33:31'),(46,'education_level','Education Level','select',NULL,NULL,NULL,7,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:48','2025-09-05 17:33:48'),(47,'english_level','English Level','select',NULL,NULL,NULL,8,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:51','2025-09-05 17:33:51'),(48,'job_benefits','Job Benefits','multiselect',NULL,NULL,NULL,9,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:55','2025-09-05 17:33:55'),(49,'application_deadline','Application Deadline','date',NULL,NULL,NULL,10,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:59','2025-09-05 17:33:59'),(50,'contact_email','Contact Email','text',NULL,NULL,NULL,11,0,0,0,0,0,1,1,0,0,NULL,0,'2025-09-05 17:33:59','2025-09-05 17:33:59'),(51,'contact_phone','Contact Phone','text',NULL,NULL,NULL,12,0,0,0,0,0,1,1,0,0,NULL,0,'2025-09-05 17:34:01','2025-09-05 17:34:01'),(52,'company_website','Company Website','text',NULL,NULL,NULL,13,0,0,0,0,0,1,1,0,0,NULL,0,'2025-09-05 17:34:02','2025-09-05 17:34:02'),(53,'is_urgent','Urgent Job','boolean',NULL,NULL,NULL,14,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:34:03','2025-09-05 17:34:03'),(54,'is_featured','Featured Job','boolean',NULL,NULL,NULL,15,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:34:04','2025-09-05 17:34:04'),(55,'application_method','Application Method','select',NULL,NULL,NULL,16,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 17:34:05','2025-09-05 17:34:05'),(56,'engine','Engine','text',NULL,NULL,NULL,1,0,0,1,0,0,1,1,0,0,NULL,0,'2025-09-05 18:32:38','2025-09-05 18:32:38'),(57,'file_size','File Size','text',NULL,NULL,NULL,3,0,0,0,1,0,1,1,0,0,NULL,0,'2025-09-06 09:50:14','2025-09-06 09:50:14');
/*!40000 ALTER TABLE `attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `parent_id` bigint unsigned DEFAULT '0',
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (1,'Unity Development','unity-development','Tất cả về lập trình game với Unity Engine','',1,0,'vi','Unity Game Development - Hướng dẫn và Tips','Học lập trình game Unity từ cơ bản đến nâng cao với các bài viết chi tiết và thực tế.','Unity, Unity 3D, Unity 2D, C#, Game Development, Unity Tutorial','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(2,'Unreal Engine','unreal-engine','Phát triển game với Unreal Engine','',1,0,'vi','Unreal Engine Game Development','Hướng dẫn phát triển game 3D chất lượng cao với Unreal Engine và Blueprint.','Unreal Engine, UE4, UE5, Blueprint, C++, 3D Game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(3,'Game Design','game-design','Thiết kế game và lý thuyết game development','',1,0,'vi','Game Design - Thiết kế Game Chuyên nghiệp','Học cách thiết kế game từ ý tưởng đến gameplay, UI/UX và player experience.','Game Design, Gameplay, UI Design, UX, Player Experience','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(4,'Programming','programming','Lập trình cho game development','',1,0,'vi','Game Programming - Lập trình Game','Học các ngôn ngữ lập trình phổ biến trong game development như C#, C++, JavaScript.','C#, C++, JavaScript, Python, Game Programming','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(5,'Mobile Game','mobile-game','Phát triển game mobile cho Android và iOS','',1,0,'vi','Mobile Game Development - Game Di động','Hướng dẫn phát triển game mobile hiệu quả cho Android và iOS.','Mobile Game, Android, iOS, Unity Mobile, Performance','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(6,'2D Game','2d-game','Phát triển game 2D','',1,0,'vi','2D Game Development - Game 2D','Tạo game 2D với các công cụ và kỹ thuật hiện đại.','2D Game, Sprite, Animation, Pixel Art, 2D Physics','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(7,'3D Game','3d-game','Phát triển game 3D chuyên nghiệp','',1,0,'vi','3D Game Development - Game 3D','Học cách tạo game 3D với đồ họa đẹp và gameplay hấp dẫn.','3D Game, 3D Modeling, Rendering, Lighting, Shaders','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(8,'VR/AR Game','vr-ar-game','Phát triển game thực tế ảo và thực tế tăng cường','',1,0,'vi','VR/AR Game Development - Game VR AR','Khám phá thế giới phát triển game VR và AR với các công nghệ mới nhất.','VR, AR, Virtual Reality, Augmented Reality, Oculus, HoloLens','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(9,'Game Art','game-art','Nghệ thuật và đồ họa trong game','',1,0,'vi','Game Art - Nghệ thuật Game','Học cách tạo art assets, animation và hiệu ứng visual cho game.','Game Art, 3D Art, Concept Art, Animation, VFX, Texturing','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(10,'Game Industry','game-industry','Tin tức và xu hướng ngành game','',1,0,'vi','Game Industry - Ngành Công nghiệp Game','Cập nhật tin tức, xu hướng và cơ hội nghề nghiệp trong ngành game.','Game Industry, Game Jobs, Game Market, Game Trends','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(11,'Game Reviews','game-reviews','ÄÃ¡nh giÃ¡ chi tiáº¿t cÃ¡c tá»±a game hot nháº¥t','',1,0,'vi','ÄÃ¡nh giÃ¡ Game - Review Game Má»›i Nháº¥t','Äá»c Ä‘Ã¡nh giÃ¡ chi tiáº¿t vá» cÃ¡c tá»±a game má»›i nháº¥t vÃ  hot nháº¥t tá»« cá»™ng Ä‘á»“ng game thá»§.','Game Review, ÄÃ¡nh giÃ¡ game, Game má»›i','2025-09-05 07:00:17','2025-09-05 07:00:17',NULL),(12,'FPS Games','fps-games','Game báº¯n sÃºng gÃ³c nhÃ¬n ngÆ°á»i thá»© nháº¥t','',1,0,'vi','Game FPS - Game Báº¯n SÃºng Hay Nháº¥t','Tá»•ng há»£p vÃ  Ä‘Ã¡nh giÃ¡ cÃ¡c game FPS - First Person Shooter hay nháº¥t hiá»‡n táº¡i.','FPS, Game báº¯n sÃºng, First Person Shooter','2025-09-05 07:00:17','2025-09-05 07:00:17',NULL),(13,'Battle Royale','battle-royale','Game sinh tá»“n Battle Royale','',1,0,'vi','Battle Royale - Game Sinh Tá»“n Háº¥p Dáº«n','KhÃ¡m phÃ¡ tháº¿ giá»›i game Battle Royale vá»›i nhá»¯ng tráº­n chiáº¿n sinh tá»“n cÄƒng tháº³ng.','Battle Royale, Game sinh tá»“n, PUBG, Fortnite','2025-09-05 07:00:17','2025-09-05 07:00:17',NULL),(14,'Action Games','action-games','Game hÃ nh Ä‘á»™ng ká»‹ch tÃ­nh','',1,0,'vi','Action Games - Game HÃ nh Äá»™ng Hay Nháº¥t','Tráº£i nghiá»‡m nhá»¯ng tá»±a game hÃ nh Ä‘á»™ng ká»‹ch tÃ­nh vÃ  Ä‘áº§y thá»­ thÃ¡ch.','Action Games, Game hÃ nh Ä‘á»™ng, Game ká»‹ch tÃ­nh','2025-09-05 07:00:17','2025-09-05 07:00:17',NULL),(15,'Game Collections','game-collections','Tuyá»ƒn táº­p vÃ  top game theo chá»§ Ä‘á»','',1,0,'vi','Tuyá»ƒn Táº­p Game - Top Game Hay Nháº¥t','KhÃ¡m phÃ¡ cÃ¡c bá»™ sÆ°u táº­p game Ä‘Æ°á»£c tuyá»ƒn chá»n ká»¹ lÆ°á»¡ng theo tá»«ng chá»§ Ä‘á».','Top game, Tuyá»ƒn táº­p game, Game hay','2025-09-05 07:00:17','2025-09-05 07:00:17',NULL),(16,'RPG Games','rpg-games','Game nháº­p vai - Role Playing Games','',1,0,'vi','RPG Games - Game Nháº­p Vai Hay Nháº¥t','Tháº¿ giá»›i game nháº­p vai vá»›i nhá»¯ng cÃ¢u chuyá»‡n vÃ  nhÃ¢n váº­t háº¥p dáº«n.','RPG, Game nháº­p vai, Role Playing','2025-09-05 07:00:17','2025-09-05 07:00:17',NULL);
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `author` bigint unsigned NOT NULL,
  `post` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_tags`
--

DROP TABLE IF EXISTS `blog_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_tags`
--

LOCK TABLES `blog_tags` WRITE;
/*!40000 ALTER TABLE `blog_tags` DISABLE KEYS */;
INSERT INTO `blog_tags` VALUES (1,'Unity','unity','Unity Game Engine',1,'vi','Unity - LamGame Blog','Tìm hiểu về Unity trong game development.','Unity, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(2,'Unity 3D','unity-3d','Unity 3D Development',1,'vi','Unity 3D - LamGame Blog','Tìm hiểu về Unity 3D trong game development.','Unity 3D, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(3,'Unity 2D','unity-2d','Unity 2D Development',1,'vi','Unity 2D - LamGame Blog','Tìm hiểu về Unity 2D trong game development.','Unity 2D, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(4,'C#','csharp','C# Programming Language',1,'vi','C# - LamGame Blog','Tìm hiểu về C# trong game development.','C#, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(5,'MonoBehaviour','monobehaviour','Unity MonoBehaviour Scripts',1,'vi','MonoBehaviour - LamGame Blog','Tìm hiểu về MonoBehaviour trong game development.','MonoBehaviour, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(6,'Unity Physics','unity-physics','Unity Physics System',1,'vi','Unity Physics - LamGame Blog','Tìm hiểu về Unity Physics trong game development.','Unity Physics, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(7,'Unity UI','unity-ui','Unity User Interface',1,'vi','Unity UI - LamGame Blog','Tìm hiểu về Unity UI trong game development.','Unity UI, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(8,'Unity Animation','unity-animation','Unity Animation System',1,'vi','Unity Animation - LamGame Blog','Tìm hiểu về Unity Animation trong game development.','Unity Animation, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(9,'Unity Shader','unity-shader','Unity Shaders and Effects',1,'vi','Unity Shader - LamGame Blog','Tìm hiểu về Unity Shader trong game development.','Unity Shader, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(10,'Unity Networking','unity-networking','Unity Multiplayer Networking',1,'vi','Unity Networking - LamGame Blog','Tìm hiểu về Unity Networking trong game development.','Unity Networking, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(11,'Unreal Engine','unreal-engine','Unreal Engine Development',1,'vi','Unreal Engine - LamGame Blog','Tìm hiểu về Unreal Engine trong game development.','Unreal Engine, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(12,'UE4','ue4','Unreal Engine 4',1,'vi','UE4 - LamGame Blog','Tìm hiểu về UE4 trong game development.','UE4, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(13,'UE5','ue5','Unreal Engine 5',1,'vi','UE5 - LamGame Blog','Tìm hiểu về UE5 trong game development.','UE5, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(14,'Blueprint','blueprint','Unreal Blueprint Visual Scripting',1,'vi','Blueprint - LamGame Blog','Tìm hiểu về Blueprint trong game development.','Blueprint, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(15,'C++','cpp','C++ Programming Language',1,'vi','C++ - LamGame Blog','Tìm hiểu về C++ trong game development.','C++, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(16,'JavaScript','javascript','JavaScript Programming',1,'vi','JavaScript - LamGame Blog','Tìm hiểu về JavaScript trong game development.','JavaScript, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(17,'Python','python','Python Programming',1,'vi','Python - LamGame Blog','Tìm hiểu về Python trong game development.','Python, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(18,'Lua','lua','Lua Scripting Language',1,'vi','Lua - LamGame Blog','Tìm hiểu về Lua trong game development.','Lua, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(19,'HLSL','hlsl','High Level Shading Language',1,'vi','HLSL - LamGame Blog','Tìm hiểu về HLSL trong game development.','HLSL, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(20,'GLSL','glsl','OpenGL Shading Language',1,'vi','GLSL - LamGame Blog','Tìm hiểu về GLSL trong game development.','GLSL, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(21,'Game Design','game-design','Game Design Principles',1,'vi','Game Design - LamGame Blog','Tìm hiểu về Game Design trong game development.','Game Design, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(22,'Gameplay','gameplay','Gameplay Design',1,'vi','Gameplay - LamGame Blog','Tìm hiểu về Gameplay trong game development.','Gameplay, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(23,'Level Design','level-design','Level Design Techniques',1,'vi','Level Design - LamGame Blog','Tìm hiểu về Level Design trong game development.','Level Design, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(24,'UI Design','ui-design','User Interface Design',1,'vi','UI Design - LamGame Blog','Tìm hiểu về UI Design trong game development.','UI Design, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(25,'UX Design','ux-design','User Experience Design',1,'vi','UX Design - LamGame Blog','Tìm hiểu về UX Design trong game development.','UX Design, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(26,'Mobile Game','mobile-game','Mobile Game Development',1,'vi','Mobile Game - LamGame Blog','Tìm hiểu về Mobile Game trong game development.','Mobile Game, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(27,'Android','android','Android Game Development',1,'vi','Android - LamGame Blog','Tìm hiểu về Android trong game development.','Android, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(28,'iOS','ios','iOS Game Development',1,'vi','iOS - LamGame Blog','Tìm hiểu về iOS trong game development.','iOS, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(29,'PC Game','pc-game','PC Game Development',1,'vi','PC Game - LamGame Blog','Tìm hiểu về PC Game trong game development.','PC Game, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(30,'Console Game','console-game','Console Game Development',1,'vi','Console Game - LamGame Blog','Tìm hiểu về Console Game trong game development.','Console Game, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(31,'RPG','rpg','Role-Playing Games',1,'vi','RPG - LamGame Blog','Tìm hiểu về RPG trong game development.','RPG, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(32,'FPS','fps','First-Person Shooter',1,'vi','FPS - LamGame Blog','Tìm hiểu về FPS trong game development.','FPS, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(33,'Strategy','strategy','Strategy Games',1,'vi','Strategy - LamGame Blog','Tìm hiểu về Strategy trong game development.','Strategy, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(34,'Puzzle','puzzle','Puzzle Games',1,'vi','Puzzle - LamGame Blog','Tìm hiểu về Puzzle trong game development.','Puzzle, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(35,'Racing','racing','Racing Games',1,'vi','Racing - LamGame Blog','Tìm hiểu về Racing trong game development.','Racing, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(36,'Performance','performance','Game Performance Optimization',1,'vi','Performance - LamGame Blog','Tìm hiểu về Performance trong game development.','Performance, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(37,'Optimization','optimization','Code and Performance Optimization',1,'vi','Optimization - LamGame Blog','Tìm hiểu về Optimization trong game development.','Optimization, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(38,'AI','ai','Artificial Intelligence in Games',1,'vi','AI - LamGame Blog','Tìm hiểu về AI trong game development.','AI, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(39,'Physics','physics','Game Physics',1,'vi','Physics - LamGame Blog','Tìm hiểu về Physics trong game development.','Physics, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(40,'Multiplayer','multiplayer','Multiplayer Game Development',1,'vi','Multiplayer - LamGame Blog','Tìm hiểu về Multiplayer trong game development.','Multiplayer, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(41,'3D Modeling','3d-modeling','3D Modeling for Games',1,'vi','3D Modeling - LamGame Blog','Tìm hiểu về 3D Modeling trong game development.','3D Modeling, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(42,'2D Art','2d-art','2D Art and Graphics',1,'vi','2D Art - LamGame Blog','Tìm hiểu về 2D Art trong game development.','2D Art, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(43,'Animation','animation','Game Animation',1,'vi','Animation - LamGame Blog','Tìm hiểu về Animation trong game development.','Animation, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(44,'VFX','vfx','Visual Effects',1,'vi','VFX - LamGame Blog','Tìm hiểu về VFX trong game development.','VFX, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(45,'Shader','shader','Shader Programming',1,'vi','Shader - LamGame Blog','Tìm hiểu về Shader trong game development.','Shader, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(46,'VR','vr','Virtual Reality',1,'vi','VR - LamGame Blog','Tìm hiểu về VR trong game development.','VR, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(47,'AR','ar','Augmented Reality',1,'vi','AR - LamGame Blog','Tìm hiểu về AR trong game development.','AR, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(48,'Career','career','Game Development Career',1,'vi','Career - LamGame Blog','Tìm hiểu về Career trong game development.','Career, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(49,'Indie Game','indie-game','Independent Game Development',1,'vi','Indie Game - LamGame Blog','Tìm hiểu về Indie Game trong game development.','Indie Game, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(50,'Tutorial','tutorial','Game Development Tutorials',1,'vi','Tutorial - LamGame Blog','Tìm hiểu về Tutorial trong game development.','Tutorial, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(51,'Beginner','beginner','Beginner-Friendly Content',1,'vi','Beginner - LamGame Blog','Tìm hiểu về Beginner trong game development.','Beginner, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(52,'Advanced','advanced','Advanced Topics',1,'vi','Advanced - LamGame Blog','Tìm hiểu về Advanced trong game development.','Advanced, game development, lập trình game','2025-09-05 04:18:37','2025-09-05 04:18:37',NULL),(53,'CS:GO','csgo','Counter-Strike: Global Offensive',1,'vi','CS:GO - Counter-Strike Global Offensive','Má»i thá»© vá» CS:GO - game FPS huyá»n thoáº¡i.','CS:GO, Counter-Strike, FPS, game báº¯n sÃºng','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(54,'PUBG','pubg','PlayerUnknown\'s Battlegrounds',1,'vi','PUBG - PlayerUnknown\'s Battlegrounds','ThÃ´ng tin vá» PUBG - game Battle Royale ná»•i tiáº¿ng.','PUBG, Battle Royale, game sinh tá»“n','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(55,'Grand Chase','grand-chase','Grand Chase Mobile game',1,'vi','Grand Chase - Game nháº­p vai hÃ nh Ä‘á»™ng','Grand Chase M - game nháº­p vai hÃ nh Ä‘á»™ng 3D.','Grand Chase, RPG, game mobile','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(56,'Steam','steam','Steam platform games',1,'vi','Steam - Ná»n táº£ng game PC','Game hay trÃªn Steam platform.','Steam, game PC, game platform','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(57,'Android Games','android-games','Game dÃ nh cho Android',1,'vi','Android Games - Game Di Äá»™ng Android','Tuyá»ƒn táº­p game hay cho Android.','Android, game mobile, game di Ä‘á»™ng','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(58,'iOS Games','ios-games','Game dÃ nh cho iOS',1,'vi','iOS Games - Game Di Äá»™ng iOS','Game hay cho iPhone vÃ  iPad.','iOS, iPhone, iPad, game mobile','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(59,'PC Games','pc-games','Game dÃ nh cho mÃ¡y tÃ­nh',1,'vi','PC Games - Game MÃ¡y TÃ­nh','Tá»•ng há»£p game PC hay nháº¥t.','PC games, game mÃ¡y tÃ­nh, game PC','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(60,'Free Games','free-games','Game miá»…n phÃ­',1,'vi','Free Games - Game Miá»…n PhÃ­','Game miá»…n phÃ­ cháº¥t lÆ°á»£ng cao.','free games, game miá»…n phÃ­, game free','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(61,'Anime Games','anime-games','Game phong cÃ¡ch Anime',1,'vi','Anime Games - Game Phong CÃ¡ch Anime','Game vá»›i Ä‘á»“ há»a vÃ  phong cÃ¡ch anime.','anime games, game anime, manga','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL),(62,'Horror Games','horror-games','Game kinh dá»‹',1,'vi','Horror Games - Game Kinh Dá»‹','Game kinh dá»‹ Ä‘áº§y rÃ¹ng rá»£n.','horror games, game kinh dá»‹, game ma','2025-09-05 07:00:43','2025-09-05 07:00:43',NULL);
/*!40000 ALTER TABLE `blog_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `shares` int NOT NULL DEFAULT '0',
  `channels` bigint unsigned NOT NULL,
  `default_category` bigint unsigned NOT NULL,
  `categorys` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint unsigned NOT NULL DEFAULT '0',
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `allow_comments` tinyint(1) NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogs_status_created_at_index` (`status`,`created_at`),
  KEY `blogs_views_index` (`views`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'Hướng dẫn Unity 2023 - Những tính năng mới đáng chú ý','huong-dan-unity-2023-tinh-nang-moi','Unity 2023 đã ra mắt với nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game.','<p>Unity 2023 đã ra mắt với nhiều cải tiến quan trọng giúp game developer tăng hiệu suất và chất lượng game. Trong bài viết này, chúng ta sẽ khám phá những tính năng nổi bật như Unity Netcode for GameObjects, Shader Graph improvements...</p>\n\n<h3>1. Unity Netcode for GameObjects</h3>\n<p>Đây là hệ thống networking mới của Unity, thay thế cho UNet cũ. Netcode for GameObjects cung cấp:</p>\n<ul>\n<li>Client-Server architecture hiện đại</li>\n<li>Sync variables và RPC calls dễ sử dụng</li>\n<li>Optimized network performance</li>\n<li>Built-in lag compensation</li>\n</ul>\n\n<h3>2. Shader Graph Improvements</h3>\n<p>Shader Graph đã được cải tiến đáng kể với các tính năng mới:</p>\n<ul>\n<li>Custom Function Nodes</li>\n<li>Sub Graph assets</li>\n<li>Better performance optimization</li>\n<li>More built-in nodes</li>\n</ul>\n\n<h3>3. Universal Render Pipeline (URP) Updates</h3>\n<p>URP trong Unity 2023 có nhiều cải tiến về hiệu suất và chất lượng visual.</p>\n\n<h3>Kết luận</h3>\n<p>Unity 2023 mang đến nhiều cải tiến đáng kể cho game developers. Hãy cập nhật và trải nghiệm những tính năng mới này!</p>',0,0,1,1,'1','1,2,3,4','LamGame Team',1,'','vi',1,1,'Unity 2023 - Những tính năng mới đáng chú ý | LamGame','Khám phá Unity 2023 với Netcode for GameObjects, Shader Graph improvements và URP updates mới nhất.','Unity 2023, Unity Netcode, Shader Graph, URP, Game Development','2025-08-31 11:27:18','2025-09-05 04:27:18','2025-09-05 04:27:18',NULL),(2,'C# Cơ bản cho Game Developer - Từ Hello World đến MonoBehaviour','csharp-co-ban-cho-game-developer','Hướng dẫn C# từ cơ bản đến nâng cao dành cho những ai muốn bắt đầu với Unity game development.','<h3>Giới thiệu về C#</h3>\n<p>C# là ngôn ngữ lập trình chính được sử dụng trong Unity. Đây là ngôn ngữ mạnh mẽ, dễ học và có syntax rất clear.</p>\n\n<h3>1. Biến và Kiểu dữ liệu</h3>\n<pre><code>// Các kiểu dữ liệu cơ bản\nint health = 100;\nfloat speed = 5.5f;\nbool isAlive = true;\nstring playerName = \"Hero\";\n\n// Arrays và Lists\nint[] scores = {10, 20, 30};\nList&lt;GameObject&gt; enemies = new List&lt;GameObject&gt;();</code></pre>\n\n<h3>2. Functions và Methods</h3>\n<pre><code>// Method cơ bản\npublic void TakeDamage(int damage)\n{\n    health -= damage;\n    if (health <= 0)\n    {\n        Die();\n    }\n}</code></pre>\n\n<h3>3. Unity MonoBehaviour</h3>\n<p>MonoBehaviour là class cơ sở cho tất cả Unity scripts:</p>\n<pre><code>public class Player : MonoBehaviour\n{\n    void Start()\n    {\n        // Chạy một lần khi object được tạo\n    }\n    \n    void Update()\n    {\n        // Chạy mỗi frame\n    }\n}</code></pre>\n\n<h3>Bài tập thực hành</h3>\n<p>Hãy tạo một script đơn giản để di chuyển player trong Unity!</p>',0,0,1,4,'4','4,1,50,51','LamGame Team',1,'','vi',1,1,'C# Cơ bản cho Game Developer | Unity C# Tutorial','Học C# từ cơ bản đến nâng cao cho Unity game development. Từ Hello World đến MonoBehaviour.','C#, Unity C#, MonoBehaviour, Game Programming, Unity Tutorial','2025-09-02 11:27:18','2025-09-05 04:27:18','2025-09-05 04:27:18',NULL),(3,'Tối ưu hóa Performance Game Mobile với Unity','toi-uu-hoa-performance-game-mobile-unity','Hướng dẫn chi tiết cách tối ưu hóa performance cho mobile game để đạt hiệu suất tốt nhất trên nhiều thiết bị.','<h3>Tại sao Performance Mobile quan trọng?</h3>\n<p>Mobile devices có giới hạn về CPU, GPU, RAM và battery. Việc tối ưu hóa performance không chỉ giúp game chạy mượt mà còn tiết kiệm pin và tương thích với nhiều thiết bị hơn.</p>\n\n<h3>1. Object Pooling</h3>\n<p>Thay vì liên tục tạo và hủy objects, hãy sử dụng object pooling:</p>\n<pre><code>public class ObjectPool : MonoBehaviour\n{\n    public GameObject prefab;\n    private Queue&lt;GameObject&gt; pool = new Queue&lt;GameObject&gt;();\n    \n    public GameObject GetObject()\n    {\n        if (pool.Count > 0)\n            return pool.Dequeue();\n        else\n            return Instantiate(prefab);\n    }\n    \n    public void ReturnObject(GameObject obj)\n    {\n        obj.SetActive(false);\n        pool.Enqueue(obj);\n    }\n}</code></pre>\n\n<h3>2. Texture Optimization</h3>\n<ul>\n<li>Sử dụng texture compression phù hợp (ASTC cho Android, PVRTC cho iOS)</li>\n<li>Giảm kích thước texture khi có thể</li>\n<li>Sử dụng mipmaps cho textures</li>\n<li>Tránh alpha channels không cần thiết</li>\n</ul>\n\n<h3>3. Audio Optimization</h3>\n<ul>\n<li>Compress audio files</li>\n<li>Load audio on demand</li>\n<li>Use audio pooling for frequent sounds</li>\n</ul>\n\n<h3>4. Code Optimization</h3>\n<pre><code>// Tránh allocations trong Update()\nvoid Update()\n{\n    // BAD: Tạo string mới mỗi frame\n    // someText.text = \"Score: \" + score.ToString();\n    \n    // GOOD: Cache string\n    if (scoreChanged)\n    {\n        someText.text = \"Score: \" + score.ToString();\n        scoreChanged = false;\n    }\n}</code></pre>\n\n<h3>5. Profiling và Testing</h3>\n<p>Luôn sử dụng Unity Profiler để identify bottlenecks và test trên thiết bị thật.</p>',0,0,1,5,'5','26,35,36,1,4','LamGame Team',1,'','vi',1,1,'Tối ưu Performance Mobile Game Unity | Optimization Tips','Hướng dẫn tối ưu hóa performance mobile game Unity với Object Pooling, Texture Optimization và nhiều tips khác.','Unity Mobile Optimization, Performance, Object Pooling, Mobile Game, Unity Tips','2025-09-04 11:27:18','2025-09-05 04:27:18','2025-09-05 04:27:18',NULL),(4,'Game Design 101: Nguyên tắc thiết kế game hiệu quả','game-design-101-nguyen-tac-thiet-ke-game','Khám phá các nguyên tắc cơ bản trong game design để tạo ra những trò chơi hấp dẫn và thu hút người chơi.','<h3>Game Design là gì?</h3>\n<p>Game Design là quá trình tạo ra nội dung và quy tắc của một game. Nó bao gồm gameplay mechanics, story, characters, levels, và player experience.</p>\n\n<h3>1. Core Gameplay Loop</h3>\n<p>Đây là chu trình hành động cơ bản mà player sẽ lặp lại trong suốt game:</p>\n<ul>\n<li><strong>Action:</strong> Player thực hiện hành động</li>\n<li><strong>Result:</strong> Game phản hồi</li>\n<li><strong>Reward:</strong> Player nhận phần thưởng</li>\n<li><strong>Progress:</strong> Player tiến bộ</li>\n</ul>\n\n<h3>2. Player Motivation</h3>\n<p>Hiểu động lực của người chơi là then chốt:</p>\n<ul>\n<li><strong>Achievement:</strong> Hoàn thành mục tiêu</li>\n<li><strong>Social:</strong> Tương tác với người khác</li>\n<li><strong>Immersion:</strong> Đắm mình vào thế giới game</li>\n<li><strong>Competition:</strong> Cạnh tranh với người khác</li>\n</ul>\n\n<h3>3. Difficulty Balancing</h3>\n<p>Game cần có độ khó phù hợp:</p>\n<ul>\n<li>Bắt đầu dễ để player làm quen</li>\n<li>Tăng độ khó dần dần</li>\n<li>Cung cấp multiple difficulty options</li>\n<li>Adaptive difficulty dựa trên skill của player</li>\n</ul>\n\n<h3>4. Feedback Systems</h3>\n<p>Player cần feedback ngay lập tức:</p>\n<ul>\n<li>Visual feedback (particles, animations)</li>\n<li>Audio feedback (sound effects, music)</li>\n<li>Haptic feedback (vibration)</li>\n<li>UI feedback (score, health bars)</li>\n</ul>\n\n<h3>5. Player Retention</h3>\n<p>Làm sao để player quay lại:</p>\n<ul>\n<li>Daily rewards</li>\n<li>Progressive unlocks</li>\n<li>Social features</li>\n<li>Regular content updates</li>\n</ul>\n\n<h3>Kết luận</h3>\n<p>Good game design là về việc hiểu player và tạo ra trải nghiệm meaningful. Hãy luôn playtest và lắng nghe feedback!</p>',0,0,1,3,'3','21,22,50','LamGame Team',1,'','vi',1,1,'Game Design 101: Nguyên tắc thiết kế game | LamGame','Học các nguyên tắc game design cơ bản: Core Loop, Player Motivation, Difficulty Balancing và Player Retention.','Game Design, Gameplay, Player Experience, Game Mechanics, Game Theory','2025-09-04 23:27:18','2025-09-05 04:27:18','2025-09-05 04:27:18',NULL),(5,'CS:GO - Đánh giá chi tiết game FPS huyền thoại vẫn hot nhất 2024','csgo-danh-gia-chi-tiet-game-fps-huyen-thoai-2024','Counter-Strike: Global Offensive vẫn là tựa game FPS được yêu thích nhất thế giới. Cùng tìm hiểu những điểm đặc biệt khiến CS:GO trở thành huyền thoại.','<h2>Giới thiệu về Counter-Strike: Global Offensive</h2>\n<p>Counter-Strike: Global Offensive (CS:GO) là phiên bản thứ tư và mới nhất trong series Counter-Strike huyền thoại. Ra mắt năm 2012, CS:GO đã khẳng định vị thế là một trong những game FPS competitive hay nhất mọi thời đại.</p>\n\n<h2>Những cải tiến đáng chú ý so với CS 1.6</h2>\n<h3>Hệ thống chế độ chơi đa dạng</h3>\n<p>CS:GO mang đến 5 chế độ chơi chính:</p>\n<ul>\n<li><strong>Casual</strong>: Chế độ giải trí, phù hợp cho người mới</li>\n<li><strong>Competitive</strong>: Chế độ cạnh tranh chính thức với hệ thống rank</li>\n<li><strong>Deathmatch</strong>: Chế độ bắn giết tự do với hệ thống tính điểm mới</li>\n<li><strong>Arms Race</strong>: Chế độ độc đáo, thay đổi vũ khí sau mỗi kill</li>\n<li><strong>Demolition</strong>: Kết hợp giữa teamplay và Arms Race</li>\n</ul>\n\n<h2>Vũ khí và trang bị mới</h2>\n<p>CS:GO bổ sung nhiều vũ khí mới như M4A1-S, CZ-75, SCAR-20 cùng hệ thống skin đa dạng cho phép tùy chỉnh ngoại hình vũ khí.</p>\n\n<h2>Tại sao CS:GO vẫn hot đến năm 2024?</h2>\n<ul>\n<li><strong>Gameplay timeless</strong>: Lối chơi tactical shooter không bao giờ lỗi thời</li>\n<li><strong>Esports phát triển</strong>: Các giải đấu Major với giải thưởng triệu USD</li>\n<li><strong>Cộng đồng mạnh</strong>: Hàng triệu người chơi trên toàn thế giới</li>\n<li><strong>Miễn phí</strong>: Từ 2018, CS:GO đã chuyển sang mô hình Free-to-Play</li>\n</ul>\n\n<p><strong>Đánh giá:</strong> 9.5/10</p>\n<p><em>CS:GO có sẵn miễn phí trên Steam.</em></p>',0,0,1,12,'12','11,12,17','LamGame Team',1,'/storage/blog/csgo-review-2024.jpg','vi',1,1,'CS:GO 2024 - Đánh giá chi tiết game FPS huyền thoại | LamGame','Đánh giá toàn diện CS:GO năm 2024. Tại sao Counter-Strike Global Offensive vẫn là game FPS hay nhất? Gameplay, esports, và tương lai của CS:GO.','CS:GO, Counter-Strike, game FPS, FPS hay nháº¥t, game báº¯n sÃºng, esports, steam','2025-09-05 14:30:00','2025-09-05 07:03:49','2025-09-05 07:03:49',NULL),(6,'PUBG - Game sinh tồn Battle Royale gây nghiện nhất thế giới','pubg-game-sinh-ton-battle-royale-gay-nghien-nhat','PlayerUnknowns Battlegrounds đã tạo nên cơn sốt Battle Royale toàn cầu. Tìm hiểu tại sao PUBG lại gây nghiện đến vậy và những điểm đặc biệt của game.','<h2>PUBG - Kẻ tiên phong của thể loại Battle Royale</h2>\n<p>PlayerUnknown\'s Battlegrounds (PUBG) không chỉ là một tựa game, mà còn là người tạo nên cả một thể loại game mới: Battle Royale. Từ một mod của Arma, PUBG đã phát triển thành hiện tượng gaming toàn cầu với hàng trăm triệu người chơi.</p>\n\n<h2>Gameplay căng thẳng từng giây</h2>\n<h3>Quy tắc sinh tồn đơn giản nhưng nghẹt thở</h3>\n<ul>\n<li><strong>100 người chơi</strong> nhảy dù xuống đảo Erangel rộng 8x8km</li>\n<li><strong>Khu vực an toàn thu hẹp</strong> liên tục, buộc người chơi phải di chuyển</li>\n<li><strong>Mục tiêu duy nhất</strong>: Trở thành người cuối cùng sống sót</li>\n<li><strong>Thời gian trận đấu</strong>: Tối đa 30 phút, trung bình 20-25 phút</li>\n</ul>\n\n<h2>Tại sao PUBG gây nghiện?</h2>\n<ul>\n<li>Cảm giác thành tựu khi Chicken Dinner (tỷ lệ thắng 1/100)</li>\n<li>Yếu tố RNG tạo bất ngờ trong mỗi trận đấu</li>\n<li>Gameplay căng thẳng kết hợp kỹ năng và may mắn</li>\n<li>PUBG Mobile mở rộng trải nghiệm lên di động</li>\n</ul>\n\n<p><strong>Đánh giá:</strong> 8.5/10</p>\n<p><em>PUBG có sẵn trên Steam, Mobile và Console.</em></p>',0,0,1,13,'13','12,13,17,18','LamGame Team',1,'/storage/blog/pubg-battle-royale-2024.jpg','vi',1,1,'PUBG - Game Battle Royale gây nghiện nhất thế giới | LamGame','Tìm hiểu tại sao PUBG lại gây nghiện đến vậy. Đánh giá chi tiết gameplay, đồ họa, và tương lai của PlayerUnknowns Battlegrounds.','PUBG, PlayerUnknowns Battlegrounds, Battle Royale, game sinh tá»“n, chicken dinner','2025-09-05 15:00:00','2025-09-05 07:07:37','2025-09-05 07:07:37',NULL),(7,'League of Legends - Game MOBA số 1 thế giới năm 2024','top-10-game-pc-cu-nhung-van-cuc-hay-2024','10 tựa game PC kinh điển mà mọi game thủ không nên bỏ qua. Dù đã cũ nhưng vẫn giữ được sức hấp dẫn và giá trị giải trí cao.','<h2>League of Legends - MOBA hàng đầu thế giới</h2>\n<p>League of Legends (LoL) là tựa game MOBA (Multiplayer Online Battle Arena) phổ biến nhất thế giới với hơn 180 triệu người chơi hàng tháng. Được phát triển bởi Riot Games từ 2009, LoL đã định hình thể loại MOBA hiện đại.</p>\n\n<h2>5 vị trí quan trọng trong đội hình</h2>\n<ul>\n<li><strong>Top Lane</strong>: Thường là tank hoặc fighter, chịu sát thương</li>\n<li><strong>Jungle</strong>: Gank, kiểm soát mục tiêu và bản đồ</li>\n<li><strong>Mid Lane</strong>: Mage hoặc Assassin, sát thương phép thuật chính</li>\n<li><strong>ADC (Bot Lane)</strong>: Carry vật lý, nguồn sát thương chủ yếu</li>\n<li><strong>Support</strong>: Hỗ trợ đội nhóm, ward và utility</li>\n</ul>\n\n<h2>Tại sao LoL vẫn thống trị MOBA?</h2>\n<ul>\n<li>Cập nhật liên tục với tướng mới và cân bằng game</li>\n<li>Hệ thống skin và trang phục đẹp mắt</li>\n<li>Cộng đồng Việt Nam đông đảo và nhiệt huyết</li>\n<li>Free-to-play với mô hình kinh doanh công bằng</li>\n</ul>\n\n<p><strong>Đánh giá:</strong> 9.0/10</p>\n<p><em>LoL hoàn toàn miễn phí trên PC.</em></p>',0,0,1,16,'16','17,19,14','LamGame Team',1,'/storage/blog/top-10-game-pc-cu-hay.jpg','vi',1,1,'Top 10 Game PC Cũ Nhưng Vẫn Cực Hay 2024 | LamGame','Khám phá 10 tựa game PC kinh điển vẫn đáng chơi năm 2024. Half-Life 2, Skyrim, Portal 2 và nhiều game hay khác đang chờ bạn.','game PC cÅ©, game kinh Ä‘iá»ƒn, top game hay, game PC hay nháº¥t, retro gaming','2025-09-05 16:00:00','2025-09-05 07:09:12','2025-09-05 07:09:12',NULL),(8,'FIFA Online 4 - Game bóng đá ảo số 1 Việt Nam 2024','grand-chase-m-game-nhap-vai-hanh-dong-anime-3d-sieu-hot','Grand Chase M kế thừa tinh thần từ game PC kinh điển với đồ họa 3D anime tuyệt đẹp và gameplay hành động mãn nhãn trên mobile.','<h2>FIFA Online 4 - Bóng đá ảo số 1 Việt Nam</h2>\n<p>FIFA Online 4 (FO4) là phiên bản miễn phí của series FIFA, được phát triển riêng cho thị trường Châu Á. Với đồ họa tuyệt đẹp và gameplay mượt mà, FO4 đã trở thành tựa game bóng đá ảo được yêu thích nhất tại Việt Nam.</p>\n\n<h2>Trải nghiệm bóng đá chân thực</h2>\n<h3>Các chế độ chơi phong phú</h3>\n<ul>\n<li><strong>Manager Mode</strong>: Điều hành câu lạc bộ, mua bán cầu thủ</li>\n<li><strong>Ultimate Team</strong>: Xây dựng đội hình trong mơ với các cầu thủ Real</li>\n<li><strong>Ranked Match</strong>: Thi đấu xếp hạng với hệ thống ELO</li>\n<li><strong>Tournament</strong>: Các giải đấu mô phỏng World Cup, Champions League</li>\n</ul>\n\n<h2>Esports và cộng đồng Việt Nam</h2>\n<ul>\n<li><strong>FVNL</strong>: Giải đấu chuyên nghiệp với tiền thưởng lớn</li>\n<li><strong>FO4 Champions Cup</strong>: Giải đấu offline uy tín</li>\n<li><strong>Community mạnh</strong>: Hàng triệu game thủ Việt Nam</li>\n<li><strong>Streamer nổi tiếng</strong>: Pewpew, ViruSs, Cris Phan</li>\n</ul>\n\n<p><strong>Đánh giá:</strong> 8.8/10</p>\n<p><em>Tải miễn phí tại Garena PC.</em></p>',0,0,1,17,'17,5','13,19,20,17','LamGame Team',1,'/storage/blog/grand-chase-m-mobile-rpg.jpg','vi',1,1,'Grand Chase M - Game Mobile RPG Anime 3D Siêu Hot | LamGame','Review chi tiết Grand Chase M - game nhập vai hành động anime 3D tuyệt đẹp. Gameplay, đồ họa, và tất cả về game mobile RPG hot nhất.','Grand Chase M, game mobile RPG, game anime 3D, Grand Chase Mobile, game nháº­p vai mobile','2025-09-05 17:00:00','2025-09-05 07:11:54','2025-09-05 07:11:54',NULL),(9,'Top 15 Game Miễn Phí Hay Nhất Trên Steam 2024','top-15-game-mien-phi-hay-nhat-tren-steam-2024','Khám phá 15 tựa game miễn phí chất lượng cao trên Steam mà bạn có thể tải và chơi ngay. Từ FPS, MOBA đến Battle Royale, tất cả đều free!','<h2>Top 15 Game Free-to-Play Tuyá»‡t Hay TrÃªn Steam</h2><p>Steam khÃ´ng chá»‰ cÃ³ nhá»¯ng game AAA Ä‘áº¯t tiá»n mÃ  cÃ²n chá»©a kho tÃ ng game miá»…n phÃ­ cháº¥t lÆ°á»£ng cao. DÆ°á»›i Ä‘Ã¢y lÃ  15 tá»±a game F2P hay nháº¥t mÃ  báº¡n nÃªn thá»­ ngay!</p><h2>1. Dota 2 - MOBA HÃ ng Äáº§u Tháº¿ Giá»›i</h2><p>MOBA kinh Ä‘iá»ƒn tá»« Valve vá»›i prize pool esports lÃªn Ä‘áº¿n hÃ ng chá»¥c triá»‡u USD. Gameplay depth cá»±c cao vá»›i hÆ¡n 100 heroes Ä‘á»™c Ä‘Ã¡o.</p><ul><li>Genre: MOBA</li><li>Developer: Valve</li><li>Esports: The International vá»›i giáº£i thÆ°á»Ÿng khá»§ng</li><li>Learning curve: Ráº¥t cao nhÆ°ng rewarding</li></ul><h2>2. CS:GO - FPS Competitive Sá»‘ 1</h2><p>Game báº¯n sÃºng tactical Ä‘Ã£ trá»Ÿ thÃ nh free-to-play tá»« 2018. Váº«n lÃ  chuáº©n má»±c cho FPS competitive vá»›i hÃ ng triá»‡u ngÆ°á»i chÆ¡i.</p><h2>3. Apex Legends - Battle Royale Äá»‰nh Cao</h2><p>BR game tá»« Respawn Entertainment vá»›i movement system mÆ°á»£t mÃ  vÃ  character abilities Ä‘á»™c Ä‘Ã¡o. Cáº¡nh tranh trá»±c tiáº¿p vá»›i PUBG vÃ  Fortnite.</p><h2>4. Warframe - Sci-fi Action RPG</h2><p>Space ninja game vá»›i combat system má»m máº¡i vÃ  customization vÃ´ táº­n. ÄÆ°á»£c cáº­p nháº­t liÃªn tá»¥c vá»›i content má»›i.</p><ul><li>Third-person shooter/melee combat</li><li>Massive weapon vÃ  warframe selection</li><li>Co-op PvE focus</li><li>Excellent F2P model</li></ul><h2>5. Team Fortress 2 - Class-based FPS</h2><p>FPS hÃ i hÆ°á»›c tá»« Valve vá»›i 9 class Ä‘á»™c Ä‘Ã¡o. Art style cartoon timeless vÃ  community mods phong phÃº.</p><h2>6. Path of Exile - Action RPG Hardcore</h2><p>Spiritual successor cá»§a Diablo 2 vá»›i skill tree khá»•ng lá»“ vÃ  build diversity cá»±c cao. ÄÆ°á»£c coi lÃ  F2P game cÃ´ng báº±ng nháº¥t.</p><h2>7. War Thunder - Combat Simulation</h2><p>Game mÃ´ phá»ng chiáº¿n Ä‘áº¥u vá»›i planes, tanks vÃ  ships tá»« WW2 Ä‘áº¿n hiá»‡n Ä‘áº¡i. Realistic physics vÃ  historical accuracy.</p><h2>8. World of Tanks - Tank Combat MMO</h2><p>MMO tank combat vá»›i hÃ ng trÄƒm xe tÄƒng lá»‹ch sá»­. Tactical gameplay vÃ  team coordination quan trá»ng.</p><h2>9. Rocket League - Car Soccer</h2><p>Unique concept: football vá»›i cars. Easy to learn, hard to master vá»›i esports scene phÃ¡t triá»ƒn máº¡nh.</p><h2>10. Paladins - Hero Shooter</h2><p>Alternative cho Overwatch vá»›i champion system vÃ  card customization. Faster-paced combat vÃ  mount system.</p><h2>11. Smite - Third-person MOBA</h2><p>MOBA vá»›i perspective Ä‘á»™c Ä‘Ã¡o vÃ  gods tá»« mythology. Skill-shot based combat thay vÃ¬ point-and-click.</p><h2>12. Brawlhalla - Platform Fighter</h2><p>2D fighting game kiá»ƒu Smash Bros vá»›i weekly rotation legends. Cross-platform play vÃ  competitive scene.</p><h2>13. Lost Ark - MMORPG Action</h2><p>Korean MMORPG vá»›i Ä‘á»“ há»a tuyá»‡t Ä‘áº¹p vÃ  combat system dynamic. Raid content vÃ  class diversity phong phÃº.</p><h2>14. Fall Guys - Party Battle Royale</h2><p>Colorful battle royale vá»›i minigames vui nhá»™n. Perfect cho casual gaming vá»›i friends vÃ  family.</p><h2>15. Counter-Strike 2 - Next Gen FPS</h2><p>Successor cá»§a CS:GO vá»›i Source 2 engine. Improved graphics, refined gameplay vÃ  free upgrade cho CS:GO players.</p><h2>Táº¡i Sao NÃªn ChÆ¡i Game Free-to-Play?</h2><h3>Æ¯u Ä‘iá»ƒm</h3><ul><li>KhÃ´ng tá»‘n tiá»n Ä‘á»ƒ thá»­ nghiá»‡m</li><li>ThÆ°á»ng cÃ³ player base lá»›n</li><li>Regular updates vÃ  new content</li><li>Accessible cho má»i ngÆ°á»i</li><li>CÃ³ thá»ƒ chÆ¡i vá»›i báº¡n bÃ¨ mÃ  khÃ´ng cáº§n convince há» mua game</li></ul><h3>LÆ°u Ã½ khi chá»n F2P games</h3><ul><li>Check monetization model - trÃ¡nh pay-to-win</li><li>Äá»c reviews vá» grind vÃ  progression</li><li>Thá»­ nghiá»‡m trÆ°á»›c khi invest time</li><li>Community vÃ  esports scene</li></ul><h2>Tips ChÆ¡i Game F2P Hiá»‡u Quáº£</h2><h3>Quáº£n lÃ½ thá»i gian</h3><ul><li>Set limit Ä‘á»ƒ trÃ¡nh addiction</li><li>Focus vÃ o 1-2 games thay vÃ¬ chÆ¡i táº¥t cáº£</li><li>Take breaks Ä‘á»ƒ trÃ¡nh burnout</li></ul><h3>Monetization thÃ´ng minh</h3><ul><li>Chá»‰ spend tiá»n khi thá»±c sá»± enjoy game</li><li>Æ¯u tiÃªn cosmetic over gameplay advantages</li><li>Look for value trong bundles vÃ  sales</li></ul><h2>Game F2P ÄÃ¡ng Chá» Äá»£i</h2><ul><li>Valorant (náº¿u ra Steam)</li><li>The Finals</li><li>XDefiant</li><li>Marvel Snap (náº¿u ra PC)</li></ul><h2>Káº¿t luáº­n</h2><p>Steam F2P ecosystem cá»±c ká»³ Ä‘a dáº¡ng vá»›i quality games cho má»i thá»ƒ loáº¡i. Tá»« competitive FPS Ä‘áº¿n casual party games, báº¡n cÃ³ thá»ƒ tÃ¬m tháº¥y tráº£i nghiá»‡m phÃ¹ há»£p mÃ  khÃ´ng tá»‘n má»™t xu nÃ o.</p><p>Äáº·c biá»‡t, nhiá»u F2P games trÃªn Steam cÃ³ cháº¥t lÆ°á»£ng khÃ´ng thua kÃ©m games tráº£ phÃ­, vá»›i community máº¡nh vÃ  esports scene phÃ¡t triá»ƒn. ÄÃ¢y lÃ  cÆ¡ há»™i tuyá»‡t vá»i Ä‘á»ƒ explore gaming mÃ  khÃ´ng cáº§n lo vá» budget.</p><p><strong>Pro tip:</strong> HÃ£y thá»­ Ã­t nháº¥t 3-5 games trong list nÃ y Ä‘á»ƒ tÃ¬m ra style phÃ¹ há»£p vá»›i báº¡n nháº¥t!</p>',0,0,1,16,'16','18,14,17','LamGame Team',1,'/storage/blog/top-15-game-mien-phi-steam.jpg','vi',1,1,'Top 15 Game Miễn Phí Hay Nhất Trên Steam 2024 | LamGame','Khám phá 15 game miễn phí chất lượng cao trên Steam. Dota 2, CS:GO, Apex Legends và nhiều F2P games tuyệt hay khác đang chờ bạn!','game miá»…n phÃ­ Steam, free games Steam, F2P Steam, game free hay nháº¥t','2025-09-05 18:00:00','2025-09-05 07:14:49','2025-09-05 07:14:49',NULL),(10,'5Kitu - Game ghép chữ tiếng Việt gây nghiện','5kitu-game-ghep-chu-tieng-viet-gay-nghien','5Kitu5kitu có nghĩa là 5 kí tự, đây là một trò chơi ghép chữ tiếng Việt từ 5 kí tự khác nhau. Với giao diện đơn giản, trực quan và cách chơi dễ dàng nhưng cũng không kém phần thú vị và đặc biệt,...','<p>5Kitu</p><p>5kitu có nghĩa là 5 kí tự, đây là một trò chơi ghép chữ tiếng Việt từ 5 kí tự khác nhau. Với giao diện đơn giản, trực quan và cách chơi dễ dàng nhưng cũng không kém phần thú vị và đặc biệt, game rất dễ gây nghiện.</p><p>​</p><p>Cách chơi thực ra rất đơn giản:</p><ul><li><blockquote><p>Xếp 5 kí tự thành 1 từ tiếng Việt có nghĩa</p></blockquote></li><li><blockquote><p>Bạn có 12 giây để xếp từ, nếu xếp đúng bạn sẽ có thêm 2 giây</p></blockquote></li><li><blockquote><p>Lắc để xóa từ sai</p></blockquote></li><li><blockquote><p>Bảng xếp hạng toàn cầu</p></blockquote></li><li><blockquote><p>Chia sẻ dễ dàng qua facebook, twitter,...</p></blockquote></li></ul><p></p><p>Lưu ý:</p><ul><li><blockquote><p>Trong màn chơi lúc nào 1 từ cũng có 5 kí tự. Ví dụ \"Nhưng\"</p></blockquote></li><li><blockquote><p>Từ ghép có 5 kí tự, bao gồm cả khoảng trắng (space), ví dụ từ \"bí ẩn\"</p></blockquote></li><li><blockquote><p>Từ ghép có 5 kí tự, không có khoảng trắng, ví dụ từ \"ácthú\" (ác thú)</p></blockquote></li></ul><p>Link download:</p><p>IOS: https://itunes.apple.com/us/app/5kitu-5-ki-tu/id996838574?ls=1&mt=8</p>',0,0,1,16,'16','mobile game,puzzle,tieng viet,5kitu,ghep chu','LamGame Team',1,'','vi',1,1,'5Kitu - Game ghép chữ tiếng Việt gây nghiện | LamGame','5Kitu5kitu có nghĩa là 5 kí tự, đây là một trò chơi ghép chữ tiếng Việt từ 5 kí tự khác nhau. Với giao diện đơn giản, trực quan và cách chơi dễ dàng nhưng cũng không kém phần thú vị và đặc biệt,...','mobile game,puzzle,tieng viet,5kitu,ghep chu','2025-09-05 20:22:54','2025-09-05 13:22:54','2025-09-05 13:22:54',NULL),(11,'Borderlands 2 - Game bắn súng RPG hấp dẫn','borderlands-2-game-ban-sung-rpg-hap-dan','Borderlands 2 - Xuất sắc hơn hẳn người tiền nhiệmNếu phần Borderlands đầu tiên nổi danh nhờ ý tưởng sáng tạo mới mẻ cộng với phong cách hài hước độc đáo, thì trong Borderlands 2, game thủ sẽ còn được...','<h1 id=\"borderlands-2---xuất-sắc-hơn-hẳn-người-tiền-nhiệm\"><strong>Borderlands 2 - Xuất sắc hơn hẳn người tiền nhiệm</strong></h1><p>Nếu phần <em>Borderlands</em> đầu tiên nổi danh nhờ ý tưởng sáng tạo mới mẻ cộng với phong cách hài hước độc đáo, thì trong <em>Borderlands 2</em>, game thủ sẽ còn được tận hưởng nhiều hơn thế nữa.</p><p></p><p>Đặc điểm đầu tiên mà hãng Gearbox tập trung cải tiến chính là thế giới của game. Dù Pandara được thể hiện bằng những hình ảnh ấn tượng với các dân cư sinh sống quái chiêu, nhưng nhìn chung nơi này vẫn thiếu đi một cục diện bao quát. Tại sao bạn có mặt tại nơi đây? Có thật là những nhân vật khác cũng có thể sinh sống tại đây, hay là chỉ toàn kẻ thù của bạn thôi?</p><p>Theo lời ông Gibson, trong phần hai này, các nhà viết kịch bản sẽ được đặt ngang hàng với các nhà chỉ đạo thiết kế. Điều này đảm bảo cho việc cốt truyện trong game cũng như hệ thống các nhiệm vụ sẽ được gắn kết một cách chặt chẽ.</p><p></p><p>Những tình tiết cụ thể của câu chuyện trong game khó có thể được tiết lộ ở thời điểm hiện tại. Tuy vậy, fan hâm mộ có thể hy vọng rằng nó cũng sẽ độc đáo như chính những chi tiết trong <em>Borderlands</em> 2 vậy.</p><p>Hãy tưởng tượng đến quang cảnh một trận chiến, khi mà tên boss được bọc thép hùng hậu lao vào khống chế một gã lùn bé nhỏ với chiếc khiên đồ sộ của mình. Hay như những con robot đánh bom cảm tử lao đến bạn một cách liều lĩnh, sẵn sàng bò lồm cồm trên mặt đất ngay cả khi đã mất đi đôi chân. Thậm chí trong game, bạn sẽ có cơ hội sử dụng các loại vũ khí \"ăn liền\", chỉ xài một lần rồi quăng vào lũ địch.</p><p></p><p>Ngoài ra, Gearbox còn hứa hẹn sẽ tân trang lại cách kẻ địch của bạn trong các trận chiến. Người chơi sẽ sớm nhận thấy kẻ địch trong <em>Borderlands</em>rất tinh khôn, nhận thức được các biến cố diễn ra xung quanh, và điều này giúp chúng tăng cơ hội tồn tại trên chiến trường.</p><p>AI sẽ nhảy lên các rìa tường để tấn công phía mạn sườn của bạn, tránh né phía đạn đang bắn xối xả, nhảy về chỗ ẩn nấp, hoặc chạy ra chỗ thoát hiểm khi bị trọng thương. Ông Gibson cho biết, ở phần game mới, kẻ địch có thể thực sự phối hợp chiến đấu, lợi dụng các ưu thế địa hình.</p><p></p><p>Trong khi đó, những tên \"tâm thần\" ở phần một vẫn sẽ cư xử hết sức điên loạn như trước, chẳng hạn như kiểu: “Wow, khẩu súng kìa! Tao muốn dí mặt mình vào nó quá đi”?!</p><p>Việc di chuyển qua lại khi thực hiện các nhiệm vụ nay cũng đã trở nên dễ chịu hơn, nhờ việc cải thiện tính năng vật lý của các phương tiện di chuyển.</p><p></p><p>Cách thức thiết kế nhiệm vụ cũng được hãng Gearbox thay đổi. Chẳng hạn như trong khi phải giải cứu một đồng đội đang bị bắt giữ làm con tin, ở phía trên con đập ngăn nước của tập đoàn tộc ác Hypertion. Nếu đã từng thưởng thức phần một, bạn sẽ nhanh chóng nhận ra con tin không ai khác hơn là nhân vật Roland.</p><p>Các nhân vật chính ở phần một nay sẽ trở thành NPC trong phần hai, vị trí của họ nay sẽ được thay thế bởi các nhân vật hoàn toàn mới.</p><p></p><p>Roland đang bị bắt giữ bởi gã robot gắn động cơ phản lực W4R-D3N. Con robot này sẽ lừa bạn phải đuổi theo rồi kêu cầu viện trợ đến tấn công người chơi dọc khu đập nước. Một vài trong kẻ địch thậm chí đến từ bản doanh trên… mặt trăng của tổ chức Hyperion.</p><p>Để bù lại, người chơi sẽ được đáp trả bằng hai khẩu súng có sức tàn phá cực kì khủng khiếp. Điều này cho thấy Gearbox đang cố gắng thực hiện mỗi nhiệm vụ như một cuộc phiêu lưu nhỏ, chứ không đơn thuần chỉ là chạy đến chỗ này, chỗ nọ trong thế giới game.</p><p>Link download: http://www.skidrowreloaded.com/borderlands-2-v1-8-1-all-dlcs/</p>',0,0,1,12,'12','borderlands 2,fps,rpg,co-op,pc game','LamGame Team',1,'/storage/blog/blog_11_thumb.jpg','vi',1,1,'Borderlands 2 - Game bắn súng RPG hấp dẫn | LamGame','Borderlands 2 - Xuất sắc hơn hẳn người tiền nhiệmNếu phần Borderlands đầu tiên nổi danh nhờ ý tưởng sáng tạo mới mẻ cộng với phong cách hài hước độc đáo, thì trong Borderlands 2, game thủ sẽ còn được...','borderlands 2,fps,rpg,co-op,pc game','2025-09-05 20:23:07','2025-09-05 13:23:07','2025-09-05 13:23:07',NULL),(12,'Boss Boxing - Game đấm bốc mobile sống động','boss-boxing-game-dam-boc-mobile-song-dong','Boss BoxingLà một arcade game do một Studio trẻ ở Việt Nam phát hành nên cốt truyện của Boss Boxing cũng rất đơn giản. Bạn sẽ vào vai một nhân viên hiền lành bị gã trưởng phòng khó tính liên tục bắt...','<h1 id=\"boss-boxing\"><strong>Boss Boxing</strong></h1><p>Là một arcade game do một Studio trẻ ở Việt Nam phát hành nên cốt truyện của Boss Boxing cũng rất đơn giản. Bạn sẽ vào vai một nhân viên hiền lành bị gã trưởng phòng khó tính liên tục bắt nạt. Khi mà bản thân đã không thể chịu được nữa thì cần phải \"bùng cháy\", tính tình thay đổi và bạn bắt đầu... tấn công chính gã trưởng phòng khó tính kia.</p><p>Nhiệm vụ chính của người chơi khá là đơn giản, chỉ cần đấm lão sếp khó tính này thật nhanh và chính xác, đến khi hắn ta gục hẳn xuống sàn. Rồi sau đó người chơi tiếp tục gặp các nhân vật khác mạnh hơn, bá đạo hơn ví dụ như tên cướp, ác quỷ, thậm chí là cả Superman và Terminator. Nế bạn đấm ko đủ tốc độ để làm đối tượng xây xẩm mặt mày thì chính bạn sẽ là người bị hắn đấm lại với chỉ 1 đấm và khiến bạn bị Game Over ngay lập tức.</p><p>Cách điều khiển trong Boss Boxing theo hình thức \"tap-tap\" quen thuộc và đơn giản, yêu cầu người chơi chạm vào 2 bên màn hình, mỗi bên tương ứng với một quả đấm xuất hiện phía trên đầu mục tiêu mình tấn công. Bên phải tượng trưng cho nắm đấm màu xanh và bên trái là màu đỏ.</p><p></p><p>Lâu lâu sẽ có những đồng tiền thưởng xen giữa những cú đấm và bạn có thể thu thập chúng bằng cách chạm vào màn hình. Tuy nhiên, chính những đồng tiền này đôi khi lại làm \"lỡ nhịp\" đấm của bạn lúc đang tập trung khiến game trở nên \"khó nhằn\" hơn và chắc là sẽ có nhiều game thủ tức tối muốn chơi lại sau khi \"ham tiền bỏ mạng\".</p><p></p><p>Với cách chơi đơn giản cùng hình ảnh thú vị, hài hước. Boss Boxing là một game khó có thể bỏ qua đối với dân văn phòng ưa thích những tựa game minigame mang giá trị giải trí nhẹ nhàng sau thời gian làm việc căng thẳng.</p>',0,0,1,16,'16','boss boxing,mobile game,dam boc,action,boxing','LamGame Team',1,'/storage/blog/blog_12_thumb.jpg','vi',1,1,'Boss Boxing - Game đấm bốc mobile sống động | LamGame','Boss BoxingLà một arcade game do một Studio trẻ ở Việt Nam phát hành nên cốt truyện của Boss Boxing cũng rất đơn giản. Bạn sẽ vào vai một nhân viên hiền lành bị gã trưởng phòng khó tính liên tục bắt...','boss boxing,mobile game,dam boc,action,boxing','2025-09-05 20:23:16','2025-09-05 13:23:16','2025-09-05 13:23:16',NULL),(13,'Overwatch - Cập nhật bản đồ Eichenwald mới','overwatch-cap-nhat-ban-do-eichenwald-moi','Bản cập nhât Overwatch trực tuyến dàn trận với bản đồ Eichenwald, Season 2 và hơn thế nữaSự phát triển mới nhất của D.Va, Zenyatta và các nhân vật khác.Sau khi chạy thử nghiệm vài tuần, Overwatch...','<p><strong>Bản cập nhât Overwatch trực tuyến dàn trận với bản đồ Eichenwald, Season 2 và hơn thế nữa</strong></p><p>Sự phát triển mới nhất của D.Va, Zenyatta và các nhân vật khác.</p><p>Sau khi chạy thử nghiệm vài tuần, Overwatch phiên bản 1.3 đã chính thức có mặt trên PC ngày hôm nay. Phiên bản này được quảng cáo rầm rộ sau khi kiểm tra kỹ lưỡng Competitive Play hoạt động như thế nào, bao gồm hạn chế các trận Sudden Death và sự xuất hiện của các emote cảm xúc mới, cũng như là phục trang Reinhardt skin hay bản đồ Eichenwald mới.</p><p>Ngoài ra còn có sự thay đổi trong các bản lỗi. Dưới đây là những điểm nổi bật nhất:</p><p><strong>Những điểm nổi bật nhất trong Patch 1.3</strong></p><ul><li><blockquote><p>Điểm kỹ năng skill rating được đánh giá theo mức 1-1500 thay vì 1-100 như phiên bản trước đây.</p></blockquote></li><li><blockquote><p>Game thủ trong Diamonds, Master và Grandmaster không tham gia bất kỳ trận đấu nào trong vòng 7 ngày sẽ mất hết điểm kỹ năng.</p></blockquote></li><li><blockquote><p>Chơi trong trận single sẽ ngừng giảm điểm kỹ năng</p></blockquote></li><li><blockquote><p>Thay vì chơi trận Sudden Death, những trận đấu ràng buộc sẽ có kết quả hòa.</p></blockquote></li><li><blockquote><p>Watch point: Xóa bỏ điểm đầu Gibraltar.</p></blockquote></li><li><blockquote><p>Các game thủ phải tham gia nhiều trận đấu hơn để xóa bỏ hình phạt vì họ rời bỏ trận đấu sớm.</p></blockquote></li><li><blockquote><p>Ultimate( kỹ năng tối thượng) khi được kích hoạt sẽ nhanh hơn nhiều ( 0,25 giây thay vì 1 giây như trước đây)</p></blockquote></li><li><blockquote><p>Hầu hết khả năng của các tướng sẽ không làm gián đoạn khả năng tấn công cận chiến nhanh.</p></blockquote></li><li><blockquote><p>Genji – cú nhảy đúp sẽ không được thiết lập khi leo tường.</p></blockquote></li><li><blockquote><p>Genji – thời hạn cuối cùng của Genji sẽ giảm từ 8 giây xuống còn 6 giây.</p></blockquote></li><li><blockquote><p>Mercy- lượng máu hồi phục trong 1 giây tăng 20%</p></blockquote></li><li><blockquote><p>Roadhog- Nếu mục tiêu móc không có trong tầm ngắm của Roadhog thì nó sẽ được chuyển trở lại vị trí ban đầu.</p></blockquote></li><li><blockquote><p>Zenyatta- Mức độ hủy diệt tăng thêm khi gắn Orb of Discord giảm từ 50% còn 30%</p></blockquote></li><li><blockquote><p>Mở khóa mới- các biểu tượng ngồi và cười đều sẵn sàng cho các tướng</p></blockquote></li><li><blockquote><p>Mở khóa mới – phục trang mới New Legendary skins mới được bổ sung cho Reinhardt.</p></blockquote></li></ul><blockquote><p>Theo tôi đây là những điểm nổi bật nhất. Đọc bản ghi chú đầy đủ cho các bản bổ sung và thay đổi.</p></blockquote>',0,0,1,12,'12','overwatch,eichenwald,update,blizzard,fps','LamGame Team',1,'/storage/blog/blog_13_thumb.jpg','vi',1,1,'Overwatch - Cập nhật bản đồ Eichenwald mới | LamGame','Bản cập nhât Overwatch trực tuyến dàn trận với bản đồ Eichenwald, Season 2 và hơn thế nữaSự phát triển mới nhất của D.Va, Zenyatta và các nhân vật khác.Sau khi chạy thử nghiệm vài tuần, Overwatch...','overwatch,eichenwald,update,blizzard,fps','2025-09-05 20:23:18','2025-09-05 13:23:18','2025-09-05 13:23:18',NULL),(14,'Bộ sưu tập chuột gaming chất lượng cao','bo-suu-tap-chuot-gaming-chat-luong-cao','Bộ sưu tập chuột, bàn phím và tai nghe Logitech Prodigy được kỳ vọng sẽ là những món phụ kiện không thể thiếu.Ở một mức giá phải chăng, không khá tốn kém – chỉ $70.$70. Đó là số tiền Logitech đang...','<p><strong>Bộ sưu tập chuột, bàn phím và tai nghe Logitech Prodigy được kỳ vọng sẽ là những món phụ kiện không thể thiếu.</strong></p><p>Ở một mức giá phải chăng, không khá tốn kém – chỉ $70.</p><p>$70. Đó là số tiền Logitech đang đánh cược những game thủ khi họ sẵn sàng chi trả cho một tai nghe, chuột, bàn phím hoặc một thiết bị chuyên dụng của bộ sưu tập gaming gear. Tôi đã và đang theo dõi thương hiệu game của Logitech, họ luôn luôn tung ra thị trường một hoặc hai dòng con chuột mới, bàn phím hay bộ tai nghe mới tại cùng một thời điểm. Hôm nay họ đã tung ra bốn thiết kế trong bộ sưu tập mới nhất của dòng ‘Prodigy’. Những thiết kế này hứa hẹn mang lại những trải nghiệm thoải mái nhất cho người dùng: dễ dàng sử dụng và có độ nhạy cao. Những thiết kế bao gồm một bàn phím màng, một tai nghe có dây và hai con chuột ( giống hệt nhau về hình thức, nhưng một trong chúng là mô hình không dây). Chuột không dây có giá bán ở mức $100, riêng chuột có dây được bán với giá cả hợp lý hơn: 70 đô.</p><p>Dòng Prodigy không chỉ nhằm vào các game thủ chuyên nghiệp – những người đã sở hữu một con chuột chất lượng cao hay một bàn phím cơ học mà còn quảng bá đén những người chơi đang sử dụng chuột văn phòng và bàn phím màng giá rẻ đi kèm với máy tính của họ. Đây chỉ là tổng thể một số phụ kiện đơn giản so với hầu hết các thiết bị chơi game được giới thiệu gần đây của Logitech - ưu tiên các tính năng dễ sử dụng và tạo sự thoải mái cho người chơi. Họ còn rất khôn khéo trong việc tái sử dụng một số công nghệ khá ấn tượng.</p><p>Trong nhóm sản phẩm mới, Logitech G403 Prodigy Gaming Mouse và mẫu PMW 3366 sử dụng cảm biến từ không dây được cho là những con chuột tuyệt vời nhất của thương hiệu Logitech. Chúng thực hiện tốt tất cả các hoạt động với CPI tối đa lên đến 12,000. Hơn thế nữa, nó được thiết kế với bộ nhớ bên trong và vẫn có thể hoạt động với phần mềm điều khiển Logitech trong trường hợp bạn muốn tuỳ chỉnh các thiết lập được lập trình sẵn. Mô hình không dây sử dụng tần số radio 2.4GHz giống như mẫu G900, có nghĩa là bạn có thể chơi game bằng chuột ở nơi có mạng LAN rộng lớn với sự có mặt bắt buộc của mạng không dây và sẽ không bao giờ thấy biểu tượng con trỏ nhấp nháy.</p><p>Con chuột G403 bao gồm 10 gram trọng lượng di động và là một trong những con chuột chơi game có sẵn nhẹ nhất với trọng lượng đạt 90 grams. Mô hình khoogn dây nặng hơn một chút ở mức 107 gram, hoặc 117 gram với trọng lượng tăng thêm. Giống như Logitech cũ G302/303 và chuột Pro Gaming gần đây, những thiết kế này có hệ thống lò xo căng trong các nút bấm trái/phải, cho khả năng truyền tín hiệu giữa máy và chuột cực kỳ nhanh chóng và chính xác..</p><p>Hình dạng của G403 đơn giản hơn so với một số mô hình con chuột trước của Logitech, G403 không được thiết kế để mang các nút và các tính năng, nhưng nó không có một bộ phận dư nào .</p><p>Các bàn phím G213 Prodigy và tai nghe G231 Prodigy không sử dụng lại công nghệ cùng một cách với những con chuột. $ 70 có vẻ đáng tiền cho một bàn phím màng - thậm chí là một bàn phím tốt nữa - và Logitech hứa hẹn sản phẩm sẽ mang tốc độ báo cáo lên đến 500Hz và rollover cực kỳ phong phú và chủ chốt để chiến game. $ 70 là giá cả phải chăng cho một tai nghe chuyên game, nhưng bạn có thể nhận được một tai nghe tuyệt vời hơn nữa ở khoảng 100 $. Trong thời gian tới chúng tôi sẽ mua Logitech G231 để trải nghiệm chúng.</p><p>Dòng Prodigy sử dụng ánh sáng RGB tùy biến trên diện rộng sẽ được ra mắt trong tháng Chín. Tại châu Âu, chúng sẽ có giá từ € 60 đến € 120. Tuy nhiên vẫn chưa có thông tin về giá cả được bán ra Anh .</p>',0,0,1,20,'20','gaming mouse,hardware,gear,review,pc gaming','LamGame Team',1,'/storage/blog/blog_14_thumb.jpg','vi',1,1,'Bộ sưu tập chuột gaming chất lượng cao | LamGame','Bộ sưu tập chuột, bàn phím và tai nghe Logitech Prodigy được kỳ vọng sẽ là những món phụ kiện không thể thiếu.Ở một mức giá phải chăng, không khá tốn kém – chỉ $70.$70. Đó là số tiền Logitech đang...','gaming mouse,hardware,gear,review,pc gaming','2025-09-05 20:23:22','2025-09-05 13:23:22','2025-09-05 13:23:22',NULL),(15,'CS:GO - Đánh giá chi tiết game FPS huyền thoại','cs-go-danh-gia-chi-tiet-game-fps-huyen-thoai','Đánh giá chi tiết game CS: Global OffensiveLà tựa game kế thừa những tinh hoa của Counter-Strike 1.6, Global Offensive (CS: GO) hiện đang là tựa game bắn súng FPS nhận được rất nhiều sự quan tâm...','<h1 id=\"đanh-gia-chi-tiêt-game-cs-global-offensive\"><strong>Đánh giá chi tiết game CS: Global Offensive</strong></h1><p>Là tựa game kế thừa những tinh hoa của Counter-Strike 1.6, Global Offensive (CS: GO) hiện đang là tựa game bắn súng FPS nhận được rất nhiều sự quan tâm của cộng đồng game thủ Việt.</p><p><strong>Những thay đổi về chế độ chơi</strong></p><p>Global Offensive sở hữu 5 Mode để chơi bao gồm 3 Mode chơi quen thuộc là Casual, Competitive với 2 loại map chính là giải cứu con tin và gỡ bom được áp dụng. Bên cạnh đó mode chơi Deathmatch dù khá quen thuộc nhưng đã có những sửa đổi khi chuyển sang tính điểm chứ không chỉ dựa trên chỉ số Kill/Death để xếp hạng, và tất nhiên, những người chơi khi \"assist\" cũng vẫn nhận được điểm chứ không phải chỉ cần \"Last Hit\" ăn mạng như Counter-Strike 1.6.</p><p></p><p>Ngoài ra, Arms Race và Demolition là 2 Mode chơi tương đối mới lạ trong Global Offensive. Trong Arms Race, người chơi sẽ được bắn theo kiểu \"Deathmatch\" nhưng cứ sau khoảng vài mạng kill, nhân vật sẽ tự động bị thay đổi vũ khí của mình. Cứ tiếp tục, nhân vật sẽ lần lượt sử dụng tất cả các loại vũ khí của mình trong trận đấu. Người thắng cuộc ở đây sẽ là người đầu tiên sử dụng hết tất cả các loại vũ khí để kết liễu đối thủ (kể cả dao).</p><p>Trong khi đó, Demolition lại là sự kết hợp giữa các Mode chơi Casual/Competitive và Arms Race, khi mà trong trận đấu theo đội (thường là các map đặt bom), nhân vật sẽ tự động bị thay đổi vũ khí sau vài lượt kill đối thủ.</p><p><strong>Thay đổi về súng</strong></p><p>Một số khẩu súng khác đã được Valve thêm mới vào để tạo nên sự đa dạng cũng như cho game thủ có nhiều sự lựa chọn hơn, ví như khẩu M4A1-S, rẻ hơn khẩu đàn anh nhưng lại chỉ có 20 viên (nhưng lại có thêm giảm thanh). Tuy nhiên, điều đáng chú ý là người chơi chỉ được phép mua một số khẩu súng nhất định khi vào trận đấu (những khẩu súng này sẽ được gamer lựa chọn trước trận đấu). Ví dụ, trong trận đấu, một người chơi chỉ có thể sử dụng duy nhất 1 khẩu M4A1-S hoặc M4A4 (tương tự M4A1 cũ) chứ không thể mua cả 2 loại súng này. Một số súng mới cũng được thêm vào như CZ-75, SCAR...</p><p></p><p>Một điểm khác biệt là người chơi có thể thay đổi hình dạng cho các khẩu súng của mình (skin), vật phẩm mà người chơi có thể nhận được từ hệ thống vào cuối trận đấu hoặc mua nó từ những người chơi khác. Tất nhiên, các skin này chỉ có tác dụng \"làm đẹp\" chứ không thay đổi sức mạnh của các khẩu súng.</p><p><strong>Thay đổi về bom và cơ chế ném bom</strong></p><p>Global Offensive cũng cung cấp thêm cho người chơi nhiều hơn về lựa chọn sử dụng bom nhờ việc thêm loại bom mới - \"Bom Lửa - Molotov Cocktail\". Loại bom lửa mới sau khi nổ sẽ tạo nên một vùng sát thương lửa trong vòng 7s. Bên cạnh đó, loại bom mới \"Decoy Grenade\" cũng cần được chú ý khi có dụng tạo tiếng súng giả.</p><p><strong>Đồ họa</strong></p><p>Rõ ràng, là tựa game kế thừa những tinh hoa từ người đàn anh đi trước , Global Offensive sở hữu nền đồ họa đẹp và mịn hơn hẳn so với Counter-Strike 1.6.</p><p></p><p><strong>Một số thay đổi đáng chú ý khác về cơ chế gameplay</strong></p><p>- Đục tường trong Global Offensive đã bị giảm thiểu rất nhiều. Game thủ sẽ gần như chỉ đục được ở những chỗ tường mỏng như mái nhà... Trong khi đó, ở những bức tường bê tông bình thường thì người chơi sẽ không đục được.</p><p></p><p>- Hệ thống Matchmaking cũng được tích hợp, hệ thống sẽ tự động tìm match cho người chơi dựa trên cấp độ chứ game thủ sẽ không phải tìm phòng như Counter-Strike 1.6. Điều này tránh cho việc các game thủ sẽ được chơi với người cùng cấp độ với mình, tránh việc người mới chơi phải đụng độ ngay với game thủ \"pro\".</p><p></p><p>- Địa hình của một số map đã bị thay đổi, ví dụ như đường lên bom nhỏ của map Inferno (Banana) đã được thay đổi, giúp cho phe Terrorist dễ tấn công hơn. Một ví dụ khác như người chơi sẽ không còn phải cúi để đi qua hốc nhỏ ở Bom B của Map Dust_2 mà có thể đi thẳng qua...</p><p>Nếu quan tâm game bạn có thể mua game tại: http://store.steampowered.com/app/730/</p>',0,0,1,12,'12','csgo,counter strike,fps,competitive,steam','LamGame Team',1,'/storage/blog/blog_15_thumb.jpg','vi',1,1,'CS:GO - Đánh giá chi tiết game FPS huyền thoại | LamGame','Đánh giá chi tiết game CS: Global OffensiveLà tựa game kế thừa những tinh hoa của Counter-Strike 1.6, Global Offensive (CS: GO) hiện đang là tựa game bắn súng FPS nhận được rất nhiều sự quan tâm...','csgo,counter strike,fps,competitive,steam','2025-09-05 20:23:25','2025-09-05 13:23:25','2025-09-05 13:23:25',NULL),(16,'Captain Heroes - Game siêu anh hùng mobile','captain-heroes-game-sieu-anh-hung-mobile','Captain Heroes - Game bắn súng thú vị cho game thủGame online di động Captain Heroes với lối chơi bắn súng arcade đang trở nên rất hot trong cộng đồng game thủ dạo gần đây vì lối chơi đầy thú...','<h1 id=\"captain-heroes---game-bắn-súng-thu-vi-cho-game-thủ\"><strong>Captain Heroes - Game bắn súng thú vị cho game thủ</strong></h1><p>Game online di động Captain Heroes với lối chơi bắn súng arcade đang trở nên rất hot trong cộng đồng game thủ dạo gần đây vì lối chơi đầy thú vị và game còn sở hữu nhiều chế độ chơi phong phú mới lạ.</p><p></p><p>Theo nhiều game thủ Việt thì Captain Heroes có gameplay mang tính giải trí khá cao, và đặc biệt là phong cách tạo hình nhân vật đậm chất anime được lấy ra từ Soul Guardians: Age of Midgard tạo nên sự quen thuộc dễ gần cho nhiều người</p><p></p><p>Có một điểm đặc biệt là các phần chơi của Captain Heroes có độ khó khá cao, tạo nên sức hút riêng biệt cho game.</p><p></p><p>Ngoài ra, một điểm cộng của Captain Heroes chính là trò chơi vận hành khá tốt trên các phần mềm giả lập, khiến game thủ không cần cầm điện thoại cả ngày gây tốn pin và khó chịu.</p><p>Link download</p><p>Android: https://play.google.com/store/apps/details?id=com.zqgame.CH.en.androidgp</p>',0,0,1,16,'16','captain heroes,mobile game,superhero,action','LamGame Team',1,'/storage/blog/blog_16_thumb.jpg','vi',1,1,'Captain Heroes - Game siêu anh hùng mobile | LamGame','Captain Heroes - Game bắn súng thú vị cho game thủGame online di động Captain Heroes với lối chơi bắn súng arcade đang trở nên rất hot trong cộng đồng game thủ dạo gần đây vì lối chơi đầy thú...','captain heroes,mobile game,superhero,action','2025-09-05 20:23:29','2025-09-05 13:23:29','2025-09-05 13:23:29',NULL),(17,'Cartel Kings - Game chiến thuật băng đảng','cartel-kings-game-chien-thuat-b-ng-dang','Cartel Kings - khi người chơi trở thành \"bố già\" Mafia ​Bạn đã chán những trò chơi trong sáng /tốt đẹp? Vậy thì hãy để Cartel Kings đưa bạn vào thế giới Mafia với những cuộc đọ súng này lửa và...','<h1 id=\"cartel-kings---khi-ngươi-chơi-trở-thành-bố-già-mafia\"><strong>Cartel Kings - khi người chơi trở thành \"bố già\" Mafia</strong> </h1><p>​</p><p>Bạn đã chán những trò chơi trong sáng /tốt đẹp? Vậy thì hãy để Cartel Kings đưa bạn vào thế giới Mafia với những cuộc đọ súng này lửa và trở thành bố già .</p><p>​</p><p>Cartel Kings là sản phẩm game hành động mới nhất của hãng Mobile Gaming Studios, kế nhiệm phiên bản All Guns Blazing khá thành công trên di động. Trong Cartel Kings, người chơi sẽ vào vai những gã to xác thuộc thế giới ngầm cùng chuỗi nhiệm vụ cướp ngân hàng vô cùng nguy hiểm. Bạn sẽ sở hữu trong tay những khẩu súng ngắn hiện đại nhất, di chuyển trên chiếc xe bọc thép được bảo vệ nghiêm ngặt cho phi vụ lớn này.</p><p>Với những chiến lợi phẩm, tiền bạc kiếm được sau những pha đọ súng nảy lửa, Cartel Kings còn cho phép bạn xây dựng căn cứ riêng cho mình cũng như các công trình khác nhằm phát triển thế giới ngầm một cách rộng rãi và mạnh mẽ hơn. Hãy cố gắng để trờ thành một \"Godfather\" thứ thiệt nhé.</p><p>Linkdownload:</p><p>Android: https://play.google.com/store/apps/details?id=com.mgs.cc</p><p>IOS: https://itunes.apple.com/us/app/cartel-kings/id969155632?mt=8</p>',0,0,1,16,'16','cartel kings,mobile game,strategy,gang,tactical','LamGame Team',1,'/storage/blog/blog_17_thumb.jpg','vi',1,1,'Cartel Kings - Game chiến thuật băng đảng | LamGame','Cartel Kings - khi người chơi trở thành \"bố già\" Mafia ​Bạn đã chán những trò chơi trong sáng /tốt đẹp? Vậy thì hãy để Cartel Kings đưa bạn vào thế giới Mafia với những cuộc đọ súng này lửa và...','cartel kings,mobile game,strategy,gang,tactical','2025-09-05 20:23:30','2025-09-05 13:23:30','2025-09-05 13:23:30',NULL),(18,'Deadwalk: The Last War - Game zombie sinh tồn','deadwalk-the-last-war-game-zombie-sinh-ton','Deadwalk: The Last WarĐến vối Deadwalk: The Last War bạn sẽ đóng vai vị chỉ huy cấp cao và có nhiệm vụ xây dựng tuyến phòng thủ vững chắc, tuyển lựa những đội quân hùng mạnh để ngăn cản và tiêu...','<h1 id=\"deadwalk-the-last-war\"><strong>Deadwalk: The Last War</strong></h1><p>Đến vối Deadwalk: The Last War bạn sẽ đóng vai vị chỉ huy cấp cao và có nhiệm vụ xây dựng tuyến phòng thủ vững chắc, tuyển lựa những đội quân hùng mạnh để ngăn cản và tiêu diệt bè lũ Zombie. Cũng như những nhà chỉ huy quân sự tài ba khác, người chơi hãy cố gắng tận dụng những lợi thế nhỏ nhất của mình từ số lượng tiền ít ỏi mà game cung cấp ban đầu, tránh tiêu tốn vào những việc không cần thiết như tăng tốc độ xây dựng các tòa nhà.</p><p></p><p>Đám Zombie rất đông và hung hãn, thế nên bạn sẽ không phải đơn độc chiến đấu một mình mà còn nhận được sự trợ giúp từ hơn 100 vị thần và anh hùng trong truyền thuyết như thần Zeus, Hades, Thor... hay những đội quân tinh nhuệ như Siren, Phoenix, War Machines...để tiêu diệt sạch sẽ nguồn cơn của bè lũ xác sống. Bên cạnh đó, người chơi hoàn toàn có thể gia nhập liên minh với những game thủ khác, cùng họ phát triển một liên minh hùng mạnh và chinh phục mọi thử thách.</p><p></p><p>Ngoài lối chơi và nội dung không quá mới lạ thì đồ họa trong game là một trong những yếu tố làm nên thành công lớn. Ngoại cảnh và nhân vật được thiết kế 3D theo phong cách truyện tranh, mỗi vị thần đều tạo được một phong thái rất riêng, tạo nên một đội quân có 1 không 2 cho bất kỳ ai tham gia trải nghiệm.</p><p></p><p>Trailer:</p><p><a href=\"https://www.youtube.com/watch?v=XXmPC23fzMY\"><span class=\"underline\">https://www.youtube.com/watch?v=XXmPC23fzMY</span></a></p><p>Link download:</p><p>Android:https://play.google.com/store/apps/details?id=com.bigkraken.thelastwar</p><p>IOS:https://itunes.apple.com/us/app/deadwalk-the-last-war/id974915593</p>',0,0,1,16,'16','deadwalk,zombie,survival,mobile game,action','LamGame Team',1,'/storage/blog/blog_18_thumb.jpg','vi',1,1,'Deadwalk: The Last War - Game zombie sinh tồn | LamGame','Deadwalk: The Last WarĐến vối Deadwalk: The Last War bạn sẽ đóng vai vị chỉ huy cấp cao và có nhiệm vụ xây dựng tuyến phòng thủ vững chắc, tuyển lựa những đội quân hùng mạnh để ngăn cản và tiêu...','deadwalk,zombie,survival,mobile game,action','2025-09-05 20:23:32','2025-09-05 13:23:32','2025-09-05 13:23:32',NULL),(19,'10 tựa game PC cũ \"hay tuyệt\" mà bạn không nên bỏ qua','10-tua-game-pc-cu-hay-tuyet-ma-ban-khong-nen-bo-qua','10 tựa game PC cũ \'hay tuyệt\' mà bạn không nên bỏ quaThực tế không phải cái gì mới cũng hay hơn, tốt hơn những cái cũ, điều đó được thể hiện qua Các trò chơi tuyệt vời dù thực sự chúng đã cũ nhưng...','<h1 id=\"tựa-game-pc-cũ-hay-tuyệt-mà-bạn-không-nên-bỏ-qua\"><strong>10 tựa game PC cũ \'hay tuyệt\' mà bạn không nên bỏ qua</strong></h1><h2 id=\"thực-tế-không-phải-cái-gì-mới-cũng-hay-hơn-tốt-hơn-những-cái-cũ-điều-đó-được-thể-hiện-qua-các-trò-chơi-tuyệt-vời-dù-thực-sự-chúng-đã-cũ-nhưng-vẫn-thu-hút-được-sự-quan-tâm-của-người-chơi-bởi-giá-trị-mà-chúng-mang-lại.-vì-thế-bạn-đừng-ngạc-nhiên-khi-có-một-số-trò-chơi-được-chính-các-hãng-phát-hành-cho-làm-lại-dưới-dạng-remaster-hay-full-hd\">Thực tế không phải cái gì mới cũng hay hơn, tốt hơn những cái cũ, điều đó được thể hiện qua Các trò chơi tuyệt vời dù thực sự chúng đã cũ nhưng vẫn thu hút được sự quan tâm của người chơi bởi giá trị mà chúng mang lại. Vì thế bạn đừng ngạc nhiên khi có một số trò chơi được chính các hãng phát hành cho làm lại dưới dạng “remaster” hay Full HD…</h2><p>Và 10 tựa game PC cổ điển dưới đây đều có thể tìm mua tại trang web www.gog.com, đáp ứng nhu cầu tìm hiểu của các chuyên gia ngành game lẫn những người chơi muốn tìm chút gì đó “hoài cổ”.</p><p><strong>Baldur\'s Gate II</strong></p><p>Trong bộ sưu tập các game nhập vai , cốt truyện của Baldur của Gate II trông có vẻ khá bình thường với thể loại huyễn tưởng, nhưng điểm đặc biệt của nó là vô cùng rộng lớn. Game có các chuỗi nhiệm vụ mang tính thử thách cao và dàn nhân vật đồng hành đa dạng.</p><p>Phiên bản Baldur\'s Gate II: Enhanced Edition sẽ là lựa chọn không tồi nếu bạn muốn có một bản Baldur\'s Gate II hỗ trợ màn hình rộng cùng một vài thay đổi cơ chế trong game .</p><p></p><p><strong>Heroes of Might and Magic 3</strong></p><p>Heroes of Might and Magic 3 là một trò chơi chiến lược theo lượt (turn-based strategy) xuất sắc, pha trộn giữa kiểu thám hiểm khám phá với cách chiến đấu theo lượt. Trong đó, người chơi điều khiển quân đội là những sinh vật thần thoại do các nhân vật anh hùng làm đầu kéo. Trong những năm qua, lần lượt đã có nhiều phiên bản Heroes of Might and Magic ra mắt, nhưng chung quy thì bản thứ 3 vẫn là bản nổi trội, đơn giản và hay nhất.</p><p></p><p>Bạn có thể tránh phiên bản HD mà Ubisoft vừa ra mắt hồi đầu năm nay. Rõ ràng phiên bản mới có chất lượng đồ họa tốt hơn, màn hình rộng tốt hơn, nhưng không ít nội dung đã bị cắt bỏ, có thể do mất mã nguồn. Bản chính thức và các bản mở rộng của Heroes of Might and Magic 3 mới là đáng tiền.</p><p><strong>Deus Ex</strong></p><p>Cùng với System Shock 2, Deus Ex tạo nên nền tảng lai tạo giữa 2 thể loại bắn súng góc nhìn thứ nhất và nhập vai đã xuất hiện ở cuối những năm 90. Có nhiều người trong ngành công nghiệp game thế giới vẫn còn những người chơi Deus Ex mỗi năm một lần.</p><p></p><p>Điểm mấu chốt thành công của Deus Ex là sự lựa chọn, cả về cốt truyện lẫn cách chơi. Các màn chơi đầy giá trị chơi lại vì có nhiều cách tiếp cận nhiệm vụ khác nhau cho người chơi lựa chọn. Đó là một mức độ thiết kế đạt tới đỉnh cao, cho người chơi sự tự do hơn hẳn.</p><p><strong>Star Wars: X-Wing/TIE Fighter</strong></p><p>Các trò chơi mô phỏng không gian thực sự tuyệt vời. Còn gì tuyệt hơn khi bạn có thể chỉ huy các tàu không gian tấn công những thế lực nổi loạn bảo vệ đế chế?</p><p></p><p>Điều đáng tiếc là bạn sẽ không có cơ hội sở hữu phiên bản CD-ROM của game ra mắt năm 1995. Phiên bản mới đã được nâng cao độ phân giải, thêm các giọng lồng tiếng đầy đủ, và bổ sung một số đoạn cắt cảnh.</p><p><strong>Riven</strong></p><p>Có lẽ bạn đã chơi Myst, trò chơi phiêu lưu giải đố nắm giữ kỉ lục game PC bán chạy nhất trong gần một thập kỷ. Nhưng bạn đã chơi phần tiếp theo của nó là Riven? Rộng lớn hơn, táo bạo hơn, Riven vẫn mang dáng vẻ cổ điển như Myst, và cũng tạo thành một tổng thể gắn kết hơn.</p><p></p><p>Điều thú vị bây giờ là bạn chỉ cần tải về, thay vì ngồi cài đặt với 5-6 CD-ROM khác nhau. Hãy chuẩn bị thử thách trí phán đoán của bạn, vì 99% bạn sẽ vấp ngay khó khăn ở lần đầu tiên và hãy sẵn sàng được tư vấn bằng một bản hướng dẫn trò chơi hoàn chỉnh!</p><p><strong>System Shock 2</strong></p><p>Là phiên bản \"linh hồn\" tiền nhiệm của trò chơi nổi tiếng BioShock, System Shock 2 trông gần gũi và thân thiện hơn với thể loại bắn súng góc nhìn thứ nhất , đặc biệt nhấn mạnh vào cốt truyện \"sinh tồn kinh dị trong vũ trụ\".</p><p></p><p>Với một số thay đổi về đồ họa, System Shock 2 trở nên tỏa sáng hơn.</p><p><strong>The Longest Journey</strong></p><p>The Longest Journey, được phát hành vào năm 1999, thường được xem là \"con thiên nga\" của thể loại phiêu lưu . Đây là một trong những trò chơi phiêu lưu có thời gian chơi dài nhất, sâu đậm nhất từng được thực hiện, sở hữu một trong những thế giới lớn nhất mà lịch sử ngành game biết đến.</p><p></p><p>Đặc biệt hơn cả, The Longest Journey ra mắt khi mà thể loại phiêu lưu trỏ và nhấn như nó đang... giãy chết hàng loạt!</p><p>Một số câu đố trong game bị đánh giá là... \"đần\" vì đáp án không thỏa mãn được các suy luận của dân nghiền, vì vậy bạn hãy chuẩn bị bản hướng dẫn để tham khảo khi chơi. Giá trị của The Longest Journey cứ thế kéo các fan mãi cho tới 8 năm sau, khi mà phiên bản tiếp theo của nó được ra đời, đó là Dreamfall: The Longest Journey.</p><p><strong>Sid Meier\'s Alpha Centauri</strong></p><p>Phiên bản Civilization: Beyond Earth ra mắt năm ngoái gây thất vọng cho bạn? Đó sẽ là cơ hội để bạn sống lại với Sid Meier\'s Alpha Centauri - linh hồn của dòng game này, ra mắt lần đầu tiên vào năm 1999.</p><p></p><p>Sid Meier\'s Alpha Centauri là trò chơi chiến lược theo lượt dạng cơ bản. Nó không đẹp và được đẩy mạnh như là Civilization V, nhưng nó không khó để giải quyết và thưởng thức.</p><p><strong>The Last Express</strong></p><p>Trò chơi phiêu lưu với phong cách đồ họa độc đáo nói về một vụ ám sát bí hiểm đã rất hấp dẫn người chơi. Thời điểm diễn ra sự việc cũng khá bí ẩn: trên một chuyến xe lửa năm 1914 dưới cái bóng của chiến tranh thế giới thứ nhất. Diễn biến trong The Last Express diễn ra theo các diễn biến thời gian thực: chuyển động chậm và khó có kết thúc \"chính xác\".</p><p></p><p>Cốt truyện của The Last Express do Jordan Mechner biên soạn. Thật thú vị, sau đó Jordan Mechner chính là người tạo dựng siêu phẩm Prince of Persia.</p><p><strong>Duke Nukem 3D</strong></p><p>Nếu bạn có nhu cầu chơi một game bắn súng góc nhìn thứ nhất để hiểu những gì có ở những năm 90 thì Duke Nukem 3D là lựa chọn hoàn toàn phù hợp. Game sẽ giúp bạn bùng nổ đúng điệu!</p><p></p><p>Có một số quan điểm về văn hóa Pop sẽ khác biệt, ngoài ra do là một game bắn súng cổ điển nên tiết tấu của Duke Nukem 3D sẽ nhanh hơn với những gì bạn đã quen chơi hiện nay.</p>',0,0,1,12,'12','pc games,retro games,classic games,old pc games,game cu','LamGame Team',1,'/storage/blog/blog_19_thumb.jpg','vi',1,1,'10 tựa game PC cũ \"hay tuyệt\" mà bạn không nên bỏ qua | LamGame','10 tựa game PC cũ \'hay tuyệt\' mà bạn không nên bỏ quaThực tế không phải cái gì mới cũng hay hơn, tốt hơn những cái cũ, điều đó được thể hiện qua Các trò chơi tuyệt vời dù thực sự chúng đã cũ nhưng...','game PC cu,game classic,retro games,game hay,top 10 game','2025-09-05 20:27:48','2025-09-05 13:27:48','2025-09-05 13:27:48',NULL),(20,'Dirty Bomb - Game FPS miễn phí hay nhất từ Splash Damage','dirty-bomb-game-fps-mien-phi-hay-nhat-tu-splash-damage','Đánh giá Dirty Bomb - Game bắn súng cực hot trong tháng 6Game online bắn súng hấp dẫn Dirty Bomb đã được open beta rộng rãi vào ngày 2/6 vừa qua thông qua hệ thống Steam. Đây là cơ hội cực...','<h1 id=\"đánh-giá-dirty-bomb---game-băn-sung-cưc-hot-trong-thang-6\"><strong>Đánh giá Dirty Bomb - Game bắn súng cực hot trong tháng 6</strong></h1><p>Game online bắn súng hấp dẫn Dirty Bomb đã được open beta rộng rãi vào ngày 2/6 vừa qua thông qua hệ thống Steam. Đây là cơ hội cực tốt cho các gamer FPS Việt Nam muốn trải nghiệm trò chơi \"hard core\" này bởi trò chơi sẽ được phát hành hoàn toàn miễn phí. Thông tin thêm có thể tham khảo tại:<a href=\"http://dirtybomb.nexon.net/\">http://dirtybomb.nexon.net/</a>.</p><p></p><p>Có thể nói, Dirty Bomb sở hữu nền đồ họa và gameplay khá tương đồng với Warface - Tựa game bắn súng đang được NPH GoPlay phát hành tại Việt Nam hiện nay. Tuy nhiên, game cũng có những đặc điểm nổi bật riêng của mình và một trong những điểm đáng chú ý nhất trong Dirty Bomb chính là việc yêu cầu người chơi phải phối hợp với nhau một cách cẩn trọng.</p><p>Bên cạnh đó, Dirty Bomb cung cấp tới 15 nhân vật khác nhau, mỗi nhân vật đều sở hữu các khả năng đặc biệt độc nhất, cũng như các loại trang bị khác nhau. Mỗi nhân vật đều có thể chọn lựa miễn phí, nhưng người chơi sẽ phải \"luyện level\" cho các nhân vật này (để nhận được skill và mở slot trang bị) cũng như dùng tiền vàng để mua trang bị.</p><p>Ngoài các chế độ chơi quen thuộc như những tựa game bắn súng khác, Dirty Bomb giới thiệu đến người chơi một số chế độ chơi khá mới, ví dụ như Objective - Chế độ chơi yêu cầu mỗi đội phải hoàn thành một số nhiệm vụ được đưa ra từ trước...</p><p></p><p>Ngoài ra, trận đấu trong Dirty Bomb cũng được đẩy lên với Mode chơi tối đa 16 người, 8vs.8 để nâng cao tính cạnh tranh.</p><p>Dirty Bomb áp dụng hình thức lấy điểm kinh nghiệm để nhân vật có thể dần mở khóa được thêm các trang bị mới, cũng như slot trang bị trong nhân vật. Điều đáng tiếc là để dùng được các trang bị này, người chơi cũng sẽ cần phải có thêm tiền vàng để mua trong shop.</p><p></p><p>Tiền vàng sẽ nhận được sau mỗi trận đấu, tùy theo thành tích của người chơi trong các trận đấu PvP. Ngoài ra, họ cũng có thể kiếm tiền vàng và kinh nghiệm bằng cách hoàn thành các đợt nhiệm vụ được giao hay tham gia các màn chơi PvE. Dẫu vậy, để gia tăng lượng tiền cày kéo thì người chơi cũng có thể mua thêm các loại vật phẩm tăng lượng kinh nghiệm, lương tiền vàng nhân được sau mỗi trận đấu từ Cash Shop.</p><p><strong>Tổng kết</strong></p><p>Nhìn chung, Dirty Bomb là một tựa game bắn súng miễn phí rất đáng chú ý trong tháng 6. Không chỉ bởi nội dung chơi phong phú, đồ họa đẹp, bắt mắt mà cấu hình game cũng đòi hỏi không quá cao nên những fan của dòng game FPS có thể an tâm down game về trải nghiệm dễ dàng mà không gặp bất cứ trở lại nào.</p>',0,0,1,12,'12','dirty bomb,fps,free to play,splash damage,competitive','LamGame Team',1,'/storage/blog/blog_20_thumb.jpg','vi',1,1,'Dirty Bomb - Game FPS miễn phí hay nhất từ Splash Damage | LamGame','Đánh giá Dirty Bomb - Game bắn súng cực hot trong tháng 6Game online bắn súng hấp dẫn Dirty Bomb đã được open beta rộng rãi vào ngày 2/6 vừa qua thông qua hệ thống Steam. Đây là cơ hội cực...','dirty bomb,fps,free to play,splash damage,competitive','2025-09-05 21:29:33','2025-09-05 14:29:33','2025-09-05 14:29:33',NULL),(21,'Dirty Bomb - Đánh giá chi tiết game FPS miễn phí','dirty-bomb-danh-gia-chi-tiet-game-fps-mien-phi','Đánh giá Dirty Bomb - Game bắn súng cực hot trong tháng 6Game online bắn súng hấp dẫn Dirty Bomb đã được open beta rộng rãi vào ngày 2/6 vừa qua thông qua hệ thống Steam. Đây là cơ hội cực...','<h1 id=\"đánh-giá-dirty-bomb---game-băn-sung-cưc-hot-trong-thang-6\"><strong>Đánh giá Dirty Bomb - Game bắn súng cực hot trong tháng 6</strong></h1><p>Game online bắn súng hấp dẫn Dirty Bomb đã được open beta rộng rãi vào ngày 2/6 vừa qua thông qua hệ thống Steam. Đây là cơ hội cực tốt cho các gamer FPS Việt Nam muốn trải nghiệm trò chơi \"hard core\" này bởi trò chơi sẽ được phát hành hoàn toàn miễn phí. Thông tin thêm có thể tham khảo tại:<a href=\"http://dirtybomb.nexon.net/\">http://dirtybomb.nexon.net/</a>.</p><p></p><p>Có thể nói, Dirty Bomb sở hữu nền đồ họa và gameplay khá tương đồng với Warface - Tựa game bắn súng đang được NPH GoPlay phát hành tại Việt Nam hiện nay. Tuy nhiên, game cũng có những đặc điểm nổi bật riêng của mình và một trong những điểm đáng chú ý nhất trong Dirty Bomb chính là việc yêu cầu người chơi phải phối hợp với nhau một cách cẩn trọng.</p><p>Bên cạnh đó, Dirty Bomb cung cấp tới 15 nhân vật khác nhau, mỗi nhân vật đều sở hữu các khả năng đặc biệt độc nhất, cũng như các loại trang bị khác nhau. Mỗi nhân vật đều có thể chọn lựa miễn phí, nhưng người chơi sẽ phải \"luyện level\" cho các nhân vật này (để nhận được skill và mở slot trang bị) cũng như dùng tiền vàng để mua trang bị.</p><p>Ngoài các chế độ chơi quen thuộc như những tựa game bắn súng khác, Dirty Bomb giới thiệu đến người chơi một số chế độ chơi khá mới, ví dụ như Objective - Chế độ chơi yêu cầu mỗi đội phải hoàn thành một số nhiệm vụ được đưa ra từ trước...</p><p></p><p>Ngoài ra, trận đấu trong Dirty Bomb cũng được đẩy lên với Mode chơi tối đa 16 người, 8vs.8 để nâng cao tính cạnh tranh.</p><p>Dirty Bomb áp dụng hình thức lấy điểm kinh nghiệm để nhân vật có thể dần mở khóa được thêm các trang bị mới, cũng như slot trang bị trong nhân vật. Điều đáng tiếc là để dùng được các trang bị này, người chơi cũng sẽ cần phải có thêm tiền vàng để mua trong shop.</p><p></p><p>Tiền vàng sẽ nhận được sau mỗi trận đấu, tùy theo thành tích của người chơi trong các trận đấu PvP. Ngoài ra, họ cũng có thể kiếm tiền vàng và kinh nghiệm bằng cách hoàn thành các đợt nhiệm vụ được giao hay tham gia các màn chơi PvE. Dẫu vậy, để gia tăng lượng tiền cày kéo thì người chơi cũng có thể mua thêm các loại vật phẩm tăng lượng kinh nghiệm, lương tiền vàng nhân được sau mỗi trận đấu từ Cash Shop.</p><p><strong>Tổng kết</strong></p><p>Nhìn chung, Dirty Bomb là một tựa game bắn súng miễn phí rất đáng chú ý trong tháng 6. Không chỉ bởi nội dung chơi phong phú, đồ họa đẹp, bắt mắt mà cấu hình game cũng đòi hỏi không quá cao nên những fan của dòng game FPS có thể an tâm down game về trải nghiệm dễ dàng mà không gặp bất cứ trở lại nào.</p>',0,0,1,12,'12','dirty bomb,fps,review,free game,multiplayer','LamGame Team',1,'/storage/blog/blog_21_thumb.jpg','vi',1,1,'Dirty Bomb - Đánh giá chi tiết game FPS miễn phí | LamGame','Đánh giá Dirty Bomb - Game bắn súng cực hot trong tháng 6Game online bắn súng hấp dẫn Dirty Bomb đã được open beta rộng rãi vào ngày 2/6 vừa qua thông qua hệ thống Steam. Đây là cơ hội cực...','dirty bomb,fps,review,free game,multiplayer','2025-09-05 21:39:37','2025-09-05 14:29:37','2025-09-05 14:29:37',NULL),(22,'Resident Evil 7 - Ác mộng kinh hoàng trở lại','resident-evil-7-ac-mong-kinh-hoang-tro-lai','https://youtu.be/W1OUs3HwIuoResident Evil 7 mang màu sắc của game kinh dị theo phong cách hiện đại, gây ấn tượng mạnh cho người chơi ngay từ những màn đầu tiên. Người hâm mộ các game kinh dị như...','<p>https://youtu.be/W1OUs3HwIuo</p><p>Resident Evil 7 mang màu sắc của game kinh dị theo phong cách hiện đại, gây ấn tượng mạnh cho người chơi ngay từ những màn đầu tiên. Người hâm mộ các game kinh dị như Outlast có thể nhận ra nhiều điểm tương đồng về cấu trúc trong RE7 với những tùy chọn cùng các sự kiện với kịch tính cao. Trong những màn chơi đầu đôi khi bạn sẽ phung phí đạn dược lẽ ra sẽ được dùng đến cuối màn chơi. Mỗi màn chơi sẽ để lại cho bạn những khoảnh khắc độc đáo và vô cùng đáng ghi nhớ. Nhưng bạn cũng chú ý đừng phạm phải sai lầm vì xen lẫn trong những khoảnh khắc đáng nhớ là những kẻ thù đáng gờm không thể lơ là mà bạn cần phải giữ vững mục tiêu chiến đấu đến phút cuối. Có thể bạn sẽ phải mất một vài cơ hội trong tiếc nuối để có thể dần dần tiến bộ.</p><p>Quay lại câu chuyện xuyên suốt trong game, bạn sẽ theo chân nhân vật chính Ethan Winters. Sau khi nhận được một đoạn video bí ẩn từ Mia, người vợ yêu dấu của anh đã mất tích đã ba năm. Ethan quyết tâm đi tìm tung tích vợ mình. Ethan dừng chân tại một tòa nhà đổ nát sâu trong vịnh Louisiana. Ở đây anh phát hiện ra những bí mật kinh hoàng và hàng loạt bí ẩn xoay quanh nó. Và câu chuyện không chỉ xoay quanh Mia; mà nó còn là câu chuyện của cơn ác mộng khủng khiếp mà Ethan phải cố thoát ra.</p><p></p><p>Trong game người chơi có thể dễ dàng dự đoán nút thắt của câu chuyện, nhưng nó vẫn đủ sức lôi cuốn khiến người chơi cảm thấy thú vị khi từng nút thắt được mở ra - và khi đó RE7 sẽ không khiến bạn phải tò mò quá lâu. RE7 vẫn sẽ dẫn dắt bạn theo chủ đề xuyên suốt và rõ ràng, khiến cho bạn lún sâu vào câu chuyện ngày cành kịch tính và ngột ngạt cùng mới cảnh vật xung quanh từ sàn gỗ ẩm mốc, từng ô cửa sổ cũ kỹ, từng đốm sáng nhấp nháy ma mị. Các kết cấu, chi tiết, và âm thanh cũng không ngoại lệ, chúng đều hòa cùng tổng thể một cách ấn tượng nhất. RE7 sẽ khiến bạn phải toát mồ hôi hột từ bầu không khí ma mị rùng rợ chứ ko hù dọa bạn bằng những trò cũ rích.</p><p></p><p>Game thông minh trong cách sử các thành viên trong gia đình Baker khát máu gieo rắc nỗi kinh hoàng cho người chơi. Bạn phải liều mình vượt qua đống ngổn ngang tại nhà Baker và những khu vực xung quanh đó rất giống với bản Resident Evil gốc trước đây. Nó có cùng độ rộng mênh mông đáng sợ, cùng với một lịch sử tối tăm và tàn bạo. Nhưng nó đáng sợ hơn những con zombie đơn thuần. Chúng là những con quái vật gần như bất tử, bạn chỉ được trang bị một lượng đạn dược ít ỏi – nỗi sợ thực sự đến từ những người cha Jack của gia đình Baker với khuôn mặt không thể đáng sợ hơn mà bạn phải đối mặt ngay từ đầu màn chơi cho đến người mẹ Marguerite cùng đứa con Lucas đáng sợ không kém nhưng sẽ khiến bạn hòa mình trọn vẹn vào gameplay.</p><p>Bên cạnh đó, các đoạn băng VHS sẽ giúp bạn thu thập khá nhiều thông tin. Mỗi nhân vật đều có một cảnh quay hồi tưởng từ góc nhìn của nhân vật khác, chúng hoạt động tựa như một thiết bị tường thuật dẫn đường và giúp Ethan xóa tan phần nào sự sợ hãi và mệt mỏi trong sứ mệnh của mình. Bạn cũng sẽ gặp phải các câu đố, nhưng chúng không giống với các các phiên bản Resident Evil trước đó, ở phiên bản này chúng chỉ đóng một vai trò nhỏ, và hầu hết các câu đố đều đơn giản và dễ dàng vượt qua. Chắc chắn sẽ có một vài thử nghiệm phức tạp và nhiều thử thách hơn, nhưng cũng giống như các đoạn băng VHS, các câu đố phù hợp với game và không cản trở sứ mệnh của nhân vật.</p><p></p><p>Ethan có thể thu thập vũ khí vào ban đêm, nhờ đó anh ta có thể sở hữu cho mình bộ sưu tập vũ khí nhỏ nhưng đa dạng. Trong game có nhiều súng hơn mong đợi, nhưng bạn sẽ không bao giờ cảm thấy chúng là đủ. Nhìn chung, RE7 giữ phong cách đơn giản, ưu tiên thể hiện lối chơi qua hành động. Bạn sẽ phải cảnh giác trước những khoảng không gian xung quanh mình và cẩn thận tìm kiếm xung quanh một cách kiên nhẫn, khi đó bạn sẽ có thể tìm thấy những thứ bạn cần. Trong RE7, không quá khó cho bạn để thu thập các vật phẩm cần thiết.</p><p>Đôi lúc bạn phải đối mặt với kẻ thù mạnh hơn, khi đó thì chạy là thượng sách vì kẻ thù của bạn chạy không nhanh và cũng không đủ thông minh để đuổi theo và tóm lấy bạn.</p><p>Trận chiến ly kỳ này được mượn lại thiết kế cũ của Resident Evil bằng cách quăng bạn vào trận chiến đáng sợ và phức tạp ẩn chứa những bất ngờ mà bạn không lường trước được. Bạn cần học cách để sử dụng hiệu quả nhất thứ vũ khí mà mình nắm giữ trong tay. Chẳng may khi bị mất mạng, bạn sẽ lại được hồi sinh với vũ khí và vật phẩm nguyên vẹn. Khi đó có lẽ bạn sẽ cảm thấy rất sợ hãi và thất vọng, nhưng ít nhất bạn không mất nhiều thời gian.</p><p>RE7 có thể chơi trên PSVR . Về cơ bản, nội dung game vẫn giữ nguyên, nhưng cách bạn trải nghiệm chắc chắn sẽ khác. Nhìn chung, với VR thì game hoạt động tốt: đồ hoạ không mấy thay đổi, nhằm mục đích giúp bạn cảm nhận một cách trực quan nhất (đặc biệt là khi bạn có thể nhắm mục tiêu hay chỉ đơn giản bởi một cái xoay đầu), và khi đó bạn sẽ cảm nhận được độ kinh dị một cách chân thực hơn. Và điều quan trọng là, RE7 làm mọi thứ có thể để mang đến trải nghiệm tốt nhất và giảm thiểu tối đa sự khó chịu cho người chơi. Mặc dù không thể tưởng tượng được việc chơi toàn bộ game kéo dài 12 giờ cùng headphone nhưng RE7 chắc chắn là một lựa chọn tuyệt vời cho người hâm mộ VR.</p><p></p><p>Vào cuối game, tôi đã sẵn sàng cho trò chơi kết thúc, nhưng thực tế là tôi cảm thấy giống như mình đã sống sót một cuộc hành trình kinh hoàng đầy đau đớn. Các cuộc tấn công của kẻ thù đa dạng và một số phần chiếm khá nhiều thời gian, nhưng RE7 vẫn là game kinh dị khá thành công. Nó mang một tầm nhìn rõ ràng đến người chơi và thực sự gây ấn tượng. Trở lại về yếu tố kinh dị, Resident Evil đã lại và vô cùng đặc biệt.</p><p>Nhìn chung, RE7 không quá kén các nền tảng chơi game và có thể chạy ổn trên đa số cấu hình mà không gặp bất kỳ vấn đề kỹ thuật đáng kể nào - chẳng hạn như giảm tốc độ khung hình hoặc bị đơ. Tuy nhiên, vẫn có một số khác biệt đáng chú ý giữa các nền tảng khác nhau.</p><p></p><p>Như bạn mong đợi, trải nghiệm game trên PC mang đến cho bạn hình ảnh chất lượng cao nhất, với các hiệu ứng ánh sáng tuyệt vời. Trò chơi chạy trơn tru ngay cả ở độ phân giải 1440p với tất cả các tùy chọn hình ảnh cơ bản nhất. Với PS4 thì đồ hoạ không quá xuất sắc (RE7 hỗ trợ HDR trên PC, PS4, PS4 Pro, và Xbox One S.)</p><p>Thật không may, phiên bản Xbox One có thể sẽ khiến bạn phải thất vọng. Ngược lại với các phiên bản trên PC và PS4, khi chơi trên Xbox One màu sắc và hình ảnh sẽ trông như bị mờ.</p><p>Nhưng những hạn chế đó chẳng thể phá hỏng nhiệt tình của người chơi trên Xbox. Ở thời điểm hiện tại, chúng tôi khuyên bạn nên chơi trên PS4 hoặc PC nếu có thể.</p>',0,0,1,12,'12','resident evil 7,horror,survival horror,capcom,vr','LamGame Team',1,'/storage/blog/blog_22_thumb.jpg','vi',1,1,'Resident Evil 7 - Ác mộng kinh hoàng trở lại | LamGame','https://youtu.be/W1OUs3HwIuoResident Evil 7 mang màu sắc của game kinh dị theo phong cách hiện đại, gây ấn tượng mạnh cho người chơi ngay từ những màn đầu tiên. Người hâm mộ các game kinh dị như...','resident evil 7,horror,survival horror,capcom,vr','2025-09-05 21:49:38','2025-09-05 14:29:38','2025-09-05 14:29:38',NULL),(23,'PUBG - Lý do thu hút hàng triệu người chơi trên toàn thế giới','pubg-ly-do-thu-hut-hang-trieu-nguoi-choi-tren-toan-the-gioi','Lý do nào khiến Playerunknown’s Battlegrounds thu hút hàng triệu người chơiTrong tháng vừa qua, PlayerUnknown’s Battlegrounds đã đổ bộ lên Steam. Tựa game bắn súng sinh tồn này đã thu về được 11...','<h1 id=\"lý-do-nào-khiến-playerunknowns-battlegrounds-thu-hút-hàng-triệu-người-chơi\"><strong>Lý do nào khiến Playerunknown’s Battlegrounds thu hút hàng triệu người chơi</strong></h1><p>Trong tháng vừa qua, PlayerUnknown’s Battlegrounds đã đổ bộ lên Steam. Tựa game bắn súng sinh tồn này đã thu về được 11 triệu đô la chỉ trong tuần đầu tiên với bản Early Access và đã bán được hơn một triệu bản. Với những con số trên thì không có gì khó hiểu khi PlayerUnknown’s Battlegrounds đứng trong top những game được xem nhiều nhất trên Twitch, bên cạnh Hearthstone, Counter-Strike: Global Offensive và League of Legends. Vậy lý do gì khiến cho PlayerUnknown’s Battlegrounds có được thành công vang dội và vô cùng nhanh chóng? Lý do cực kỳ đơn giản: vì nó quá hay.</p><p>PlayerUnknown’s Battlegrounds cũng giống như những game bạn đã từng chơi: có quy mô lớn, lối chơi đối kháng hấp dẫn và dễ tiếp cận. Với lối chơi khá đơn giản, số lượng người chơi có thể lên đến 100, tất cả người chơi sẽ đến một hòn đảo khổng lồ trên cùng một chiếc máy bay. Ở đó bạn sẽ một mình chống lại cả “thế giới” là 99 người còn lại. Người chơi phải tự trang bị cho mình những vũ khí mà họ tìm thấy trong các thị trấn, các nhà máy hay căn cứ quân sự bị bỏ hoang. Trong mỗi trận chiến, kích thước vòng tròn trắng sẽ bị thu hẹp nhanh chóng. Vòng tròn trắng là nơi mà game bắt bạn phải ở bên trong, ở bên ngoài vòng tròn này, một vùng năng lượng sẽ xuất hiện và bạn sẽ bị tụt máu đến chết. Vào cuối mỗi trận đấu, người chơi sống sót cuối cùng sẽ là người chiến thắng. Có vẻ quá thú vị đấy chứ?</p><p>Các ý tưởng trong PlayerUnknown’s Battlegrounds không hề mới mẻ. Một số game cũng có chủ đề tương tự như: The Culling, Hungercraft, H1Z1: King of the Kill, trong đó có cả Arma 2 mod DayZ: Battle Royale - cũng được phát triển bởi Brendan Greene, nhà sáng tạo PlayerUnknown’s Battlegrounds (PlayerUnknown). PlayerUnknown không mang tính sáng tạo đột phá, nhưng sự kết hợp các yếu tố trong game lại tạo nên sức đột phá mạnh mẽ đánh vào thị hiếu người chơi hơn là những game đang có trên thị trường. Với những lý do đó, PlayerUnknown sẽ mang đến cho bạn một trải nghiệm trên cả tuyệt vời.</p><p>“Điều gì khiến bạn bị cuốn vào cuộc chạm trán căng thẳng với những người chơi khác?”</p><p>Hầu hết những người chiến thắng của PlayerUnknown đều trân trọng từng khoảnh khắc thời gian trong cuộc chiến. Trừ khoảng thời gian ban đầu để chuẩn bị, người chơi có rất ít thời gian để thăm dò xung quanh. Thất bại trong việc tìm vũ khí trong những phút mở đầu giống như là án tử cho chính người chơi. Vòng tròn trắng thu hẹp chết người luôn buộc người chơi vào giữa sân, khi đó bạn sẽ xoay sở thế nào nếu chỉ có hai bàn tay trắng?. Trận đấu diễn ra với tốc độ nhanh và ngày ngày càng căng thẳng khi bạn phải chiến đấu chống lại những người chơi khác, nhưng bạn sẽ không hề cảm thấy đơn độc, vì họ cũng đơn thân độc mã và có cùng mục tiêu như bạn, điều này càng khiến cho trò chơi trở nên tuyệt vời hơn. Theo cách nào đó, PlayerUnknown được người chơi cảm nhận như là một phần rất đặc biệt của DayZ: Battle Royale.</p><p>Là game sinh tồn nổi tiếng trên thế giới, DayZ (cả bản original và phiên bản độc lập) trở nên phổ biến và hấp dẫn nhờ tập trung vào những trải nghiệm khó có thể lường trước. DayZ không có mục tiêu cuối cùng mà bạn cần đạt được, vì thế những cuộc chiến với những người chơi khác trong bối cảnh Liên Xô có thể kết thúc bằng vô số cách: bạn sợ hãi và chạy trốn, hay những cuộc phiêu lưu đường dài hoặc tham gia các game mở rộng theo kiểu mèo vờn chuột, vv và vv ... game sẽ kết thúc theo kiểu nào thì chỉ có bạn mới có thể đặt tên cho nó được.</p><p>“PlayerUnknown’s Battlegrounds là DayZ với tốc độ nhanh hơn và ở một cấp độ cao hơn”.</p><p>Nhưng vì tính chất không mục đích, có thể bạn sẽ thấy DayZ chậm, nhàm chán và khá là trống rỗng. Một section game sẽ chiếm khoảng thời gian dài, nặng nề và không thể lường trước.</p><p>Từ người anh em đi trước, PlayerUnknown’s Battlegrounds giải quyết vấn đề bằng cách tạo ra đủ không gian giữa các trận chiến để tránh làm suy giảm cường độ của chúng. Bạn phải luôn luôn di chuyển và bạn cũng luôn có một mục đích cho mình: trước tiên là đặt ra các mục tiêu nhỏ, đạt được từng cột mốc nhỏ nhưng quan trọng, sau đó là vượt qua các mục tiêu khó khăn hơn. Đó chính là một DayZ khác với một tốc độ nhanh hơn và với cấp độ cao hơn nhiều. Bạn phải quan sát nhiều hơn, không những thế bạn cần học cách lần theo vết của người chơi khác chỉ trong nháy mắt như khi cánh mở cửa, khi phát hiện món vũ khí trên sàn nhưng không có đạn hay một thùng vật phẩm của người chơi khác bị ngã. Đôi khi đó là những dấu hiệu rõ ràng hơn như sự chuyển động của cánh cửa sổ hoặc tiếng động của một chiếc xe từ xa.</p><blockquote><p>Bạn có thể cảm nhận game một cách đầy đủ</p></blockquote><p>Có rất nhiều điều nhỏ mà PlayerUnknown’s Battlegrounds đã làm cho nó trở nên hoàn thiện hơn, ngay cả trong bản Early Access. Bạn có thể di chuyển trong khi kiểm tra bản đồ và vũ khí. Các vật phẩm nằm rải rác trên sàn dễ dàng thu thập như bạn chỉ dùng chuột phải hoặc click rồi thả nhanh. Nhưng nó cũng khiến người chơi mất vài phút để tiếp tục. Việc bị loại khỏi cuộc chơi đôi khi xảy ra bất ngờ khiến bạn không kịp trở tay, và khi đó không hề dễ dàng để bạn có thể quay trở lại. Là thể loại game bắn súng sinh tồn chỉ với bản Early Access nhưng PlayerUnknown’s Battlegrounds đã khiến người chơi có thể cảm nhận một cách đầy đủ.</p><p></p><p>PlayerUnknown’s Battlegrounds chắc chắn sẽ được hoàn thiện hơn nữa, nhưng ở thời điểm hiện tại không có gì ngạc nhiên khi PlayerUnknow có thể thu hút được nhiều người chơi đến thế. Trong game với thể loại bắn súng sinh tồn như thế này, điều lôi cuốn cũng như thử thách mà bạn phải vượt qua chỉ đơn giản là phải sống sót trước 99 người chơi khác trong khi bạn chẳng hề bất tử.</p>',0,0,1,12,'12','pubg,battle royale,playerunknown,steam,popular game','LamGame Team',1,'/storage/blog/blog_23_thumb.jpg','vi',1,1,'PUBG - Lý do thu hút hàng triệu người chơi trên toàn thế giới | LamGame','Lý do nào khiến Playerunknown’s Battlegrounds thu hút hàng triệu người chơiTrong tháng vừa qua, PlayerUnknown’s Battlegrounds đã đổ bộ lên Steam. Tựa game bắn súng sinh tồn này đã thu về được 11...','pubg,battle royale,playerunknown,steam,popular game','2025-09-05 21:59:38','2025-09-05 14:29:38','2025-09-05 14:29:38',NULL),(24,'Bayonetta & Vanquish - Bản cập nhật mới cho PC','bayonetta-vanquish-ban-cap-nhat-moi-cho-pc','Sau hơn sáu năm từ khi được xuất hiện trên Xbox 360 và PlayStation 3, Bayonetta cuối cùng đã cập nhật từ hồi đầu tháng này - và nó thành công một cách ngoạn mục. \"Phiên bản cập nhật đòi hỏi tính linh...','<p>Sau hơn sáu năm từ khi được xuất hiện trên Xbox 360 và PlayStation 3, Bayonetta cuối cùng đã cập nhật từ hồi đầu tháng này - và nó thành công một cách ngoạn mục. \"Phiên bản cập nhật đòi hỏi tính linh động và ổn định, so với bản gốc thì phiên bản trên PC đã đáp ứng được những yêu cầu đó. Phiên bản mới này sẽ tuyệt vời để bạn có thể trải nghiệm lại một trong những trò chơi hay nhất trong thập kỷ qua\".</p><p>Nhà phát triển Platinum Games đã xác định rõ rằng họ muốn những game của họ sẽ đạt được thành công tương tự: “Nếu điều tương tự đến với chúng tôi, chúng tôi sẽ cập nhật tất cả các game với phiên bản PC, nhưng điều đó còn tùy thuộc vào nhà xuất bản”. Có vẻ như các nỗ lực cho ý tưởng trên còn vấp phải sự phản đối, do đó bạn không nên mong đợi nhiều, đôi khi nó sẽ xuất hiện vào lúc bạn không ngờ nhất.</p><p>Những người sở hữu phiên bản Bayonetta trên PC vừa ra mắt gần đây mới nhận được một bản cập nhật nhỏ và nó làm cho người hâm mộ của một tựa game khác cũng do Platinum Games phát triển cảm thấy phấn khích. Một bản cập nhật vừa ra mắt bổ sung một file nhỏ vào folder Extras của Bayonetta. Bên trong file đó là một ảnh đại diện của Sam Gideon, nhân vật chính trong Vanquish.</p><p></p><p>Vanquish là một game bắn súng với góc nhìn thứ ba được phát hành vào năm 2010 và nó là game độc quyền trên console. Chúng tôi không can thiệp quá sâu, nhưng những dấu hiệu trên rõ ràng là một cú hit quan trọng. Dựa vào các phản hồi trên NeoGAF và Steam forum, có rất nhiều người hâm mộ đang lo lắng chờ đợi Vanquish phô diễn cùng với sức mạnh của PC.</p><p>https://youtu.be/HgYXCKFc_ys</p><p>Sega chưa đưa ra bất kỳ nhận định chính thức nào về bản cập nhật của Vanquish, nhưng các GAFfers đã kịp nhận thấy rằng Platinum Games đã tải 5 video lên kênh YouTube, mỗi video là một clip ngắn từ trò chơi. Điều đó có ý nghĩa gì? Phải chăng họ đang bật mí về Bayonetta 3 hoặc có thể nó là gợi ý một tiết lộ phiên bản PC của Vanquish tại E3. Hoặc có thể đó là Bayonetta 3 sẽ được công bố tại E3.</p><p>Nhưng quan trọng hơn: chúng tôi nhận định rằng Vanquish sẽ sớm đến với PC mà thôi.</p>',0,0,1,12,'12','bayonetta,vanquish,pc port,sega,action game','LamGame Team',1,'/storage/blog/blog_24_thumb.jpg','vi',1,1,'Bayonetta & Vanquish - Bản cập nhật mới cho PC | LamGame','Sau hơn sáu năm từ khi được xuất hiện trên Xbox 360 và PlayStation 3, Bayonetta cuối cùng đã cập nhật từ hồi đầu tháng này - và nó thành công một cách ngoạn mục. \"Phiên bản cập nhật đòi hỏi tính linh...','bayonetta,vanquish,pc port,sega,action game','2025-09-05 22:09:41','2025-09-05 14:29:41','2025-09-05 14:29:41',NULL),(25,'Call of Duty WWII - Sự trở lại của thời kỳ Thế chiến 2','call-of-duty-wwii-su-tro-lai-cua-thoi-ky-the-chien-2','Gần đây xuất hiện tin đồn rằng series Call of Duty của Activision sẽ trở lại với Thế chiến thứ hai trong phần tiếp theo. Giờ đây tin đồn đó đã sắp thành hiện thực: Sau sự kiện đếm ngược tại...','<p></p><p>Gần đây xuất hiện tin đồn rằng series Call of Duty của Activision sẽ trở lại với Thế chiến thứ hai trong phần tiếp theo. Giờ đây tin đồn đó đã sắp thành hiện thực: Sau sự kiện đếm ngược tại callofduty.com sẽ livestream toàn cầu mang đến cho người hâm mộ công bố chính thức của Call of Duty: WWII vào giữa tuần tới.</p><p>Trang web không tiết lộ bất cứ điều gì ngoài hình ảnh của một người lính Mỹ, điều này có lẽ đã thỏa lòng trông đợi của người hâm mộ, trang web cũng cho biết rằng Sledgehammer Games là nhà phát triển. Ở đó cũng có sẵn tùy chọn để người hâm mộ đăng ký khi cuộc livestream đã sẵn sàng cho những công bố mới.</p>',0,0,1,12,'12','call of duty,wwii,world war 2,fps,activision','LamGame Team',1,'/storage/blog/blog_25_thumb.jpg','vi',1,1,'Call of Duty WWII - Sự trở lại của thời kỳ Thế chiến 2 | LamGame','Gần đây xuất hiện tin đồn rằng series Call of Duty của Activision sẽ trở lại với Thế chiến thứ hai trong phần tiếp theo. Giờ đây tin đồn đó đã sắp thành hiện thực: Sau sự kiện đếm ngược tại...','call of duty,wwii,world war 2,fps,activision','2025-09-05 22:19:45','2025-09-05 14:29:45','2025-09-05 14:29:45',NULL),(26,'Dota 2 - Valve yêu cầu số điện thoại cho Ranked Match','dota-2-valve-yeu-cau-so-dien-thoai-cho-ranked-match','Valve đã công bố một số thay đổi quan trọng trong Dota 2, đáng chú ý nhất là tính năng hoàn toàn mới đòi hỏi người chơi phải liên kết số điện thoại của họ với tài khoản Steam để chơi các trận đấu xếp...','<p>Valve đã công bố một số thay đổi quan trọng trong Dota 2, đáng chú ý nhất là tính năng hoàn toàn mới đòi hỏi người chơi phải liên kết số điện thoại của họ với tài khoản Steam để chơi các trận đấu xếp hạng.</p><p>Theo bài đăng trên Blog của Dota 2, các nhà phát triển hy vọng rằng sự thay đổi này sẽ giảm bớt việc các game thủ sử dụng cùng lúc nhiều tài khoản. Người chơi sẽ phải đợi đến ngày 4 tháng 5 để đăng ký số số điện thoại, tại thời điểm đó bất kỳ tài khoản nào không có liên kết với số điện thoại sẽ không đủ điều kiện để chơi các trận đấu xếp hạng.</p><blockquote><p></p></blockquote><p>Ngoài việc liên kết số điện thoại, Valve sẽ mang hệ thống Solo Queue trở lại. Người chơi tham gia đánh rank có thể lựa chọn chỉ chơi với các người chơi cũng đánh solo khác. Bất kỳ người chơi chọn chế độ này sẽ được tham dự trận đấu mà cả 10 người đều chơi solo. (tức là đánh rank solo nhưng không gặp party rank).</p><p>Valve cũng đang thay đổi hệ thống báo cáo và kỷ luật trong Dota 2. Từ bây giờ, bất kỳ người chơi nào bị báo mức độ ưu tiên thấp sẽ bị cấm bởi ranked matchmaking. Hình phạt này sẽ được thêm vào cùng những hình phạt ở thời điểm hiện tại. Với nỗ lực nâng cao môi trường lành mạnh cho người chơi, một hệ thống phát hiện mới cũng được đưa ra để phát hiện những hành vi không tốt và đưa ra hình phạt nghiêm khắc.</p>',0,0,1,12,'12','dota 2,valve,ranked match,phone verification,moba','LamGame Team',1,'','vi',1,1,'Dota 2 - Valve yêu cầu số điện thoại cho Ranked Match | LamGame','Valve đã công bố một số thay đổi quan trọng trong Dota 2, đáng chú ý nhất là tính năng hoàn toàn mới đòi hỏi người chơi phải liên kết số điện thoại của họ với tài khoản Steam để chơi các trận đấu xếp...','dota 2,valve,ranked match,phone verification,moba','2025-09-05 22:29:49','2025-09-05 14:29:49','2025-09-05 14:29:49',NULL),(27,'Top 5 game \"đau não\" nhưng cực hay (Phần 1)','top-5-game-dau-nao-nhung-cuc-hay-phan-1','Game giúp người chơi thư giãn sau một ngày dài căng thẳng. Chúng khiến giúp chúng ta như bị lạc trong thế giới ảo đầy lôi cuốn. Nói đến lý do chơi game thì hầu hết chúng ta chơi cho vui hoặc đơn giản...','<p>Game giúp người chơi thư giãn sau một ngày dài căng thẳng. Chúng khiến giúp chúng ta như bị lạc trong thế giới ảo đầy lôi cuốn. Nói đến lý do chơi game thì hầu hết chúng ta chơi cho vui hoặc đơn giản vì yêu thích. Nhưng đôi khi, một số game sẽ phá vỡ quan điểm về chơi game của bạn và khiến bạn phải tự hỏi về mục đích chơi game của mình.</p><p><strong>Tetris</strong></p><p><a href=\"https://youtu.be/d7fajYz0r68\"><span class=\"underline\">https://youtu.be/d7fajYz0r68</span></a></p><p>Được thiết kế bởi kỹ sư máy tính người Nga Alexey Pajitnov, Tetris là trò chơi xếp hình kinh điển, được chơi nhiều nhất và dễ nhận biết nhất. Nó cũng là một trong những game gây đau não nhất.</p><p></p><p>Tất nhiên, để lôi kéo người chơi thì game luôn bắt đầu dễ dàng. Các khối hình chậm rãi rơi xuống theo cách của riêng nó giống như không có sự tồn tại của trọng lực. Người chơi có thể dễ dàng xếp các khối hình phù hợp sao cho chúng vừa khớp. Sau đó các khối cố định lại với nhau, nhưng nó vẫn dễ chơi và bạn không phải lo lắng nhiều. Không có gì là khó khăn phải không?</p><p>Nhưng sau đó các khối bắt đầu rơi xuống nhanh hơn và nhanh hơn nữa. Khó khăn xuất hiện rồi đây, bất thình lình, bạn khựng lại khi vô tình đặt một cái khối chẳng ăn nhập gì và phá hủy toàn bộ thành quả bạn tạo nên! Giờ đây bạn phải tìm cho mình giải pháp mới. Nhưng làm thế nào khi các khối hình chỉ biết rơi và rơi! Tetris trông giống như một bài kiểm tra không hồi kết và đầy căng thẳng về logic cũng như kỹ năng lập kế hoạch của bạn.</p><p>Thật thú vị nhưng cũng không kém phần căng thẳng khi chơi Tetris, đôi khi nó khiến bạn nổi điên lên và muốn ném máy chơi game GameBoy vào tường. Đùa thôi, tôi biết bạn sẽ chẳng muốn nó phải vỡ nát đâu.</p><p><strong>Bloodborne</strong></p><p><a href=\"https://youtu.be/_5o8jwqKsDE\"><span class=\"underline\">https://youtu.be/_5o8jwqKsDE</span></a></p><p></p><p>Thật khó có thể đánh giá được khía cạnh nào của Bloodborne từ nhà phát hành FromSoftware khiến bạn phải e dè - vì game được bao trùm bởi bầu không khí khủng khiếp, những con quái thú đáng sợ, hay những nhiệm vụ đầy khó khăn từ nhà phát triển của series Soul. Trong Demon Souls hoặc bất kỳ trò chơi Dark Souls nào đều có thể dễ dàng khiến bạn rơi vào trạng thái cực kỳ căng thẳng. Với Bloodborne, nó có thể đẩy huyết áp của bạn lên đến một chỉ số khác. Hãy thận trọng!</p><p></p><p>Trong Bloodborne, các yếu tố gây sợ hãi cho bạn có vẻ giống nhau. Bạn hãy bỏ qua số phận của mình trong game bằng cách đưa bản thân đến những giới hạn chẳng hạn mà bạn chưa bao giờ nghĩ đến như việc mất hàng chục ngàn echos máu mà bạn đã thu thập trong suốt một giờ trước hay việc đối đầu với những con quái thú khổng lồ khiến cho nhân vật của bạn trông nhỏ bé hơn là một nhân vật trong game hành động. Bạn sẽ không bao giờ biết những gì xảy ra ở góc hành lang phía trước mà bạn chưa bao giờ đến. Tất cả những điều trên có lẽ là quá bình thường trong game Souls. Tuy nhiên trong Bloodborne, bạn sẽ luôn cảm thấy bị đe dọa với không gian vô chừng và rộng lớn cùng những con quái thú khát máu.</p><p><strong>Resident Evil</strong></p><p>https://youtu.be/w1vF3KxsMPY</p><p>Capcom đã đưa ra tiêu chuẩn vàng cho các video game khi nhà phát triển nổi tiếng này phát hành Resident Evil cho PlayStation năm 1996.</p><p>Điều gì đã làm cho Resident Evil không chỉ là zombie hay là tiếng đồng hồ tik tăk và âm thanh của các cửa đóng sầm phá vỡi sự im lặng của tòa nhà đáng sợ? Resident Evil có thể giữ người chơi trên ghế và đôi khi phải hét vì lo sợ cho nhân vật của họ.</p><p></p><p>Cho dù bạn nhập vai Jill Valentine hay Chris Redfield thì người chơi đều bị ràng buộc vào hệ thống kiểm soát tank-control của game. Nó khiến cho nhân vật bị hạn chế di chuyển khiến cho người chơi cảm thấy bất lực khi bị đuổi bởi một thây ma hay Zombie dog. Bằng cách bị giới hạn trong chế độ di chuyển, người chơi luôn cảm thấy vô cùng áp lực, đòi hỏi họ phải suy nghĩ và phản xạ nhanh nhạy hơn.</p><p></p><p>Giống như một quả bóng chày bay về phía mình và bạn phải đánh trúng nó, người chơi trong Resident Evil cũng nằm trong tình huống tương tự, điều đó buộc bạn phải lên kế hoạch trước và suy nghĩ thật nhanh.</p><p><strong>Punch-Out!</strong></p><p>https://youtu.be/v5Vk0LFvj2Y</p><p>Được biết đến ở Bắc Mỹ với cái tên MikePunch-Out !! Nó là trò chơi cổ điển đầu tiên của Nintendo tên Punch-Out !! Rất thú vị phải không - ít nhất là khi bạn chiến đấu với Glass Joe, Von Kaiser và Piston Honda. Một khi bạn đạt đến World Circuit thì có vẻ như mọi thứ bớt thú vị hơn.</p><p></p><p>Với bất kỳ ai đã biết về game quyền anh cổ điển này, để chơi giỏi đòi hỏi người chơi phải ghi nhớ từng đặc điểm của đối phương và biết chính xác khi nào cần tấn công. Điều này có vẻ khá đơn giản, về nguyên tắc là thế nhưng rất khó thực hiện cho dù đối thủ của bạn là một cậu nhóc hay là gã choai lớp 12.</p><p>Dù sao đi nữa thì việc đấu với một Mike Tyson 8 bit cũng dễ dàng hơn là 1 Tyson ở ngoài đời thật.</p><p><strong>Alien: Isolation</strong></p><p><a href=\"https://youtu.be/5Ff-R5pyeGk\"><span class=\"underline\">https://youtu.be/5Ff-R5pyeGk</span></a></p><p>Ẩn náu và tìm kiếm chẳng phải là điều dễ chịu. Trong thực tế thì nó khá thú vị. Nhưng nó còn phụ thuộc vào việc bạn đang ẩn nấp ở đâu - và ai đang truy tìm bạn.</p><p></p><p>Bạn ẩn nấp trong một trò chơi trốn tìm? Chẳng có gì đáng sợ cả. Nhưng chạy trốn khỏi một kẻ giết người hàng loạt. Vâng, hẳn là rất đáng sợ đấy. Còn việc trốn khỏi những con quái vật ngoài hành tinh đến từ con tàu không gian và chúng chực chờ biến bạn thành những mảnh vụn thì sao? Chính xác đó là trò trốn tìm khiếp vía nhất mà bạn từng tham gia rồi đấy. May mắn thay, trò trốn tìm đó chỉ xuất hiện trong game kinh dị Alien: Isolation mà thôi. Bạn đừng quá lo lắng vì sẽ có nhiều nơi để bạn ẩn náu như phòng tối với ánh đèn nhấp nháy và sẽ có hàng tá địa điểm mà lũ Alien sẽ xuất hiện bất cứ lúc nào!</p>',0,0,1,12,'12','puzzle games,brain games,difficult games,strategy,indie','LamGame Team',1,'/storage/blog/blog_27_thumb.jpg','vi',1,1,'Top 5 game \"đau não\" nhưng cực hay (Phần 1) | LamGame','Game giúp người chơi thư giãn sau một ngày dài căng thẳng. Chúng khiến giúp chúng ta như bị lạc trong thế giới ảo đầy lôi cuốn. Nói đến lý do chơi game thì hầu hết chúng ta chơi cho vui hoặc đơn giản...','puzzle games,brain games,difficult games,strategy,indie','2025-09-05 22:40:01','2025-09-05 14:30:01','2025-09-05 14:30:01',NULL),(28,'Top 5 game \"đau não\" nhưng cực hay (Phần 2)','top-5-game-dau-nao-nhung-cuc-hay-phan-2','Battletoadshttps://youtu.be/idZ9C0Qtj2ABattletoads là một tựa game nổi tiếng vì độ khó kinh khủng của nó, ngay cả khi game được phát hành ở thời kì mà hầu hết các trò chơi đều không dễ chơi chút nào....','<p><strong>Battletoads</strong></p><p>https://youtu.be/idZ9C0Qtj2A</p><p>Battletoads là một tựa game nổi tiếng vì độ khó kinh khủng của nó, ngay cả khi game được phát hành ở thời kì mà hầu hết các trò chơi đều không dễ chơi chút nào. Bạn sẽ điều khiển một chiến binh ếch vượt qua nhiều chướng ngại vật và kẻ thù trên hành trình giải cứu công chúa Angelica đang bị giam giữ. Dù khó chơi nhưng nó vẫn được yêu thích và chiếm một trí đặc biệt trong tim của nhiều game thủ thế hệ 8X.</p><p>Battletoads rất khó chơi - phải nói là cực kỳ khó - người chơi vẫn hay đùa rằng nó là nguyên nhân gây ra chứng hói đầu ở người trẻ (!?) và chỉ những ai chơi Battletoads mới biết chính xác mức độ khó của Đường hầm Turbo. Ở đó người chơi phải đi trên chiếc xe đạp và liên tục tăng tốc với xung quanh các bức tường, nhảy qua hay chui dưới những rào chắn và né những kẻ xấu. Có vẻ như đường hầm Turbo được thiết kế để đánh bại hầu hết người chơi. Giả sử bạn bằng cách nào đó có thể vượt qua thử thách gần như không thể này - có thể bạn là thánh chơi game hay đơn giản là bạn đang trong cơn say và tưởng tượng như thế.</p><p>Dù có độ khó khủng khiếp nhưng Battletoads đã trở thành một tựa game kinh điển chiếm vị trí đặc biệt trong lòng game thủ ngoại trừ việc họ phải chết lên chết xuống liên tục.</p><p><strong>Silent Hill 2</strong></p><p>https://youtu.be/dk7JkSArEdQ</p><p>Silent Hill 2 là game rất nổi tiếng, đến thời điểm hiện tại nó vẫn giữ vững danh hiệu là một trong những trò chơi kinh dị có ảnh hưởng nhất mọi thời đại. Mọi thứ trong game đều ổn ngoại trừ việc game sẽ khiến bạn cảm thấy không ổn định về tinh thần trong vài ngày sau đó.</p><p>Mặc dù rất nhiều game kinh dị khiến bạn phải sợ hãi với tràn ngập bạo lực cùng các chiến thuật đáng sợ thì Silent Hill 2 sẽ lôi kéo bạn vào câu chuyện phức tạp trong một thị trấn ảm đạm, tạo cho bạn cảm giác hồi hộp trong suốt trò chơi. Silent Hill 2 được mô tả là \"Cơn ác mộng được tạo ra dành riêng cho bạn\" hay “Hành trình của bạn trong game được hình thành bởi sự lo lắng, sợ hãi và tội lỗi của chính bạn.\" Ngoài ra, thay vì những con zombie thông thường thì nhà thiết kế trò chơi đã tạo ra những quái vật thật sự khủng khiếp.</p><p><strong>Hotline: Miami</strong></p><p><strong>https://youtu.be/_5AJtaYx1Eg</strong></p><p>Nhiệm vụ của bạn trong <em>Hotline Miami</em> - một game bắn súng góc nhìn từ trên xuống - là tiêu diệt tất cả những ai lọt vào tầm mắt của bạn. Bạn không cần biết gì nhiều, chỉ cần một chiếc máy nhắn tin và một dòng địa chỉ để tàn sát đến tận sinh linh cuối cùng trong thế giới 2D cổ điển của game.</p><p>Được phát triển bởi Dennaton Games và Devolver Digital, Hotline: Miami là một câu chuyện đáng sợ. Nhưng chắc chắn rằng đây không phải là game dễ dàng, cái chết trong game trở nên quá quen thuộc và bạn sẽ cùng tiến bộ với nó. Bạn sẽ cảm thấy game quá khó và căng thẳng bởi nó quá bạo lực. Bạn sẽ đá cánh cửa, sơn bức tường màu đỏ bằng máu của kẻ thù, tấn công chúng bằng gậy bóng chày hay xả súng máy vào chúng trong khi bạn cố gắng để những điều như vậy không xảy ra với mình. Game có dung lượng siêu nhẹ, điều đó thật tuyệt vời. Hotline: Miami là một trong rất ít game khiến bạn cảm thấy mình như kẻ ngáo đá giết người hàng loạt, thật đáng sợ.</p><p><strong>Trials: Fusion</strong></p><p><strong>https://youtu.be/VbzNqO8ZVpQ</strong></p><p>Tiền đề của Trials: Fusion khá đơn giản, mục tiêu của bạn là lái xe mô tô đi từ điểm A đến điểm B, bạn cần điều khiển xe một cách khéo léo, có thể nghiêng xe hay nhảy lên để vượt chướng ngại vật. Nghe có vẻ dễ dàng phải không? Vâng, các level đầu tiên sẽ dễ dàng như thế đấy. Đầu tiên bạn sẽ cảm thấy thất vọng và nghĩ rằng \"Trò chơi này không khó như lời đồn đại!\". Sau đó, chiếc xe của bạn sẽ chỉ có bánh xe sau nhảy trên những gờ không đủ rộng cho cái lốp xe và chiếc xe yêu quý của bạn có khi phải nghiêng đến 90 độ. Trò chơi này điên rồ đến mức người chơi thường phải \"chơi lại từ đầu\" như là thói quen.</p><p>Theo thống kê chỉ có 127 tài khoản đăng ký trên PSNprofiles kết thúc trò chơi đến vòng cuối cùng với tỷ lệ trung bình 5% trong hơn 88.860 người chơi. Vì lý do đó mà Trials: Fusion xứng đáng trở thành huyền thoại game về độ khó !</p><p><strong>Super Meat Boy</strong></p><p><strong>https://youtu.be/5McCwFBi36I</strong></p><p>Được mang danh là một trong những trò chơi khó nhất mọi thời đại, độ khó của Super Meat Boy được cho là đủ để khiến một huấn luyện viên yoga phải lên cơn đau tim.</p><p>Trò chơi gần như không thể đối với các game thủ thông thường với các skill đơn giản như vượt qua những thử thách từ khi bắt đầu đến khi kết thúc game. Tuy nhiên, đối với những thợ săn cúp Super Meat Boy thì đây là trò chơi với thử thách tàn bạo và đầy kiên nhẫn. Theo PSNProfiles.com, chỉ có 139 tài khoản đạt được cúp bạch kim, đòi hỏi người chơi phải đánh bại mọi thứ mà không mất mạng. Đó là 0,11 % trong tổng các tài khoản đã đăng ký. Nhưng bạn cần lưu ý rằng PSNProfiles.com không theo dõi tất cả người dùng đã đăng ký trên PlayStation Network - mà chỉ những người chơi ưu tú mới được trang web chọn để theo dõi thống kê. Vì vậy, tỉ lệ thật sự chắc chắn sẽ làm bạn choáng hơn.</p>',0,0,1,12,'12','puzzle games,brain games,difficult games,strategy,indie','LamGame Team',1,'/storage/blog/blog_28_thumb.jpg','vi',1,1,'Top 5 game \"đau não\" nhưng cực hay (Phần 2) | LamGame','Battletoadshttps://youtu.be/idZ9C0Qtj2ABattletoads là một tựa game nổi tiếng vì độ khó kinh khủng của nó, ngay cả khi game được phát hành ở thời kì mà hầu hết các trò chơi đều không dễ chơi chút nào....','puzzle games,brain games,difficult games,strategy,indie','2025-09-05 22:50:07','2025-09-05 14:30:07','2025-09-05 14:30:07',NULL),(29,'Grand Chase M - Game nhập vai hành động mobile tuyệt vời','grand-chase-m-game-nhap-vai-hanh-dong-mobile-tuyet-voi','Grand Chase MTrong tựa game này, người dùng sẽ cố gắng để đánh bại KazeAze bằng cách chiêu mộ và huấn luyện một liên quân ưu tú từ 2 vương quốc Kanaban và Serdin. Tất cả các nhân vật trong tựa game...','<h1 id=\"grand-chase-m\"><strong>Grand Chase M</strong></h1><p></p><p>Trong tựa game này, người dùng sẽ cố gắng để đánh bại KazeAze bằng cách chiêu mộ và huấn luyện một liên quân ưu tú từ 2 vương quốc Kanaban và Serdin. Tất cả các nhân vật trong tựa game này đều được lấy cảm hứng từ tựa game đàn anh trên PC trước đó. Sự khác biệt ở đây là nhân vật của bạn trong Grand Chase M sẽ tự động chiến đấu nhưng người chơi cũng có thể kích hoạt các kỹ năng của chúng bằng tay.</p><p></p><p>Game kết hợp cùng hiệu ứng công nghệ 3D tân thời của Dynamic Graphic, GrandChase M sẽ tạo nên một tựa game hoàn hảo về đồ họa cũng như lối chơi.</p><p></p><p>Hệ thống kỹ năng chủ động, bị động cùng kỹ năng đặc biệt của các nhân vật cực khủng trong GrandChase M sẽ khiến bạn như đang được chơi một game PC trên các thiết bị android vậy.</p><p></p><p>Nói tóm lại, với những tạo hình 3D theo phong cách Anime cực dễ thương của Nhật bản cùng lối chơi chuyển cảnh màn hình ngang của game tạo nên những pha hành động cực kỳ lôi cuốn và hấp dẫn bên cạnh những hiệu ứng kỹ năng được chăm chút tỉ mĩ thì quả không ngoa khi nói Grand Chase M sẽ thực sự gây sốt toàn thế giới.</p><p>Trailer:</p><p>https://www.youtube.com/watch?v=c2hbBsLlbeU</p><p>Link download:</p><p>Android: https://play.google.com/store/apps/details?id=com.actoz.gca&hl=en</p>',0,0,1,16,'16','grand chase m,mobile rpg,action rpg,korean game,anime','LamGame Team',1,'/storage/blog/blog_29_thumb.jpg','vi',1,1,'Grand Chase M - Game nhập vai hành động mobile tuyệt vời | LamGame','Grand Chase MTrong tựa game này, người dùng sẽ cố gắng để đánh bại KazeAze bằng cách chiêu mộ và huấn luyện một liên quân ưu tú từ 2 vương quốc Kanaban và Serdin. Tất cả các nhân vật trong tựa game...','grand chase m,mobile rpg,action rpg,korean game,anime','2025-09-05 23:00:09','2025-09-05 14:30:09','2025-09-05 14:30:09',NULL),(30,'Grand Sphere - Game mobile RPG với đồ họa tuyệt đẹp','grand-sphere-game-mobile-rpg-voi-do-hoa-tuyet-dep','Grand Sphere - Game nhập vai hấp dẫn vừa ra mắt tại Nhật BảnSilicon Studio - cha đẻ của series Bravely Default nổi tiếng đã kết hợp cùng hãng Square Enix để cho ra đời tựa game nhập vai mới...','<h1 id=\"grand-sphere---game-nhập-vai-hâp-dân-vưa-ra-mắt-tại-nhật-bản\"><strong>Grand Sphere - Game nhập vai hấp dẫn vừa ra mắt tại Nhật Bản</strong></h1><p>Silicon Studio - cha đẻ của series Bravely Default nổi tiếng đã kết hợp cùng hãng Square Enix để cho ra đời tựa game nhập vai mới toanh của mình là Grand Sphere tại thị trường Nhật Bản.</p><p><a href=\"https://www.youtube.com/watch?v=s31cfE6rdNA\"><em><span class=\"underline\">https://www.youtube.com/watch?v=s31cfE6rdNA</span></em></a></p><p>Grand Sphere chủ yếu sử dụng và \"đặt nặng\" yếu tố cốt truyện, yếu tố chính dẫn dắt người chơi. Bạn sẽ bắt đầu cuộc phiêu lưu kỳ thú của mình tại thế giới Astrum, nơi đây quy tụ sự có mặt của yêu tinh, con người và nhiều chủng loại khác đang sinh sống. Mỗi sinh vật sống tại xứ Astrum đều sở hữu cho mình một loại năng lượng gọi là \"sphere\", và khi kết hợp một số lượng lớn các \"sphere\" thì sẽ tạo ra một sức mạnh siêu nhiên mang tên \"Grand Sphere\".</p><p></p><p>Trong game, người chơi sẽ được tự do thu thập và dẫn dắt một đội quân gồm 5 nhân vật chính cùng nhiều \"phụ tá\" khác để trợ giúp trong suốt quá trình phiêu lưu tại Astrum. Cơ chế combat trong game mobile này là khá đơn giản, người chơi chỉ cần bấm vào ảnh đại diện của các nhân vật để tạo ra những chuỗi kỹ năng tấn công, phòng thủ và khéo léo sử dụng những \"Grand Sphere\" khi đáp ứng điều kiện được đưa ra.</p><p></p><p>Hiện tại vẫn chưa có nhiều thông tin về một phiên bản khác của Grand Sphere tại nước ngoài nên chúng ta chỉ có thể chờ đợi và hi vọng vào một phiên bản quốc tế của tựa game được phát hành vào một tương lai không xa.</p>',0,0,1,16,'16','grand sphere,mobile rpg,jrpg,beautiful graphics,anime','LamGame Team',1,'/storage/blog/blog_30_thumb.jpg','vi',1,1,'Grand Sphere - Game mobile RPG với đồ họa tuyệt đẹp | LamGame','Grand Sphere - Game nhập vai hấp dẫn vừa ra mắt tại Nhật BảnSilicon Studio - cha đẻ của series Bravely Default nổi tiếng đã kết hợp cùng hãng Square Enix để cho ra đời tựa game nhập vai mới...','grand sphere,mobile rpg,jrpg,beautiful graphics,anime','2025-09-05 23:10:12','2025-09-05 14:30:12','2025-09-05 14:30:12',NULL),(31,'HIT - Game mobile hành động cực hấp dẫn','hit-game-mobile-hanh-dong-cuc-hap-dan','HIT - Game cực hấp dẫn trên nền tảng MobileNexon và NAT Games đã chính thức phát hành siêu phẩm hack-and-slash đồ họa khủng HIT (Heroes of Incredible Tales) trên cả 2 kho ứng dụng App Store và Google...','<p><strong>HIT - Game cực hấp dẫn trên nền tảng Mobile</strong></p><p>Nexon và NAT Games đã chính thức phát hành siêu phẩm hack-and-slash đồ họa khủng HIT (Heroes of Incredible Tales) trên cả 2 kho ứng dụng App Store và Google Play toàn cầu và hơn hết game còn hỗ trợ cả ngôn ngữ tiếng Việt để gamers VN dễ dàng trải nghiệm mà không gặp phải trở ngại nào.</p><p></p><p>Đến với game, bạn sẽ được lựa chọn một trong 4 nhân vật cơ bản bao gồm: Hugo là lớp nhân vật tay to kiểu cơ bắp dùng đại kiếm, Anika là nhân vật nữ sexy với lưỡi hái sắc bén trên tay, Lucas reo rắc nỗi sợ hãi với song kiếm và Kiki sử dụng gậy ma thuật để điều khiển phát thuật.</p><p></p><p>Được xây dựng theo hướng nhập vai hành động nên HIT có gameplay khá quen thuộc với các màn chiến đấu vượt ải. Bên cạnh đó, Game không có quá nhiều chế độ chơi rắc rối hay phức tạp mà gói gọn trong những mode chính như: Solo Dungeon, Daily Dungeon, Cốt Truyện, Raid (Đấu boss), Thách Đấu, và Guild chiến,...</p><p></p><p>Một điểm hết sức đặc biệt của trò chơi nằm ở hệ thống skill và vũ khí đa dạng do mỗi class nhân vật tất nhiên sẽ có bộ skill giống nhau, tuy nhiên việc phát triển theo chiều hướng như thế nào lại phụ thuộc rất nhiều vào phong cách khác nhau của mỗi người chơi.</p><p></p><p>Ngoài ra, Đồ họa của game là cái đáng phải nhắc đến do nhà phát hành đã chăm chút cực kì kĩ càng nên hình ảnh trong game hết sức là tuyệt vời, chi tiết đến kinh ngạc dù chỉ là một chi tiếc nhỏ trên trang phục của nhân vật hoặc cảnh vật xung quanh.</p><p></p><p>Và hơn cả việc thay đổi ngoại hình của nhân vật dựa những trên trang bị bạn đang mặc trên người cũng là một đặc điểm hết sức độc đáo và thú vị của game vì không giống như những game khác trên thị trường, mỗi khi bạn thay đổi y phục, vũ khí, giày hay bao tay, nhân vật trông vẫn như cũ hoặc không có sự thay đổi nhiều.</p><p><a href=\"https://www.youtube.com/watch?v=9-KLLDX_TTU\"><span class=\"underline\">https://www.youtube.com/watch?v=9-KLLDX_TTU</span></a></p><p>Tóm lại, HIT (Heroes of Incredible Tales) là một game rất đáng để download và sở hữ trong chiếc smartphone của bạn vì cho dù bạn là người khó tính đòi hỏi game phải có chiều sâu, đồ họa đẹp,.. Hay đơn giản chỉ là những người thích một kiểu game hành động đơn thuần (đánh Dun, đánh PVP,..) thì HIT có thể đáp ứng được tất cả những yếu tố đó.</p><p>Nếu cảm thấy hứng thú bạn có thể downgame tại:</p><p>IOS: https://itunes.apple.com/ca/app/hit-heroes-incredible-tales/id1108780504?mt=8</p><p>Android: https://play.google.com/store/apps/details?id=com.nexon.hit.global&hl=vi</p>',0,0,1,16,'16','hit mobile,action rpg,nexon,mobile game,3d graphics','LamGame Team',1,'/storage/blog/blog_31_thumb.jpg','vi',1,1,'HIT - Game mobile hành động cực hấp dẫn | LamGame','HIT - Game cực hấp dẫn trên nền tảng MobileNexon và NAT Games đã chính thức phát hành siêu phẩm hack-and-slash đồ họa khủng HIT (Heroes of Incredible Tales) trên cả 2 kho ứng dụng App Store và Google...','hit mobile,action rpg,nexon,mobile game,3d graphics','2025-09-05 23:20:15','2025-09-05 14:30:15','2025-09-05 14:30:15',NULL),(32,'Hidden Heroes - Game chiến thuật ẩn danh hay ho','hidden-heroes-game-chien-thuat-an-danh-hay-ho','Hidden HeroesHidden Heroes là một game mobile thuộc thể loại chiến thuật thời gian thực vừa được ra mắt bởi hãng phát triển 505 Games. Đến với Hidden Heroes, các game thủ sẽ được thưởng thức...','<h1 id=\"hidden-heroes\"><strong>Hidden Heroes</strong></h1><p>Hidden Heroes là một game mobile thuộc thể loại chiến thuật thời gian thực vừa được ra mắt bởi hãng phát triển 505 Games. Đến với Hidden Heroes, các game thủ sẽ được thưởng thức những trận đấu rực lửa, đầy hấp dẫn với những chiến binh được dẫn dắt bởi chính bản thân mình và ngoài ra bạn còn có thể xây dựng cho riêng mình một vương quốc hùng mạnh nhất trên tất cả các vùng đất.</p><p></p><p>Mở đầu trò chơi, bạn sẽ phải lựa chọn cho mình một trong 3 nhân vật bao gồm chiến binh Warrior - Usus, Archer - Hemi và Caster - Tempus.Có thể nói, mỗi hero sở hữu một cách thức chiến đấu và thuộc tính đặc biệt riêng nhưng hoàn toàn cân bằng về công thủ. Để có thể giành được chiến thắng, bạn cần sử dụng các chiến binh của mình hỗ trợ sao cho hợp lý. Điểm ấn tượng khác mà Hidden Heroes mang tới cho người chơi là tính tương tác của môi trường đối với nhân vật, bạn sẽ gặp sử cản trở của những ngọn núi, bìa rừng hay thậm chí những dòng sông làm giảm tốc độ.</p><p></p><p>Về lối chơi, bạn sẽ đưa quân đội của mình đi chiếm đánh những lâu đài khác, sở hữu càng nhiều thì số vàng hàng ngày bạn kiếm được sẽ tăng lên càng cao. Ngoài ra, bạn còn có thể tấn công cả các mỏ khai thác của người chơi khác và biến chúng trở thành của riêng mình và đừng quên để lại một người anh hùng để canh giữ từng địa điểm bạn vừa đánh chiếm nếu không muốn bị người chơi khác đánh cướp lại khi \"vườn không nhà trống\".</p><p></p><p>Trailer:</p><p>https://www.youtube.com/watch?v=d2B5iU-igWY</p><p>Link Download:</p><p>IOS: https://itunes.apple.com/us/app/hidden-heroes/id883226170?mt=8</p>',0,0,1,16,'16','hidden heroes,strategy game,mobile strategy,tactical','LamGame Team',1,'/storage/blog/blog_32_thumb.jpg','vi',1,1,'Hidden Heroes - Game chiến thuật ẩn danh hay ho | LamGame','Hidden HeroesHidden Heroes là một game mobile thuộc thể loại chiến thuật thời gian thực vừa được ra mắt bởi hãng phát triển 505 Games. Đến với Hidden Heroes, các game thủ sẽ được thưởng thức...','hidden heroes,strategy game,mobile strategy,tactical','2025-09-05 23:30:17','2025-09-05 14:30:17','2025-09-05 14:30:17',NULL),(33,'Hungry Shark Evolution - Cuộc phiêu lưu của cá mập đói','hungry-shark-evolution-cuoc-phieu-luu-cua-ca-map-doi','Hungry Shark EvolutionHungry Shark Evolution thuộc thể loại game hành động với đồ họa 3D, việc của bạn là nuôi dưỡng một chú cá mập sao cho từ một chú cá mập nhỏ cho tới khi thành cá mập...','<h1 id=\"hungry-shark-evolution\"><strong>Hungry Shark Evolution</strong></h1><p>Hungry Shark Evolution thuộc thể loại game hành động với đồ họa 3D, việc của bạn là nuôi dưỡng một chú cá mập sao cho từ một chú cá mập nhỏ cho tới khi thành cá mập trắng nặng 10 tấn có thể ăn bất kỳ thứ gì xung quanh trong thế giới đại dương đầy thú vị những cũng không ít nguy hiểm.</p><p></p><p>Trong Hungry Shark Evolution, người chơi đóng vai trò là một chú cá mập. Chú cá mập này liên tục cảm thấy đói và cần phải ăn, gần như là ăn bất kỳ thứ gì và mọi thứ, sau đó phát triển thành cá mập lớn hơn, mạnh mẽ hơn và ăn nhiều hơn. Ngoài ra, chú có thể gây ra tàn phá khủng khiếp hơn cho môi trường xung quanh.</p><p></p><p>Nói một cách dễ hiểu về lối chơi trong game, đây là phiên bản cải tiến của trò chơi Cá Lớn Nuốt Cá Bé - Feeding Frenzy đình đám. Ban đầu, bạn chỉ là một chú cá mập nhỏ và nhanh chóng phát triển nhờ vào lũ sứa hoặc là những chú cá nhỏ khác và kẻ thù của bạn sẽ là những quả mìn săn bắt do con người thả xuống đại dương hay lũ cá mập to lớn hơn. Tuy nhiên, đây chỉ là cuộc chiến sinh tồn của bạn dưới đáy biển. Khi đã đủ trưởng thành bạn sẽ chiến đấu với những loại tàu ngầm chiến lớn và biến cả thế giới đại dương là của riêng mình.</p><p></p><p>Ban đầu, tôi cứ nghĩ những đồng tiền vàng chỉ tăng thêm điểm số cho thành tích của mình vì cá mập thì tiêu tiền làm cái quái gì? Nhưng không, tôi đã quá lầm khi Hungry Shark Evolution chúng lại vô cùng quý giá, mặc dù không hợp logic cho lắm nhưng vì là game thì hãng phát triển muốn làm gì chẳng được. Bạn có thể sử dụng số tiền này để mua các loại trang bị hỗ trợ khác nhau. Đặc biệt hơn, bạn có thể chiêu mộ thêm vài \"người bạn\" sát cánh cùng mình.</p><p></p><p>Tóm lại, Hungry Shark Evolution có thể tạo được sự thú vị cũng như hứng thú trong lối chơi và hình ảnh nó mang lại. Tuy nhiên, một số yếu tố khác lại khiến cho game thủ có thể cảm thấy khó chịu vì thử thách trong game sẽ trở nên quá dễ dàng khi con cá mập của mình phát triển tới cấp độ cuối.</p><p>Trailer:</p><p>https://www.youtube.com/watch?v=ZqW396NgGwE</p><p>Link download:</p><p>Android: <a href=\"https://play.google.com/store/apps/details?id=com.fgol.HungrySharkEvolution&feature=search_result#?t=W251bGwsMSwyLDEsImNvbS5mZ29sLkh1bmdyeVNoYXJrRXZvbHV0aW9uIl0\"><span class=\"underline\">https://play.google.com/store/apps/details?id=com.fgol.HungrySharkEvolution&feature=search_result#?t=W251bGwsMSwyLDEsImNvbS5mZ29sLkh1bmdyeVNoYXJrRXZvbHV0aW9uIl0</span></a></p><p>IOS:</p><p>https://itunes.apple.com/us/app/hungry-shark-evolution/id535500008?mt=8</p>',0,0,1,16,'16','hungry shark,mobile game,arcade,shark game,evolution','LamGame Team',1,'/storage/blog/blog_33_thumb.jpg','vi',1,1,'Hungry Shark Evolution - Cuộc phiêu lưu của cá mập đói | LamGame','Hungry Shark EvolutionHungry Shark Evolution thuộc thể loại game hành động với đồ họa 3D, việc của bạn là nuôi dưỡng một chú cá mập sao cho từ một chú cá mập nhỏ cho tới khi thành cá mập...','hungry shark,mobile game,arcade,shark game,evolution','2025-09-05 23:40:23','2025-09-05 14:30:23','2025-09-05 14:30:23',NULL),(34,'Hunter Apocalypse - Săn lùng zombie trong tận thế','hunter-apocalypse-s-n-lung-zombie-trong-tan-the','Hunter: ApocalypeZombie Hunter: Apocalype đưa người chơi tới bối cảnh thế giới bị tàn phá nghiêm trọng bởi bè lũ zombie. Là một game bắn súng FPS nên hiển nhiên người chơi sẽ được vào vai một...','<h1 id=\"hunter-apocalype\"><strong>Hunter: Apocalype</strong></h1><p>Zombie Hunter: Apocalype đưa người chơi tới bối cảnh thế giới bị tàn phá nghiêm trọng bởi bè lũ zombie. Là một game bắn súng FPS nên hiển nhiên người chơi sẽ được vào vai một quân nhân đã có kinh nghiệm chiến đấu trong nhiều năm nên nhiệm vụ của bạn trong game là phải xách súng lên và càn quét chúng để cứu thoát, bảo vệ những người dân vô tội trước sự hoành hành của quân đoàn xác sống.</p><p></p><p>Game thủ sẽ phải sẵn sàng tâm lý lẫn sử dụng kĩ năng của bản thân cho tất cả các loại thử thách như bắn zombie khi mình đang đứng trên trực thăng hay nhanh chóng tới được chỗ khởi động để giải phóng tên lửa chứa loại huyết thanh đặc biệt, cứu thoát những người dân trong một thành phố bị biến thành xác sống.</p><p></p><p>Số lượng nhiệm vụ trong Zombie Hunter: Apocalype khá là nhiều, game cung cấp cho game thủ tới hơn 250 nhiệm vụ phải hoàn thành. Bên cạnh đó, người chơi sẽ được đặt chân tới nhiều địa điểm khác nhau như các thành phố, viện bảo tàng, công viên hay thậm chí là Bắc Cực.</p><p></p><p>Ngoài ra, Zombie Hunter: Apocalype con cung cấp cho người chơi một kho vũ khí khổng lồ đủ để người chơi thỏa sức lựa chọn bao gồm các loại súng từ hạng nhẹ cho tới hạng nặng như Barret M82, M90, Dragunov,... Theo đó, mỗi loại súng sẽ có những ưu nhược điểm riêng và game thủ sẽ phải cân nhắc và đưa ra những sự lựa chọn hợp lý cho từng màn chơi vì nếu không, game thủ sẽ phải trả giá bằng tính mệnh của mình.</p><p>Trailer:</p><p>https://www.youtube.com/watch?v=C6l3c4RygcM</p><p>Link download:</p><p>Android: https://play.google.com/store/apps/details?id=com.generamobile.zhunter.gp</p><p>IOS: https://itunes.apple.com/app/id853087449</p>',0,0,1,16,'16','hunter apocalypse,zombie game,shooting,mobile fps,survival','LamGame Team',1,'/storage/blog/blog_34_thumb.jpg','vi',1,1,'Hunter Apocalypse - Săn lùng zombie trong tận thế | LamGame','Hunter: ApocalypeZombie Hunter: Apocalype đưa người chơi tới bối cảnh thế giới bị tàn phá nghiêm trọng bởi bè lũ zombie. Là một game bắn súng FPS nên hiển nhiên người chơi sẽ được vào vai một...','hunter apocalypse,zombie game,shooting,mobile fps,survival','2025-09-05 23:50:24','2025-09-05 14:30:24','2025-09-05 14:30:24',NULL),(35,'Insurgency - Game FPS chiến thuật quân phiến loạn','insurgency-game-fps-chien-thuat-quan-phien-loan','Insurgency - quân phiến loạnCó thể nói Isnurgency là một trong những game hiếm hoi có thể đem đến trải nghiệm chân thật nhất đối với gamer, bất kể bạn là một người mới chơi hay một...','<h1 id=\"insurgency---qu&acirc;n-phiến-loạn\"><strong>Insurgency - qu&acirc;n phiến loạn</strong></h1>\r\n<h2 id=\"co-th&ecirc;-noi-isnurgency-la-m&ocirc;t-trong-nhưng-game-hi&ecirc;m-hoi-co-th&ecirc;-đem-đến-trải-nghiệm-ch&acirc;n-thật-nh&acirc;t-đ&ocirc;i-vơi-gamer-b&acirc;t-k&ecirc;-ban-l&agrave;-m&ocirc;t-người-mới-chơi-hay-một-game-thủ-kỳ-cựu.\">Có th&ecirc;̉ nói Isnurgency là m&ocirc;̣t trong những game hi&ecirc;́m hoi có th&ecirc;̉ đem đến trải nghiệm ch&acirc;n thật nh&acirc;́t đ&ocirc;́i với gamer, b&acirc;́t k&ecirc;̉ bạn l&agrave; m&ocirc;̣t người mới chơi hay một game thủ kỳ cựu.</h2>\r\n<p>Tựa game FPS n&agrave;y tuy mới ra mắt chưa l&acirc;u nhưng đ&atilde; nhận được những đ&aacute;nh gi&aacute; kh&aacute; t&iacute;ch cực từ cộng đồng game thủ thế giới n&oacute;i chung, cũng như cộng đồng FPS n&oacute;i ri&ecirc;ng.</p>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Bối cảnh game thực sự khá đơn giản khi đưa người chơi đến với cu&ocirc;̣c chi&ecirc;́n giữa qu&acirc;n đội Mỹ v&agrave; phe phiến qu&acirc;n Taliban. C&oacute; lẽ chủ đề chống khủng bố đ&atilde; qu&aacute; quen thuộc với những game thủ FPS (tương tự như trong game CS), nhưng d&ugrave; sao th&igrave; cốt truyện cũng kh&ocirc;ng phải yếu tố được nh&agrave; ph&aacute;t triển Insurgency ch&uacute; trọng, v&igrave; trong game cũng chẳng c&oacute; phần Campaign mà chỉ đơn thu&acirc;̀n là ch&ecirc;́ đ&ocirc;̣ multiplayer và co-op.</p>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Có th&ecirc;̉ nói, Insurgency tập trung chính v&agrave;o phần Multiplayers.N&ecirc;n đều đ&oacute; đồng nghĩa với việc game thủ phải c&oacute; kết nối internet li&ecirc;n tục để thưởng thức tựa game. Cảm nhận đầu ti&ecirc;n của người chơi khi v&agrave;o game đ&oacute; l&agrave; sự \"đơn giản\". Từ bảng Menu b&ecirc;n ngo&agrave;i cho đến khi v&agrave;o trong trận, giao diện của game kh&aacute; đơn giản v&agrave; c&oacute; phần th&ocirc; sơ, điều n&agrave;y cũng kh&ocirc;ng c&oacute; g&igrave; đ&aacute;ng ngạc nhi&ecirc;n v&igrave; đ&acirc;y l&agrave; một tựa game indie n&ecirc;n c&oacute; lẽ chi ph&iacute; để l&agrave;m game cũng kh&ocirc;ng nhiều như những tựa game AAA.</p>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Do nh&agrave; ph&aacute;t triển đ&atilde; thiết kế Insurgency theo hướng của d&ograve;ng game simulator , ch&iacute;nh v&igrave; thế chiến trường trong game v&ocirc; c&ugrave;ng ch&acirc;n thật v&agrave; khốc liệt. Mọi h&agrave;nh động d&ugrave; nhỏ nhất cũng c&oacute; thể quyết định sinh mạng của bạn, chỉ cần tr&uacute;ng v&agrave;i vi&ecirc;n đạn l&agrave; bạn đ&atilde; c&oacute; thể gục ng&atilde;.</p>\r\n<p>Cơ chế gameplay của game cũng được m&ocirc; phỏng kh&aacute; giống thực tế. Giao diện HUD trong game được đơn giản h&oacute;a đến mức tối thiểu, gamer chỉ c&oacute; thể biết được v&agrave;i th&ocirc;ng tin cơ bản như thời gian c&ograve;n lại, c&aacute;c cứ điểm,v&agrave; số băng đạn bạn c&oacute;. Game kh&ocirc;ng cung cấp bản đồ cho người chơi n&ecirc;n game thủ phải ho&agrave;n to&agrave;n tự xử, những điều n&agrave;y tuy kh&aacute; bất tiện lúc đ&acirc;̀u nhưng m&ocirc;̣t khi bạn đã làm quen, nó sẽ đem lại cho bạn những trải nghiệm thuy&ecirc;̣t vời và ch&acirc;n thật nh&acirc;́t. Insurgency đ&ograve;i hỏi ở người chơi sự phối hợp ăn ý giữa c&aacute;c đồng đội, đ&acirc;̀u óc chiến thuật, kĩ năng hay th&acirc;̣m chí là bản năng.</p>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Trong game c&oacute; khá nhiều class cho người chơi lựa chọn, mỗi class lại c&oacute; những vũ kh&iacute; đặc trưng ri&ecirc;ng. Hệ thống vũ kh&iacute; trong game cũng tương đối đa dạng v&agrave; đầy đủ c&aacute;c chủng loại, với hơn 20 loại vũ kh&iacute; k&egrave;m theo đ&oacute; l&agrave; những phụ kiện để bạn t&ugrave;y chỉnh. Vốn l&agrave; game simulator n&ecirc;n c&aacute;c loại s&uacute;ng trong game cũng được m&ocirc; phỏng sao cho giống thực tế nhất, điều đ&oacute; đồng nghĩa với việc sẽ kh&ocirc;ng c&oacute; hồng t&acirc;m và bạn sẽ phải m&acirc;́t m&ocirc;̣t khoảng thời gian đ&ecirc;̉ quen d&acirc;̀n với đi&ecirc;̀u đó.</p>\r\n<p>Ngoài ra, độ giật của s&uacute;ng cũng v&ocirc; c&ugrave;ng khủng khiếp, mỗi loại s&uacute;ng trong game đều c&oacute; độ giật cũng như c&aacute;c chỉ số kh&aacute;c nhau, ch&iacute;nh v&igrave; vậy người chơi cần t&igrave;m hiểu về th&ocirc;ng số của ch&uacute;ng trước khi sử dụng. Nếu như game thủ chưa từng chơi qua d&ograve;ng game simulator th&igrave; việc l&agrave;m quen với game sẽ v&ocirc; c&ugrave;ng kh&oacute; khăn, thật may l&agrave; nh&agrave; sản xuất đ&atilde; tạo ra mục Practice để người chơi c&oacute; thể thực h&agrave;nh kĩ năng của m&igrave;nh với Bot trước khi bước v&agrave;o c&aacute;c trận chiến với người thật.</p>\r\n<table>\r\n<thead>\r\n<tr class=\"header\">\r\n<th>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Số lượng mode trong Insurgency cũng rất đa dạng, từ co-op đến những mode PvP. Một ưu điểm cần phải nhắc đến của game so với những tựa game FPS ng&agrave;y nay l&agrave; tất cả vũ kh&iacute; v&agrave; c&aacute;c class trong game cũng để cho người chơi sử dụng thoải m&aacute;i m&agrave; kh&ocirc;ng cần đạt y&ecirc;u cầu g&igrave; cả, d&ugrave; l&agrave; người mới chơi hay game thủ k&igrave; cựu đều được hưởng những t&iacute;nh năng như nhau. C&oacute; lẽ đ&acirc;y cũng ch&iacute;nh l&agrave; điểm thu h&uacute;t của tựa game, d&ugrave; l&agrave; game thủ FPS l&acirc;u năm hay Newbie đều c&oacute; thể đến với game v&agrave; thể hiện khả năng của m&igrave;nh.</p>\r\n<table>\r\n<thead>\r\n<tr class=\"header\">\r\n<th>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>\r\n<table>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Về phần đồ họa, đ&aacute;ng tiếc l&agrave; game chỉ dừng lại ở mức kh&aacute; (so với những tựa game hiện nay). Đối với một tựa game FPS indie th&igrave; c&oacute; lẽ việc đ&ograve;i hỏi đồ họa ho&agrave;nh tr&aacute;ng l&agrave; điều kh&oacute; khăn, tuy vậy game vẫn đem lại một trải nghiệm ch&acirc;n thật cho người chơi từ tiếng s&uacute;ng, tiếng la h&eacute;t cho đến tiếng nổ vang trời của RPG, lựu đạn c&ugrave;ng khung cảnh chiến trường khốc liệt.</p>\r\n<p>C&oacute; thể n&oacute;i, Insurgency đ&atilde; ho&agrave;n th&agrave;nh tốt vai tr&ograve; của một tựa game FPS. Nếu như game thủ đ&atilde; ng&aacute;n ngẩm với những game shooter hiện đại th&igrave; đ&acirc;y sẽ l&agrave; một lựa chọn kh&ocirc;ng tồi cho bạn.</p>',0,0,1,12,'12','26','Example',1,'/storage/blog/blog_35_thumb.jpg','en',1,1,'Insurgency - Game FPS chiến thuật quân phiến loạn | LamGame','Insurgency - quân phiến loạnCó thể nói Isnurgency là một trong những game hiếm hoi có thể đem đến trải nghiệm chân thật nhất đối với gamer, bất kể bạn là một người mới chơi hay một...','insurgency,tactical fps,realistic,new world interactive,multiplayer','2025-09-05 00:00:00','2025-09-05 14:30:27','2025-09-05 15:06:50',NULL),(36,'Ire: Blood Memory - Game nhập vai hành động đầy máu me','ire-blood-memory-game-nhap-vai-hanh-dong-day-mau-me','Ire-Blood Memory - Tựa game mobile có độ khó ngang ngửa dark souls!!!Theo thông tin từ nhà phát triển, Ire-Blood Memory sẽ tích hợp hệ thống các nhiệm vụ khó nhằn nhất và cơ chế chiến đấu theo...','<h1 id=\"ire-blood-memory---tưa-game-mobile-co-đô-kho-ngang-ngưa-dark-souls\"><strong>Ire-Blood Memory - Tựa game mobile có độ khó ngang ngửa dark souls!!!</strong></h1><p>Theo thông tin từ nhà phát triển, Ire-Blood Memory sẽ tích hợp hệ thống các nhiệm vụ khó nhằn nhất và cơ chế chiến đấu theo hơi hướng game PC/console truyền thống, đơn cử như là Dark Souls. Nhà phát triển cũng tiết lộ rằng họ đang cố gắng chăm chút kỹ lưỡng nhất có thể để tựa game mới này có thể mượt mà và dễ dàng thao tác nhất trên các thiết bị di động nhỏ gọn.</p><p></p><p>Bên cạnh đó, chắc chắn Ire-Blood Memory cũng sẽ hứa hẹn mang lại những pha chiến đấu với hình ảnh đồ họa \"chất lượng console\" tương tự như game Implosion vừa ra dạo gần đây. Game sử dụng hệ thống điều khiển ảo, tối ưu hóa cho màn hình cảm ứng và gần như mô tả trọn vẹn những gì tinh túy nhất mà Dark Souls đã làm rất tốt, đó là chất hardcore, độ thử thách và tính phiêu lưu căng thẳng leo thang khiến bạn không thể rời màn hình bất kì phút giây nào.</p><p></p><p>Ire-Blood Memory yêu cầu người chơi phải có một kết nối Wi-Fi ổn định để trải nghiệm tốt nhất và chắc chắn cũng sẽ hỗ trợ các tay cầm game trên các thiết bị chạy iOS. Hiện tại, phiên bản dành cho Android vẫn chưa được nhà phát triển tiết lộ, cùng với đó là việc game sẽ phát hành dưới dạng free-to-play hay Premium thì cũng chưa rõ ràng.</p><p></p><p>Được biết, Ire-Blood Memory là tựa game đầu tay của hãng Tenbirds Corp theo như thông tin mà họ đã đăng tải trên trang web của công ty mình. Có vẻ như nhà phát triển của game đã có những tính toán và kỳ vọng hết sức lớn lao vào tựa game của mình khi đặt nhiều tham vọng vào một trò chơi mới sẽ đạt được những thành công tương tự như Dark Souls.</p><p></p><p>Hiện Ire-Blood Memory vẫn đang trong quá trình hoàn thiện và dự kiến sẽ ra mắt game thủ trong một hoặc hai tuần tới. Ngoài hé lộ về cơ chế chiến đấu và nền đồ họa đẳng cấp console thì hệ thống tiền tệ, nhân vật trong game vẫn đang là ẩn số.</p><p></p><p>Và giống như Demon Souls hay Dark Souls, Ire-Blood Memory cũng sẽ yêu cầu game thủ phải thật tập trung trong việc né tránh và ra đòn sao cho dứt khoát, chính xác và hợp lý nhất. Bởi nếu không, chỉ một chút sơ sẩy cũng có thể khiến bạn trả giá bằng chính \"mạng sống\" của mình.</p>',0,0,1,16,'16','ire blood memory,action rpg,mobile game,dark fantasy,hack slash','LamGame Team',1,'/storage/blog/blog_36_thumb.jpg','vi',1,1,'Ire: Blood Memory - Game nhập vai hành động đầy máu me | LamGame','Ire-Blood Memory - Tựa game mobile có độ khó ngang ngửa dark souls!!!Theo thông tin từ nhà phát triển, Ire-Blood Memory sẽ tích hợp hệ thống các nhiệm vụ khó nhằn nhất và cơ chế chiến đấu theo...','ire blood memory,action rpg,mobile game,dark fantasy,hack slash','2025-09-06 00:10:30','2025-09-05 14:30:30','2025-09-05 14:30:30',NULL),(37,'Marvel Contest of Champions - Đại chiến siêu anh hùng','marvel-contest-of-champions-dai-chien-sieu-anh-hung','Marvel Contest of Champions - Cuộc chiến giữa các siêu anh hùngMarvel Contest of Champions được phát triển bởi Vancouver Studio của Kabam và cốt truyện được viết bởi nhà văn Sam Humphries. Trong...','<h1 id=\"marvel-contest-of-champions---cuôc-chiên-giưa-cac-siêu-anh-hung\"><strong>Marvel Contest of Champions - Cuộc chiến giữa các siêu anh hùng</strong></h1><h2 id=\"section\"></h2><p>Marvel Contest of Champions được phát triển bởi Vancouver Studio của Kabam và cốt truyện được viết bởi nhà văn Sam Humphries. Trong game, người chơi sẽ xây dựng và quản lý một nhóm các anh hùng và nhân vật phản diện từ Marvel Universe, đưa họ vào cuộc chiến chống lại kẻ thù trong một âm mưu tìm kiếm quyền lực.</p><p>Trong Marvel Contest of Champions, người chơi có 2 lựa chọn là chế độ chơi đơn hoặc chế độ trận đánh nhiều người. Chế độ này cho người chơi bước vào vai trò của người đại diện Trái đất trong \"cuộc thi nhà vô địch giữa các thiên hà\" của The Collector tổ chức. Nếu người chơi giành chiến thắng, họ sẽ nhận được \"sức mạnh vô biên\", nhưng nếu họ thất bại, họ sẽ vĩnh viễn trở thành một phần trong bộ sưu tập vĩnh của The Collector.</p><p></p><p>Về cơ bản, người chơi sẽ bắt đầu với hai siêu anh hùng, và có thể thu thập lên tới tổng cộng 25 nhân vật siêu anh hùng khác nữa trong Marvel Contest of Champions. Game có lối chiến đấu theo phong cách cổ điển và khá giống với The King of Fighters. Người chơi sẽ được gặp lại Captain America, Thor, Iron Man, Wolverine, Spider Man... đều là những siêu anh hùng nổi tiếng và có số lượng người hâm mộ đông đảo trên khắp thế giới. Mỗi siêu anh hùng đều có những đặc điểm, khả năng và cách di chuyển riêng biệt.</p><p></p><p>Vào cuối mỗi trận chiến, người chơi kiếm được từ việc chiến thắng một số tiền và 1 món item gọi là ISO-8. ISO-8 được sử dụng để kết hợp nâng cấp siêu anh hùng, trong khi đó tiền thưởng được sử dụng để chiêu mộ những anh hùng mới. Vì nhiệm vụ chứa nhiều trận chiến khốc liệt nên sức khỏe của mỗi Anh hùng và mức độ thiệt hại qua những trận chiến sẽ ngày càng tăng,vì thế người chơi cũng có thể dùng tiền thưởng để chữa lành cho anh hùng của mình trước cuộc chạm trán mới.</p><p></p><p>Ngoài gameplay solo, người chơi hoàn toàn có thể chiến đấu và thách thức bạn bè mình trong chế độ PVP. Ở đây, game thủ có thể chọn tên người dùng của họ để xác định trong trận chiến hoặc trong chức năng chat của game.</p><p></p><p><em>Trailer:</em></p><p><em>https://www.youtube.com/watch?v=FP8CAfwgdn8</em></p><p>Link download:</p><p>Android: <a href=\"https://play.google.com/store/apps/details?id=com.kabam.marvelbattle\"><span class=\"underline\">https://play.google.com/store/apps/details?id=com.kabam.marvelbattle</span></a></p><p>IOS:</p><p>https://itunes.apple.com/us/app/marvel-contest-of-champions/id896112560?mt=8</p>',0,0,1,16,'16','marvel,contest of champions,superhero,fighting game,kabam','LamGame Team',1,'/storage/blog/blog_37_thumb.jpg','vi',1,1,'Marvel Contest of Champions - Đại chiến siêu anh hùng | LamGame','Marvel Contest of Champions - Cuộc chiến giữa các siêu anh hùngMarvel Contest of Champions được phát triển bởi Vancouver Studio của Kabam và cốt truyện được viết bởi nhà văn Sam Humphries. Trong...','marvel,contest of champions,superhero,fighting game,kabam','2025-09-06 00:20:33','2025-09-05 14:30:33','2025-09-05 14:30:33',NULL),(38,'Million Arthur - Game thẻ bài chiến thuật hay nhất','million-arthur-game-the-bai-chien-thuat-hay-nhat','Million Arthur - Game thẻ bài cực hot đến từ Square EnixMillion Arthur giới thiệu đến người chơi cơ chế chiến đấu khá đơn giản giống như một số tựa game khác, khi trận đấu diễn ra hoàn toàn...','<h1 id=\"million-arthur---game-the-bai-cưc-hot-đên-tư-square-enix\"><strong>Million Arthur - Game thẻ bài cực hot đến từ Square Enix</strong></h1><p>Million Arthur giới thiệu đến người chơi cơ chế chiến đấu khá đơn giản giống như một số tựa game khác, khi trận đấu diễn ra hoàn toàn tự động dựa trên việc sắp xếp các thẻ bài sẵn có. Trong trận chiến, tùy theo cách sắp xếp 5 thẻ bài sẵn có mà nhân vật sẽ tung ra các đòn combo skill tấn công địch thủ. Lần lượt, các đòn combo này sẽ được lặp lại cho đến khi trận đấu kết thúc.</p><p></p><p>Tất cả các nhân vật trong game đều được vẽ khá đẹp và bắt mắt theo phong cách Manga/Anime, gây ấn tượng tốt với những tín đồ thậm chí người chơi mới ngay từ lần đầu tiên đăng nhập. Có một điểm đáng chú ý trong game là các nhân vật nữ được thiết kế khá dễ thương và có phần gợi cảm với những bộ trang phục đẹp, bó sát…đem đến sự hào hứng đặc biệt đối với những game thủ nam.</p><p></p><p>Ngoài ra, Million Arthur còn tích hợp sẵn tính năng lồng tiếng cho nhân vật, điều đó giúp cho việc chơi game trở nên hấp dẫn và cuốn hút hơn. Thêm một điểm đặc biệt nữa là việc bạn có thể tạo dựng lực lượng mạnh nhất, hùng hậu nhất cho riêng mình khi tuyển lựa hơn 170 thẻ bài đủ thể loại trong số hàng nghìn nhân vật khác nhau và mỗi thể bài đều có điểm mạnh, điểm yếu và skill riêng biệt.</p><p></p><p>Với việc tạo ra sự kết hợp giữa cốt truyện lôi cuốn, cùng sự tinh tế trong lối chơi và cuối cùng là một nội dung sâu xa chắc chắn sẽ mang đến cho bạn một thế giới Arthur giàu trí tưởng tượng và vô cùng sinh động trong Million Arthur.</p><p>Trailer:</p><p>https://www.youtube.com/watch?v=_NX-ddCY878</p><p>Link download:</p><p>Android: https://play.google.com/store/apps/details?id=com.square_enix.million_sg&hl=en</p><p>IOS: https://itunes.apple.com/sg/app/million-arthur/id689960743?mt=8</p>',0,0,1,16,'16','million arthur,card game,strategy,japanese game,square enix','LamGame Team',1,'','vi',1,1,'Million Arthur - Game thẻ bài chiến thuật hay nhất | LamGame','Million Arthur - Game thẻ bài cực hot đến từ Square EnixMillion Arthur giới thiệu đến người chơi cơ chế chiến đấu khá đơn giản giống như một số tựa game khác, khi trận đấu diễn ra hoàn toàn...','million arthur,card game,strategy,japanese game,square enix','2025-09-06 00:30:33','2025-09-05 14:30:33','2025-09-05 14:30:33',NULL),(39,'Minidom - Game chiến thuật thời gian thực mini','minidom-game-chien-thuat-thoi-gian-thuc-mini','Minidom - Game mobile đỉnh cao đến từ Hàn QuốcMinidom là một game nhập vai kết hợp chiến thuật trên nền tảng di động được hãng Nooby Island phát hành dưới dạng free-to-play, bối cảnh game...','<h1 id=\"minidom---game-mobile-đinh-cao-đên-tư-han-quôc\"><strong>Minidom - Game mobile đỉnh cao đến từ Hàn Quốc</strong></h1><p>Minidom là một game nhập vai kết hợp chiến thuật trên nền tảng di động được hãng Nooby Island phát hành dưới dạng free-to-play, bối cảnh game Minidom đưa bạn hòa mình vào một thế giới huyền bí, nơi đã bị đám quỷ dữ hung tợn thống trị. Dĩ nhiên, cũng như bao thể loại game nhập vai khác, người sẽ trở thành một vị anh hùng cứu tinh không ngoài ai khác chính là bạn.</p><p></p><p>Lối chơi của Minidom mang đến sự quen thuộc của những tựa game nhập vai kết hợp chiến thuật ngày nay, đó là việc bạn sẽ trở thành người nắm quyền chỉ huy một đội quân gồm nhiều chiến binh anh hùng mà trong đó, mỗi người đều sở hữu cho mình những kỹ năng riêng biệt được sử dụng tùy vào những tình huống khác nhau</p><p></p><p>Trong trận đấu, người chơi có thể rảnh tay nhờ vào thiết lập auto đánh thường thấy trong các tựa game cùng thể loại, việc duy nhất mà bạn có thể làm là sắp xếp đội hình một cách hợp lý nhất, kích hoạt những kỹ năng đặc biệt từ anh hùng mỗi khi thanh nộ khí đầy.</p><p>Nâng cấp, cường hóa, và trang bị vật phẩm cho từng anh hùng trong đội hình là điều cần thiết trong bất cứ thể loại game nhập vai nào. Ngoài những nhiệm vụ chính theo cốt truyện, người chơi Minidom còn có thể trải nghiệm một chiến trường PvP rất hấp dẫn thông qua việc giao chiến với những người chơi khác.</p><p></p><p>Trailer:</p><p><a href=\"https://www.youtube.com/watch?v=h1JVY7S4IxY\"><span class=\"underline\">https://www.youtube.com/watch?v=h1JVY7S4IxY</span></a></p><p>Link download:</p><p>Android: https://play.google.com/store/apps/details?id=com.unitytechnologieskorea.minidom&hl=en</p>',0,0,1,16,'16','minidom,rts,real time strategy,mobile strategy,mini game','LamGame Team',1,'/storage/blog/blog_39_thumb.jpg','vi',1,1,'Minidom - Game chiến thuật thời gian thực mini | LamGame','Minidom - Game mobile đỉnh cao đến từ Hàn QuốcMinidom là một game nhập vai kết hợp chiến thuật trên nền tảng di động được hãng Nooby Island phát hành dưới dạng free-to-play, bối cảnh game...','minidom,rts,real time strategy,mobile strategy,mini game','2025-09-06 00:40:45','2025-09-05 14:30:45','2025-09-05 14:30:45',NULL),(40,'PES Club Manager - Game quản lý bóng đá chuyên nghiệp','pes-club-manager-game-quan-ly-bong-da-chuyen-nghiep','PES Club ManagerMới đây, Konami đã công bố việc sẽ phát hành PES Club Manager - một tựa game quản lý bóng đá (dựa trên dòng game PES nổi tiếng) dành cho các thiết bị di động. Game sử dụng nền...','<h1 id=\"pes-club-manager\"><strong>PES Club Manager</strong></h1><p>Mới đây, Konami đã công bố việc sẽ phát hành PES Club Manager - một tựa game quản lý bóng đá (dựa trên dòng game PES nổi tiếng) dành cho các thiết bị di động. Game sử dụng nền tảng đồ họa 3D trong các trận đấu khiến cho các trận đấu trở nên sống động và hấp dẫn hơn. Điều đó khiến PES Club Manager được đánh giá không thua kém gì người anh em của mình là PES 2015.</p><p></p><p>Trong PES Club Manager, bạn sẽ hóa thân thành một huấn luyện viên tài ba, chỉ đạo các học trò của mình với ước mơ chinh phục ngôi vị cao nhất trong làng bóng đá thế giới. Đặc biệt, game có sự góp mặt của hơn 5000 cầu thủ nối tiếng trên khắp thế giới để người chơi có thể thoải mái lựa chọn và tạo cho mình một đội bóng mạnh nhất có thể.</p><p></p><p>Cũng như các tựa game cùng thể loại, cơ chế điều khiển trong PES Club Manager cũng không quá phức tạp, chủ yếu các tính năng đều xuất hiện trên thanh Menu và người chơi chạm để lựa chọn. Ngoài ra, game thủ cũng có thể thay đổi tốc độ trận đấu theo ý thích. Ngay cả việc thay đổi chiến thuật trong trận đấu cũng được hiển thị ngay trên màn hình.</p><p></p><p>Tính năng đáng chú ý nhất trong game là tính năng quản lý cầu thủ, một trong những tính năng giúp cho người chơi có thể tăng cường khả năng thi đấu và chỉ số cho các học trò của mình. Nếu như chưa vừa ý với đội hình mình đang có, game thủ cũng có thể tìm kiếm các siêu sao mới thông qua thị trường chuyển nhượng. Tuy nhiên, vì là một game miễn phí nên để có được cầu thủ tốt vừa ý thì bạn cần phải sử dụng tới PES coin (loại tiền ingame có được thông qua chi trả tiền mặt).</p><p></p><p>Nhìn chung, PES Club Manager sẽ là một sân chơi mới đầy lôi côi đối với những tín đồ của dòng game PES nói riêng và cộng đồng game thủ yêu thích thể loại quản lý bóng đá nói chung và với những ưu thế của mình như lối chơi gây nghiện và nền tảng đồ họa đẹp mắt thì tựa game này hoàn toàn có khả năng gây sốt trong thời gian tới.</p><p>Link download:</p><p>IOS:</p><p>https://itunes.apple.com/au/app/pes-club-manager/id930350602?mt=8</p><p>Android: https://play.google.com/store/apps/details?id=jp.konami.pesclubmanager</p>',0,0,1,16,'16','pes,club manager,football manager,mobile sports,konami','LamGame Team',1,'/storage/blog/blog_40_thumb.jpg','vi',1,1,'PES Club Manager - Game quản lý bóng đá chuyên nghiệp | LamGame','PES Club ManagerMới đây, Konami đã công bố việc sẽ phát hành PES Club Manager - một tựa game quản lý bóng đá (dựa trên dòng game PES nổi tiếng) dành cho các thiết bị di động. Game sử dụng nền...','pes,club manager,football manager,mobile sports,konami','2025-09-05 21:44:12','2025-09-05 14:44:12','2025-09-05 14:44:12',NULL),(41,'Resident Evil 0 HD Remaster - Kinh dị sinh tồn kinh điển','resident-evil-0-hd-remaster-kinh-di-sinh-ton-kinh-dien','Resident Evil 0 remake Vào cuối năm ngoái, nhiều fan hâm mộ của dòng game Resident Evil đã ngờ vực rằng, Resident Evil Zero sẽ theo chân nhiều người anh em của mình để đến với độ phân giải HD thông...','<h1 id=\"resident-evil-0-remake\"><strong>Resident Evil 0 remake</strong> </h1><p>Vào cuối năm ngoái, nhiều fan hâm mộ của dòng game Resident Evil đã ngờ vực rằng, Resident Evil Zero sẽ theo chân nhiều người anh em của mình để đến với độ phân giải HD thông qua một phiên bản remake và giờ đây, Capcom đã chính thức xác nhận mối nghi ngờ ấy bằng việc giới thiệu chính thức Resident Evil 0 HD Remaster.</p><p></p><p>Ra mắt lần đầu tiên trên hệ máy GameCube của Nintendo, Resident Evil 0 là một trong những phiên bản được giới chuyên môn lẫn người hâm mộ đánh giá cao nhất vì lối chơi độc đáo cho phép luân phiên điều khiển hai nhân vật Rebecca Chambers và Billy Coen, mỗi người lại sở hữu kĩ năng riêng biệt và bù trừ cho nhau trong quá trình giải đố. Game lấy bối cảnh trước những sự kiện diễn ra ở phiên bản Resident Evil đầu tiên đồng thời làm rõ thêm về nguồn gốc của T-virus.</p><p></p><p>Resident Evil Zero đã từng một lần được remake lại cho hệ máy Wii vào năm 2008 nhưng chỉ phát hành riêng tại thị trường Nhật Bản. Còn ở phiên bản lần này, Capcom cho biết nó sẽ có mặt trên nhiều hệ console bao gồm PS4, Xbox One, PS3, Xbox 360 và cả PC. Thời điểm ra mắt dự kiến là đầu năm 2016 ở Nhật, còn hiện tại Capcom chưa có bình luận gì về phiên bản quốc tế của trò chơi.</p>',0,0,1,12,'12','resident evil 0,horror,survival horror,capcom,hd remaster','LamGame Team',1,'','vi',1,1,'Resident Evil 0 HD Remaster - Kinh dị sinh tồn kinh điển | LamGame','Resident Evil 0 remake Vào cuối năm ngoái, nhiều fan hâm mộ của dòng game Resident Evil đã ngờ vực rằng, Resident Evil Zero sẽ theo chân nhiều người anh em của mình để đến với độ phân giải HD thông...','resident evil 0,horror,survival horror,capcom,hd remaster','2025-09-05 21:59:19','2025-09-05 14:44:19','2025-09-05 14:44:19',NULL),(42,'Sonic Dash 2: Sonic Boom - Endless runner tốc độ','sonic-dash-2-sonic-boom-endless-runner-toc-do','Sonic Dash 2: Sonic Boom Sau sự ra mắt thành công của Sonic Dash vào năm 2013 thì vừa mới đây, nhà phát triển SEGA đã chính thức phổ biến phần tiếp theo của tựa game này với cái tên Sonic Dash 2:...','<h1 id=\"sonic-dash-2-sonic-boom\"><strong>Sonic Dash 2: Sonic Boom</strong> </h1><p>Sau sự ra mắt thành công của Sonic Dash vào năm 2013 thì vừa mới đây, nhà phát triển SEGA đã chính thức phổ biến phần tiếp theo của tựa game này với cái tên Sonic Dash 2: Sonic Boom trên các thiết bị Android.</p><p>Do vẫn kế thừa đồ họa của phần trước nên0 Sonic Dash 2: Sonic Boom vẫn khoác lên mình chiếc áo 3D đầy sắc nét, kết hợp với tông màu tươi sáng, đem tới cho người chơi những trải nghiệm đầy hấp dẫn và thú vị.</p><p></p><p>Về lối chơi, dù vẫn đi theo thể loại endless-runner nhưng Sonic Dash 2: Sonic Boom lại sỡ hữu riêng nhiều điểm mới lạ so với đàn anh tiền nhiệm. Nếu như ở phần trước, người chơi vào vai Sonic - chú nhím siêu tốc là đại diện thương hiệu của hãng SEGA với nhiệm vụ chính là khám phá những vùng đất mới thì với Sonic Dash 2: Sonic Boom, game thủ sẽ có thể điều khiển tối đa 3 nhân vật trong mỗi màn chơi. Cùng với đó, người chơi sẽ phải khéo léo chuyển đổi giữa 3 nhân vật này để tận dụng những khả năng đặc biệt của chúng nhằm đạt số điểm cao hơn trong mỗi màn chơi.</p><p></p><p>Các nhân vật không hề xa lạ với những fan của Sonic khi chúng đều là những người bạn như: Knuckles, Amy,.. vốn vẫn luôn kề vai sát cánh cùng chú nhím siêu tốc này trong các cuộc phiêu lưu trong phim hoạt hình cùng tên. Bên cạnh đó, Sonic Dash 2: Sonic Boom còn bổ sung thêm một nhân vật mới có tên là Sticks với tuyệt chiêu boomerang siêu nhiên. Cơ chế điều khiển vẫn rất dễ dàng khi Sonic Dash 2: Sonic Boom ngoài các cử chỉ chạm vuốt đã được bổ sung thao tác nghiêng màn hình trong những khi sử dụng power-up \"Enerbeam\".</p><p>https://www.youtube.com/watch?v=S4XC-MZjd6E</p><p>Link download</p><p>Android: https://play.google.com/store/apps/details?id=com.sega.sonicboomandroid</p>',0,0,1,16,'16','sonic dash 2,endless runner,sonic boom,mobile game,sega','LamGame Team',1,'/storage/blog/blog_42_thumb.jpg','vi',1,1,'Sonic Dash 2: Sonic Boom - Endless runner tốc độ | LamGame','Sonic Dash 2: Sonic Boom Sau sự ra mắt thành công của Sonic Dash vào năm 2013 thì vừa mới đây, nhà phát triển SEGA đã chính thức phổ biến phần tiếp theo của tựa game này với cái tên Sonic Dash 2:...','sonic dash 2,endless runner,sonic boom,mobile game,sega','2025-09-05 22:14:31','2025-09-05 14:44:31','2025-09-05 14:44:31',NULL),(43,'Đồ họa mãn nhãn của game online phương Đông','do-hoa-man-nhan-cua-game-online-phuong-dong','Cửu Âm Chân Kinh 2Sau khi mở cửa cho phép game thủ đăng ký tài khoản chơi thử thì Cửu Âm Chân Kinh 2 đã tiếp tục hé lộ thêm một số tính năng về mặt gameplay, đặc biệt là phần chiến đấu. Theo đó game...','<p><strong>Cửu Âm Chân Kinh 2</strong></p><p>Sau khi mở cửa cho phép game thủ đăng ký tài khoản chơi thử thì <strong>Cửu Âm Chân Kinh 2</strong> đã tiếp tục hé lộ thêm một số tính năng về mặt gameplay, đặc biệt là phần chiến đấu. Theo đó game online này sẽ có cơ chế đánh vô cùng đặc biệt, siêu khó và chưa từng xuất hiện trong bất kỳ game nhập vai trực tuyến nào, đảm bảo game thủ sẽ phải khóc ròng nhưng say mê khi vào chơi thực.</p><p></p><p>Theo đó, <strong>Cửu Âm Chân Kinh 2</strong> sẽ có phong cách game đối kháng, giống như Street Fighter vậy! Game thủ sẽ phải bấm từng nút trên bàn phím để tạo ra đòn combo cho nhân vật, kết hợp với phần di chuyển. Nếu như đang mong chờ kiểu đánh đơn giản chỉ cần bấm phím 1, 2, 3, 4 như đại đa phần các MMORPG khác thì bạn sẽ phải thất vọng toàn tập.</p><p>Mỗi chiêu thức hay chuỗi combo skill của nhân vật trong <strong>Cửu Âm Chân Kinh 2</strong> được kích hoạt bởi tổ hợp phím khác nhau. Người chơi sẽ phải thuộc lòng từng loại cho nhân vật của mình thì mới có cơ hội chiến thắng trong các cuộc so tài.</p><p></p><p>Ngoài ra, từng đòn đánh của nhân vật sẽ có 2 chế độ là nặng và nhẹ, chiêu nặng sẽ mạnh hơn nhưng lại tốn sức hơn và xuất chiêu chậm, nhẹ thì ngược lại. Vì vậy muốn tạo ra được combo hoàn chỉnh thì phải biết kết hợp tốt giữa các loại để dùng được cả chân tay đấm đá lẫn nhiều loại binh khí, cũng như các võ công khác nhau.</p><p><strong>Dark and Light</strong></p><p>Mới đây, Snail Games lại tiếp tục giới thiệu một số chi tiết trong tựa game online bom tấn của mình là <strong>Dark and Light</strong>. Lần này nhà phát hành \'câu kéo\' game thủ bằng hệ thống phép thuật trong game, theo đó thì trò chơi này sỡ hữu cơ chế vô cùng khác biệt so với truyền thống của dòng game MMORPG.</p><p></p><p>Cụ thể thì các phép thuật mà game thủ sử dụng được không \'gắn\' vào người như skill học được, mà được coi như một \'bổ trợ\' của vũ khí, thậm mọi người cũng có thể craft ra những món đồ độc đáo để \'gán\' phép vào trong một số binh khí cận chiến.</p><p>Được biết, nhà sản xuất muốn đưa game thủ <strong>Dark and Light</strong> vào một thế giới ảo có sự tự do hơn trong việc lựa chọn sử dụng phép thuật thay vì phải đi học hành theo kiểu \'tuyến tính\' tương đối nhàm chán mà các game online khác thường xuyên áp dụng. Thực tế thì trong <strong>Dark and Light</strong> thì trong bất kỳ việc gì từ chiến đấu tới khám phá, xây dựng đều sử dụng phép thuật nên đây là điều rất thú vị.</p><p></p><p><strong>Dark and Light</strong> là dự án được Snail Games chăm sóc phát triển trong suốt 12 năm trời với rất nhiều cố gắng. Trò chơi bắt đầu được phát triển từ năm 2004 và qua nhiều lần thay đổi, nhất là ở mặt đồ hoạ với nền tảng Unreal Engine 4 mới. Như đã giới thiệu thì <strong>Dark and Light</strong> là một game thuộc thể loại sandbox với thế giới ảo siêu thực có nhiều tính năng hết sức phức tạp, được bê nguyên từ thực tế vào như thời tiết, các khung cảnh tuyệt đẹp, cuộc sống của muông thú, các loài cây và cả luật pháp nữa.</p><p><strong>Transformers Online</strong></p><p><strong>Transformers Online</strong> là một game online 3D thuộc thể loại MMOFPS có đề tài viễn tưởng được phát triển và vận hành bởi công ty Tencent Games. Trò chơi là sản phẩm bản quyền chính hiệu và hứa hẹn khai thác bối cảnh “Transformers” nguyên bản hoạt hình lồng ghép phim điện ảnh, sử dụng công nghệ Unreal Engine 4 tân tiến để mang lại chất lượng đồ họa next-gen cực đỉnh ngang tầm quốc tế.</p><p></p><p>Như đã biết, trò chơi vẫn có bóng hình của “Overwatch” nhưng vẫn đang trong giai đoạn thử nghiệm, nên nó sẽ còn thay đổi nhiều nữa và nhà sản xuất cũng đang cố gắng đưa ra điểm đặc sặc của riêng mình. Trò chơi tận dụng IP nguyên gốc rất tốt, khi mỗi lần thử nghiệm đầu bổ sung thêm nhân vật mới, chưa kể thiết kế nhân vật rất giống nguyên bản kết hợp thêm cả những bộ skin lấy từ phim bom tấn nữa.</p><p></p><p>Ở giai đoạn thử nghiệm trước, các chế độ gameplay của <strong>Transformers Online</strong> được phân làm ba chủng loại chính gồm người vs người, người vs máy và lập phòng tự do. Hình thức giao đấu ở mỗi trận sẽ có 4 kiểu chính gồm hộ tống, đoạt cờ, đoàn đội và tranh đoạt tài nguyên, mỗi chế độ có một mục tiêu chiến thắng riêng biệt và rất dễ tìm hiểu. Thú vị hơn cả là chế độ tự do lập phòng khi người chơi có đưa thoải mái đưa ra những điều kiện thi đấu quái gở như cấm biến hình, nhân vật chỉ được dùng vũ khí cận chiến…</p><p><strong>Project DH</strong></p><p>Mới đây gã khổng lồ đến từ Hàn Quốc là Nexon đã giới thiệu dự án game online nhập vai mới siêu ấn tượng của mình mang tên <strong>Project DH</strong>. Chắc chắn rằng game thủ sẽ phải ngất ngây trước ý tưởng kỳ quặc của trò chơi với một thế giới giả tưởng mang phong cách Steampunk, vừa hiện đại lại vừa nghệ thuật vô cùng độc đáo.</p><p></p><p>Theo giới thiệu, <strong>Project DH</strong> có phong cách Steampunk với các loại máy móc cơ khí dị dạng và kết hợp với cả bối cảnh thế giới con người đang nguy cấp, bị loài rồng tấn công dữ dội. Game thủ sẽ bị quấn vào những nhiệm vụ đầy khó khăn phải tiêu diệt những con quái vật hung dữ và to khổng lồ, tuy nhiên chúng lại chính là mỏ nguyên liệu cho bạn chế đồ đạc. Quả thực thì rất giống với huyền thoại Monster Hunter!</p><p></p><p>Hiện tại những thông tin chi tiết về gameplay cũng như các tính năng trong <strong>Project DH</strong> vẫn chưa được tiết lộ rõ ràng và trò chơi vẫn chưa có tên chính thức cũng như thời điểm ra mắt trên thị trường.</p>',0,0,1,12,'12','asian mmo,beautiful graphics,online games,eastern games','LamGame Team',1,'/storage/blog/blog_43_thumb.jpg','vi',1,1,'Đồ họa mãn nhãn của game online phương Đông | LamGame','Cửu Âm Chân Kinh 2Sau khi mở cửa cho phép game thủ đăng ký tài khoản chơi thử thì Cửu Âm Chân Kinh 2 đã tiếp tục hé lộ thêm một số tính năng về mặt gameplay, đặc biệt là phần chiến đấu. Theo đó game...','asian mmo,beautiful graphics,online games,eastern games','2025-09-05 22:29:31','2025-09-05 14:44:31','2025-09-05 14:44:31',NULL),(44,'Nintendo New 2DS XL - Máy chơi game cầm tay mới','nintendo-new-2ds-xl-may-choi-game-cam-tay-moi','New 2DS XL là phần cứng mới nhất được trình làng bởi Nintendo. Chiếc máy cầm tay này sẽ được bán ra vào 28/7 với mức giá 150 USD. Đây là phiên bản rút gọn của 3DS XL, phục vụ nhu cầu chơi game nhưng...','<h1 id=\"new-2ds-xl-là-phần-cứng-mới-nhất-được-trình-làng-bởi-nintendo.-chiếc-máy-cầm-tay-này-sẽ-được-bán-ra-vào-287-với-mức-giá-150-usd.-đây-là-phiên-bản-rút-gọn-của-3ds-xl-phục-vụ-nhu-cầu-chơi-game-nhưng-không-có-nhu-cầu-chơi-game-3d.-dù-là-bản-rút-gọn-nhưng-new-2ds-xl-lại-có-thiết-kế-khá-mới-gọn-gàng-hơn-khá-nhiều-so-với-bản-gốc.-các-tính-năng-cơ-bản-vẫn-được-giữ-nguyên-như-cần-c-stick-2-nút-z-hỗ-trợ-amiibo-và-các-tương-thích-với-các-tựa-game-new-3ds-xl.\">New 2DS XL là phần cứng mới nhất được trình làng bởi Nintendo. Chiếc máy cầm tay này sẽ được bán ra vào 28/7 với mức giá 150 USD. Đây là phiên bản rút gọn của 3DS XL, phục vụ nhu cầu chơi game nhưng không có nhu cầu chơi game 3D. Dù là bản rút gọn nhưng New 2DS XL lại có thiết kế khá mới, gọn gàng hơn khá nhiều so với bản gốc. Các tính năng cơ bản vẫn được giữ nguyên như cần C-Stick, 2 nút Z, hỗ trợ Amiibo và các tương thích với các tựa game New 3DS XL.</h1><h1 id=\"nintendo-công-bố-new-2ds-xl-tiếp-nối-thành-công\"></h1><h1 id=\"nintendo-công-bố-new-2ds-xl-tiếp-nối-thành-công-1\"></h1><h1 id=\"với-thị-trường-mỹ-new-2ds-xl-sẽ-được-bán-ra-với-phối-màu-đenxanh-trong-khi-ở-nhật-sẽ-có-thêm-bản-phối-màu-trắngvàng.\">Với thị trường Mỹ, New 2DS XL sẽ được bán ra với phối màu đen/xanh trong khi ở Nhật sẽ có thêm bản phối màu trắng/vàng.</h1><h1 id=\"nintendo-công-bố-new-2ds-xl-tiếp-nối-thành-công-2\"></h1><h1 id=\"section\"></h1><h1 id=\"section-1\"></h1>',0,0,1,20,'20','nintendo,2ds xl,handheld console,nintendo hardware','LamGame Team',1,'/storage/blog/blog_44_thumb.jpg','vi',1,1,'Nintendo New 2DS XL - Máy chơi game cầm tay mới | LamGame','New 2DS XL là phần cứng mới nhất được trình làng bởi Nintendo. Chiếc máy cầm tay này sẽ được bán ra vào 28/7 với mức giá 150 USD. Đây là phiên bản rút gọn của 3DS XL, phục vụ nhu cầu chơi game nhưng...','nintendo,2ds xl,handheld console,nintendo hardware','2025-09-05 22:44:32','2025-09-05 14:44:32','2025-09-05 14:44:32',NULL),(45,'Top 5 game RPG mini mới trên Android và iOS','top-5-game-rpg-mini-moi-tren-android-va-ios','Nonstop Chuck NorrisNonstop Chuck Norris là tựa game hành động mini rất hấp dẫn. Trong game bạn sẽ nhập vai anh chàng chuyên đi đến hang ổ của bọn xã hội đen, ma túy để tiêu diệt...','<p><strong>Nonstop Chuck Norris</strong></p><p>Nonstop Chuck Norris là tựa game hành động mini rất hấp dẫn. Trong game bạn sẽ nhập vai anh chàng chuyên đi đến hang ổ của bọn xã hội đen, ma túy để tiêu diệt chúng.</p><p>https://youtu.be/_l_1LeyrBaU</p><p>Các băng nhóm tội phạm được trang bị vũ khí thô sơ, không có nhiều súng máy như các game hạng nặng bạn từng biết, bên cạnh đó cũng có nhiều loại vũ khí hết sức \"khó đỡ\". Tuy nhiên, trong quá trình chơi, bạn sẽ cảm nhận đầy đủ 1 thế giới xã hội đen thu nhỏ.</p><p>Link cho Android <strong><span class=\"underline\">tại đây</span></strong></p><p>Link cho iOS <strong><span class=\"underline\">tại đây</span></strong></p><p><strong>CATS: Crash Arena Turbo Stars</strong></p><p><strong>https://youtu.be/yK9Nf4Siyr8</strong></p><p>CATS: Crash Arena Turbo Stars cho phép bạn xây dựng một cỗ máy chiến tranh từ những thứ mà bạn thu thập được. Sau đó, phát huy sức mạnh của nó để thách thức với những người chơi khác trong trận chiến PvP tự động. Thông thường 2 chiếc xe sẽ lao vào nhau cho tới khi 1 trong 2 tan nát.</p><p>Kẻ chiến thắng sẽ có thêm nguyên liệu để nâng cấp cho con xe của mình, còn người chiến bại thì không được gì cả.</p><p>Link cho Android <strong><span class=\"underline\">tại đây</span></strong></p><p>Link cho iOS <strong><span class=\"underline\">tại đây</span></strong></p><p><strong>Legacy Quest: Rise of Heroes</strong></p><p><strong>https://youtu.be/Qe_bLAn1ruc</strong></p><p>Legacy Quest: Rise of Heroes từng gây náo loạn làng game bởi sức hấp dẫn của nó. Đây là một game nhập vai chiến thuật RPG được đánh giá có lối chơi đề cao tính chiến thuật nhất từ trước tới nay. Chỉ một thời gian ngắn ra mắt, tựa game đã đưa về một lợi nhuận cực khủng cho ông lớn Nexon, điều đó đủ để thấy được độ hot của game như thế nào</p><p>Game mang đậm phong cách thần thoại châu Âu. Tham gia vào thế giới game Legacy Quest: Rise of Heroes, người chơi cần chiêu mộ các anh hùng, rèn luyện cho họ và tham gia vào các cuộc chiến quy mô lớn. Đoàn kết các chiến binh hùng mạnh và thú khổng lồ lại với nhau, binh đoàn hùng mạnh này sẽ cùng đối đầu với những đội quân ma quỷ.</p><p>Link cho Android <strong><span class=\"underline\">tại đây</span></strong></p><p>Link cho iOS <strong><span class=\"underline\">tại đây</span></strong></p><p><strong>Planet of Heroes: Chiến Lược</strong></p><p><strong>https://youtu.be/tXXyIM4ZA6w</strong></p><p>Planet of Heroes là một tân binh MOBA. Game được nhận xét là \"thiết kế để mang lại cho người chơi những trải nghiệm tuyệt vời nhất giống như đang chơi game MOBA \"chất lượng PC\" ngay cả trên các thiết bị di động\".</p><p>Trong Planet of Heroes, game thủ sẽ đóng vai một chiến lược gia - người mà được lựa chọn bởi các Forerunner và tham gia chiến đấu trong các đấu trường MOBA khốc liệt.</p><p>Planet of Heroes với đồ họa đỉnh cao, chiến đấu kịch tính đậm chất chiến thuật trong trận đấu 7 phút được xem là một trong những tựa game MOBA hấp dẫn nhất năm 2017.</p><p>Link cho Android <strong><span class=\"underline\">tại đây</span></strong></p><p>Link cho iOS <strong><span class=\"underline\">tại đây</span></strong></p><p><strong>Enneas Saga</strong></p><p><strong>https://youtu.be/rYcEeq2DCL4</strong></p><p>Enneas Saga là dòng game hành động nhập vai hết sức kịch tính. Lấy lối chơi thu thập nhân vật làm trọng tâm, Enneas Saga sẽ làm hài lòng những ai yêu thích thể loại game thẻ tướng với số lượng nhân vật đông đảo.</p><p>Cốt truyện game vẫn là cuộc chiến giữa con người và ma quỷ khi nhân vật chính Dante buộc phải dùng sức mạnh nửa người nửa quỷ của mình để chống lại binh đoàn quỷ dữ đang xâm chiếm thế giới con người. Thông qua cuộc phiêu lưu đó, người chơi sẽ thu thập rất nhiều nhân vật vào đội hình của mình.</p><p>Thu thập nhiều vật phẩm có giá trị và triệu hồi các vị anh hùng mạnh mẽ nhất, thách thức bản thân khi bạn leo lên tháp đen... đó là những nhiệm vụ thú vị bạn sẽ thực hiện khi tham gia vào Enneas Saga.</p><p>Link cho Android <a href=\"https://play.google.com/store/apps/details?id=com.skeinglobe.global.enneassaga\"><strong><span class=\"underline\">tại đây</span></strong></a></p>',0,0,1,16,'16','mini rpg,mobile rpg,android games,ios games,action rpg','LamGame Team',1,'/storage/blog/blog_45_thumb.jpg','vi',1,1,'Top 5 game RPG mini mới trên Android và iOS | LamGame','Nonstop Chuck NorrisNonstop Chuck Norris là tựa game hành động mini rất hấp dẫn. Trong game bạn sẽ nhập vai anh chàng chuyên đi đến hang ổ của bọn xã hội đen, ma túy để tiêu diệt...','mini rpg,mobile rpg,android games,ios games,action rpg','2025-09-05 22:59:39','2025-09-05 14:44:39','2025-09-05 14:44:39',NULL),(46,'Riot tiết lộ tướng mới Liên Minh Huyền Thoại','riot-tiet-lo-tuong-moi-lien-minh-huyen-thoai','Riot tiết lộ tướng mới của Liên Minh Huyền Thoại và những điều ít ai ngờ đếnKhông chỉ đưa ra thông tin về vị tướng thứ 135 này, một dự án khác cũng mới được đại diện của Riot Games tiết lộ mới...','<h1 id=\"riot-tiết-lộ-tướng-mới-của-liên-minh-huyền-thoại-và-những-điều-ít-ai-ngờ-đến\"><strong>Riot tiết lộ tướng mới của Liên Minh Huyền Thoại và những điều ít ai ngờ đến</strong></h1><h2 id=\"không-chỉ-đưa-ra-thông-tin-về-vị-tướng-thứ-135-này-một-dự-án-khác-cũng-mới-được-đại-diện-của-riot-games-tiết-lộ-mới-đây.\"><strong>Không chỉ đưa ra thông tin về vị tướng thứ 135 này, một dự án khác cũng mới được đại diện của Riot Games tiết lộ mới đây.</strong></h2><p>Hiện tại Riot Games đang có những động thái chuẩn bị cho sự ra mắt của vị tướng thứ 135 trong Liên Minh Huyền Thoại. Cách đây vài hôm, một kỹ sư của Riot đã đăng lên Twitter một dòng Tweet đầy bí ẩn:</p><p><em>\"Có ai đoán được vị tướng tiếp theo sẽ là gì không? Tôi cá luôn là chả ai đoán được\"</em>, động thái này cho thấy, Riot sẽ cho ra mắt vị tướng thứ 135 đến với Đấu Trường Công Lý. Trong một diễn biến khác, August Browning (được biết đến với những nickname Gypsylord), một trong những người thiết kế tướng kỳ cựu của Riot vừa cho biết vị tướng mới sắp ra mắt sẽ có \"họ hàng\" với Quái Vật Hư Không.</p><p></p><p>Nhưng thực hư vị tướng này sẽ có hình dạng như thế nào? Không ai biết ngoài Riot, và họ lại rất kín tiếng về điều này. Thêm vào đó, mỗi vị tướng được tạo ra thường không chỉ bằng ý tưởng của một cá nhân mà là kết quả từ sự hợp tác của cả team thiết kế xoay quanh ý tưởng ban đầu. Vì thế, bạn có thể thấy dù là tướng do cùng một người tạo ra nhưng chúng lại rất khác nhau về lối chơi.</p><p>Lấy ví dụ như cả Vel\'Koz và Zac đều được tạo ra bởi Subninja, nhưng Vel\'Koz một là pháp sư đường giữa “không thể mềm hơn” còn Zac lại là tướng đa nhiệm đi rừng đi đường trên rất trâu bò.</p><p>Tất cả những gì chúng ta biết về vị tướng mới này, chỉ đơn giản là câu nói qua lời mô tả của August: \"Một vị tướng rất tuyệt vời và bạn không thể đoán trước được\".</p><p>Sau vị tướng thứ 135, vị tướng thứ 136 cũng được hé lộ là được thiết kế bởi ZenonTheStoic. Đây sẽ là tướng thứ 6 do Zenon thực hiện sau Azir, Lucian, Xerath (phiên bản mới), Tahm Kench và Taliyah. Bạn có thể thấy rẳng 3 trong số 5 vị tướng này là tướng đường giữa, nên rất có thể vị tướng thứ 6 sẽ ở vị trí đường trên hoặc đi rừng.</p><p>Sau khi tiết lộ thông tin về tướng thứ 135, bản thân August cũng đã xác nhận mình đang bắt tay vào thực hiện một vị tướng hỗ trợ mới. Tuy nhiên do quá trình thực hiện một vị tướng mới của Riot thường kéo dài từ 6 tháng đến... vài năm (như trường hợp của Ao Shin, bị tạm hoãn sau đó được đổi tên thành Aurelion Sol) nên rất có thể chúng ta sẽ không được thấy vị tướng này trong năm 2017.</p><p>Nếu bạn chưa biết, những vị tướng do August thiết kế hiện đang có mặt trong game là Vi, Jinx, Gnar (hợp tác với Meddler), Ekko – những vị tướng khiến August nhận được biệt danh “ba cộng dồn” từ game thủ LMHT.</p><p></p><p>Đó là tất cả những thông tin về ba trong số những vị tướng mới mà Riot sẽ cho ra mắt trong năm nay và năm sau. Dĩ nhiên cả ba vị tướng trên đều nằm trong giai đoạn phát triển và Riot không hề đưa ra bất cứ lời bất kỳ thông tin gì, những thông tin này hoàn toàn có thể thay đổi khi các vị tướng mới được ra mắt trong tương lai.</p>',0,0,1,12,'12','league of legends,riot games,new champion,lol,moba','LamGame Team',1,'/storage/blog/blog_46_thumb.jpg','vi',1,1,'Riot tiết lộ tướng mới Liên Minh Huyền Thoại | LamGame','Riot tiết lộ tướng mới của Liên Minh Huyền Thoại và những điều ít ai ngờ đếnKhông chỉ đưa ra thông tin về vị tướng thứ 135 này, một dự án khác cũng mới được đại diện của Riot Games tiết lộ mới...','league of legends,riot games,new champion,lol,moba','2025-09-05 23:14:46','2025-09-05 14:44:46','2025-09-05 14:44:46',NULL),(47,'Top 3 trang bị OP trong Liên Minh Huyền Thoại Esports','top-3-trang-bi-op-trong-lien-minh-huyen-thoai-esports','Điểm danh 3 trang bị dị đang tung hoành đấu trường Liên Minh Huyền Thoại chuyên nghiệpThông thường, những trang bị được xem là phổ thông vẫn rất được ưa chuộng như Kiếm, Khiên hay Nhẫn Doran. Nhưng...','<h1 id=\"điểm-danh-3-trang-bị-dị-đang-tung-hoành-đấu-trường-liên-minh-huyền-thoại-chuyên-nghiệp\"><strong>Điểm danh 3 trang bị dị đang tung hoành đấu trường Liên Minh Huyền Thoại chuyên nghiệp</strong></h1><p>Thông thường, những trang bị được xem là phổ thông vẫn rất được ưa chuộng như Kiếm, Khiên hay Nhẫn Doran. Nhưng trong khoảng thời gian gần đây, các game thủ LMHT lại chuộng những trang bị dị khi bắt đầu trận đấu. Chúng là những trang bị gì và lý do được ưa chuộng đến thế.</p><p></p><p>Tại đường dưới, các Xạ Thủ thường sắm cho mình Kiếm Doran hoặc Lưỡi Hái cùng 1 Bình Máu. Nhưng trong meta game hiện tại, một số game thủ đã quyết định lên những trang bị khác. Đầu tiên đó là <strong>Kiếm Dài và 3 Bình Máu</strong>, và lựa chọn này đang khá được ưa chuộng. Trang bị thứ 2 là <strong>Giày Thường và 3 Bình Máu</strong>.</p><p>Theo thông số, Kiếm Doran có giá 450 vàng cùng 8 sát thương, 3% hút máu và 80 máu, trong khi đó Kiếm Dài chỉ có 10 sát thương nhưng có mức giá rẻ hơn là 350 vàng. Theo đánh giá ban đầu, chỉ số giữa 2 trang bị có vẻ khác chênh lệch, nhưng nếu so sánh về khả năng lên trang bị nhanh hơn và cầm cự trong giai đoạn đầu trận thì Kiếm Dài lại chiếm ưu thế. Kiếm Doran và một Bình Máu chỉ được khoảng 230 máu mà thôi, nhưng Kiếm Dài và 3 Bình Máu lại hồi được tổng cộng 450 máu. Meta game hiện tại đang là những tướng Hỗ Trợ có khả năng cấu máu cực tốt như Lulu, Karma, Malzahar và Zyra thì việc hồi máu nhiều sẽ giúp bạn trụ đường hiệu quả hơn. Ngoài ra, nếu lên Kiếm Dài trước sẽ giúp người chơi lên được những trang bị như Kiếm Ma Youmuu, Rìu Đen hay Lưỡi Hái Linh Hồn. Và đó cũng là lí do cách 1 được ưa chuộng nhất.</p><p></p><p>Còn đối với lựa chọn 2, hiện tại chỉ có Jhin là phù hợp nhất để lên cách này nhưng nếu các bạn muốn cũng có thể áp dụng cho một vài vị tướng khác. Cơ bản, Giày Thường giúp người chơi có thể né được các chiêu thức định hướng từ đối phương (như Caitlyn, Ezreal, Zyra, ...) tốt hơn trong giai đoạn đi đường. Dù thiếu đi sát thương tăng thêm, nhưng 600 máu hồi lại cũng không phải là vô dụng. Đặc biệt đối với Jhin, phát bắn thứ 4 của vị tướng này bắn khá đau nên thật sự giai đoạn đầu trận cũng không quá cần thiết phải lên trang bị tăng thêm sát thương. Một khi né chiêu thức tốt và giành chiến thắng giao tranh, người chơi đã nắm được 50% chiến thắng vì meta game hiện tại là phải giành chiến thắng trong giai đoạn đi đường.</p><p>Và cuối cùng là đường trên, với phong cách lên trang bị mới. Thông thường, khi Shen đối đầu với những Đấu Sĩ sẽ lên Khiên Doran để gia tăng khả năng phòng thủ. Nhưng đối với những cuộc đối đầu giữa Đỡ Đòn với nhau thì việc hạ gục nhau là điều không dễ, có khi đánh nhau cả ... nửa tiếng đồng hồ cũng chưa chết vì không có sát thương. Vì vậy tất cả những gì Shen cần trong cuộc đối đầu này là sắm ngay <strong>3 trang bị Ngọc Lục Bảo</strong> để tăng cường khả năng bám đường của mình. Về chỉ số, 3 trang bị này cung cấp thêm 150% hồi máu gốc, giúp gã ninja này không cần sắm thêm nhiều Bình Máu mà chỉ tập trung vào mua trang bị nhanh hơn. Thêm vào đó, những trang bị này cũng nâng cấp thành Rìu Tiamat.</p>',0,0,1,12,'12','league of legends,esports,pro items,lol items,competitive','LamGame Team',1,'/storage/blog/blog_47_thumb.jpg','vi',1,1,'Top 3 trang bị OP trong Liên Minh Huyền Thoại Esports | LamGame','Điểm danh 3 trang bị dị đang tung hoành đấu trường Liên Minh Huyền Thoại chuyên nghiệpThông thường, những trang bị được xem là phổ thông vẫn rất được ưa chuộng như Kiếm, Khiên hay Nhẫn Doran. Nhưng...','league of legends,esports,pro items,lol items,competitive','2025-09-05 23:29:48','2025-09-05 14:44:48','2025-09-05 14:44:48',NULL),(48,'Đột Kích - Top 5 tips Zombie V4 map Hoàng Lăng','dot-kich-top-5-tips-zombie-v4-map-hoang-l-ng','Đột Kích: Những điều cơ bản cần biết để chơi tốt trong chế độ Zombie V4 bản đồ Hoàng LăngBản đồ Hoàng Lăng là điểm đến yêu thích của nhiều game thủ Đột Kích khi trải nghiệm chế độ Zombie V4. Nhưng...','<h1 id=\"đột-k&iacute;ch-những-điều-cơ-bản-cần-biết-để-chơi-tốt-trong-chế-độ-zombie-v4-bản-đồ-ho&agrave;ng-lăng\"><strong>Đột K&iacute;ch: Những điều cơ bản cần biết để chơi tốt trong chế độ Zombie V4 bản đồ Ho&agrave;ng Lăng</strong></h1>\r\n<h2 id=\"bản-đồ-ho&agrave;ng-lăng-l&agrave;-điểm-đến-y&ecirc;u-th&iacute;ch-của-nhiều-game-thủ-đột-k&iacute;ch-khi-trải-nghiệm-chế-độ-zombie-v4.-nhưng-chơi-ra-sao-chiến-thuật-như-n&agrave;o-mưu-mẹo-n&agrave;o-cần-phải-nắm-r&otilde;-th&igrave;-kh&ocirc;ng-phải-ai-cũng-r&otilde;.-dưới-đ&acirc;y-l&agrave;-những-điều-m&agrave;-nếu-người-chơi-đột-k&iacute;ch-n&agrave;o-mới-v&agrave;-chưa-quen-với-bản-đồ-ho&agrave;ng-lăng-bắt-buộc-phải-ghi-nhớ-nếu-kh&ocirc;ng-muốn-bị-ăn-h&agrave;nh-no-n&ecirc;.\">Bản đồ Ho&agrave;ng Lăng l&agrave; điểm đến y&ecirc;u th&iacute;ch của nhiều game thủ Đột K&iacute;ch khi trải nghiệm chế độ Zombie V4. Nhưng chơi ra sao, chiến thuật như n&agrave;o, mưu mẹo n&agrave;o cần phải nắm r&otilde; th&igrave; kh&ocirc;ng phải ai cũng r&otilde;. Dưới đ&acirc;y l&agrave; những điều m&agrave; nếu người chơi Đột K&iacute;ch n&agrave;o mới v&agrave; chưa quen với bản đồ Ho&agrave;ng Lăng bắt buộc phải ghi nhớ nếu kh&ocirc;ng muốn bị &ldquo;ăn h&agrave;nh no n&ecirc;&rdquo;.</h2>\r\n<p>&nbsp;</p>\r\n<p><strong>Giữ khoảng c&aacute;ch với những người chơi kh&aacute;c ngay từ đầu</strong></p>\r\n<p>Nếu suy nghĩ sai lầm, &ldquo;thơ ng&acirc;y&rdquo; rằng m&igrave;nh chỉ l&agrave; người săn Zombie th&igrave; bạn sẽ nhanh ch&oacute;ng bị biến đổi. Ch&iacute;nh việc tập trung đứng ngay cạnh nhau l&agrave; hiểm họa lớn nhất v&igrave; người đứng cạnh bạn c&oacute; thể h&oacute;a Zombie bất kỳ l&uacute;c n&agrave;o. Những người chơi giỏi sẽ lu&ocirc;n duy tr&igrave; khoảng c&aacute;ch đủ an to&agrave;n, tạo chỗ đứng ri&ecirc;ng để c&oacute; thể quan s&aacute;t tổng thể &ldquo;chiến trường&rdquo;.</p>\r\n<p><strong>Li&ecirc;n tục đảo t&acirc;m từ trước ra sau khi bạn đ&atilde; đứng ở tr&ecirc;n v&aacute;ch tường</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Ngoại trừ những vị tr&iacute; v&aacute;ch trong g&oacute;c tường ra th&igrave; c&aacute;c bức v&aacute;ch hai b&ecirc;n tường đều c&oacute; 2 hướng để đối phương tiếp cận. Zombie sau khi bị bạn giết c&oacute; thể hồi sinh ở vị tr&iacute; ph&iacute;a ngược lại, với Terminator th&igrave; thậm ch&iacute; chỉ mất 3 - 4 gi&acirc;y l&agrave; ch&uacute;ng đ&atilde; lại xuất hiện tập k&iacute;ch ph&iacute;a sau người vừa hạ n&oacute; rồi. Tinh thần cảnh gi&aacute;c n&agrave;y c&agrave;ng quan trọng khi bạn kh&ocirc;ng phải l&agrave; người chơi giỏi parkour m&agrave; chỉ quen ph&ograve;ng thủ, đẩy l&ugrave;i bước tiến đối phương từ những khẩu s&uacute;ng c&oacute; h&agrave;ng trăm vi&ecirc;n đạn.</p>\r\n<p><strong>Đối đầu trực diện Terminator th&igrave; kh&ocirc;ng n&ecirc;n đ&aacute;nh trực diện m&agrave; luồn ra sau</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Đ&acirc;y l&agrave; mẹo chơi cực kỳ kh&ocirc;n ngoan được nhiều game thủ &aacute;p dụng. &Aacute;p s&aacute;t tới đối phương một khoảng c&aacute;ch vừa phải rồi n&eacute; sang tr&aacute;i hoặc phải, từ đ&acirc;y tạo điều kiện luồn hẳn ra sau lưng đối phương v&agrave; hạ s&aacute;t Terminator nhanh gọn.</p>\r\n<p><strong>Tận dụng ch&iacute;nh đồng đội để parkour</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Tranh thủ một người chơi kh&aacute;c cũng đang cố gắn &ldquo;l&ecirc;n n&oacute;c nh&agrave; để bắt con g&agrave;&rdquo; th&igrave; đ&acirc;y l&agrave; điều cần thiết để bạn đứng tr&ecirc;n &ldquo;đỉnh n&uacute;i&rdquo;. Bạn h&atilde;y biến người chơi đ&oacute; th&agrave;nh bước đệm m&agrave; bật l&ecirc;n, nhưng phải l&agrave;m điều n&agrave;y trước khi hệ thống &ldquo;chọn&rdquo; Zombie xong.</p>\r\n<p><strong>Cố chiếm giữ đỉnh cao nhất một m&igrave;nh</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Việc leo l&ecirc;n được điểm cao nhất trong map ch&iacute;nh l&agrave; yếu tố quan trọng để bạn cho đối phương ăn h&agrave;nh bởi rất kh&oacute; để một người chơi b&igrave;nh thường n&agrave;o c&oacute; thể tự m&igrave;nh leo l&ecirc;n được đỉnh m&agrave; kh&ocirc;ng c&oacute; sự trợ gi&uacute;p từ đồng đội. Chỉ cần kh&ocirc;ng để số lượng Zombie l&ecirc;n tới con số 2 &aacute;p s&aacute;t tới vị tr&iacute; ngay s&aacute;t &ldquo;b&agrave;n thờ&rdquo; (nhằm kh&ocirc;ng để Zombie &ldquo;dẫm&rdquo; l&ecirc;n nhau m&agrave; tr&egrave;o l&ecirc;n đỉnh) m&agrave; bạn chiếm giữ th&igrave; Zombie kh&oacute; c&oacute; cơ hội s&aacute;t thương tới bạn. Đặc biệt l&agrave; hạn chế đứng s&aacute;t m&eacute;p &ldquo;b&agrave;n thờ&rdquo; v&igrave; Zombie vẫn ch&eacute;m tới được. L&uacute;c n&agrave;y với một khẩu sniper, rifle, MG hay SMG thuộc danh s&aacute;ch B&aacute;u vật sẽ rất tuyệt vời để bạn ti&ecirc;u diệt ch&uacute;ng dễ d&agrave;ng.</p>',0,0,1,12,'12','31','Example',1,'/storage/blog/blog_48_thumb.jpg','en',1,1,'Đột Kích - Top 5 tips Zombie V4 map Hoàng Lăng | LamGame','Đột Kích: Những điều cơ bản cần biết để chơi tốt trong chế độ Zombie V4 bản đồ Hoàng LăngBản đồ Hoàng Lăng là điểm đến yêu thích của nhiều game thủ Đột Kích khi trải nghiệm chế độ Zombie V4. Nhưng...','dot kich,crossfire,zombie mode,fps tips,gaming guide','2025-09-05 00:00:00','2025-09-05 14:44:52','2025-09-05 15:04:38',NULL),(49,'Deformers - Game online từ The Order 1886 Open Beta','deformers-game-online-tu-the-order-1886-open-beta','Deformers - Game online từ The Order: 1886 chính thức ra mắt bản Open BetaMới đây, hãng phát triển độc lập Ready at Dawn đã chính thức mở Open Beta cho tựa game online mới nhất của hãng mang tên...','<h1 id=\"deformers---game-online-từ-the-order-1886-chính-thức-ra-mắt-bản-open-beta\"><strong>Deformers - Game online từ The Order: 1886 chính thức ra mắt bản Open Beta</strong></h1><p>Mới đây, hãng phát triển độc lập Ready at Dawn đã chính thức mở Open Beta cho tựa game online mới nhất của hãng mang tên <em>Deformers.</em> Đây là game online thuộc dạng đấu trường (arena combat) nơi người chơi sẽ điều khiển những sinh vật tròn vo, không kém phần đáng yêu ngộ nghĩnh mô phỏng hình dáng của động vật, alien thậm chí cả... đồ ăn được gọi là Forms. Bạn sẽ tùy biến vẻ ngoài của chúng sao cho phù hợp với cá tính của mình và bước đấu trường để \"lăn lộn\", tấn công các đối thủ khác.</p><p>Game mang đến nhiều chế độ chơi thú vị bao gồm: Deathmatch, Team Deathmatch hay Form Ball - chế độ đá bóng - ghi bàn tương tự như game <em>Rocket League. Deformers</em> bắt đầu Open Beta từ 1.4 đến 8 giờ sáng ngày 3.4, game dự kiến sẽ ra mắt ngay trong tháng 4 này với giá bán 29,99 USD (gần 700k) trên các hệ máy PC, PS4 và Xbox One.</p><p>Ready at Dawn là hãng phát triển độc lập có trụ sở tại Irvine, California, Hoa Kỳ, tập hợp những \"cựu binh\" của Naughty Dog và Blizzard Entertainment. Là cha đẻ của các tựa game nổi tiếng như: <em>Daxter, God of War: Chains of Olympus, God of War: Ghost of Sparta</em> hay <em>The Order: 1886</em>.</p><p>Cùng chiêm ngưỡng trailer và một số hình ảnh của game</p><p><em>https://youtu.be/uyn5Y-p2e30</em></p><p></p><p></p><p></p><p></p>',0,0,1,12,'12','deformers,the order 1886,open beta,online game,action','LamGame Team',1,'/storage/blog/blog_49_thumb.jpg','vi',1,1,'Deformers - Game online từ The Order 1886 Open Beta | LamGame','Deformers - Game online từ The Order: 1886 chính thức ra mắt bản Open BetaMới đây, hãng phát triển độc lập Ready at Dawn đã chính thức mở Open Beta cho tựa game online mới nhất của hãng mang tên...','deformers,the order 1886,open beta,online game,action','2025-09-05 23:59:53','2025-09-05 14:44:53','2025-09-05 14:44:53',NULL),(50,'Top game online phong cách hoạt hình độc đáo','top-game-online-phong-cach-hoat-hinh-doc-dao','Tổng hợp những game online mang phong cách hoạt hình độc đáoKhông phải mọi game oline hot đều có đồ họa mang phong cách siêu thực, dưới đây là những cái tên sẽ khiến bạn mê mẫn với phong cách độc...','<h1 id=\"tổng-hợp-những-game-online-mang-phong-cách-hoạt-hình-độc-đáo\"><strong>Tổng hợp những game online mang phong cách hoạt hình độc đáo</strong></h1><h1 id=\"không-phải-mọi-game-oline-hot-đều-có-đồ-họa-mang-phong-cách-siêu-thực-dưới-đây-là-những-cái-tên-sẽ-khiến-bạn-mê-mẫn-với-phong-cách-độc-đáo\">Không phải mọi game oline hot đều có đồ họa mang phong cách siêu thực, dưới đây là những cái tên sẽ khiến bạn mê mẫn với phong cách độc đáo</h1><ol type=\"1\"><li><blockquote><p><strong>PixArk</strong></p></blockquote></li></ol><p>Cách đây kông lâu, Snail Games cho ra mắt tựa game online PixArk với phong cách kết hợp giữa Minecraft và Ark: Survival Evolved. Và mới đây họ lại tung ra những hình ảnh đầu tiên của game trên toàn thế giới. Và PixArk đã thể hiện được sức lôi cuốn đến kỳ lạ. Từ tựa game mang nặng tính trải nghiệm thực tế đầy khó khăn và bạo lực nay đã được lột xác hoàn toàn thành thế giới hoạt hình tươi sáng phù hợp với nhiều đối tượng game thủ.</p><p></p><p>Cũng giống như Ark: Survival Evolved, PixArk cho phép người chơi khám phá cả trên trời, dưới đất và cả dưới nước. Công việc chính của bạn sẽ là thu thập các tài nguyên, chế tạo đồ đạc, sinh tồn, xây dựng nhà cửa, nghiên cứu công nghệ, tất nhiên phần thú vị nhất là săn, nuôi khủng long.</p><p>Đây thật sự là một tựa game hết sức thú vị cho những game thủ yêu thích MineCraft.</p><p></p><p>Theo công bố của Snail Games thì PixArk sẽ mở cửa trên toàn thế giới ngay trong nửa đầu năm 2017 này, có nghĩa là game thủ Việt cũng có thể tham gia chơi một cách dễ dàng. Thêm một chi tiết đáng chú ý nữa là game này sẽ kinh doanh kiểu miễn phí giờ chơi.</p><ol start=\"2\" type=\"1\"><li><blockquote><p><strong>Dauntless</strong></p></blockquote></li></ol><p>Sau một thời gian úp mở lôi kéo sự chú ý của hội yêu game trên toàn thế giới thì mới đây dự án game online bom tấn Dauntless đã bắt đầu cho phép mọi người đăng ký chơi thử giai đoạn alpha test.</p><p>Dauntless bước đầu đã gây ấn tượng mạnh với nền tảng đồ hoạ ấn tượng cùng gameplay đánh đấm chất lừ và nhanh chóng trở thành một trong những game online được mong chờ nhất trên thế giới từ cuối năm 2016 tới nay.</p><p>Thế giới trong Dauntless là đống \'đổ nát\' sau khi trái đất gặp thảm hoạ tự nhiên khiến cho các lục địa bị tách rời và tạo ra cả loạt những vùng đất huyền bí chưa được khám phá, tồn tại như những đảo lớn giữa đại dương. Game thủ sẽ vào vai những \"Slayers\" với mục đích bảo vệ loài người khỏi kẻ đi săn Behemoth.</p><p></p><p>Về phần kẻ địch chính Behemoth, chúng là những chủng loài mới xuất hiện và chiếm lĩnh những vùng đất không có con người với sức mạnh vướt trội, cùng khả năng phá hoại khủng khiếp. Các slayer sẽ phải tiêu diệt toàn bộ bọn quái vật này trước khi chúng kịp lớn mạnh, đồng thời khai phá lại các vùng đất cho con người sinh sống.</p><ol start=\"3\" type=\"1\"><li><blockquote><p><strong>Record of Lodoss War Online</strong></p></blockquote></li></ol><p>Theo thông tin mới nhận thì tựa game online đình đám Record of Lodoss War Online, một game nhập vai 2,5D đúng chuẩn \'cổ điển\' mới đây đã ấn định mở cửa vào ngày 6/4 tại thị trường Hàn Quốc. Đây là trò chơi do L&K Logic Korea phát triển với nội dung dựa trên bộ manga kinh điển cùng tên, vốn rất nổi tiếng vào những năm 80 của thế kỷ trước.</p><p></p><p>Cũng giống như trong truyện, Record of Lodoss War Online kể về cuộc phiêu lưu của một chàng trai trẻ tên là Parn - con trai của một kỵ sỹ hết thời, bị tước danh hiệu. Từng bước trong cuộc hành trình của cậu là đi tìm hiểu xem chuyện gì đã xảy ra với cha mình và tìm cách phục hồi lại danh dự cho dòng họ. Từ đây, nhân vật chính của chúng ta gặp rất nhiều người bao gồm cả đồng bạn lẫn tử địch và rồi tìm ra rất nhiều bí mật ẩn giấu.</p><p></p><p>Về cơ bản thì lối chơi của Record of Lodoss War Online đi theo hướng nhập vai truyền thống, game thủ sẽ gặp các NPC để nhận nhiệm vụ rồi đi thực hiện. Tuỳ theo cấp độ mà các yêu cầu sẽ khó - dễ khác nhau, mọi thứ đều có hướng dẫn tương đối rõ ràng và chỉ đường cụ thể. Tất nhiên trò chơi còn có nhiều tính năng khác nữa cho game thủ khám phá trong quá trình chinh phục thế giới ảo.</p><ol start=\"4\" type=\"1\"><li><blockquote><p><strong>Lost Saga</strong></p></blockquote></li></ol><p>Khá bất ngờ khi hãng PlayOne Asia đã công bố sẽ phát hành tựa game online hành động đối kháng cực hot là Lost Saga tại thị trường Đông Nam Á với server đặt tại Singapore và ngôn ngữ tiếng Anh, khá thuận lợi cho game thủ Việt Nam vào chơi. Trò chơi dự kiến sẽ thử nghiệm vào ngày 10/5 tới.</p><p></p><p>Lost Saga là một tựa game online đối kháng 3D với lối đánh theo màn hình ngang. Lost Saga cung cấp cho người chơi tới hàng chục lớp nhân vật (tùy theo từng phiên bản của Bắc Mỹ hay Hàn Quốc mà số lượng các lớp nhân vật có thể khác nhau) để game thủ có thể thay đổi theo ý thích. Các lớp nhân vật này sẽ được xây dựng dựa trên các đặc điểm cơ bản như đánh gần (Shadow Assasin, Iron Knight...), đánh xa (Space Soldier, Treasure Hunter...), sử dụng phép thuật (Cyber Medic, Fire Mage...) và các lớp nhân vật có lối đánh đặc biệt (Crazy Miner, Captain Hook...). Bên cạnh đó, game có nhiều chế độ chơi có thể cho phép 16 người tham gia cùng lúc.</p><p></p><p>Mỗi lớp nhân vật sẽ sở hữu những skill, combo riêng biệt của mình. Và tất nhiên, với những lớp nhân vật có lối đánh độc (như Crazy Miner có khả năng đặt bom) thì người chơi lại cần phải có cách điều khiển sao cho thật hợp lý để có thể tung ra được những combo skill chính xác để hạ gục kẻ thù. Đây cũng là đặc điểm thường thấy ở những tựa game đối kháng nói chung.</p>',0,0,1,12,'12','anime style games,cartoon games,online games,asian mmo','LamGame Team',1,'/storage/blog/blog_50_thumb.jpg','vi',1,1,'Top game online phong cách hoạt hình độc đáo | LamGame','Tổng hợp những game online mang phong cách hoạt hình độc đáoKhông phải mọi game oline hot đều có đồ họa mang phong cách siêu thực, dưới đây là những cái tên sẽ khiến bạn mê mẫn với phong cách độc...','anime style games,cartoon games,online games,asian mmo','2025-09-06 00:14:55','2025-09-05 14:44:55','2025-09-05 14:44:55',NULL),(51,'Riot hé lộ tướng thứ 135 của Liên Minh Huyền Thoại','riot-he-lo-tuong-thu-135-cua-lien-minh-huyen-thoai','Riot chính thức hé lộ chân dung vị tướng thứ 135 của Liên Minh Huyền ThoạiLMHT, tướng thứ 135, phoenix, Riot games, Vastaya, Harpyhttps://youtu.be/z-wNGWNgzPkRiot Games vừa mới chính thức công bố...','<p><strong>Riot chính thức hé lộ chân dung vị tướng thứ 135 của Liên Minh Huyền Thoại</strong></p><p>LMHT, tướng thứ 135, phoenix, Riot games, Vastaya, Harpy</p><p><a href=\"https://youtu.be/z-wNGWNgzPk\"><span class=\"underline\">https://youtu.be/z-wNGWNgzPk</span></a></p><p>Riot Games vừa mới chính thức công bố những hình ảnh đầu tiên của vị tướng thứ 135 của Liên Minh Huyền Thoại. Đúng như dự đoán, vị tướng mới là một đôi xạ thủ.</p><p>Đoạn teaser cho chúng ta thấy rõ chân dung của vị tướng thứ 135. Theo Riot, tướng mới sẽ có tên Vastaya và theo như Riot Games chia sẻ thì đây là một sinh vật Chimera (một sinh vật là tập hợp nhiều phần giữa các cá thể khác nhau).</p><p>Việc vị tướng thứ 135 của Liên Minh Huyền Thoại là một sinh vật sở hữu 2 thực thể nam và nữ là điều được dự đoán từ trước từ những thông tin rò rỉ. Cụ thể theo thông tin đã được hé lộ, Vastaya là sự kết hợp giữa Harpy và Phoenix.</p><p></p><p>Được biết Harpy là quái vật mình người cánh chim trong thần thoại. Chúng có bản tính tham lam, thường đánh cắp đồ ăn của con người. Thần Zeus thường sử dụng Harpy làm tay sai đi lấy cắp những đồ vật trong nhân gian về cho ngài. Vì thế, khi không tìm thấy một món đồ nào, người ta thường đổ cho là tại lũ Harpy đã đánh cắp.</p><p>Còn Phoenix là phượng hoàng, một loài chim huyền thoại cao quý. Có lẽ Riot muốn tạo nên một thực thể với 2 mặt đối lập hoàn toàn nhưng không diệt trừ mà hỗ trợ cho nhau. Theo tiết lộ của Riot Games, Harpy là nữ và Phoenix là đàn ông. Trong đó, vai trò của Harpy là hỗ trợ và Phoenix là xạ thủ chủ lực.</p><p></p>',0,0,1,12,'12','league of legends,champion 135,riot games,new champion','LamGame Team',1,'/storage/blog/blog_51_thumb.jpg','vi',1,1,'Riot hé lộ tướng thứ 135 của Liên Minh Huyền Thoại | LamGame','Riot chính thức hé lộ chân dung vị tướng thứ 135 của Liên Minh Huyền ThoạiLMHT, tướng thứ 135, phoenix, Riot games, Vastaya, Harpyhttps://youtu.be/z-wNGWNgzPkRiot Games vừa mới chính thức công bố...','league of legends,champion 135,riot games,new champion','2025-09-06 00:30:01','2025-09-05 14:45:01','2025-09-05 14:45:01',NULL),(52,'Top 5 game FPS miễn phí tuyệt hay trên Android','top-5-game-fps-mien-phi-tuyet-hay-tren-android','Điểm danh 5 tựa game FPS tuyệt hay và miễn phí cho AndroidNhững tín đồ Android yêu thích thể loại bắn súng hẳn sẽ cảm thấy thích thú với 5 tựa game dưới đây.Crisis ActionCrisis Action được xây dựng...','<h1 id=\"điểm-danh-5-tựa-game-fps-tuyệt-hay-và-miễn-phí-cho-android\"><strong>Điểm danh 5 tựa game FPS tuyệt hay và miễn phí cho Android</strong></h1><h2 id=\"những-tín-đồ-android-yêu-thích-thể-loại-bắn-súng-hẳn-sẽ-cảm-thấy-thích-thú-với-5-tựa-game-dưới-đây.\"><strong>Những tín đồ Android yêu thích thể loại bắn súng hẳn sẽ cảm thấy thích thú với 5 tựa game dưới đây.</strong></h2><ol type=\"1\"><li><blockquote><p><strong>Crisis Action</strong></p></blockquote></li></ol><p><strong>Crisis Action</strong> được xây dựng trên nền đồ họa 3D tuyệt đỉnh với mang tính chân thực cao. Không chỉ mang tới cho người chơi nhiều map đa dạng bên cạnh những hiệu ứng chiến đấu, cháy nổ trong game cũng được chăm chút vô cùng tỉ mỉ. Hóa thân vào nhân vật, bạn sẽ có cảm giác như đang được chìm đắm trong những trận chiến đầy căng thẳng.</p><p></p><p>Bên cạnh đó, Crisis Action có thể được xem là game bắn súng trên thiết bị di động có nhiều thao tác nhất hiện nay. Người chơi không chỉ điều khiển tâm ngắm, di chuyển thoải mái trong bản đồ mà còn có thể nhảy, ngồi hay nấp... Đây đều là những hành động chúng ta chỉ thấy trên những tựa game bắn súng trên PC, hiếm có sản phẩm dành cho di động nào có được những điều này.</p><p></p><ol start=\"2\" type=\"1\"><li><blockquote><p><strong>N.O.V.A. Legacy</strong></p></blockquote></li></ol><p><strong>N.O.V.A. Legacy</strong> là trò chơi có bối cảnh tương lai giả tưởng vô cùng hấp dẫn về một cuộc chiến liên ngân hà giữa loài người và quái vật không gian. Trong thời điểm sự cạnh tranh trong cuộc đua FPS đang tăng cao, Gameloft đã khôn ngoan tung ra phiên bản \"làm lại\" của phần N.O.V.A. đầu tiên với nhiều cải tiến vượt trội về đồ họa cũng như lối chơi so với người tiền nhiệm.</p><p></p><p>Theo đó, N.O.V.A. Legacy là tựa game bắn súng với góc nhìn thứ nhất trên di động với đồ họa 3D sống động, hứa hẹn một gameplay đa dạng và phần chơi mạng tuyệt vời hơn đa phần các tựa game khác.</p><p></p><p>Với phong cách FPS đặc trưng, N.O.V.A. Legacy mang người chơi đến những phút đấu súng nghẹt thở và xen kẽ vài phút thư giãn khi người chơi có thể trực tiếp chiến đấu trên những cỗ máy robot khổng lồ, sử dụng vũ khí hạng nặng trên xe hay nhiệm vụ hỗ trợ bắn tỉa cho đồng đội. Thật vậy, cũng giống phiên bản đầu tiên, phần chơi PvE của game vẫn được chăm chút khá tỉ mỉ đi kèm cốt truyện tuyệt vời.</p><p></p><p>N.O.V.A. Legacy sẽ mang đến 19 nhiệm vụ để chống lại lũ Zombie cùng hệ thống vũ khí phong phú đa dạng, đi kèm những bản đồ rộng lớn kết hợp tính năng đấu mạng PvP gồm 6 người tham gia cùng lúc. Nhờ tất cả những điểm mạnh này mà N.O.V.A. Legacy đang rất được Gameloft đặt nhiều hy vọng để một lần nữa đứng trên đỉnh vinh quang.</p><ol start=\"3\" type=\"1\"><li><blockquote><p><strong>Modern Strike Online</strong></p></blockquote></li></ol><p><strong>Modern Strike Online</strong> là tựa game bắn súng có đồ họa 3D hoành tráng, sắc nét không kém gì một sản phẩm game PC. Game dựa trên ý tưởng kết hợp giũa 2 trò chơi Modern Combat và CS Online.</p><p></p><p>Hòa mình vào bối cảnh trong game, người chơi sẽ có được những trải nghiệm hoàn toàn mới, bạn sẽ được sử dụng lại những loại súng thông dụng như AWM, M4A1,.. với thiết kế hoàn toàn mới, bên cạnh đó là nhiều bản đồ chiến đấu để thoải mái lựa chọn.</p><p></p><ol start=\"4\" type=\"1\"><li><blockquote><p><strong>Bullet Force</strong></p></blockquote></li></ol><p>Bullet Force là một tựa game FPS online dành cho di động, game sử dụng Unity Engine cho đồ đọa của mình. Mặc dù không đạt mức đồ họa lung linh như Modern Combat nhưng bù lại mức 3D tầm trung cho Bullet Force lợi thế với máy cấu hình tầm trung và khả năng kết nối chơi online.</p><p></p><p>Game có nhiều chế độ chơi khác nhau, người chơi có thể chơi 1 mình ở các chế độ như chơi đơn theo độ khó (easy/normal/hard) hay thách đấu Skirmish. Khi chơi đơn bạn sẽ tham gia một bản đồ (sau này là nhiều bản đồ để lựa chọn), sẽ có một số lượng quân máy điều khiển nhất định và bạn sẽ thắng nếu tiêu diệt hết chúng. Ở Skirmish cũng tương tự nhưng chính bạn sẽ quy định số lượng kẻ địch.</p><p></p><p>Với chơi mạng sẽ có 2 chế độ là Team Death Match (đấu đội) và Free-For-All (bắn tự do). Người chơi sẽ thu thập các đồng xu dựa theo số điểm bắn hạ của mình từ đó có thể lên cấp. Phần chơi mạng vận hành với mức đồ họa trung bình tạo cảm giác tương tự như Counter Strike ngày xưa nhưng với thời lượng đầu ngắn hơn.</p><ol start=\"5\" type=\"1\"><li><blockquote><p><strong>Critical Ops</strong></p></blockquote></li></ol><p>Critical Ops đã từng được phát hành cho PC dưới dạng ứng dụng Facebook chơi trên nền web. Theo đó, phiên bản mobile sẽ mang trong mình những đặc điểm tương tự webgame như hai chế độ chơi là Custom và Rank. Bên cạnh đó, <strong>Critical Ops</strong> sẽ chỉ cung cấp 2 map là Amsterdam và Barcelona chứa 2 mode phổ biến là Deathmatch (Đấu đội) và Defuse (Gỡ bom).</p><p></p><p>Tuy không đa dạng về số lượng map và mode nhưng trong một trận đấu,<strong>Critical Ops</strong> cho phép tới 20 game thủ cùng tham gia. Với số lượng người chơi đông như vậy góp phần tạo nên sự đa dạng về chiến thuật, đem lại cảm giác thú vị cho người chơi.</p><p></p>',0,0,1,16,'16','mobile fps,free fps,android fps,shooting games,mobile games','LamGame Team',1,'/storage/blog/blog_52_thumb.jpg','vi',1,1,'Top 5 game FPS miễn phí tuyệt hay trên Android | LamGame','Điểm danh 5 tựa game FPS tuyệt hay và miễn phí cho AndroidNhững tín đồ Android yêu thích thể loại bắn súng hẳn sẽ cảm thấy thích thú với 5 tựa game dưới đây.Crisis ActionCrisis Action được xây dựng...','mobile fps,free fps,android fps,shooting games,mobile games','2025-09-06 00:45:02','2025-09-05 14:45:02','2025-09-05 14:45:02',NULL),(53,'Top 15 game đua xe mobile hấp dẫn nhất','top-15-game-dua-xe-mobile-hap-dan-nhat','15 game đua xe siêu hấp dẫn trên di động dành cho người yêu tốc độandroid,iOS, Asphalt Xtreme, CSR Racing 2, Dirt Trackin, Drag Racing, Grand Prix Story, GT Racing 2Dưới đây là danh sách những game...','<h1 id=\"game-đua-xe-siêu-hấp-dẫn-trên-di-động-dành-cho-người-yêu-tốc-độ\"><strong>15 game đua xe siêu hấp dẫn trên di động dành cho người yêu tốc độ</strong></h1><p>android,iOS, <strong>Asphalt Xtreme, CSR Racing 2, Dirt Trackin, Drag Racing, Grand Prix Story, GT Racing 2</strong></p><h2 id=\"dưới-đây-là-danh-sách-những-game-đua-xe-siêu-hay-trên-cả-ios-và-android-mà-bạn-không-thể-bỏ-qua.\"><strong>Dưới đây là danh sách những game đua xe siêu hay trên cả iOS và Android mà bạn không thể bỏ qua.</strong></h2><p>Đối với những người yêu tốc độ thì những tựa game đua xe là nơi mà họ tha hồ tận hưởng niềm đam mê của mình mà không sợ ảnh hưởng đến ai. Do đó, những game thể loại đua xe luôn được rất nhiều người yêu thích. Với sự phát triển chóng mặt của thị trường smartphone, không khó hiểu khi rất nhiều hãng game đã giới thiệu các siêu phẩm game đua xe cực hay cho những tín đồ di động. Dưới đây là danh sách những game đua xe hay nhất cả trên iOS và Android mà bạn không thể bỏ qua.</p><p><strong>1. Asphalt Xtreme</strong></p><p><strong>Giá: Free</strong></p><p>https://youtu.be/c86kDaYSxFU</p><p>Asphalt Xtreme là phần mới nhất của dòng game đua xe Asphalt nổi tiếng từ Gameloft. Khác với những phiên bản trước, trong bản Xtreme, bạn sẽ được đua xe trên những con đường bụi bặm, băng qua các khu rừng lầy lội,...mục tiêu cuối cùng vẫn là đánh bại các tay đua khác. Game có tổng cộng 5 chế độ, hơn 400 vòng đua, 500 thử thách và 35 loại xe để chọn lựa. Asphalt Xtreme còn có chế độ multiplayer để bạn có thể so tài với người chơi khác trên toàn thế giới.</p><p><strong>2. CSR Racing 2</strong></p><p><strong>Gía: Free</strong></p><p>https://youtu.be/LN2p9xGHmio</p><p>Nói đến game đua xe di động thì CSR Racing là series khá nổi tiếng. Cũng như phần trước, CSR Racing 2 có cách chơi theo dạng drag racing, nghĩa là bạn chỉ chạy theo một đường thẳng, cố gắng chuyển số và sử dụng nitơ một cách nhịp nhàng nhất để chiến thắng đối thủ. Game có khá nhiều tuỳ chọn nâng cấp, cùng nhiều vòng đua, nhiều loại xe để bạn trải nghiệm tốc độ. Với những ai thích cạnh tranh thì có thể chọn tham gia đua trực tuyến với người khác.</p><p><strong>3. Dirt Trackin</strong></p><p><strong>Giá: 2.99 USD</strong></p><p></p><p>Với phong cách cổ điển từ đồ hoạ, cách điều khiển đến giao diện, Dirt Trackin sẽ mang đến cho người chơi trở về tuổi thơ khi còn trốn mẹ chơi game tại những quán điện tử nhỏ. Điểm đáng khen của game là quy tụ được tên tuổi của những tay đua ngoài đời để bạn sử dụng, cùng với đó là 24 chiếc xe để chọn lựa, 10 vòng đua để thể hiện tài năng. Người chơi có thể chọn cách điều khiển với nút ảo trên màn hình hoặc điều khiển bằng cảm biến chuyển động.</p><p><strong>4. Drag Racing</strong></p><p><strong>Giá: Free</strong></p><p></p><p>Cũng có cách chơi tương tự CSR Racing, tuy Drag Racing có đồ hoạ không ấn tượng như đối thủ, nhưng gameplay chính là thứ giúp nó lấy điểm từ người chơi. Bạn có thể tuỳ chỉnh chiếc xe gần như theo bất kỳ điểm nào mình muốn. Người chơi sẽ dễ dàng bị cuốn hút bởi tính năng tuỳ chỉnh xe rất hay này. Cũng như CSR, Drag Racing có chế độ chơi trực tuyến để bạn so tài với người khác.</p><p><strong>5. Grand Prix Story</strong></p><p><strong>Giá: 4.99 USD</strong></p><p></p><p>Với đồ hoạ theo dạng pixel đơn giản, Grand Prix Story là tựa game đua xe đến từ nhà phát triển Kairosoft. Đây là game có lối chơi pha trộn thể loại mô phỏng quản lý, bạn sẽ vào vai chủ đội đua, huấn luyện các tay đua, tìm kiếm nhà tài trợ và giành chiến thắng nhiều cuộc thi nhất có thể.</p><p><strong>6. GT Racing 2</strong></p><p><strong>Giá: Free</strong></p><p>https://youtu.be/79kup3-bnFo</p><p>GT Racing 2 cũng là một game đến từ nhà phát triển game di động nổi tiếng là Gameloft. Tuy không được nhiều sự chú ý như “người anh em\" Asphalt, nhưng chất lượng của GT Racing cũng rất tốt. Bản GT Racing 2 sẽ mang đến cho người chơi 71 chiếc xe từ hơn 30 nhà sản xuất ngoài đời thật, 13 vòng đua đẹp mắt và hơn 1400 sự kiện để tham gia. Tất nhiên, bạn vẫn có thể tranh tài online với người chơi khác.</p><p><strong>7. Hill Climb Racing 2</strong></p><p><strong>Giá: Free</strong></p><p>https://youtu.be/FOTiua3b-iU</p><p>Hill Climb Racing 2 là một trong số những game thể loại đua xe vừa ra mắt trên Android trong thời gian gần đây. Game có đồ hoạ đơn giản, nhiều màu sắc theo lối hoạt hình và background rất đẹp. Trong Hill Climb Racing 2, bạn sẽ đua cùng đối thủ lên xuống những con đồi, nghe có vẻ đơn giản nhưng thật sự khi chơi thì không như vậy. Bạn cũng có thể tuỳ chỉnh phương tiện của mình để dễ dàng chiến thắng hơn, và phù hợp với những màn chơi cụ thể. Game khá nhẹ và chơi ổn trên những máy có “tuổi đời\" cao.</p><p><strong>8. Horizon Chase</strong></p><p><strong>Giá: Free/ 2.99 USD</strong></p><p><a href=\"https://youtu.be/HQDVSRmhiP0\"><span class=\"underline\">https://youtu.be/HQDVSRmhiP0</span></a></p><p>Horizon Chase là một trong những game đua xe hay nhất 2016, game có đồ hoạ mang phong cách retro nhưng vẫn có nét hiện đại và độc đáo riêng. Số lượng đường đua trong game rất nhiều, và những cung đường này được thiết kế rất đẹp. Với phiên bản miễn phí, bạn sẽ được thử qua vài vòng đua demo. Nếu thích bạn có thể trả 2.99 để tận hưởng toàn bộ game.</p><p><strong>9. Moto X3M Bike Race</strong></p><p><strong>Giá: Free/ 9.99 USD</strong></p><p>https://youtu.be/7mf6DeNMOXE</p><p></p><p>Tuy có khá nhiều game đua xe trên di động, nhưng lại số lượng game đua moto lại tương đối ít và trong số đó, những game có chất lượng còn hiếm hoi hơn. May thay, Moto X3M Bike Race là một trong những game đề tài đua moto có chất lượng cao mà bạn có thể thử qua. Game có đồ hoạ 2D, với hơn 75 màn chơi và rất nhiều xe để mở khoá. Trong khi thể hiện tài năng điều khiển và vượt chướng ngại vật, bạn có thể biểu diễn những kỹ năng khó để lấy thêm điểm.</p><p><strong>10. Nitro Nation Online</strong></p><p><strong>Giá: Free</strong></p><p>https://youtu.be/E5LjFiXeCiU</p><p>Nitro Nation Online cũng là một game dạng drag racing, nhưng điều đáng nói là tựa game này vừa có đồ hoạ tuyệt vời như CSR Racing và vừa cho phép người chơi tinh chỉnh nhiều thứ như Drag Racing, một sự phối hợp tuyệt vời. Nitro Nation Online được phát hành miễn phí và không hề bị giới hạn lượt chơi, hay giới hạn thời gian nâng cấp như nhiều game freemium khác.</p><p><strong>11. Racing Fever</strong></p><p><strong>Giá: Free</strong></p><p></p><p>Nhìn chung, Racing Fever không phải là một game quá xuất sắc, nhưng nếu bạn đang muốn một game đua xe nhẹ nhàng để giải trí trong thời gian ngắn thì Racing Fever có thể làm bạn hài lòng. Game có tổng cộng 4 môi trường đua, 4 chế độ chơi, bảng xếp hạng, và cho phép người chơi tuỳ chỉnh rất nhiều thú trên xe, ngoài ra, khi chơi bạn còn có thể làm chậm thời gia để bo cua mượt hơn.</p><p><strong>12. Real Drift Car Racing</strong></p><p><strong>Giá: Free/0.99 USD</strong></p><p>https://youtu.be/Olm9aAV3i5M</p><p>Đúng như tên, Real Drift Car Racing sẽ tập trung vào những màn drift đẹp mắt. Bạn sẽ điều khiển xe vào những vòng cua và thực hiện những pha drift để vượt điểm đối thủ. Real Drift Car Racing cũng có những tính năng cơ bản của một game đua xe thông thường như mở khoá xe, chế độ chơi sự nghiệp, nhiều đường đua khác nha và những tuỳ chỉnh xe hấp dẫn. Game có phiên bản miễn phí và tính phí, bản tính phí vẫn có những tuỳ chọn in-app purchase nhưng sẽ loại bỏ quảng cáo và có những món đồ “độc\" so với bản miễn phí.</p><p><strong>13. Real Racing 3</strong></p><p><strong>Giá: Free</strong></p><p>https://youtu.be/nEmc53kZPMY</p><p>Real Racing 3 là game đua xe mang đến trải nghiệm giống thực tế nhất trong danh sách này, đồng thời cũng là tựa game khá có tiếng trong cộng đồng những người thích game đua xe di động. Game có đến hơn 100 chiếc xe có thật ngoài đời, nhiều chế độ chơi bao gồm cả chơi trực tuyến, thậm chí ngay cả vòng đua trong game cũng được “sao lưu\" từ đời thật.</p><p><strong>14. Riptide GP Renegade</strong></p><p><strong>Giá: 2.99 USD</strong></p><p>https://youtu.be/ftcchLle9G8</p><p>Cũng thuộc thể loại đua xe, chính xác hơn là đua jet ski (moto nước) Riptide GP: Renegade có đồ hoạ vô cùng đep mắt. Game có hệt thống nâng cấp phương tiện khá hấp dẫn, bạn càng chơi nhiều thì càng có khả năng tạo cho mình chiếc jet ski nhanh hơn để đối đầu với nững đối thủ khó nhằn về sau. Game cũng có hệ thống multiplayer, cho phép người chơi so tài với nhau. Thậm chí, bạn còn có thể chơi dạng split-screen (chia đôi màn hình) nếu có gamepad.</p><p><strong>15. Traffic Rider</strong></p><p><strong>Download: Free</strong></p><p>Traffhttps://youtu.be/0FimuzxUiQY</p><p>Đây không hẳn là một game đua xe mà thiên về thể loại endless runner hơn, tuy nhiên, nó vẫn tập trung vào những chiếc xe tốc độ cao và xứng đáng có một chỗ trong danh sách này. Game có góc nhìn người thứ nhất độc đáo, giúp bạn cảm nhận tốc độ một cách chân thật nhất. Bạn sẽ điều khiển nhân vật chạy giữa làn xe dày đặc, thu thập tiền và hoàn thành những chỉ tiêu được giao để qua màn.</p>',0,0,1,16,'16','racing games,mobile racing,car games,speed games,mobile games','LamGame Team',1,'/storage/blog/blog_53_thumb.jpg','vi',1,1,'Top 15 game đua xe mobile hấp dẫn nhất | LamGame','15 game đua xe siêu hấp dẫn trên di động dành cho người yêu tốc độandroid,iOS, Asphalt Xtreme, CSR Racing 2, Dirt Trackin, Drag Racing, Grand Prix Story, GT Racing 2Dưới đây là danh sách những game...','racing games,mobile racing,car games,speed games,mobile games','2025-09-06 01:00:09','2025-09-05 14:45:09','2025-09-05 14:45:09',NULL),(54,'Call of Duty Mobile - Game từ cha đẻ Candy Crush','call-of-duty-mobile-game-tu-cha-de-candy-crush','Game di động mới thuộc series Call of Duty sẽ được phát triển bởi cha đẻ của Candy CrushStudio King sẽ đảm nhận nhiệm vụ mang thương hiệu Call of Duty của Activision lên màn hình cảm ứng.Phải nói...','<h1 id=\"game-di-động-mới-thuộc-series-call-of-duty-sẽ-được-phát-triển-bởi-cha-đẻ-của-candy-crush\"><strong>Game di động mới thuộc series Call of Duty sẽ được phát triển bởi cha đẻ của Candy Crush</strong></h1><p>Studio King sẽ đảm nhận nhiệm vụ mang thương hiệu Call of Duty của Activision lên màn hình cảm ứng.</p><p>Phải nói rằng, Call of Duty là một series game thuộc thể loại bắn súng từ góc nhìn thứ nhất (FPS) và góc nhìn thứ ba (TPS). Tuy nhiên, cái tên này đã từ lâu được biết tới nhiều nhất với các phiên bản dùng cho PC và sau đó được mở rộng ra các hệ máy console và máy chơi game cầm tay. Một vài bản mở rộng cũng được phát hành bên cạnh các tựa game chính.</p><p></p><p>Bên cạnh đó, trò chơi cũng đã có các phiên bản trên di động nhưng không thành công như mong đợi như Call of Duty: Strike Team, Call of Duty: Black Ops Zombies và Call of Duty: Heroes.</p><p></p><p>Không dừng lại ở đấy, trong thời gian tới sẽ tiếp tục được thưởng thức một phiên bản di động nữa dựa trên thương hiệu Call of Duty đình đám của Activision. Lần này, King - cha đẻ của Candy Crusd Saga và Bubble Witch Saga sẽ đảm nhận nhiệm vụ đưa Call of Duty quay trở lại với màn hình cảm ứng.</p><p>King cho biết, team phát triển Call of Duty mới được thành lập nhằm mục đích <em>\"cải thiện giao diện điều khiển trực quan tốt nhất cho các người hâm mộ, đồng thời tạo ra một sản phẩm đột phá trên di động cho thể loại này.\"</em></p><p></p><p><em>\"Cách tiếp cận và tham vọng của chúng tôi là mang lại sự mới mẻ, tương tác xã hội và khả năng tiếp cận cao, đồng thời cung cấp trải nghiệm trò chơi hoàn hảo nhất\",</em> nhà phát triển cho biết. Đây chỉ mới là những thông tin ban đầu của dự án Call of Duty.</p><p>Activision Blizzard đã mua lại King Digital Entertainment trong hợp đồng trị giá 5,9 tỷ đô la vào năm 2015. <em>\"Kết hợp một trong những cộng đồng game thủ di động lớn nhất với thương hiệu hàng đầu của Activision Blizzard để tạo ra những cơ hội tiềm năng để phát triển và quảng bá chéo nội dung cho nhiều đối tượng người chơi\"</em>.</p><p></p><p>Liệu King có thể mang lại một bom tấn trên di động cho Call of Duty không?, dù sao thì việc tiếp tục có thêm một sản phẩm trên nền tảng di động đã là tin vui cho cộng đồng game thủ nói chung và fan hâm mộ của Call of Duty nói riêng.</p>',0,0,1,16,'16','call of duty mobile,king digital,mobile fps,cod mobile','LamGame Team',1,'/storage/blog/blog_54_thumb.jpg','vi',1,1,'Call of Duty Mobile - Game từ cha đẻ Candy Crush | LamGame','Game di động mới thuộc series Call of Duty sẽ được phát triển bởi cha đẻ của Candy CrushStudio King sẽ đảm nhận nhiệm vụ mang thương hiệu Call of Duty của Activision lên màn hình cảm ứng.Phải nói...','call of duty mobile,king digital,mobile fps,cod mobile','2025-09-06 01:15:12','2025-09-05 14:45:12','2025-09-05 14:45:12',NULL),(55,'Modern Combat Versus - FPS đấu mạng từ Gameloft','modern-combat-versus-fps-dau-mang-tu-gameloft','Với động thái Gameloft hé lộ loạt hình ảnh mới và đoạn trailer quảng bá gần đây, chính vì thế mà những game thủ đang săn đón Modern Combat Versus từng ngày rất có thể không phải đợi quá lâu nữa để...','<p>Với động thái Gameloft hé lộ loạt hình ảnh mới và đoạn trailer quảng bá gần đây, chính vì thế mà những game thủ đang săn đón Modern Combat Versus từng ngày rất có thể không phải đợi quá lâu nữa để được sờ tận tay siêu phẩm này.</p><p></p><p>Trong phần tiếp theo của series bắn súng đình đám này, thay vì song song hai mảng Single và Multipplayer thì Modern Combat Versus sẽ chỉ tập trung hoàn toàn vào đấu Online. Theo đó, người chơi sẽ phải vận dụng hết khả năng cũng như sự khéo léo của mình để giành chiến thắng trước những người chơi khác.</p><p>Trailer Game</p><p>Một khi ra mắt, Modern Combat Versus sẽ có 12 Agent để bạn lựa chọn chiến đấu dựa trên 4 lớp nhân vật chính bao gồm Attacker, Defender, Assassin và Specialist. Trong đó, Attacker có khả năng tấn công vô cùng mạnh mẽ; Defender lại hỗ trợ cho đồng đội khả năng giảm thiểu thiệt hại, sức khỏe tốt và phòng thủ vững vàng. Kế đến là Assassin có sức sát thương lớn nhưng máu giấy dễ bị tiêu diệt. Cuối cùng, Specialist có nhiều khả năng và cách sử dụng vũ khí độc đáo tùy theo từng tình huống chiến đấu.</p><p></p><p></p><p>Mỗi nhân vật trong Modern Combat Versus đều có thể được tùy chỉnh về vũ khí, skin, biểu tượng khi hạ gục và khả năng nâng cấp. Người chơi sẽ cần Core Charges (linh kiện đặc biệt) để kích hoạt các năng lực đặc biệt của nhân vật mà có thể được mua lại thông qua trải nghiệm gameplay. 10 Core Charges có thể được sử dụng cùng một lúc và mỗi năng lực có thể yêu cầu số lượng Core Charges khác nhau.</p><p></p><p>Trong giai đoạn khởi động phiên bản soft-launch, Modern Combat Versus sẽ có chế độ chiến đấu 4vs4, nơi mỗi đội sẽ hợp sức để kiểm soát các khu vực quan trong trên bản đồ. Hơn thế nữa, trò chơi còn có sẵn 5 bản đồ rộng lớn với nhiều địa điểm độc đáo gọi là Apex, Sandstorm, Port, Blackrock, và Slums.</p><p></p><p></p><p>Giống như những phần <a href=\"http://gamek.vn/\"><span class=\"underline\">game</span></a> trước, hệ thống điều khiển của Modern Combat Versus đặt phím bấm bên trái để di chuyển nhân vật và chạy nước rút bằng cách giữ về một hướng, trong khi đó phía bên phải là dành riêng cho việc quan sát xung quanh và ngắm bắn bằng cách \"double tap\". Năng lực đặc biệt của các nhân vật sẽ được kích hoạt bằng một phím ở giữa phía dưới màn hình cảm ứng.</p><p></p><p></p><p>Trước đây, nhiều người đã thực sự thất vọng và lên án mạnh mẽ về vấn đề \"trả tiền\" trong Modern Combat 5 trước đây. Chính vì thế, Gameloft đã hứa hẹn sẽ không có việc \"trả tiền\" để giành chiến thắng cũng như hệ thống năng lượng dù cho Modern Combat Versus là một game Free-to-Play.</p>',0,0,1,16,'16','modern combat versus,gameloft,mobile fps,multiplayer fps','LamGame Team',1,'/storage/blog/blog_55_thumb.jpg','vi',1,1,'Modern Combat Versus - FPS đấu mạng từ Gameloft | LamGame','Với động thái Gameloft hé lộ loạt hình ảnh mới và đoạn trailer quảng bá gần đây, chính vì thế mà những game thủ đang săn đón Modern Combat Versus từng ngày rất có thể không phải đợi quá lâu nữa để...','modern combat versus,gameloft,mobile fps,multiplayer fps','2025-09-06 01:30:15','2025-09-05 14:45:15','2025-09-05 14:45:15',NULL),(56,'Transformers Mobile - Đồ họa đẹp nhất từ trước đến nay','transformers-mobile-do-hoa-dep-nhat-tu-truoc-den-nay','Mê mẩn với phiên bản Transformers trên di động có đồ họa đẹp nhất từ trước đến nayTransformers: Forged to Fight phiên bản toàn cầu đã chính thức ra mắt sau bao ngày trông đợi của game thủ trên toàn...','<h1 id=\"mê-mẩn-với-phiên-bản-transformers-trên-di-động-có-đồ-họa-đẹp-nhất-từ-trước-đến-nay\"><strong>Mê mẩn với phiên bản Transformers trên di động có đồ họa đẹp nhất từ trước đến nay</strong></h1><p>Transformers: Forged to Fight phiên bản toàn cầu đã chính thức ra mắt sau bao ngày trông đợi của game thủ trên toàn thế giới.</p><p>Transformers tuy chưa bao giờ là một tác phẩm gây ấn tượng mạnh cho giới phê bình phim nhưng nó là một trong những đề tài thú vị để các hãng phát triển game nhắm tới. Nói không ngoa, chúng ta từng biết tới những cái tên nổi bật như Transformers Online, Transformers: Earth Wars, Transformers: Devastation...</p><p></p><p>Vào cuối năm 2016, hãng phát triển Kabam đã hé lộ những thông tin ban đầu về sản phẩm mà hãng đang thai nghén mang tên Transformers: Forged to Fight. Đây là một game mobile mang phong cách hành động, chiến đấu đối kháng. Điều thú vị nhất mà trò chơi mang tới là một hệ thống nhân vật quen thuộc, giữ nguyên tạo hình, tỷ lệ chuẩn như nguyên tác trên phim.</p><p>Transformers: Forged to Fight trailer</p><p>Sau hơn 3 tháng im hơi lặng tiếng, Transformers: Forged to Fight tiếp tục quay trở lại tại sự kiện Toy Fair 2017 diễn ra tại New York, Mỹ. Tại đây, hai công ty Kabam và Hasbro đã công bố trailer mới nhất của trò chơi khiến cộng đồng game thủ và những fan hâm mộ đứng ngồi không yên. Game mobile này đã ra mắt bản \"soft launched\" tại kho ứng dụng App Store Đan Mạch cùng thời điểm đó và mới đây thì phiên bản toàn cầu cũng đã chính thức ra mắt.</p><p>Cốt truyện của game căn cứ dựa trên sự va chạm thực tại khác nhau, nơi mà hai phe cánh Autobots và Decepticons đối đầu trực tiếp trong cuộc chiến một mất một còn. Điểm đặc biệt về game là người chơi có thể chiến đấu với những người khác hoặc thậm chí xây dựng các liên minh vững mạnh cho riêng mình.</p><p></p><p>Mọi hành động của các chiến binh robot đều được tái hiện một cách mượt mà, hiệu ứng cháy nổ liên tục xuất hiện sau từng đợt máy móc va chạm đầy máu lửa. Đó là còn chưa kể tới việc các chi tiết nhỏ cũng được chăm chút tỉ mỉ như lớp sơn bong tróc, cửa kính vỡ vụn... Trên thực tế, để làm được điều này thì Kabam đã sử dụng một công nghệ đồ họa 3D đỉnh cao, chuyên dành cho việc thiết kế hình ảnh trên Xbox 360 hay PlayStation 3.</p><p></p><p>Bên cạnh đó, phong cách chiến đấu của Transformers: Forged to Fight không còn bị giới hạn bởi màn hình ngang mà thay vào đó là áp dụng hệ thống camera xoay 360 độ. Chính vì vậy, người chơi có thể tấn công kẻ địch từ bốn hướng và ngược lại những đòn đáp trả cũng tương đối là khó chơi đấy chứ. Hệ thống đặc biệt này sẽ nâng tầm những pha combat chất lượng hơn nhiều, đa dạng và rất khó để đoán biết trước.</p><p></p><p>Cơ chế điều khiển trong Transformers: Forged to Fight không hiển thị theo dạng phím bấm mà hành động sẽ thông qua thao tác chạm vào 2 nửa màn hình. Ở phía nửa bên phải game thủ có thể nhấp liên tục để đưa ra những đòn tấn công cơ bản, giữ để phá thế khóa của đối phương hoặc kích hoạt kỹ năng. Ngoài ra, bạn cũng có thể vuốt qua lại 2 bên để nhân vật có thể né tránh những đợt tấn công bằng vũ khí của đối phương.</p><p></p><p>Nhìn chung, Transformers: Forged to Fight không chỉ được đánh giá là game mobile có đề tài Transformers đẹp nhất trên di động vào thời điểm hiện tại. Trò chơi cũng được giới chuyên môn đánh giá cao nhờ khả năng sáng tạo đa dạng trong lối chơi, mang tới nhiều trải nghiệm mới lạ cho game thủ.</p>',0,0,1,16,'16','transformers mobile,robot games,action games,mobile games','LamGame Team',1,'/storage/blog/blog_56_thumb.jpg','vi',1,1,'Transformers Mobile - Đồ họa đẹp nhất từ trước đến nay | LamGame','Mê mẩn với phiên bản Transformers trên di động có đồ họa đẹp nhất từ trước đến nayTransformers: Forged to Fight phiên bản toàn cầu đã chính thức ra mắt sau bao ngày trông đợi của game thủ trên toàn...','transformers mobile,robot games,action games,mobile games','2025-09-06 01:45:16','2025-09-05 14:45:16','2025-09-05 14:45:16',NULL),(57,'Xbox Project Scorpio - Thông số khủng vượt PS4 Pro','xbox-project-scorpio-thong-so-khung-vuot-ps4-pro','Hồi E3 2016, Microsoft đã giới thiệu tới cộng đồng game thủ một chiếc máy chơi game dù mới chỉ nằm trên giấy tờ nhưng có sức mạnh chẳng hề thua kém gì so với những cỗ PC chơi game. Tiếc thay Sony đã...','<p>Hồi E3 2016, Microsoft đã giới thiệu tới cộng đồng game thủ một chiếc máy chơi game dù mới chỉ nằm trên giấy tờ nhưng có sức mạnh chẳng hề thua kém gì so với những cỗ PC chơi game. Tiếc thay Sony đã đi trước một bước, tung ra chiếc máy chơi game PS4 Pro với cấu hình được overclock và dành riêng cho nhu cầu chơi game trên màn hình siêu nét độ phân giải 4K.</p><p></p><p>Thế nhưng nói gì thì nói, khi ông trùm đứng phía sau nền tảng x86 và hệ điều hành Windows trên PC đã làm phần cứng và hệ điều hành cho những chiếc máy tính chơi game, chắc chắn cỗ máy sẽ chỉ có thể khỏe mà thôi. Thất bại duy nhất của Xbox One chính là những ngày đầu quá quan tâm tới những nội dung giải trí như show truyền hình hay thể thao, mà quên mất thực tế rằng máy chơi game người ta chỉ mua về để chơi game mà thôi. Và đó chính là lý do Xbox One thất bại trước PS4 của Sony.</p><p>Quay trở lại với Project Scorpio. Mới đây, Microsoft đã chính thức giới thiệu cấu hình của chiếc máy chơi game này, và chỉ cần nhìn vào tấm hình dưới đây, chúng ta có thể nhận ra rằng, đây là một cỗ máy chơi game cực mạnh đội lốt console gia đình, với CPU 8 nhân 2,3 GHz, 12GB RAM và chip GPU xử lý đồ họa riêng biệt:</p><p></p><p>Qua cuộc phỏng vấn mới đây với ông Phil Spencer - phụ trách mảng game của Microsoft thì xem ra nó hứa hẹn sẽ là một cỗ máy đáng để game thủ trông đợi. \"<em>Scorpio mạnh hơn nhiều so với Xbox One gốc khoảng 4 lần rưỡi. Khả năng tính toán lên tới 6 teraflop chắc chắn cũng sẽ có ảnh hưởng tới các tựa game phát triển dành cho Xbox One sau này.</em> \" - ông Spencer phát biểu.</p><p>Dự kiến Scorpio sẽ bắt đầu được bán ra vào năm 2017, vẫn còn khá nhiều thời gian để các kĩ sư của Microsoft tính toán cân bằng giữa các yếu tố quan trọng làm nên một hệ máy chơi game như: Sức mạnh phần cứng, điện năng tiêu thụ, nhiệt lượng tỏa ra, giá thành sản xuất và đó nhiều khả năng là lý do cấu hình Scorpio chưa được tiết lộ ở E3 2016.</p><p></p><p>Điều quan trọng là nếu những gì mà ông Spencer khẳng định ngày hôm nay là chính xác thì Xbox One sẽ khắc phục được nhược điểm lớn nhất trước kình địch PS4 là cấu hình yếu hơn trong khi giá thành vẫn ngang bằng. Cuộc chiến máy chơi game thế hệ thứ 8 vì thế mà cũng sẽ có sự xáo trộn về vị trí chứ không còn là sân chơi của riêng Sony như hiện tại nữa.</p>',0,0,1,20,'20','xbox scorpio,xbox series x,console gaming,microsoft xbox','LamGame Team',1,'/storage/blog/blog_57_thumb.jpg','vi',1,1,'Xbox Project Scorpio - Thông số khủng vượt PS4 Pro | LamGame','Hồi E3 2016, Microsoft đã giới thiệu tới cộng đồng game thủ một chiếc máy chơi game dù mới chỉ nằm trên giấy tờ nhưng có sức mạnh chẳng hề thua kém gì so với những cỗ PC chơi game. Tiếc thay Sony đã...','xbox scorpio,xbox series x,console gaming,microsoft xbox','2025-09-06 02:00:25','2025-09-05 14:45:25','2025-09-05 14:45:25',NULL),(58,'Top game offline phong cách Anime tuyệt đẹp','top-game-offline-phong-cach-anime-tuyet-dep','Power Ping PongPower Ping Pong của hãng Chillingo Ltd là tựa game có lối chơi khá vui nhộn. Với Power Ping Pong, game thủ sẽ được trải nghiệm lối chơi bóng bàn khá là hài hước, vui nhộn, và đầy mới...','<p>Power Ping Pong</p><p>Power Ping Pong của hãng Chillingo Ltd là tựa game có lối chơi khá vui nhộn. Với Power Ping Pong, game thủ sẽ được trải nghiệm lối chơi bóng bàn khá là hài hước, vui nhộn, và đầy mới mẻ.</p><p></p><p>Người chơi sẽ chiến đấu với những nhân vật khác như chú gấu Kung-Fu huyền thoại, và nhiều nhân vật bá đạo khác. Power Ping Pong sẽ rèn luyện tính phản xạ nhanh, và sự tập trung cao độ của bạn. Power Ping Pong có nhiều tính năng hấp dẫn, đang chờ bạn khám phá.</p><p></p><p>Terra Monsters 3</p><p>Terra Monsters 3 thuộc series game phiêu lưu kỳ thú trên Android kể về chuyến hành trình tìm kiếm tự do của những người dân cư ngụ tại xứ sở Afer, nơi luôn tồn tại những mâu thuẫn, xung đột khó giải quyết. Cuộc sống của cư dân Afer từ lâu đã vô cùng hỗn loạn bởi những âm mưu thống trị của những con quái vật Terra Monsters độc ác luôn thường trực đe dọa cuộc sống người dân.</p><p></p><p>Để trả lại tự do cho người dân nơi đây, không ai khác mà chính là bạn sẽ phải tham gia vào chuyến phiêu lưu đầy gian nan và thử thách trong game, bắt và huấn luyện những chú Terra Monster trở thành chiến hữu đáng tin cậy của mình trên hành trình mang lại bình yên cho vùng đất. Hãy cố gắng giải đáp mọi bí ẩn về Terrarium trong game, đồng thời tận dụng tối đa sự hỗ trợ của những Terra Monster trong các cuộc chiến để giành lại tự do. Bên cạnh đó, bạn cũng đừng quên dành chút thời gian khám phá thế giới mở 3D đẹp mắt trong game và cùng các anh hùng giải cứu thế giới khỏi thảm họa.</p><p></p><p>Trong game, người chơi sẽ có cơ hội thu thập và huấn luyện hơn 100 con quái vật 3D khác nhau bao gồm cả những con Terra Monster huyền thoại khét tiếng. Ngoài ra, người chơi còn có cơ hội tham gia các trận chiến PvP độc đáo trên nhiều đấu trường huyền thoại trong các thế giới 3D khác nhau. Hãy cùng tham gia và trải nghiệm một sân chơi chiến lược phỏng theo phong cách Pokemon đình đám.</p><p>Hardest Game Ever 2</p><p>Hardest Game Ever 2 (HGE2) được phát triển bởi Orangenose Studios, có trụ sở tại Singapore và Đài Loan. Studios chuyên phát triển các tựa game mobile giải đố và thử thách IQ, độ nhanh nhạy của bộ não. Những tựa game trước đây của họ như “What’s my IQ?” “Stupidness 3” đều được cộng đồng smartphone ủng hộ nhiệt liệt.</p><p></p><p>Được biết HGE2 được xếp hạng #1 về các game giải đố tại nhiều nước trên thế giới như Canada, Đức, Úc… Trò chơi là một tập hợp những mini-game mà chủ đề chính tập trung vào thử thách phản ứng nhanh nhạy của người chơi và cực kỳ TROLL. Tất cả những mini-game trong HGE2 đều được cân đo đong đếm bằng…mili giây, chỉ một tích tắc là đủ để người chơi đạt đỉnh vinh quang hoặc bị tống giam.</p><p></p><p>Vimala: Defense Warlords</p><p>Vimala: Defense Warlords là một tựa game mang phong cách thủ thành được phát triển bởi Masshive Media đến từ Indonesia. Trong tựa game chiến đấu này là sự kết hợp nhuần nhuyễn giữa hành động nhập vai và đánh theo lượt khá quen thuộc trên thị trường game mobile hiện nay.</p><p></p><p>Vimala: Defense Warlords có sự bổ sung thêm nhiều yếu tố khác cùng với đó là một hệ thống thời tiết thay đổi gameplay khá độc đáo. Trong game, người chơi sẽ xây dựng lại vương quốc Aranya qua nhiều cấp độ chơi, đồng thời thu thập các anh hùng và cả những thiết bị mới để gia tăng thêm sức mạnh.</p><p></p><p>Phong cách nghệ thuật của Vimala: Defense Warlords mang hơi hướng hướng hình ảnh anime đến từ Nhật Bản, với màu sắc tươi sáng và tập trung chú ý đến từng chi tiết nhỏ. Bên cạnh đó, hệ thống âm nhạc trong game là tập hợp của những bản thiên anh hùng ca khá hấp dẫn.</p><p>S.O.L : Stone of Life EX</p><p></p><p>S.O.L : Stone of Life EX lấy bối cảnh là những ngục tối bí hiếm, những thử thách vô hạn tràn ngập trên chiến trường chết chóc. Những thế lực với âm mưu đen tối đang hoành hành. Những khu rừng kinh dị đầy chết chóc và lũ quân máu lạnh. Đến với game bạn sẽ nhập vai là một chiến binh huyền thoại. Tiêu diệt lũ quỷ và bè lũ tay sai bảo vệ cuộc sống vốn dĩ bình yên của nó.</p>',0,0,1,12,'12','anime games,offline games,japanese games,anime style','LamGame Team',1,'/storage/blog/blog_58_thumb.jpg','vi',1,1,'Top game offline phong cách Anime tuyệt đẹp | LamGame','Power Ping PongPower Ping Pong của hãng Chillingo Ltd là tựa game có lối chơi khá vui nhộn. Với Power Ping Pong, game thủ sẽ được trải nghiệm lối chơi bóng bàn khá là hài hước, vui nhộn, và đầy mới...','anime games,offline games,japanese games,anime style','2025-09-06 02:15:27','2025-09-05 14:45:27','2025-09-05 14:45:27',NULL),(59,'Quake Champions - Game FPS miễn phí cấu hình nhẹ','quake-champions-game-fps-mien-phi-cau-hinh-nhe','Sau một thời gian cho phép đăng ký đợt thử nghiệm thì mới đây tựa game online bắn súng cực khủng Quake Champions đã cho mở cửa bản Close Beta vào ngày 6/4. Trong giai đoạn thử nghiệm này, những game...','<p>Sau một thời gian cho phép đăng ký đợt thử nghiệm thì mới đây tựa game online bắn súng cực khủng <strong>Quake Champions</strong> đã cho mở cửa bản Close Beta vào ngày 6/4. Trong giai đoạn thử nghiệm này, những game thủ may mắn sở hữu code test sẽ được tham gia chơi và trải nghiệm nhiều tính năng của game.</p><p>https://youtu.be/-UhHcEiegb8</p><p></p><p>Được biết, Quake Champions là sản phẩm hợp tác của <strong>id Software</strong> và <strong>Bethasda Softworks</strong>, bộ đôi đã từng rất thành công với dòng game <strong>Doom</strong>. Theo nhà phát hành tiết lộ, Quake Champions là tựa game bắn súng góc nhìn thứ nhất với phong cách hành động mạnh mẽ và tốc độ cao. Game được phát hành theo hình thức miễn phí “<strong>free to play</strong>”. Do đó, mọi game thủ đều có thể tham gia trải nghiệm khi game ra mắt chính thức.</p><p>Bên cạnh tin vui về việc được “free to play”, một điểm đặc biệt nữa khiến Quake Champions được lòng game thủ Việt là vì cấu hình đòi hỏi của tựa game này không cao. Với một máy tính tầm trung (thậm chí là yếu), bạn vẫn có thể chơi game thoải mái mà không cần nghĩ ngợi gì. Đương nhiên nếu muốn trải nghiệm game một cách tốt nhất, bạn vẫn nên chuẩn bị cho mình một cỗ máy ổn định và chất lượng.</p><p><strong>Cấu hình đòi hỏi của Quake Champions:</strong></p><p><strong>Cấu hình tối thiểu:</strong></p><p>CPU: Intel Core i3-530 hoặc cao hơn</p><p>HDD: 50GB</p><p>Card màn hình: Intel HD 4600, NVIDIA GeForce 630 (1GB), AMD Radeon R7-240 (1GB)</p><p>Hệ điều hành: Windows 7 64-bit</p><p>RAM: 4GB</p><p></p><p><strong>Cấu hình đề nghị:</strong></p><p>CPU: Intel Core i5-3330 hoặc cao hơn</p><p>HDD: 50GB</p><p>Card màn hình: GeForce GTX 670 (2GB) hoặc cao hơn</p><p>Hệ điều hành: Windows 7 64-bit</p><p>RAM: 8GB</p>',0,0,1,12,'12','quake champions,free fps,id software,arena shooter','LamGame Team',1,'/storage/blog/blog_59_thumb.jpg','vi',1,1,'Quake Champions - Game FPS miễn phí cấu hình nhẹ | LamGame','Sau một thời gian cho phép đăng ký đợt thử nghiệm thì mới đây tựa game online bắn súng cực khủng Quake Champions đã cho mở cửa bản Close Beta vào ngày 6/4. Trong giai đoạn thử nghiệm này, những game...','quake champions,free fps,id software,arena shooter','2025-09-06 02:30:31','2025-09-05 14:45:31','2025-09-05 14:45:31',NULL),(60,'Tổng hợp game offline mobile miễn phí hay nhất','tong-hop-game-offline-mobile-mien-phi-hay-nhat','Galaxy on Fire 3: ManticoreGalaxy on Fire là một dòng game khá quen thuộc đối với người dùng smartphone. Trò chơi này thu hút một lượng lớn fan hâm mộ bởi sự hấp dẫn của việc kết hợp giữa không gian,...','<p><strong>Galaxy on Fire 3: Manticore</strong></p><p>Galaxy on Fire là một dòng game khá quen thuộc đối với người dùng smartphone. Trò chơi này thu hút một lượng lớn fan hâm mộ bởi sự hấp dẫn của việc kết hợp giữa không gian, vũ trụ và chiến đấu. Ở phiên bản <strong>Galaxy on Fire 3: Manticore</strong>, bạn sẽ được trải nghiệm những trận không chiến điên cuồng, chiêm ngưỡng các khung cảnh thiên hà theo phong cách 3D cực kỳ hoành tráng.</p><p></p><p>Không giống như những phiên bản tiền nhiệm, Galaxy on Fire 3 tập trung vào việc mở rộng không gian chiến đấu với nhiều phe phái khác nhau, tăng cường sự kết nối giữa các người dùng bằng việc kinh doanh hàng hoá, bổ sung thêm một số nhiệm vụ khắc nghiệt hơn. Ngoài việc xây dựng căn cứ địa giữa các thiên hà, người chơi có thể mua tàu mới, nâng cấp các trang thiết bị cần thiết cho mỗi chuyến bay.</p><p></p><p><strong>Power Hover</strong></p><p><strong>Power Hover</strong> là một game phiêu lưu giải trí cực thú vị. Người chơi sẽ điều khiển một chú robot bay trên không trung. Bạn sẽ điều khiển chú robot này bay đi theo cách của bạn thông qua một thế giới hoang vu và đầy các vật thể nguy hiểm. Bên cạnh đó bạn cũng đừng quên thu thập năng lượng nhé.</p><p></p><p>Viết lên một câu chuyện về chú robot bay trên thé giới hoang vắng. Đồ họa trong game là những hình khối 3D đẹp mắt, làm cho người chơi cảm thấy mình đang trong một thế giới hoàn toàn chỉ có những chiếc hộp 3D bay lên và tạo thành đường đi trong game.</p><p></p><p><strong>Jetpack Joyride</strong></p><p><strong>Jetpack Joyride</strong> là một game hành động đi cảnh khá hay được phát hành bởi Halfbrick Studios - nhà sáng tạo ra các tựa game chém hoa quả Fruit Ninja đình đám một thời gian dài.</p><p></p><p>Trong game này, người chơi sẽ cùng với anh chàng Barry đột nhập vào phòng thí nghiệm bí mật để lấy những tên lửa và vũ khí hiện đại từ tay các nhà khoa học xấu xa. Tuy nhiên, trong quá trình thâm nhập, không may anh chàng Barry bé nhỏ bị phát hiện và một loạt các chướng ngại vật được đưa ra khiến Barry phải cố gắng rất nhiều mới có thể vượt qua chúng.</p><p></p><p>Nhiệm vụ của người chơi là phải né tránh tên lửa, hàng rào điện và cố gắng thu thập đồng tiền vàng càng nhiều càng tốt để có thể nâng cao điểm số.</p><p><strong>Mekorama</strong></p><p><strong>Mekorama</strong> là một trong những game giải đố khá nổi bật được trình làng bởi Odd Bot Out trên cả 2 thiết bị chạy hệ điều hành iOS và Android. Mekorama được gán mác game giải đố Diorama và là game hoàn toàn miễn phí với phong cách đậm chất Monument Valley.</p><p></p><p>Magni là cha đẻ của những game kỳ quặc nhưng không kém phần thú vị. Anh đồng thời cũng đảm nhiệm luôn cả các công việc nghệ thuật, âm nhạc và âm thanh của trò chơi, bên cạnh việc lập trình chính. Theo mỗi chủ đề, những câu đố đều có cùng một cấu trúc và một cảm giác khám phá, kỳ bí chung. Mục tiêu của game là điều khiển để nhân vật robot ‘B’ di chuyển tới những lối thoát xuyên suốt các màn chơi cực kỳ đau đầu và thử thách. ​</p><p></p><p>Game có đến 50 level, thêm vào đó, bạn còn có thể thu thập hàng loạt thẻ bài trong từng level thông qua những game thủ khác hoặc online. Tại đây, bạn có thể xoay quanh những mẫu máy móc để khám phá toàn level, thậm chí, bạn còn có thể phóng to hình ảnh nhằm quan sát hình mẫu một cách chi tiết hơn, tận hưởng từng đường nét tuyệt mỹ của giao diện game. Ngoài ra, bạn còn có thể tạo nên những level của riêng mình cùng màn chơi đã được lập trình sẵn. Điều này mở rộng thế giới game, mở rộng cơ hội để bạn tận hưởng Mekorama một cách tự do, khoáng đạt nhất.</p><p><strong>Tennis Bits</strong></p><p>Hoàn toàn đối lập với những tựa game chơi tennis chân thực trên di động với sự xuất hiện của những vận động viên quần vợt nổi tiếng. Trong <strong>Tennis Bits</strong>, người chơi sẽ bước vào thế giới hoạt hình với các nhân vật dễ thương, cách đánh bóng mới lạ và luật chơi cũng độc đáo không kém.</p><p></p><p>Tennis Bits sử dụng kiểu điều khiển 1 chạm quen thuộc để phát bóng và đỡ những cú đánh hiểm hóc của đối phương. Mục tiêu của bạn là leo lên vị trí số 1 và trở thành nhà vô địch tennis trong thế giới hoạt hình vô cùng sinh động. Cùng chơi Tennis Bits và khám phá kho nhân vật, vật phẩm vô cùng đồ sộ ngay trên màn hình thiết bị cảm ứng của bạn nhé!</p><p></p>',0,0,1,16,'16','offline mobile games,free games,mobile games,single player','LamGame Team',1,'/storage/blog/blog_60_thumb.jpg','vi',1,1,'Tổng hợp game offline mobile miễn phí hay nhất | LamGame','Galaxy on Fire 3: ManticoreGalaxy on Fire là một dòng game khá quen thuộc đối với người dùng smartphone. Trò chơi này thu hút một lượng lớn fan hâm mộ bởi sự hấp dẫn của việc kết hợp giữa không gian,...','offline mobile games,free games,mobile games,single player','2025-09-06 02:45:32','2025-09-05 14:45:32','2025-09-05 14:45:32',NULL),(61,'Lịch sử Zombie trong Video Game - Tại sao phổ biến?','lich-su-zombie-trong-video-game-tai-sao-pho-bien','Dù là thể loại game hành động, kinh dị, nhập vai, chiến thuật hoặc thậm chí giải trí, chúng ta đều có thể bắt gặp những kẻ địch dạng zombie. Tại sao zombie lại phổ biến đến thế trong lĩnh vực video...','<p>Dù là thể loại game hành động, kinh dị, nhập vai, chiến thuật hoặc thậm chí giải trí, chúng ta đều có thể bắt gặp những kẻ địch dạng zombie. Tại sao zombie lại phổ biến đến thế trong lĩnh vực video game? Đây chắc hẳn là một câu hỏi mà có không ít người từng băn khoăn. Ở phần này của loạt bài “Lịch sử Kinh dị”, chúng ta sẽ cùng luận một chút về sinh vật xác sống, thích gầm gừ, dễ truyền nhiễm và khoái cấu xé con người này.</p><p><strong>Lịch sử zombie: Khi địa ngục không còn chỗ, người chết sẽ bước vào game của chúng ta</strong></p><p>Zombie đã tồn tại kể từ những ngày đầu của thế giới video game. Cũng giống như một dạng bệnh dịch, chúng bắt đầu chậm rãi và rồi lây lan một cách chóng mặt. Tựa game đầu tiên có sự hiện đại của những người bạn xác sống có thể kể đến “Zombie Zombie” dành cho Spectrum VX trong năm 1984, với hình ảnh thể hiện bằng một tổ hợp pixel xanh lè, và tất nhiên là chưa đủ để làm ấn tượng người chơi.</p><p>Chỉ cho tới khi tựa game FPS mang tính cách mạng “Doom” được phát hành, zombie mới thực sự tạo ra một chỗ đứng của mình và bắt đầu hành trình tấn công sang mọi mặt trận của ngành game. Trong vòng vài năm sau đó, chúng ta đã được đón nhận cả tá game có kẻ thù zombie, bao gồm những cái tên bị đánh giá thập như “Zombies Ate My Neighbors”, “Alone in the Dark 3”, “House of the Dead” và đương nhiên là cả tượng đài “Resident Evil”.</p><p></p><p>Kể từ đó cho tới nay, đại dịch zombie đã lan truyền một cách không thể kiểm soát. Hầu hết mọi game kinh dị và giả tưởng đều có một dạng zombie nào đó hoặc không thì là kẻ địch kiểu zombie, ngay cả những tựa game bắn súng hiện đại cũng phải tự chế ra một vài chế độ zombie đặc biệt để chiều lòng fan hâm mộ.</p><p><strong>Nhưng tại sao lại là zombie? Hãy nhìn xa hơn cái xác sống biết đi</strong></p><p>Để thực sự khám phá lại do tại sao zombie lại được ưa chuộng đến thế trong video game, chúng ta phải nói về ý nghĩa của chúng đối với cả nhà phát triển và người chơi.</p><p>Đối với nhà phát triển, zombie là “món quà tặng của chúa” trên khía cạnh thiết kế mô hình và AI. Lập trình AI kẻ địch là một trong những công việc khó nhất trong video game, mà cho tới tận ngày nay, chúng ta vẫn chưa thể làm hoàn hảo. Trong thời đại của những game AAA trị giá nhiều triệu USD, người chơi có nhu cầu được thấy AI chân thực, và họ sẽ nhanh chóng nhận ra những điều ngớ ngẩn như một tay lính đặc nhiệm lao thẳng vào bạn mà không hề biết ẩn nấp hay leo qua vật cản trước mặt. Kẻ địch với diện mạo con người, trí tuệ con người sẽ cần có AI cấp độ con người để có thể phản ứng một cách tự nhiên nhất với đủ tình huống khác nhau.</p><p></p><p>Zombie không hề yêu cầu sự phức tạp trong hành vi như thế, bởi chúng ngốc nghếch và ai cũng biết điều đó. Khi bạn tạo ra một kẻ địch zombie, bạn không cần phải lo lắng về chuyện chúng sẽ có thái độ phản ứng ra sao với lựu đạn, có lối di chuyển phức tạp hay biết ẩn nấp, lăn lộn tránh né thế nào. Zombie sẽ đi lững thững hoặc chạy như điên về phía chúng ta, cố gắng cắn ta, và thông thường chúng sẽ bị thu hút bởi tiếng động ầm ĩ.</p><p></p><p>Trên khía cạnh thiết kế mô hình và cử động, zombie tiếp tục là một sự lựa chọn tuyệt vời cho các nhà sản xuất. Chúng có hình dáng con người và di chuyển một cách hậu đậu, nên chúng không hề cần một thiết kế cử động đặc biệt nào cả. Nếu một con zombie có di chuyển lệch nhịp và kỳ quái một chút, người chơi cũng không để ý hay thậm chí chả buồn quan tâm. Mô hình zombie về cơ bản là con người với một chút máu me, một vài bộ phận bị chặt đứt hay lở loét, nên thường thường chúng có thể được làm từ những mô hình NPC có sẵn với một chút sửa đổi là xong.</p><p><strong>Và tại sao chúng ta lại yêu thích zombie?</strong></p><p>Ở thời kỳ đầu của điện ảnh zombie, các xác sống đã đại diện một chiếc gương phản chiếu hình ảnh con người. Trong “Night of the Living Dead”, chúng là sự minh họa rằng con người có thể trở thành quái vật dưới áp lực, lối suy nghĩ định kiến, hoang tưởng cửa giai đoạn Chiến Tranh Lạnh và sự sụp đổ của hệ thống xã hội. Trong “Dawn of the Dead”, zombie là hình ảnh ẩn dụ cho chủ nghĩa tiêu dùng luôn thèm khát của thập niên 80’.</p><p></p><p>Ngày này, zombie đã không còn là phương tiện để chỉ trích xã hội nữa, mà đã trở thành một hình ảnh đáng sợ của con người. Có thể nói, zombie là một con người với phần “người” đã được loại bỏ bằng hết. Chúng ta thích có zombie trong game bởi tiêu diệt chúng rất vui. Zombie mang lại một cơ hội để chúng ta thoải mái bắn giết mà không phải lo lắng về những câu hỏi đạo đức, đồng thời chúng cũng mang đến một dạng chiến đấu rất khác biệt. Hơn nữa nếu thể hiện hình ảnh máu me dã man về hành vi tiêu diệt con người, một video game có thể bị cấm ở nhiều quốc gia, nhưng nếu con người đó là một zombie thì lại chẳng có vấn đề gì.</p>',0,0,1,12,'12','zombie games,gaming history,horror games,zombie culture','LamGame Team',1,'/storage/blog/blog_61_thumb.jpg','vi',1,1,'Lịch sử Zombie trong Video Game - Tại sao phổ biến? | LamGame','Dù là thể loại game hành động, kinh dị, nhập vai, chiến thuật hoặc thậm chí giải trí, chúng ta đều có thể bắt gặp những kẻ địch dạng zombie. Tại sao zombie lại phổ biến đến thế trong lĩnh vực video...','zombie games,gaming history,horror games,zombie culture','2025-09-06 03:00:33','2025-09-05 14:45:33','2025-09-05 14:45:33',NULL),(62,'Top game online đáng chơi ngay khi ra mắt 2024','top-game-online-dang-choi-ngay-khi-ra-mat-2024','Cloud PiratesTựa game online lái thuyền bay lượn và bắn nhau vừa cổ điển vừa hiện đại vô cùng độc đáo Cloud Pirates đã công bố sẽ mở cửa miễn phí hoàn toàn trên toàn thế giới vào ngày 19/4.Trước đây...','<ol type=\"1\"><li><blockquote><p><strong>Cloud Pirates</strong></p></blockquote></li></ol><p>Tựa game online lái thuyền bay lượn và bắn nhau vừa cổ điển vừa hiện đại vô cùng độc đáo Cloud Pirates đã công bố sẽ mở cửa miễn phí hoàn toàn trên toàn thế giới vào ngày 19/4.</p><p>Trước đây trò chơi này thử nghiệm thu phí 10 USD, nhưng sắp tới thì không cần phải bỏ tiền nữa.</p><p></p><p>Nếu như bạn chưa biết thì Cloud Pirates là sản phẩm do Allods Team cùng My.com hợp tác phát triển và phát hành miễn phí toàn cầu. Tựa game lấy bối cảnh viễn tưởng xây dựng trên nền tảng đồ họa vui nhộn và đầy màu sắc. Người chơi sẽ đóng vai các vị thuyền trưởng, nhưng không phải đi trên biển mà là bay trên không để chiến đấu với các thế lực khác nhằm mở rộng \'bờ cõi\' của mình, chính là các hòn đảo lơ lửng trôi dạt.</p><p></p><p>Về cơ bản thì trò chơi này có phong cách chiến đấu theo kiểu bắn súng góc nhìn thứ 3, pha trộn một chút giả lập lái xe giống như World of Tanks hay World of Warships và còn chiến đấu trong các trận chiến tương tự game MOBA vậy.</p><p>Thực tế thì Cloud Pirates gây được ấn tượng mạnh bởi sự tự do phóng khoáng trong việc... xây thuyền mà nó mang lại. Game thủ có thể tự mình tạo ra chiếc tàu ưng ý bằng cách ghép các bộ phận lại từ vỏ cho tới động cơ, các loại súng ống... để tạo ra sản phẩm hoàn thiện sao cho mạnh mẽ và độc đáo nhất.</p><ol start=\"2\" type=\"1\"><li><blockquote><p><strong>Master X Master</strong></p></blockquote></li></ol><p>Mới đây, Master X Master đã chính thức mở cửa Closed Beta lần 2 tới game thủ trên toàn thế giới. Được biết, để tham gia phiên bản thử nghiệm lần này thì người chơi sẽ phải có Code kích hoạt tài khoản. Tuy nhiên, việc kích hoạt tài khoản của game tương đối đơn giản và người chơi gần như chỉ cần hoàn tất quá trình đăng ký tài khoản NCSoft trên trang chủ mà thôi.</p><p></p><p>Điều đáng tiếc rằng để tham gia chơi thử đợt Closed Beta này, game thủ sẽ cần phải Fake IP sang Mỹ hay một quốc gia nào khác thuộc khu vực Châu Âu. Đây cũng là điều lưu ý quan trọng nhất đối với game thủ Việt nếu muốn chơi thử MOBA cực độc đáo này.</p><p>Đầu tiên, điểm thu hút của Master X Master đến từ thiết kế nhân vật, bối cảnh, cốt truyện dựa trên MMORPG nổi tiếng Blade & Soul, vốn là tựa game online được ưa chuộng thứ 2 (sau Liên Minh Huyền Thoại) tại Hàn Quốc. Có lẽ, chính vì điều này mà NSX NCSoft đã thiết kế ra một tựa game với gameplay mang phong cách chơi của cả 2 tựa game này.</p><p></p><p>Về cơ chế gameplay, Master X Master có thể khiến bạn gợi nhớ phần nào đến tựa game nhập vai đình đám Diablo III, với cách thiết kế góc nhìn thứ 3 cố định từ trên cao, bên cạnh cách điều khiển nhân vật di chuyển bằng chuột phải, tung đòn bằng chuột trái và hot key khá quen thuộc. Hầu hết các skill trong Master X Master đều là non-target, và người chơi sẽ cần phải tự định hướng để trúng mục tiêu. Và dù rằng các skill này đa phần đều gây damage trên một diện rộng nhất định, nhưng để có thể đạt hiểu quả lớn nhất, game thủ vẫn cần phải rèn luyện thêm kĩ năng chơi để tung đòn thật chính xác.</p><p>Dropzone</p><p>Theo thông tin mới nhận thì tựa game online MOBA lai chiến thuật hết sức độc đáo Dropzone sẽ được chuyển sang hình thức kinh doanh miễn phí giờ chơi vào thời gian tới. Đây là kết quả của rất nhiều yêu cầu từ phía game thủ tham gia chơi, ngoài ra một chế độ chơi mới cũng sẽ được áp dụng thêm.</p><p></p><p>Hiện tại, Dropzone đang được bán với giá khoảng 20 USD trên Steam nhưng trong thời gian tới đây sẽ là giá của các gói bổ trợ thêm chứ không phải là mua game nữa. Nhưng rất tiếc là Dropzone chưa hỗ trợ khu vực Việt Nam và phải fake IP mới chơi được.</p><p>Trong Dropzone, game thủ sẽ được đưa tới thời tương lai khi toàn bộ sự sống của nhân loại phụ thuộc vào nguồn năng lượng gọi là Core từ mỏ ở mặt trăng Europa của hành tinh Jupiter. Tuy nhiên hành tinh này đã bị một nhóm người ngoài hành tinh chiếm đóng và cuộc chiến chiếm lại nơi này nổ ra vô cùng khốc liệt.</p><p></p><p>Điều thú vị của Dropzone chính là gameplay hết sức độc đáo với sự kết hợp giữa thể loại MOBA và chiến thuật, đem đến cho game thủ những màn đấu vừa mang tính hành động cao lại bắt phải động não rất nhiều. Bạn vừa phải khiển nhân vật ra các skill, dẹp lính của địch thủ vừa phải điều động quân đến các nơi hợp lý để chiếm cứ điểm quan trọng.</p><ol start=\"3\" type=\"1\"><li><blockquote><p><strong>The Elder Scrolls Online</strong></p></blockquote></li></ol><p>Tin mừng cho nhiều game thủ Việt muốn chơi thử game online hàng khủng The Elder Scrolls Online nhưng chưa có đủ tiền để mua khi Bethesda đã bất ngờ công bố đợt thử nghiệm miễn phí cho trò chơi trong tuần này. Cụ thể thì vào ngày mai game sẽ mở miễn phí cho tới 18/4 với mọi phiên bản gồm PlayStation 4, Xbox One, PC, và Mac.</p><p></p><p>Trong 7 ngày thì game thủ có thể tận hưởng gần như toàn bộ nội dung của The Elder Scrolls Online (tất nhiên còn tuỳ bạn đi được bao xa). Sau đó muốn giữ thành quả cày kéo trong thời gian miễn phí trên thì rõ ràng là phải bỏ tiền ra mua game rồi!</p><p>Từng được đánh giá là một game online bom tấn khi mới ra mắt, song The Elder Scrolls Online đã không thành công như mong đợi và tưởng chừng như sẽ sớm \'xịt\' với hình thức kinh doanh thu phí giờ chơi. Rất may là NPH của trò chơi đã nhanh chóng chuyển đổi sang kiểu buy - to - play vào năm nay (game thủ chỉ phải trả tiền mua một lần duy nhất) và trở nên thành công rực rỡ.</p><p></p><p>Được sản xuất bởi Zenimax Online Studios, The Elder Scrolls Online là sự kết hợp của những cuộc khám phá không ngừng nghỉ của phiên bản offline truyền thống với những khía cạnh quy mô và xã hội của một tựa game online MMORPG. Người chơi sẽ tự mình khám phá cả một chương mới của lịch sử Elder Scrolls.</p>',0,0,1,12,'12','new online games,upcoming games,2024 games,online gaming','LamGame Team',1,'/storage/blog/blog_62_thumb.jpg','vi',1,1,'Top game online đáng chơi ngay khi ra mắt 2024 | LamGame','Cloud PiratesTựa game online lái thuyền bay lượn và bắn nhau vừa cổ điển vừa hiện đại vô cùng độc đáo Cloud Pirates đã công bố sẽ mở cửa miễn phí hoàn toàn trên toàn thế giới vào ngày 19/4.Trước đây...','new online games,upcoming games,2024 games,online gaming','2025-09-06 03:15:38','2025-09-05 14:45:38','2025-09-05 14:45:38',NULL),(63,'Brawl of Ages - Game thẻ bài chiến thuật miễn phí','brawl-of-ages-game-the-bai-chien-thuat-mien-phi','Mới đây, NSX S2 Games - đơn vị từng làm ra những sản phẩm đình đám là Heroes of Newerth hay Strife... đã giới thiệu một game online miễn phí mới hết sức ấn tượng mang tên Brawl of Ages. Trò chơi kết...','<p>Mới đây, NSX S2 Games - đơn vị từng làm ra những sản phẩm đình đám là Heroes of Newerth hay Strife... đã giới thiệu một game online miễn phí mới hết sức ấn tượng mang tên <strong>Brawl of Ages</strong>. Trò chơi kết hợp giữa thẻ bài, chiến thuật vô cùng độc đáo này được phát hành cho cả Windows, MAC và Linux thông qua hệ thống Steam.</p><p><em>Brawl of Ages Announcement Trailer.</em></p><p>Khác với phần lớn những game thẻ bài thì Brawl of Ages đem đến màn chơi chiến thuật theo kiểu \'thời gian thực\' với các màn so tài đầy tốc độ trên một đấu trường biến động. Nhìn chung gameplay tương đối đơn giản dễ hiểu song để xây dựng được một chiến thuật hiệu quả thì khá khó khăn bởi bạn còn phải tập trung vào kỹ năng và căn thời điểm hợp lý nữa.</p><p></p><p>Cụ thể thì thay vì ném bài xuống bàn rồi đợi đối phương ra bài tiếp thì game thủ sẽ phải đối mặt với nhau trên chiến trường trực tiếp. Các loại card trong Brawl of Ages như binh lính, phép thuật sẽ được sử dụng trong trận chiến và gây ảnh hưởng ngay lập tức tới cục diện trận đấu.</p><p></p><p>Game thủ có thể xây dựng bộ bài cho riêng mình từ 10 lá riêng biệt có công năng khác nhau. Các loại quân chủng đánh xa, đánh gần và phép thuật có hiệu ứng khác nhau trên chiến trường song tựu chung lại là để tiêu diệt kẻ địch và các công trình trên đường.</p><p></p><p>Brawl of Ages cho phép 2 đối thủ thả bài ra chiến trường có sẵn 2 tower, 2 land và bị chia cắt bởi dòng sông một cách tuỳ ý nhưng bị giới hạn bởi 20 mana, việc lựa chọn thời điểm quân địch tiến lên hay quyết định tấn công vì thế vô cùng quan trọng!</p><p></p>',0,0,1,12,'12','brawl of ages,card game,strategy game,free to play','LamGame Team',1,'/storage/blog/blog_63_thumb.jpg','vi',1,1,'Brawl of Ages - Game thẻ bài chiến thuật miễn phí | LamGame','Mới đây, NSX S2 Games - đơn vị từng làm ra những sản phẩm đình đám là Heroes of Newerth hay Strife... đã giới thiệu một game online miễn phí mới hết sức ấn tượng mang tên Brawl of Ages. Trò chơi kết...','brawl of ages,card game,strategy game,free to play','2025-09-06 03:30:42','2025-09-05 14:45:42','2025-09-05 14:45:42',NULL),(64,'Top 5 game thế giới mở hay nhất của Gameloft','top-5-game-the-gioi-mo-hay-nhat-cua-gameloft','Order and Chaos 2: RedemptionKhác với Order and Chaos, Order and Chaos 2: Redemption không phải là một tựa game trả phí mà là game free-to-play. Chính vì vậy mà người dùng có thể thoải mái tải game...','<p><strong>Order and Chaos 2: Redemption</strong></p><p>Khác với Order and Chaos, <strong>Order and Chaos 2: Redemption</strong> không phải là một tựa game trả phí mà là game free-to-play. Chính vì vậy mà người dùng có thể thoải mái tải game về trải nghiệm mà không phải trả thêm bất kỳ chi phí nào cả.</p><p></p><p>Order and Chaos 2: Redemtion đưa người chơi tới bối cảnh 600 sau khi trận chiến đầu tiên nổ ra. Chuyện kể rằng sau khi phá hủy Pimal Heart bằng Khalin\'s Hammer vào cuối Order & Chaos Online, mọi chuyện trở nên khá tệ. Khi đó, một cơn đại hồng thủy đã xuất hiện và nhấn chìm toàn bộ lục địa bao gồm cả các vị hùng.</p><p></p><p>Là phần tiếp nối của game mobile Order and Chaos Online, Order and Chaos 2: Redemtion vẫn sẽ đi theo thể loại MMORPG như đàn anh của mình nhưng được bổ sung thêm rất nhiều điều độc đáo. Một trong số đó phải kể tới là 2 tính năng mới toanh trong game mobile này là Solo Dungeons và Instant Quests. Người chơi có thể tham gia cùng hàng ngàn người chơi khác từ khắp nơi trên thế giới để hoàn thành các nhiệm vụ trực tuyến hàng ngày cùng nhau.</p><p></p><p><strong>The Amazing Spider Man 2</strong></p><p><strong>The Amazing Spider-Man 2</strong> là một tựa game phiêu lưu hành động thế giới mở xoay quanh bối cảnh thành phố New York và tham gia vào các nhiệm vụ khác nhau trong thành phố. Bạn có thể tập trung vào nhiệm vụ cốt truyện chính hoặc làm các nhiệm vụ phụ như giúp đỡ người dân, cảnh sát. Bối cảnh game như một ngày thường nhật, những người dân nơi đây vẫn sinh hoạt bình thường và nhiệm vụ của chàng sinh viên Peter Parker là đảm bảo sự yên bình đó trong bộ đồ người nhện.​</p><p></p><p>Game xây dựng một thế giới mở nơi mà bạn có thể thỏa sức bay nhảy, đu tơ nhện, đi đến bất cứ đâu trong thành phố như trong những tựa game free – roam vậy. Spider-Man là một nhân vật tuyệt vời với khả năng nhào lộn, nhanh trí, vô cùng mạnh mẽ và có chút gì đó cô độc trong một thành phố lớn.</p><p></p><p><strong>Six-Guns</strong></p><p><strong>Six-Guns</strong> kể câu chuyện về chàng cao bồi miền tây đi săn đám thây ma, quái vật nguy hiểm rùng rợn. Hình ảnh trong Six-Guns có nhiều nét tương đồng với tựa game Backstab - từ phong cách cưỡi ngựa của nhân vật chính tới phong cách chiến đấu, tạo nên một trò chơi thú vị và cuốn hút.</p><p></p><p>Với Six-Guns, game thủ sẽ có cơ hội thỏa sức khám phá miền Viễn Tây bao la rộng lớn, nơi ẩn chứa nhiều mối hiểm nguy đến từ các băng đảng và kẻ thù giấu mặt. Nơi đây có những chàng cao bồi ung dung trên lưng ngựa, vừa di chuyển vừa nghêu ngao hát những khúc ca yêu đời, nhưng cũng chính những con người này sẽ là những anh hùng thiện chiến mỗi khí đối diện với kẻ thù.</p><p></p><p>Game thủ cần hết sức thận trọng vì vẻ đẹp của thế giới này có thể đánh lừa bạn. Ẩn sau đó là hàng loạt những âm mưu vô cùng nguy nhiểm. Để khám phá những âm mưu tiềm ẩn, bạn phải trải qua hàng loạt các nhiệm vụ như đua ngựa, bắt những tên cướp và đưa chúng ra ánh sáng, chống lại âm mưu và sự tấn công của kẻ thù… Tất cả những diễn biến và hành động hấp dẫn đó sẽ diễn ra trên hành trình chinh phục cái ác của bạn.</p><p><strong>Dungeon Hunter 5</strong></p><p><strong>Dungeon Hunter 5</strong> sẽ tiếp nối phần nội dung còn dang dở của Dungeon Hunter 4. Chắc hẳn người chơi đã đoán được phần nào nhiệm vụ của mình sẽ là bắt đầu tiếp tục cuộc hành trình trên đống đổ nát của vương quốc Valenthia.</p><p></p><p>Trong phần 5 này, game thủ không chỉ đóng vai một thợ săn tiền thưởng trở về từ cuộc chiến với ác quỷ mà còn sớm chiếm được vị trí cao trong tổ chức, gánh vác trọng trách hết mực lớn lao là vị anh hùng cứu nhân độ thế. Sở hữu cốt truyện khá rõ ràng, mạch lạc, Dungeon Hunter 5 dẫn dắt người chơi vào một thế giới thần thoại giả tưởng cùng những cuộc săn lùng quái vật, những cuộc truy lùng khó khăn và những tên trùm cực mạnh.</p><p></p><p>Vẫn là thể loại free-to-play, Dungeon Hunter 5 mang đậm và nhấn mạnh vào phong cách chặt chém \"Diablo style\". Trong quá trình trải nghiệm gameplay, game mobile này phát huy tốt như những gì Gameloft đã mô tả, với nhiều tùy biến gameplay và đồ họa HD tuyệt đẹp.</p><p><strong>Gangstar: New Orleans</strong></p><p>Nếu là fan của dòng game GTA huyền thoại thì hẳn bạn sẽ không muốn bỏ qua tân binh mobile mới trong thể loại này từ Gameloft là <strong>Gangstar: New Orleans</strong>. Đây vẫn là một tựa game thế giới mở cho phép bạn làm mọi điều mình thích mà không phải lo lắng điều gì.</p><p></p><p>Trò chơi hiện tại đã xuất hiện âm thầm trên nền tảng iOS dưới dạng miễn phí tải về. Tuy nhiên, đây mới chỉ là phiên bản giới hạn cho Philippines, các fan hâm mộ cần phải có tài khoản App Store tại quốc gia này mới mong chơi được. So với phiên bản tiền nhiệm Gangstar Vegas thì Gangstar New Orleans được đánh giá là đẹp mắt và \"chất\" hơn nhiều.</p><p></p><p>Không chỉ sở hữu một thế giới mở rộng lớn và bí ẩn, Gangstar New Orleans còn cung cấp cho người chơi hệ thống xe cộ, vũ khí lên đến hàng trăm loại, ngoài sức tưởng tưởng của bạn. Không chỉ thừa hưởng những nét đặc trưng của GTA mà Gangstar New Orleans còn bổ sung thêm hệ thống mới mang tên Turf Wars. Cơ chế này cho phép người chơi tập hợp thành những băng đảng xã hội đen khét tiếng để bảo vệ địa bàn của mình cũng như tranh giành địa bàn những nơi khác.</p><p></p>',0,0,1,16,'16','gameloft,open world games,mobile open world,adventure games','LamGame Team',1,'/storage/blog/blog_64_thumb.jpg','vi',1,1,'Top 5 game thế giới mở hay nhất của Gameloft | LamGame','Order and Chaos 2: RedemptionKhác với Order and Chaos, Order and Chaos 2: Redemption không phải là một tựa game trả phí mà là game free-to-play. Chính vì vậy mà người dùng có thể thoải mái tải game...','gameloft,open world games,mobile open world,adventure games','2025-09-06 03:45:47','2025-09-05 14:45:47','2025-09-05 14:45:47',NULL),(65,'Top 8 game \"trường tồn\" theo thời gian','top-8-game-truong-ton-theo-thoi-gian','Tạo ra một game hay đã là khó, nhưng tạo ra một tựa game sống mãi theo thời gian còn khó hơn. Một số video game đỉnh thực tế chỉ “đỉnh” ở trong một giai đoạn nào đó, qua thời gian, từng chi tiết của...','<p>Tạo ra một game hay đã là khó, nhưng tạo ra một tựa game sống mãi theo thời gian còn khó hơn. Một số video game đỉnh thực tế chỉ “đỉnh” ở trong một giai đoạn nào đó, qua thời gian, từng chi tiết của nó như câu chuyện, nhịp độ, gameplay và đồ họa sẽ không còn phù hợp với thế hệ sao. Tuy nhiên, một số game có thể coi là bất tử, bất kể thế giới game đã tiến bộ ra sao kể từ lúc chúng được phát hành, chúng vẫn khiến người ta phải si mê vô cùng và ngày càng có giá trị theo thời gian. Dưới đây là 8 tựa game càng có tuổi lợi càng lợi hại và được sự tôn vinh của hàng triệu người chơi suốt qua thế hệ qua.</p><p><strong>Super Mario World</strong></p><p></p><p>Được nhiều người nhìn nhận là tựa game 2D thể loại platformer xuất sắc nhất mọi thời đại, “Super Mario World” vẫn là một trong những tựa game tuyệt nhất mà ai cũng nên chơi thử. Sự đơn giản của cơ chế điều khiển kết hợp với thiết kế màn chơi sáng tạo đã trở thành một công thức kết hợp hoàn hảo giúp mang đến những trải nghiệm vừa vui lại vừa thử thách. Mặc dù đồ họa 2D và thể loại platformer không còn phổ biến như xưa, nhưng phong cách thiết kế nghệ thuật đặc sắc và âm nhạc không thể lẫn vào đâu được khiến “Super Mario World” có thể trường tồn theo thời gian.</p><p><strong>Borderlands 2</strong></p><p></p><p>So sánh với những cái tên khác trong danh sách này, “Borderlands 2” là người trẻ trung nhất khi được phát hành trong năm 2012, nhưng không vì thế mà nó thiếu đi những phẩm chất cần thiết của một tựa game tồn tại theo thời gian. Tất cả mọi thứ về “Borderlands 2” đều vô cùng đáng nhớ, từ phong cách đồ họa hoạt hình cell-shaded độc đáo, thế giới quan giả tưởng rộng lớn, câu chuyện thú vị mà hài hước, hệ thống nhân vật ấn tượng cho tới một cơ chế gameplay FPS kết hợp RPG cực kỳ hấp dẫn.</p><p><strong>BioShock</strong></p><p></p><p>Một câu chuyện hay có thể truyền từ thế hệ này sang thế hệ khác, và câu chuyện của “BioShock” phải nói là rất xuất sắc. Không chỉ mang đến một câu chuyện phức tạp nhờ một nút thắt lớn vô cùng, “BioShock” đã thành công mỹ mãn trên phương diện viết kịch bản và xây dựng bối cảnh, mang đến một góc nhìn khác biệt về khái niệm thế giới thiên đường, sự nguy hiểm của bản chất tự phụ, dẫn dắt người chơi khám phá Rapture, một thành phố giả tưởng dưới lòng đại dương. Thêm vào đó, trò chơi còn có một cơ chế gameplay hấp dẫn, tạo ra điểm nhấn biệt với các sản phẩm FPS khác. Ra đời cách đây 10 năm, “BioShock” vẫn không hết làm người ta kinh ngạc về chất lượng của nó.</p><p><strong>Galaga</strong></p><p></p><p>Nhiều game arcade tỏ ra lỗi thời chỉ sau một vài năm phát hành, nhưng “Galaga” là một ngoại lệ. Nhà phát triển Namco đã thực hiện cân bằng độ khó của game một cách tuyệt vời để khiến người chơi kinh nghiệm vẫn còn thấy thử thách và hứng thú, còn người chơi mới lại dễ dàng tiếp cận. Với nhịp độ chơi nhanh và hình ảnh “nhìn là nhớ mãi”, “Galaga” là minh chứng của một sản phẩm trường tồn theo thời gian kể từ khi được phát hành trong năm 1981. Ngày nay, người ta vẫn có thể bắt gặp các thùng máy chơi game này ở rất nhiều nơi giải trí công cộng (rạp chiếu phim, sân chơi bowling).</p><p><strong>Fallout 3</strong></p><p></p><p>“Fallout 3”của Bethesda là người đi tiên phong cho thể loại Action RPG thế giới mở ngày nay. Sự kết hợp của thế giới quan rộng lớn, hệ thống nhân quả phức tạp, câu chuyện thú vị và cái cảm giác đơn độc, tự do trong một bối cảnh hậu tận thế đã mang đến một trải nghiệm ưu việt cho người chơi. Mặc dù đồ họa của game không còn là tiêu chuẩn như thời điểm mới ra mắt nữa, nội dung gameplay cực kỳ có chiều sâu của “Fallout 3” đã bù đắp lại cho phần hình ảnh. Thậm chí có nhiều fan hâm mộ vẫn cho rằng “Fallout 3” chất lượng hơn hẳn “Fallout 4”, tựa game ra sau nó đến 7 năm.</p><p><strong>Super Smash Bros. Melee</strong></p><p></p><p>“Super Smash Bros. Melee” được phát hành từ năm 2001, và hơn 15 năm sau, vẫn có đầy người say mê với những màn đấm đá cực kỳ vui nhộn và đầy tính cạnh tranh của nó. Khía cạnh thi đấu của game không những là điểm giúp nó trở nên tuyệt vời, mà còn đem đến một trải nghiệm hấp dẫn hơn bao giờ hết. Nhờ có thiết kế điều khiển mượt mà và dễ dàng, chắc chẳn là chẳng có gì tuyệt vời hơn một trận loạn đấu giữa bốn nhân vật nổi tiếng của thương hiệu Nintendo.</p><p><strong>Call of Duty 4: Modern Warfare</strong></p><p></p><p>Mới được phát hành lại thời gian gần đây dưới dạng remastered, nhưng thực lòng mà nói thì “Call of Duty 4: Modern Warfare” tuyệt đến độ nó chẳng cần phải được cập nhật, tút tát lại làm gì. Được nhiều fan hâm mộ nhìn nhận là phiên bản xuất sắc nhất của cả series “Call of Duty” và cũng là người có công giúp thương hiệu FPS này trở nên phổ biến như thời nay, “Modern Warfare” có một phần chiến dịch hấp dẫn và trên hết là một cơ chế multiplayer hết sức chuẩn mực, tạo nên cuộc cách mạng cho thể loại FPS nói chung.</p><p><strong>Castlevania: Symphony of the Night</strong></p><p></p><p>Là một trong những video game xuất sắc nhất mọi thời đại, “Castlevania: Symphony of the Night” hoàn toàn xứng đáng với tất cả mọi lời tán dương mà người ta dành cho nó suốt bao năm qua. Từ phong cách nghệ thuật, nhân vật và thiết kế tổng thể của nó đều vô cùng độc đáo và không thể thấy ở một sản phẩm nào khác. Sự biến đổi trên phương diện gameplay, kết hợp thêm các yếu tố RPG đã khiến “Castlevania: Symphony of the Night” khác biệt hoàn toàn so với những người tiền nhiệm, khiến người ta có thể chơi đi chơi lại nhiều lần mà vẫn thấy hay, vẫn thấy thử thách.</p>',0,0,1,12,'12','timeless games,classic games,evergreen games,retro gaming','LamGame Team',1,'/storage/blog/blog_65_thumb.jpg','vi',1,1,'Top 8 game \"trường tồn\" theo thời gian | LamGame','Tạo ra một game hay đã là khó, nhưng tạo ra một tựa game sống mãi theo thời gian còn khó hơn. Một số video game đỉnh thực tế chỉ “đỉnh” ở trong một giai đoạn nào đó, qua thời gian, từng chi tiết của...','timeless games,classic games,evergreen games,retro gaming','2025-09-06 04:00:50','2025-09-05 14:45:50','2025-09-05 14:45:50',NULL),(66,'Top 5 game Android phong cách Minecraft','top-5-game-android-phong-cach-minecraft','Block Story ($2.99)Nếu bạn thích các trò chơi như Minecraft nhưng muốn tham gia vào các nhiệm vụ để có thể lên cấp phát triển nhân vật của mình thì đây chính là những gì bạn đang tìm kiếm.Block story...','<p><strong>Block Story ($2.99)</strong></p><p>Nếu bạn thích các trò chơi như Minecraft nhưng muốn tham gia vào các nhiệm vụ để có thể lên cấp phát triển nhân vật của mình thì đây chính là những gì bạn đang tìm kiếm.</p><p></p><p><strong>Block story</strong> sẽ mang tới cho người chơi những giây phút vui vẻ với các mục tiêu cần vượt qua. Nhiệm vụ sẽ là khám phá các hòn đảo nổi, những mê cung thuộc thế giới ngầm hay các rặng san hô dưới đáy biển, thậm chí là những vùng trời bị lãng quên trong truyền thuyết. Hệ thống cũng trang bị cho bạn những thiết bị như “My Precious“ (một chiếc nhẫn tăng sức mạnh) hay chân chèo giúp bơi nhanh hơn...</p><p></p><p>Vũ khí trong game Block story thuộc về thời xưa với kiếm, cung hay súng (kiểu súng trung cổ), đôi khi là cả máy bắn đá. Nhân vật sẽ di chuyển hầu hết bằng ngựa và tàu gỗ. Ưu điểm của game là các nhiệm vụ rất hay với nhiều loại quái vật phong phú. Bạn sẽ phải chiến đấu với quái vật 2 sừng dữ tợn hay chống lại sự tấn công của những con rồng. Hãy vượt qua những cửa ải để san lấp mặt bằng, ghi điểm và nâng cấp cho nhân vật của mình.</p><p><strong>The Blockheads</strong></p><p><strong>The Blockheads</strong> là một trong những tựa game mobile có được số lượng người chơi nhiều nhất bởi sự độc đáo mới lạ mà game mang lại.</p><p></p><p>Cậu truyện của game cũng khá thú vị, khi màn đêm đã buông xuống, anh chàng Blockhead sắp chết cóng vì giá lạnh trong khi lều trại chưa được dựng và đống lửa sưởi ấm đã lụi tàn. Tất cả những gì bạn có trong tay là một cái xẻng, một ít củi và vài cây gậy. Liệu bạn có thể cứu sống nhân vật chính bằng cách tạo ra một cái giường? đào một hang động hay nhóm lại đống lửa?</p><p>Một câu truyện độc đáo khơi dậy tiềm năng sinh tồn của con người. Đây là tựa game tuyệt vời nhất cho những game thủ yêu thích thể loại game sand-box. Điểm nhiều người đánh giá sao là game là hệ “mở” trong một thế giới cực kỳ rộng lớn. Bạn sẽ thực hiện mọi hành động tùy thích mà không hệ phụ thuộc vào game. Kết cục của nhân vật trong game sẽ do bạn địch đoạt.</p><p><strong>CrashLands</strong></p><p></p><p>Công việc của người chơi trong <strong>Crashlands</strong> cũng giống như bao tựa game sinh tồn khác, đó là thu thập nhiều loại tài nguyên cơ bản nằm rải rác trên bản đồ để từ đó chế tạo nên những món đồ cao cấp hơn. Dù vậy, nhân vật của chúng ta không thể chết vì đói hay khát mà thay vào đó, hệ thống craft của Crashlands tập trung vào khía cạnh chiến đấu là chủ yếu.</p><p></p><p>Các loại vũ khí và áo giáp chế tạo ra đều mang những thuộc tính ngẫu nhiên như tăng sát thương, chí mạng, hút máu, khiến mục tiêu bốc cháy... Để tìm được trang bị ưng ý đòi hỏi bạn cần phải tốn nhiếu thời gian cày cuốc nhằm thu thập nguyên liệu cần thiết, và về mặt này có thể nói game hoàn toàn giống với Diablo hay rừng game nhập vai chặt chém có cùng phong cách khác. Đặc biệt trong phần lớn trường hợp khi mang một món đồ mới, người chơi sẽ nhìn thấy sự thay đổi rõ ràng trên mô hình nhân vật - chi tiết tường chừng nhỏ nhặt nhưng lại rất quan trọng trong việc duy trì sự hứng thú của game thủ.</p><p></p><p>Bản đồ rất rộng lớn xây dựng ngẫu nhiên của game mobile Crashlands kết hợp cùng chu kì ngày đêm giúp cho việc khám phá thế giới trở nên bớt nhàm chán khi mà Flux không sở hữu nhiều kĩ năng chiến đấu đa dạng như một tựa game RPG thực thụ. Ngược lại đòn thế của kẻ địch lại tỏ ra khá khó nắm bắt buộc bạn cần phải né tránh và chọn thời điểm tấn công hợp lý.</p><p><strong>Survivalcraft</strong></p><p>Trong <strong>Survivalcraft</strong>, hãy tận dụng mọi năng lực của bản thân để sinh tồn, học cách sử dụng các công cụ, vũ khí săn bắn, giăng bẫy bắt mồi và trồng cây lấy thực phẩm duy trì sự sống. Nhưng trước tiên bạn hãy dựng một căn nhà để trú ngụ và tránh sự xâm phạm của thế giới bên ngoài. Phương tiện đi lại của bạn là những con ngựa, lạc đà và những loài gia súc khác.</p><p></p><p>Chắc bạn sẽ cảm thấy cuộc sống ở vùng đất hoang vu này có phần thú vị giống như thời nguyên thủy cách đây hàng nghìn năm. Nhưng tất cả mọi thứ có thể thay đổi nhờ có sức mạnh của con người. Bạn có thể phá hủy những tảng đá bằng chất nổ. Bạn hãy tạo ra nguồn điện để thay đổi điều kiện sống hiện tại, xây dựng mạng lưới điện phức tạp. Các bạn hãy khám phá khả năng vô hạn của con người trong bất kỳ hoàn cảnh nào và thử thách bản thân trong game xây dựng.</p><p></p><p><strong>Terraria</strong></p><p>Ngay khi mới được phát hành, <strong>Terraria</strong> không nhận được nhiều sự chú ý bởi đồ họa phong cách 8bit cổ điển nhàm chán và gợi cho người chơi một sự ăn theo của tựa game đã quá nổi tiếng: Minecraft. Nhưng nhà phát hành 505 Games đã đưa vào đó một lối chơi mới, khác biệt hẳn so với những game phong cách sandbox này.</p><p></p><p><strong>Terraria</strong> không chỉ đơn giản là xây dựng hay chế tạo, nó còn đi kèm hệ thống chiến đấu khá đặc sắc và hệ thống chiến lợi phẩm trong quá trình phiêu lưu. Những đồ vật như áo giáp, vũ khí, đồ đạc, dụng cụ..v..v… sẽ khiến bạn hồi tưởng lại những game nhập vai với đồ họa 8bit đã gắn liền với tuổi thơ. Sự khám phá của bạn dường như không còn giới hạn nữa khi cầm chiếc xẻng, đào sau và đi tới những vùng đất mới hơn.</p><p></p>',0,0,1,16,'16','minecraft style,sandbox games,building games,creative games','LamGame Team',1,'/storage/blog/blog_66_thumb.jpg','vi',1,1,'Top 5 game Android phong cách Minecraft | LamGame','Block Story ($2.99)Nếu bạn thích các trò chơi như Minecraft nhưng muốn tham gia vào các nhiệm vụ để có thể lên cấp phát triển nhân vật của mình thì đây chính là những gì bạn đang tìm kiếm.Block story...','minecraft style,sandbox games,building games,creative games','2025-09-06 04:15:53','2025-09-05 14:45:53','2025-09-05 14:45:53',NULL),(67,'Star Wars Battlefront 2 - Game bắn súng khủng 2017','star-wars-battlefront-2-game-ban-sung-khung-2017','Sau nhiều hình ảnh úp mở thì tựa game bắn súng Star Wars: Battlefront 2 đã được hãng phát hành EA công bố thông qua một đoạn trailer hết sức hoành tráng với những hình ảnh bắt mắt về cuộc xung đột...','<p>Sau nhiều hình ảnh úp mở thì tựa game bắn súng Star Wars: Battlefront 2 đã được hãng phát hành EA công bố thông qua một đoạn trailer hết sức hoành tráng với những hình ảnh bắt mắt về cuộc xung đột giữa các thế lực trong thế giới Star Wars. Đồ họa của phần tiếp theo này xem ra còn ấn tượng hơn phiên bản năm 2015 vốn cũng có chất lượng hình ảnh không chê vào đâu được với mô hình nhân vật chi tiết, biểu cảm chân thực và tất nhiên là cả những phi thuyền, chiến hạm, robot đầy bề thế.</p><p></p><p>Nhưng điều khiến cho các fan hâm mộ của Star Wars cảm thấy thích thú nhất đó là sự hiện diện của nhiều nhân vật phim quen thuộc trải dài khắp nhiều thế hệ, từ Yoda, Luke Skywalker, Darth Maul cho tới các gương mặt mới chỉ xuất hiện ở phần Star Wars VII như Kylo Ren, Rey.</p><p>Trailer giới thiệu chính thức của Star Wars: Battlefront 2.</p><p>Được biết kì này, Star Wars: Battlefront 2 bên cạnh việc mang đến đấu trường mạng với quy mô cực lớn như thường lệ còn giới thiệu tới game thủ một chế độ chơi chiến dịch có cốt truyện liên quan tới Inferno Squadron - một lực lượng trực thuộc phe Galatic Empire. Đây hứa hẹn sẽ là góc nhìn thú vị khi người chơi không còn vào vai chính diện nữa mà sẽ tham gia cuộc chiến giữa các vì sao với tư cách \"lâu la\" của phe phản diện.</p><p></p><p>Star Wars: Battlefront 2 dự tính sẽ được phát hành vào ngày 17/11 dành cho các hệ máy PS4, Xbox One và PC.</p>',0,0,1,12,'12','star wars battlefront 2,dice,ea games,star wars games','LamGame Team',1,'/storage/blog/blog_67_thumb.jpg','vi',1,1,'Star Wars Battlefront 2 - Game bắn súng khủng 2017 | LamGame','Sau nhiều hình ảnh úp mở thì tựa game bắn súng Star Wars: Battlefront 2 đã được hãng phát hành EA công bố thông qua một đoạn trailer hết sức hoành tráng với những hình ảnh bắt mắt về cuộc xung đột...','star wars battlefront 2,dice,ea games,star wars games','2025-09-06 04:30:56','2025-09-05 14:45:56','2025-09-05 14:45:56',NULL),(68,'10 game miễn phí trên Steam siêu hay 2024','10-game-mien-phi-tren-steam-sieu-hay-2024','Một trong những nỗi sợ lớn nhất đối với game thủ là phải bỏ ra một số tiền lớn mới có thể chơi được một tựa game hay. Tuy nhiên, điều đó không phải lúc nào cũng xảy ra bởi có những tựa game miễn phí...','<p>Một trong những nỗi sợ lớn nhất đối với game thủ là phải bỏ ra một số tiền lớn mới có thể chơi được một tựa game hay. Tuy nhiên, điều đó không phải lúc nào cũng xảy ra bởi có những tựa game miễn phí nhưng chất lượng của nó lại vô cùng tốt. Steam là một trong số những nhà phân phối tốt nhất để tìm kiếm những tựa game như vậy.</p><p>Nội dung bài viết này là danh sách 10 tựa game miễn phí trên Steam nhưng kể cả có bán với giá cắt cổ, vẫn có nhiều game thủ sẵn sàng bỏ tiền ra mua.</p><p><strong>Paladins</strong></p><p></p><p>Paladins là một tựa game bắn súng FPS theo đội kết hợp với các yếu tốt chiến thuật và có thể tùy biến nhân vật một cách sâu sắc.</p><p>Hệ thống thẻ trong tựa game này cho phép người chơi nâng cấp để tạo ra những hướng xây dựng nhân vật sao cho phù hợp với lối chơi nhất. Trong Paladins, mọi thứ đều miễn phí, bất kỳ yếu tốt ảnh hưởng tới hiệu suất của người chơi đều có thể mở khóa thông qua hệ thống thành tựu trong trò chơi.</p><p><strong>War Thunder</strong></p><p></p><p>Là một game MMO chiến tranh đa nền tảng, War Thunder có nhiều loại phương tiện được sử dụng trong chiến tranh.</p><p>Cuộc chiến tranh nhập vai trong tựa game này được diễn ra trên không trung, đất liền và biển kết hợp với nhau, cùng với một số lượng lớn người chơi trong môi trường luôn thay đổi.</p><p><strong>DOTA 2</strong></p><p></p><p>DOTA 2 là một tựa game esports không cần bất cứ lời giới thiệu thêm nào nữa bởi nó là một trong những trò chơi phổ biến nhất thế giới.</p><p>Được ưa chuộng bởi cả người chơi chuyên nghiệp lẫn game thủ bình thường, tựa game hành động chiến thuật cạnh tranh này được chơi bởi 10 người chia đều cho 2 đội. Mỗi người chơi sẽ chọn một hero từ hơn 100 nhân vật có sẵn. Thực tế, tựa game này miễn phí là điều tốt hơn bao giờ hết.</p><p><strong>Hawken</strong></p><p></p><p>Hawken là tựa game bắn súng góc nhìn thứ nhất với lối chơi khá mới lạ. Tham gia vào game, người chơi sẽ được điều khiển những người máy chiến đấu với khả năng sát thương rất mạnh.</p><p>Game sở hữu nền đồ họa khá đẹp và tạo cho người chơi cảm giác chân thực. Điều này không chỉ đến với việc các công trình, môi trường được thiết kế rất đẹp và bắt mắt. Không chỉ có vậy, các hiệu ứng cháy nổ trong game cũng khiến cho người chơi cảm nhận được độ \"thật\" trong chiến trường của Hawken. Đây cũng có thể coi là điểm nhấn lớn nhất của tựa game bắn súng này.</p><p><strong>Planetside 2</strong></p><p></p><p>PlanetSide 2 là tựa game bắn súng góc nhìn thứ nhất được xây dựng trên thế giới mở rộng lớn. Tất nhiên, xây dựng ở trong thế giới thời tương lai, PlanetSide 2 không chỉ giới thiệu cơ chế chiến đấu giống như những tựa game bắn súng góc nhìn thứ nhất thông thường, mà thêm vào đó, nhân vật sẽ có được thêm các khả năng đặc biệt.</p><p>Một trong những khả năng đặc biệt của nhân vật trong PlanetSide 2 chính là việc người chơi có thể bay, nhảy trên tất cả mọi địa hình. Trên thực tế, người chơi chỉ có thể tiến hành nhảy lên cao chứ không thể bay lượn trên không trung, cũng như các bước nhảy của nhân vật đều có khoảng cách giới hạn nhất định.</p><p><strong>Blacklight: Retribution</strong></p><p></p><p>Blacklight: Retribution là tựa game bắn súng góc nhìn thứ nhất với nền đồ họa khá tiên tiến. Hình ảnh trong game được thiết kế khá đẹp và tạo cho người chơi cảm giác chân thực. Các hiệu ứng cháy nổ, súng đạn dù chưa thực chi tiết, nhưng vẫn đủ khiến cho người chơi cảm giác như đang được chơi trong một chiến trường thực sự.</p><p><strong>Warframe</strong></p><p></p><p>Không giống như nhiều tựa game bắn súng khác thường tập trung vào yếu tố shooter, khi mà người chơi gần như chỉ có thể tấn công đối thủ bằng súng đạn từ xa, Warframe lại đưa vào nhiều yếu tố hành động đi kèm một chút yếu tố \"Stealth\".</p><p>Ngoài việc sử dụng các loại súng có khả năng tấn công từ xa, nhân vật trong Warframe còn có thể sử dụng những thanh kiếm để tấn công mục tiêu trong tầm gần. Cần phải nói thêm rằng các nhân vật trong Warframe được thiết kế giống như những Ninja của Nhật, chính vì vậy các thanh kiếm, đòn đánh tầm gần trong game có thể khiến cho bạn cảm thấy khá quen thuộc.</p><p><strong>Neverwinter Online</strong></p><p></p><p>Trái ngược với nhiều tựa game online hiện nay khi người chơi có thể dễ dàng hạ gục quái vật trên bản đồ chỉ sau một vài đòn đánh đơn giản, Neverwinter Online yêu cầu người chơi phải tập trung cao độ ở mỗi lần chạm trán quái vật, khi mà quái vật thường tập trung thành một đàn nhỏ, đi kèm đó là những con Boss nhỏ.</p><p><strong>Path of Exile</strong></p><p></p><p>Kể từ khi chính thức mở cửa năm 2011 và đến khu vực Đông Nam Á năm 2013 cho tới nay, Path of Exile luôn luôn nằm trong top những game online đáng chơi nhất trên thế giới, được rất nhiều game thủ ưa thích và các chuyên gia cũng đánh giá rất cao. Cho tới tận thời điểm hiện tại là đầu năm 2017, trò chơi này vẫn vượt qua vô vàn các sản phẩm mới vừa đẹp vừa khủng mới.</p><p><strong>Team Fortress 2</strong></p><p></p><p>Gameplay của Team Fortress 2 tập trung vào phối hợp đồng đội với việc mỗi class lại có một khả năng đặc biệt riêng và vai trò riêng. Hệ thống vũ khí trong game cũng rất độc đáo với các chi tiết có thể unlock phong phú, nhờ đó các đội có thể thay đổi phong cách trong quá trình chơi.</p><p>Game có rất nhiều map thi đấu và các kiểu chơi đa dạng. Riêng các map nguyên thủy đã lên tới con số 32 và trên đó người chơi có thể thi đấu theo 7 cách khác nhau. Chính lối chơi đồng đội và sự phong phú này là điểm khác biệt của Team Fortress 2 so với các game khác.</p>',0,0,1,12,'12','free steam games,steam free to play,pc games,free pc games','LamGame Team',1,'/storage/blog/blog_68_thumb.jpg','vi',1,1,'10 game miễn phí trên Steam siêu hay 2024 | LamGame','Một trong những nỗi sợ lớn nhất đối với game thủ là phải bỏ ra một số tiền lớn mới có thể chơi được một tựa game hay. Tuy nhiên, điều đó không phải lúc nào cũng xảy ra bởi có những tựa game miễn phí...','free steam games,steam free to play,pc games,free pc games','2025-09-06 04:45:59','2025-09-05 14:45:59','2025-09-05 14:45:59',NULL),(69,'Life is Feudal - Game MMO thời trung cổ','life-is-feudal-game-mmo-thoi-trung-co','Mới đây, NSX Bitbox đã giới thiệu đoạn trailer gameplay mới của game online sinh tồn thời Trung Cổ tuyệt đỉnh Life is Feudal, đồng thời hé lộ sẽ tiếp tục đợt thử nghiệm closed beta vào ngay tháng 4...','<p>Mới đây, NSX Bitbox đã giới thiệu đoạn trailer gameplay mới của game online sinh tồn thời Trung Cổ tuyệt đỉnh <strong>Life is Feudal</strong>, đồng thời hé lộ sẽ tiếp tục đợt thử nghiệm closed beta vào ngay tháng 4 này.</p><p>Đáng buồn là trò chơi này áp dụng hình thức kinh doanh theo hình thức buy - to - play ngay từ ban đầu và bạn sẽ phải bỏ 40$, tương đương khoảng gần 900 ngàn đồng (hiện đang được giảm giá hơn 20% xuống còn hơn 600 ngàn đồng) để được chơi thử. Tuy vậy, NSX của Life is Feudal khẳng định rằng trò chơi sẽ hoàn toàn cân bằng và cash shop trong game chỉ mang tính chất thẩm mỹ và không ảnh hưởng tới gameplay.</p><p></p><p>Nếu như bạn chưa biết, Life is Feudal là một game online mang đậm phong cách của tựa game indie đình đám, Don\'t Starve. Trong game, người chơi cũng sẽ phải làm mọi thứ để có thể sống sót trong thế giới hoang sơ nhưng cũng vô cùng ấn tượng mà Bitbox đã tạo ra.</p><p></p><p>Life is Feudal có là thế giới mở rộng lớn mà trong đó, người chơi có thể tự do khám phá. Thậm chí, thế giới mở trong Life is Feudal rộng lớn và nhiều nội dung khám phá đến nỗi khiến cho người chơi không nhất định phải PvP với nhau, mà họ chỉ cần đi khám phá các hang động, hay chiến đấu với các quái vật trên bản đồ mà thôi.</p><p></p><p>Tuy vậy, các màn chiến đấu trong game vẫn hết sức ấn tượng, tuy rằng không phải đặc điểm được chú trọng nhất, nhưng cũng đem lại cho người chơi cảm giác vô cùng chân thật với những đòn đánh có phần nặng nề nhưng đầy sức mạnh, chứ không giống như những tựa game với chi tiết siêu nhiên, khi người anh hùng chẳng khác gì những \"vị thần\".</p>',0,0,1,12,'12','life is feudal,medieval mmo,survival mmo,sandbox mmo','LamGame Team',1,'/storage/blog/blog_69_thumb.jpg','vi',1,1,'Life is Feudal - Game MMO thời trung cổ | LamGame','Mới đây, NSX Bitbox đã giới thiệu đoạn trailer gameplay mới của game online sinh tồn thời Trung Cổ tuyệt đỉnh Life is Feudal, đồng thời hé lộ sẽ tiếp tục đợt thử nghiệm closed beta vào ngay tháng 4...','life is feudal,medieval mmo,survival mmo,sandbox mmo','2025-09-06 05:01:02','2025-09-05 14:46:02','2025-09-05 14:46:02',NULL),(70,'Top game online dành cho fan Manga Anime','top-game-online-danh-cho-fan-manga-anime','Fate/Grand OrderCuối cùng thì sau gần 2 năm kể từ khi được chính thức phát hành tại Nhật Bản thì mới đây, Fate/Grand Order - Tựa game online trên di động được xây dựng từ series visual novel Fate sẽ...','<p><strong>Fate/Grand Order</strong></p><p>Cuối cùng thì sau gần 2 năm kể từ khi được chính thức phát hành tại Nhật Bản thì mới đây, <strong>Fate/Grand Order</strong> - Tựa game online trên di động được xây dựng từ series visual novel Fate sẽ được phát hành phiên bản tiếng Anh vào mùa hè năm 2017 sắp tới đây.</p><p></p><p>Tại Nhật Bản, <strong>Fate/Grand Order</strong> có thể xem là một trong những tựa game di động thành công nhất. Ngay sau khi được phát hành vào năm 2015, <strong>Fate/Grand Order</strong> đã nhanh chóng đứng Top 1 bảng xếp hạng download trên AppStore lẫn GooglePlay, cũng như đứng đầu trong bảng xếp hạng Top Grossing.</p><p>Thậm chí, không chỉ người dân Nhật Bản mà thậm chí nhiều game thủ trên thế giới cũng rất ưa chuộng tựa game online này. Trong <strong>Fate/Grand Order</strong>, người chơi sẽ được vào vai một vị Master, từng bước thu phục các Servant vào đội hình của mình để tiêu diệt các thế lực đen tối đang muốn thôn tính thế giới.</p><p></p><p>Lối chơi của game được xây dựng theo phong cách thẻ tướng khá đặc sắc. Trong trận đấu, người chơi có thể kết hợp việc sử dụng các lá bài để cho các Servant của mình tung đòn tấn công, thậm chí kết hợp các lá bài lại để nhân vật tung ra được đòn tấn công đặc biệt với hiệu quả sát thương cao hơn.</p><p>Với cách chơi đề cao yếu tố chiến thuật, các Servant được chia ra làm nhiều class khác nhau trong một hệ thống tương tự trò Kéo - Búa - Bao. Saber mạnh hơn Lancer, Lancer mạnh hơn Archer và Archer lại mạnh hơn Saber. Berserker như lá wild card khi vừa gây vừa chịu sát thương gấp đôi. Ruler – class hiếm có nhất của game – là loại Servant duy nhất resist sát thương từ toàn bộ class khác trừ Avenger. Avenger là một class mới được cập nhật để giúp người chơi đối chọi với các servant là Ruler cấp cao. Có thể thấy hệ thống Servant trong dòng Fate đã được vận dụng cho lối chơi chiến thuật rất tốt.</p><p><strong>Twin Saga</strong></p><p><strong>Twin Saga</strong> vốn là một MMORPG hấp dẫn được sản xuất tại Đài Loan, và có tên gốc là Astral Realm. Astral Realm là một MMOPRG thuộc hàng \"đỉnh\" trên thế giới hiện nay, khi gameplay của trò chơi thiên về lối hành động, bên cạnh đồ họa được thiết kế theo phong cách anime khá dễ nhìn và tạo thiện cảm cho người chơi.</p><p></p><p>Cơ chế chiến đấu trong <strong>Twin Saga</strong> là sự kết hợp giữa cả lối đánh target và non-target, khi có những skill có thể dùng thẳng vào mục tiêu, trong khi sẽ có những skill buộc người chơi phải tự định hướng. Các skill tấn công trong <strong>Twin Saga</strong> đều được thực hiện theo nhiều nhịp, giúp cho nhân vật có thể thực hiện các đòn combo liên tục nhằm vào đối thủ.</p><p></p><p>Đặc biệt hơn, mỗi nhân vật đều sở hữu một skill Ultimate riêng, có khả năng gây sát thương lớn và thậm chí còn kèm theo hiệu ứng đặc biệt. Tất nhiên, các skill Ultimate của mỗi class thường có thời gian hồi khá lâu, và người chơi sẽ cần cân nhắc trước khi sử dụng. Việc kết hợp các đòn combo trong <strong>Twin Saga</strong> đóng vai trò quan trọng khi các đòn tấn công vào đối thủ sẽ còn gây thêm hiệu ứng khác nữa, hết sức thú vị.</p><p><strong>Heroes Evolved</strong></p><p>Chắc chắn các game thủ Việt vẫn còn nhớ tựa game online MOBA siêu nhẹ cực giống Liên Minh Huyền Thoại chạy trên nền web là <strong>Heroes Evolved</strong> đã phát triển mạnh trên toàn thế giới với sự tiện lợi của mình. Đến nay, thật bất ngờ là nhà phát hành của trò chơi này đã quyết định sẽ mở thêm server tại khu vực Đông Nam Á và phục vụ mọi nước tại đây, tất nhiên là bao gồm cả Việt Nam.</p><p></p><p>Rõ ràng đây là tin rất mừng cho các game thủ Việt đang muốn chơi <strong>Heroes Evolved</strong> bởi rõ ràng việc có server ở gần là hết sức tiện lợi, ping được tối ưu và trải nghiệm sẽ sướng hơn hẳn.</p><p></p><p><strong>Heroes Evolved</strong> là tựa game online nền tảng PC, đã có thêm cả bản mobile ấn tượng với bộ cài siêu nhẹ chỉ tốn khoảng 35 MB dung lượng ổ cứng mà thôi. Trò chơi vẫn đi theo phong cách MOBA truyền thống với mỗi team 5 người đấu nhau, phong cách chơi tương đối giống Liên Minh Huyền Thoại nhưng có nhiều map mới lạ hơn.</p><p><strong>Record of Lodoss War Online</strong></p><p><strong>Record of Lodoss War Online</strong> là game nhập vai 2,5D đúng chuẩn \'cổ điển\' mới đây đã ấn định mở cửa vào ngày 6/4 tại thị trường Hàn Quốc. Đây là trò chơi do L&K Logic Korea phát triển với nội dung dựa trên bộ manga kinh điển cùng tên, vốn rất nổi tiếng vào những năm 80 của thế kỷ trước.</p><p></p><p>Cũng giống như trong truyện, <strong>Record of Lodoss War Online</strong> kể về cuộc phiêu lưu của một chàng trai trẻ tên là Parn - con trai của một kỵ sỹ hết thời, bị tước danh hiệu. Từng bước trong cuộc hành trình của cậu là đi tìm hiểu xem chuyện gì đã xảy ra với cha mình và tìm cách phục hồi lại danh dự cho dòng họ. Từ đây, nhân vật chính của chúng ta gặp rất nhiều người bao gồm cả đồng bạn lẫn tử địch và rồi tìm ra rất nhiều bí mật ẩn giấu.</p><p></p><p>Về cơ bản thì lối chơi của <strong>Record of Lodoss War Online</strong> đi theo hướng nhập vai truyền thống, game thủ sẽ gặp các NPC để nhận nhiệm vụ rồi đi thực hiện. Tuỳ theo cấp độ mà các yêu cầu sẽ khó - dễ khác nhau, mọi thứ đều có hướng dẫn tương đối rõ ràng và chỉ đường cụ thể. Tất nhiên trò chơi còn có nhiều tính năng khác nữa cho game thủ khám phá trong quá trình chinh phục thế giới ảo.</p>',0,0,1,12,'12','anime mmo,manga games,japanese online games,anime style','LamGame Team',1,'/storage/blog/blog_70_thumb.jpg','vi',1,1,'Top game online dành cho fan Manga Anime | LamGame','Fate/Grand OrderCuối cùng thì sau gần 2 năm kể từ khi được chính thức phát hành tại Nhật Bản thì mới đây, Fate/Grand Order - Tựa game online trên di động được xây dựng từ series visual novel Fate sẽ...','anime mmo,manga games,japanese online games,anime style','2025-09-06 05:16:05','2025-09-05 14:46:05','2025-09-05 14:46:05',NULL),(71,'Azera Iron Heart - Game nhập vai 18+ mobile','azera-iron-heart-game-nhap-vai-18-mobile','Hé lộ suốt từ đầu năm ngoái nhưng phải cho đến tận bây giờ, Azera: Iron Heart - phiên bản di động của MMORPG 18+ Azera trên PC mới tiếp tục được Webzen, cha đẻ MU Online ấn định thời điểm ra mắt. Bản...','<p>Hé lộ suốt từ đầu năm ngoái nhưng phải cho đến tận bây giờ, <strong>Azera: Iron Heart</strong> - phiên bản di động của MMORPG 18+ Azera trên PC mới tiếp tục được Webzen, cha đẻ MU Online ấn định thời điểm ra mắt. Bản PC Azera của Timber Games đã không thành công tại Hàn Quốc. Thậm chí, với các nhân vật nữ gợi cảm và tính năng \"biến hình\" thành những bộ áo giáp Mech cực hầm hố cũng không giúp trò chơi được mấy.</p><p></p><p>Chính vì vậy, thông qua sự hợp tác với Timber Games, Webzen hy vọng sẽ cho ra mắt một game chất lượng cao trên di động. Được thiết kế với Engine tối tân, Azera: Iron Heart dự kiến sẽ là một game MMORPG 3D tuyệt đẹp, mang lối chiến đấu nhịp độ nhanh cùng nhiều hoạt động thú vị.</p><p>Được biết, game sẽ kế thừa cốt truyện và những tính năng cốt lõi nhất từ phiên bản gốc MMORPG 18+ Azera trên PC, tuy nhiên giao diện và hệ thống điều khiển cùng những tính năng cốt lõi nhất đã được tinh chỉnh, tối ưu hóa hết mức giành cho nền tảng di động.</p><p></p><p>Azera: Iron Heart vẫn lấy bối cảnh châu Âu giả tưởng, đồ họa full 3D phong cách người thật vô cùng chi tiết, đặc biệt là những class nhân vật nữ đều được tạo hình vô cùng quyến rũ. Đây là điểm đặc trưng thường thấy của những sản phẩm nhập vai đến từu xứ sở Kim Chi.</p><p></p><p>Để mang tới một trải nghiệm game nhập vai hoàn hảo trên di động, nhà phát triển đã tạo ra và lồng ghép hàng loạt những đoạn phim ngắn chuyển cảnh cực kỳ ấn tượng xen kẽ cốt truyện của Azera: Iron Heart giúp người chơi dần khám phá một cách thích thú.</p><p></p><p>Ngoài hệ thống nhân vật nữ xinh đẹp, nóng bỏng và nền đồ họa 3D bóng bẩy thì Azera: Iron Heart còn sở hữu trong mình hệ thống chuỗi combo đặc biệt, cho phép người chơi kết hợp hơn 80 kỹ năng khác nhau tạo ra các chiêu thức liên hoàn độc đáo. Bạn có thể khám phá một thế giới mở rộng lớn trong bộ Mech hào nhoáng và cuối cùng nhưng không kém phần quan trọng là những trận Guild vs Guild hoành tráng mà hầu hết các game MMORPG Hàn Quốc đều sở hữu.</p><p></p><p>Dự kiến, Azera: Iron Heart sẽ được đỡ đầu bởi hãng game nổi tiếng Webzen và đang lên kế hoạch ra mắt trong tháng 5 tới.</p>',0,0,1,16,'16','azera iron heart,mature rpg,mobile rpg,adult games','LamGame Team',1,'/storage/blog/blog_71_thumb.jpg','vi',1,1,'Azera Iron Heart - Game nhập vai 18+ mobile | LamGame','Hé lộ suốt từ đầu năm ngoái nhưng phải cho đến tận bây giờ, Azera: Iron Heart - phiên bản di động của MMORPG 18+ Azera trên PC mới tiếp tục được Webzen, cha đẻ MU Online ấn định thời điểm ra mắt. Bản...','azera iron heart,mature rpg,mobile rpg,adult games','2025-09-06 05:31:12','2025-09-05 14:46:12','2025-09-05 14:46:12',NULL),(72,'StarCraft miễn phí - Phiên bản \"cho không\" từ Blizzard','starcraft-mien-phi-phien-ban-cho-khong-tu-blizzard','Phần lớn mọi người đều đã sở hữu bản sao của trò chơi gốc StarCraft. Và nếu ai đó vẫn chưa tìm kiếm được đường dẫn để tải phiên bản “lậu” này, thì Blizzard đã cho phép bạn tải về trò chơi gốc...','<p>Phần lớn mọi người đều đã sở hữu bản sao của trò chơi gốc StarCraft. Và nếu ai đó vẫn chưa tìm kiếm được đường dẫn để tải phiên bản “lậu” này, thì Blizzard đã cho phép bạn tải về trò chơi gốc StarCraft, tựa game chiến thuật thời gian thực RTS huyền thoại.</p><p></p><p>Phiên bản Brood War 1.18 đã chính thức xuất hiện trên diễn đàn <em>Battle.net</em> ở tât cả các khu vực, không chỉ máy chủ thử nghiệm Public Test Realm (PTR).</p><p>Lưu ý rằng, bạn sẽ phải chạy trò chơi với quyền quản trị của máy tính trong lần đầu nhấp chuột truy cập. Nhưng trước tiên, bạn chỉ việc giải nén tệp tin có tên “StarCraft” và chạy file “StarCraft.exe”. Và nếu bạn sử dụng máy Mac và gặp phải vấn đề gì đó, đừng lo, bởi Blizzard sẽ sớm tung ra bản cập nhật chỉ vài ngày sau đây để hỗ trợ hệ điều hành OSX.</p><p>StarCraft chính thức trở thành tựa game miễn phí toàn cầu kể từ phiên bản 1.18, một động thái của Blizzard để chuẩn bị cho màn ra mắt StarCraft Remastered vào cuối năm nay.</p><p>Bạn sẽ cần một tài khoản PTR nếu muốn tham gia các trận đấu StarCraft 1.18 online, nhưng chế độ chơi Campaign không yêu cầu phức tạp như vậy. Toàn bộ nội dung tải về có dung lượng 1,5 GB; có hỗ trợ UPnP; tương thích với Windows 7, 8.1 & 10; có chế độ Khán Giả; tùy chỉnh phím tắt; và đương nhiên hỗ trợ hiển thị theo dạng cửa sổ hoặc khung viền màn hình…</p>',0,0,1,12,'12','starcraft free,blizzard,rts,free to play,strategy','LamGame Team',1,'/storage/blog/blog_72_thumb.jpg','vi',1,1,'StarCraft miễn phí - Phiên bản \"cho không\" từ Blizzard | LamGame','Phần lớn mọi người đều đã sở hữu bản sao của trò chơi gốc StarCraft. Và nếu ai đó vẫn chưa tìm kiếm được đường dẫn để tải phiên bản “lậu” này, thì Blizzard đã cho phép bạn tải về trò chơi gốc...','starcraft free,blizzard,rts,free to play,strategy','2025-09-06 05:46:18','2025-09-05 14:46:18','2025-09-05 14:46:18',NULL),(73,'Top 8 game thử thách não bộ cực hay','top-8-game-thu-thach-nao-bo-cuc-hay','Baldur’s Gate II: Enhanced EditionBaldur’s Gate II: Enhanced Edition, phiên bản remake của Baldur’s Gate nguyên bản. Về cơ bản, Baldur’s Gate II: Enhanced Edition không có nhiều đổi khác so với...','<p><strong>Baldur&rsquo;s Gate II: Enhanced Edition</strong></p>\r\n<p><strong>Baldur&rsquo;s Gate II: Enhanced Edition</strong>, phi&ecirc;n bản remake của Baldur&rsquo;s Gate nguy&ecirc;n bản. Về cơ bản, <strong>Baldur&rsquo;s Gate II: Enhanced Edition</strong> kh&ocirc;ng c&oacute; nhiều đổi kh&aacute;c so với &ldquo;người tiền nhiệm&rdquo;. Chỉ c&oacute; v&agrave;i nội dung mới được bổ sung bao gồm: vật phẩm, nh&acirc;n vật (Dorn Il-Khan Neera the Wild Mage v&agrave; Rasaad yn Bashir), campaign mới (The Black Pits),&hellip;</p>\r\n<p>&nbsp;</p>\r\n<p>B&ecirc;n cạnh đ&oacute;, chuẩn ph&acirc;n giải mới cũng được <strong>Baldur&rsquo;s Gate: Enhanced Edition</strong> hỗ trợ để ph&ugrave; hợp với cấu h&igrave;nh ng&agrave;y c&agrave;ng &ldquo;khủng&rdquo; của c&aacute;c m&aacute;y t&iacute;nh để b&agrave;n hay laptop. Lối chơi của game mobile n&agrave;y gần như kh&ocirc;ng c&oacute; bất kỳ thay đổi n&agrave;o so với phi&ecirc;n bản gốc. N&oacute; vẫn giữ được những tinh t&uacute;y của thể loại &ldquo;nhập vai nguy&ecirc;n thủy&rdquo;, được cho l&agrave; khởi nguồn cho những biến thể RPG sau n&agrave;y. V&igrave; lẽ đ&oacute;, bạn dễ d&agrave;ng nhận thấy sự phức tạp trong gameplay của game.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>DroidFish Chess</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Trong <strong>DroidFish Chess</strong> bạn c&oacute; thể thi đấu với đối thủ tr&ecirc;n c&ugrave;ng một thiết bị. Đ&acirc;y l&agrave; một trong những tr&ograve; chơi cờ vua cho ph&eacute;p người chơi l&agrave;m những g&igrave; họ muốn.</p>\r\n<p><strong>Duet</strong></p>\r\n<p><strong>Duet</strong> l&agrave; một game dạng chiến lược, đ&ograve;i hỏi bạn phải điều hướng để đi qua c&aacute;c chướng ngại vật bằng c&aacute;ch n&eacute; ch&uacute;ng theo đường cung mặc định h&igrave;nh tr&ograve;n.</p>\r\n<p>&nbsp;</p>\r\n<p>Duet c&oacute; game-play đơn giản, bạn chỉ cần chạm tay v&agrave;o 2 g&oacute;c m&agrave;n h&igrave;nh để điều khiển hai khối tr&ograve;n dịch chuyển sang tr&aacute;i hoặc phải. Tuy nhi&ecirc;n game mobile n&agrave;y lại khiến người chơi th&iacute;ch th&uacute; bởi độ kh&oacute; m&agrave; từng cấp độ mang lại. Chỉ cần chạm v&agrave;o một khung h&igrave;nh bạn sẽ phải quay lại vạch xuất ph&aacute;t.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Final Fantasy IV</strong></p>\r\n<p><strong>Final Fantasy IV</strong> ra mắt lần đầu ti&ecirc;n tr&ecirc;n hệ m&aacute;y SNES v&agrave;o năm 1991, l&agrave; tựa game đầu ti&ecirc;n trong series Final Fantasy giới thiệu đến người chơi hệ thống chiến đấu dạng b&aacute;n-lượt (Active Time Battle) rất được c&aacute;c fan y&ecirc;u th&iacute;ch, mỗi nh&acirc;n vật trong game l&uacute;c n&agrave;y sẽ c&oacute; thanh Active Time Battle Bar (ATB Bar) v&agrave; khi thanh n&agrave;y đầy, người chơi c&oacute; thể chọn h&agrave;nh động cho nh&acirc;n vật v&agrave; nh&acirc;n vật sẽ thực hiện ngay lập tức.</p>\r\n<p>&nbsp;</p>\r\n<p>B&ecirc;n cạnh đ&oacute;, đối phương vẫn c&oacute; thể tấn c&ocirc;ng nh&acirc;n vật của bạn nếu bạn kh&ocirc;ng h&agrave;nh động g&igrave; v&agrave; bạn c&oacute; thể chuyển lượt sang nh&acirc;n vật kh&aacute;c v&agrave; lượt của nh&acirc;n vật cũ vẫn được giữ nguy&ecirc;n. Hệ thống n&agrave;y được đ&aacute;nh gi&aacute; l&agrave; mang t&iacute;nh đột ph&aacute; v&agrave; đ&atilde; l&agrave;m n&ecirc;n th&agrave;nh c&ocirc;ng cho <strong>Final Fantasy IV</strong> so với c&aacute;c phi&ecirc;n bản <strong>Final Fantasy</strong> trước.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Pandemic</strong></p>\r\n<p>Trong khi hầu hết c&aacute;c boardgame, người chơi đều phải chiến đấu với bạn b&egrave; của m&igrave;nh, th&igrave; <strong>Pandemic</strong> lại gi&uacute;p bạn c&oacute; thể \"hợp sức\" với đồng đội của m&igrave;nh để ngăn chặn sự ph&aacute; huỷ của th&agrave;nh phố v&agrave; sau đ&oacute; sẽ c&ugrave;ng tham gia chữa khỏi ho&agrave;n to&agrave;n những đại dịch cho lo&agrave;i người.</p>\r\n<p>&nbsp;</p>\r\n<p>Thật kh&ocirc;ng may l&agrave; <strong>Pandemic</strong> lại kh&ocirc;ng c&oacute; phi&ecirc;n bản cao cấp AI, c&oacute; nghĩa l&agrave; người chơi sẽ phải chơi trực tuyến c&ugrave;ng với đồng đội hoặc sử dụng bản Pass-and-Play.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Pixel Dungeon</strong></p>\r\n<p><strong>Pixel Dungeon</strong> l&agrave; game mobile h&agrave;nh động nhập vai truyền thống với đồ họa pixel nghệ thuật v&agrave; giao diện đơn giản hơn rất nhiều so với những tr&ograve; chơi kh&aacute;c nhưng vẫn mang lại sự l&yacute; th&uacute; v&agrave; c&oacute; n&eacute;t cuốn h&uacute;t ri&ecirc;ng độc đ&aacute;o. Mặc d&ugrave; ra mắt tr&ecirc;n nền tảng Android trong thời gian ngắn nhưng trải nghiệm kh&aacute;m ph&aacute; hầm ngục n&agrave;y đ&atilde; nhanh ch&oacute;ng lọt v&agrave;o danh s&aacute;ch những game hay m&agrave; bạn n&ecirc;n thử.</p>\r\n<p>&nbsp;</p>\r\n<p>Điểm s&aacute;ng của <strong>Pixel Dungeon</strong> ch&iacute;nh l&agrave; gameplay đặc trưng của thể loại game RPG, nh&agrave; ph&aacute;t triển đ&atilde; ph&aacute;t triển rất tốt điều n&agrave;y. Trong game bạn c&oacute; thể thu thập c&aacute;c vật phẩm hữu &iacute;ch để n&acirc;ng cao sức mạnh của m&igrave;nh, chống lại những qu&aacute;i vật to lớn để t&igrave;m ra Amulet của Yendor. Đ&acirc;y l&agrave; thứ cổ vật cuối c&ugrave;ng của <strong>Pixel Dungeon</strong>, đ&oacute;ng vai tr&ograve; quyết định cho kết cục của to&agrave;n bộ cuộc chơi. Hiện tại game offline n&agrave;y đang c&oacute; 15 cấp độ, dự kiến c&oacute; th&ecirc;m 4-5 cấp độ với những con qu&aacute;i vật mới v&agrave; c&aacute;c vật phẩm mới trong bản cập nhật sắp tới.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Super Hexagon</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Trong <strong>Super Hexagon</strong>, bạn phải điều khiển một h&igrave;nh tam gi&aacute;c xoay quanh đa gi&aacute;c ở giữa. Trong khi đ&oacute;, bức tường xung quanh li&ecirc;n tục thu hẹp lại, chỉ để lại khoảng trống rất nhỏ. Người chơi phải l&agrave;m sao để h&igrave;nh tam gi&aacute;c kh&ocirc;ng chạm v&agrave;o bức tường m&agrave; vẫn lọt qua khoảng trống đ&oacute;. Tốc độ game c&oacute; thể tăng tới mức ch&oacute;ng mặt v&agrave; vị tr&iacute; của bức tường lu&ocirc;n thay đổi.</p>\r\n<p><strong>XCOM: Enemy Within</strong></p>\r\n<p><strong>XCOM: Enemy Within</strong> l&agrave; bản mở rộng ch&iacute;nh thức của tựa game đ&igrave;nh d&aacute;m <strong>XCOM: Enemy Unknown</strong> do Firaxis ph&aacute;t triển v&agrave; 2K Games ph&aacute;t h&agrave;nh. C&acirc;u chuyện của game tiếp tục kể về cuộc chiến của biệt đội XCOM chống lại c&aacute;c thế lực qu&aacute;i vật ngo&agrave;i h&agrave;nh tinh đang nhăm nhe biến Tr&aacute;i Đất th&agrave;nh \"qu&ecirc; hương\" thứ hai của ch&uacute;ng.</p>\r\n<p>&nbsp;</p>\r\n<p>Ở bản mở rộng lần n&agrave;y, điểm mới lạ nhất đến từ lớp nh&acirc;n vật mới: Mec Trooper - chiến binh nửa người nửa m&aacute;y sẽ s&aacute;t c&aacute;nh c&ugrave;ng những đồng đội Assault, Heavy, Support, Sniper, v&agrave; cả robot Drone, mang lại những chiến thuật độc đ&aacute;o gi&uacute;p tiểu đội XCOM giữ được sự c&acirc;n bằng trước những kẻ x&acirc;m lược ngo&agrave;i h&agrave;nh tinh ng&agrave;y c&agrave;ng mạnh.</p>\r\n<p>&nbsp;</p>',0,0,1,12,'12','2,3','Example',1,'','en',1,1,'Top 8 game thử thách não bộ cực hay | LamGame','Baldur’s Gate II: Enhanced EditionBaldur’s Gate II: Enhanced Edition, phiên bản remake của Baldur’s Gate nguyên bản. Về cơ bản, Baldur’s Gate II: Enhanced Edition không có nhiều đổi khác so với...','brain games,puzzle games,challenging games,mind games','2025-09-06 00:00:00','2025-09-05 14:46:25','2025-09-12 15:47:18',NULL),(74,'The Walking Dead: No Man\'s Land - Khi cái chết trỗi dậy','the-walking-dead-no-man-s-land-khi-cai-chet-troi-day','The Walking Dead: No Man’s Land - Khi cái chết trỗi dậyThe Walking Dead: No Man’s Land là một game mobile do AMC phối hợp cùng Next Games phát hành. Có thể nói, đây là phiên bản game di động chính...','<h1 id=\"the-walking-dead-no-mans-land---khi-cái-chết-trỗi-dậy\"><strong>The Walking Dead: No Man’s Land - Khi cái chết trỗi dậy</strong></h1><p>The Walking Dead: No Man’s Land là một game mobile do AMC phối hợp cùng Next Games phát hành. Có thể nói, đây là phiên bản game di động chính thức của series phim chủ đề zombie ăn khách nhất mọi thời đại The Walking Dead, đưa người chơi đến với cuộc chiến sống còn giữa bè lũ xác sống đáng sợ và những gì còn lại của loài người.</p><p>The Walking Dead: No Man’s Land là một trong những game mobile hiếm hoi hội tụ đầy đủ những yếu tố gây nghiện hàng đầu. Yếu tố đầu tiên phải nói đến chính là tính chiến thuật trong game, ngay sau đó là những trận đánh theo lượt kịch tính, mãn nhãn và cuối cùng chính là hệ thống phát triển nhân vật thông minh cùng cơ chế điều khiển mượt mà cho phép zoom-in/out tiện lợi ngay trong quá trình đánh trận.</p><p></p><p>The Walking Dead: No Man’s Land cho phép người chơi lựa chọn giữa 6 lớp nhân vật và mỗi lớp nhân vật khác nhau sở hữu năng lực và thế mạnh khác nhau để bạn có thể lựa chọn tùy vào mỗi màn chơi song song với 10 chương thuộc nhiệm vụ theo cốt truyện cùng với các thử thách theo tuần, trò chơi hứa hẹn sẽ khiến bạn mất khoảng thời gian nhiều tháng để có thể xử lý tất cả.</p><p></p><p>Cơ chế gameplay của The Walking Dead: No Man’s Land là cơ thế chơi theo lượt, khá giống với nhiều game di động hiện tại .Khi vào một màn chơi, bạn sẽ điều khiển team gồm 3 nhân vật, sử dụng thao tác vuốt trên màn hình để vẽ đường di chuyển. Bạn sẽ có những điểm hành động dùng để di chuyển, tấn công zombie hoặc để mở một vật gì đó như rương cửa,... và nếu nhân vật của bạn không sử dụng hết những điểm đó trong một lượt đi thì họ sẽ lập tức trở về trạng thái sẵn sàng phản công khi zombie tới gần.</p><p></p><p>Cùng với việc tìm diệt zombie ở những màn chơi, bạn sẽ cần phải “dọn sạch” nơi trú ẩn và tìm được các yếu tố để xây dựng như phương tiện đi lại, lều trị thương, khu trồng trọt, khu huấn luyện… Tại đây, bạn sẽ dần tập hợp số lượng người sống sót đông hơn, huấn luyện, chữa trị cho họ để cùng nhau mở rộng hơn nữa vùng an toàn. Ngoài ra, bạn đừng quên thu thập các vật phẩm sinh tồn như đồ ăn, tiền, nhiên liệu, radio… tất cả đều có vai trò trong việc bảo đảm sự sống cho các nhân vật, kết nối con người để mở rộng cộng đồng.</p><p><a href=\"https://www.youtube.com/watch?v=QgKVHNA6yvg\"><span class=\"underline\">https://www.youtube.com/watch?v=QgKVHNA6yvg</span></a></p><p>Nếu quan tâm game bạn có thể download tại: <a href=\"https://itunes.apple.com/us/app/the-walking-dead-no-mans-land/id970417047?mt=8\"><span class=\"underline\">IOS</span></a>/<a href=\"https://play.google.com/store/apps/details?id=com.nextgames.android.twd&hl=vi\"><span class=\"underline\">Android</span></a>.</p>',0,0,1,16,'16','the walking dead,zombie game,strategy,mobile game,survival','LamGame Team',1,'/storage/blog/blog_74_thumb.jpg','vi',1,1,'The Walking Dead: No Man\'s Land - Khi cái chết trỗi dậy | LamGame','The Walking Dead: No Man’s Land - Khi cái chết trỗi dậyThe Walking Dead: No Man’s Land là một game mobile do AMC phối hợp cùng Next Games phát hành. Có thể nói, đây là phiên bản game di động chính...','the walking dead,zombie game,strategy,mobile game,survival','2025-09-05 15:00:09','2025-09-05 08:00:09','2025-09-05 08:00:09',NULL),(75,'This War of Mine - Phản ánh người trong chiến tranh','this-war-of-mine-phan-anh-nguoi-trong-chien-tranh','This War of Mine – Phậnngười trong chiến tranh.Thực tế, hầu hết những tựa game lấy chủ đề chiến tranh đều đặt người chơi vào vai trò của một người lính được trang bị tận răng với những khung cảnh...','<h1 id=\"this-war-of-mine-phậnngười-trong-chiến-tranh.\"><strong>This War of Mine &ndash; Phậnngười trong chiến tranh.</strong></h1>\r\n<p>Thực tế, hầu hết những tựa game lấy chủ đề chiến tranh đều đặt người chơi v&agrave;o vai tr&ograve; của một người l&iacute;nh được trang bị tận răng với những khung cảnh ho&agrave;nh tr&aacute;ng v&agrave; &aacute;c liệt. Tuy nhi&ecirc;n, This War of Mine lại kh&ocirc;ng c&oacute; bất k&igrave; người l&iacute;nh si&ecirc;u nh&acirc;n n&agrave;o, cũng kh&ocirc;ng hề c&oacute; những cắt cảnh ho&agrave;nh tr&aacute;ng hay ấn tượng. 11 Bit Studio đặt người chơi v&agrave;o ngay nhiệm vụ cấp b&aacute;ch nhất m&agrave; những người sống s&oacute;t phải thực hiện: Cố gắng tồn tại giữa những hiểm nguy đầy rẫy b&ecirc;n trong th&agrave;nh phố bị bao v&acirc;y.</p>\r\n<p>&nbsp;</p>\r\n<p>Trong game, bạn được ph&eacute;p chọn nhiều nh&acirc;n vật v&ocirc; danh kh&aacute;c nhau trong game. Mỗi nh&acirc;n vật đều c&oacute; một ho&agrave;n cảnh kh&aacute;c nhau. Thế nhưng tất cả đều phải c&ugrave;ng nhau sống s&oacute;t qua những mối hiểm họa kh&aacute;c nhau, từ những g&atilde; l&iacute;nh bắn tỉa nằm rải r&aacute;c quanh th&agrave;nh phố, cho tới cơn đ&oacute;i, r&eacute;t hay bệnh tật, v&agrave; thậm ch&iacute; l&agrave; cả những kẻ sống s&oacute;t kh&aacute;c, chấp nhận l&agrave;m mọi thứ để sinh tồn.</p>\r\n<p>&nbsp;</p>\r\n<p>Mỗi nh&acirc;n vật trong game đều c&oacute; những kỹ năng ri&ecirc;ng, v&iacute; dụ như chạy nhanh, c&oacute; khả năng nấu ăn hoặc khả năng thu thập nhu yếu phẩm tốt. C&ocirc;ng việc của bạn sẽ l&agrave; tập hợp họ lại v&agrave; ph&acirc;n chia c&ocirc;ng việc một c&aacute;ch hợp l&yacute; để kh&ocirc;ng c&oacute; chuyện một người kiệt sức v&igrave; phải l&agrave;m tất cả mọi việc.</p>\r\n<p>&nbsp;</p>\r\n<p>Đồ họa của This War of Mine kh&ocirc;ng c&oacute; g&igrave; qu&aacute; nổi bật nếu x&eacute;t tới tổng quan đồ họa trong game n&oacute;i ri&ecirc;ng cũng như so với c&aacute;c thể loại game sinh tồn kh&aacute;c n&oacute;i chung. Tuy nhi&ecirc;n c&oacute; một điểm rất đặc biệt khiến This War of Mine trở n&ecirc;n &ldquo;Độc nhất v&ocirc; nhị\" ch&iacute;nh l&agrave; từ những n&eacute;t vẽ, những h&igrave;nh th&ugrave; người rệu r&atilde;, mệt mỏi sau một ng&agrave;y canh g&aacute;c m&agrave; kh&ocirc;ng được ngủ, những cột kh&oacute;i ở nền m&agrave;n h&igrave;nh, hay những n&eacute;t vẽ ch&igrave; nguệch ngoạc lại tạo ra một bầu kh&ocirc;ng kh&iacute; cực kỳ u &aacute;m trong game.</p>\r\n<p>&nbsp;</p>\r\n<p>B&ecirc;n cạnh đ&oacute;, T&ocirc; điểm th&ecirc;m c&aacute;i kh&ocirc;ng kh&iacute; u buồn lu&ocirc;n bao tr&ugrave;m trong game ch&iacute;nh l&agrave; những bản nhạc nền với violon l&agrave;m chủ đạo. Những bản nhạc buồn, man m&aacute;c ai o&aacute;n cũng khiến cho bầu kh&ocirc;ng kh&iacute; của game trở n&ecirc;n tối tăm, lạnh lẽo v&agrave; thiếu sức sống hơn rất nhiều.</p>\r\n<p>https://www.youtube.com/watch?v=Hxf1seOpijE</p>\r\n<p>T&oacute;m lại, This War of Mine giống như một c&acirc;u chuyện ngo&agrave;i đời thực, khi bạn kh&ocirc;ng thể sống lại ở một checkpoint trước để &ldquo;sửa sai. Bạn sẽ phải sống tiếp với những quyết định của bản th&acirc;n d&ugrave; điều đ&uacute;ng hay sai đi chăng nữa.</p>\r\n<p>Nếu quan t&acirc;m game bạn c&oacute; thể mua game tại: <a href=\"https://itunes.apple.com/us/app/this-war-of-mine/id982175678?mt=8\"><span class=\"underline\">IOS</span></a>/<a href=\"https://play.google.com/store/apps/details?id=com.elevenbitstudios.twommobile&amp;hl=vi\"><span class=\"underline\">Android</span></a>/<a href=\"http://store.steampowered.com/app/282070/\"><span class=\"underline\">Steam</span></a></p>',0,0,1,12,'12','5','Example',1,'blogs/75/kcXEb7Rcze2CI04syoDLalPyjw8nwWxSUNlL5N0y.webp','en',1,1,'This War of Mine - Phản ánh người trong chiến tranh | LamGame','This War of Mine – Phậnngười trong chiến tranh.Thực tế, hầu hết những tựa game lấy chủ đề chiến tranh đều đặt người chơi vào vai trò của một người lính được trang bị tận răng với những khung cảnh...','this war of mine,war game,survival,indie game,emotional','2025-09-05 00:00:00','2025-09-05 08:00:14','2025-09-20 05:38:10',NULL),(76,'Tools phát triển game HTML5 tốt nhất 2024','tools-phat-trien-game-html5-tot-nhat-2024','http://developer.blackberry.com/html5/documentation/http://developer.blackberry.com/html5/documentation/webworks_testing.htmlhttp://developer.blackberry.com/html5/documentation/signing_setup.htmlhttp:...','<p><a href=\"http://developer.blackberry.com/html5/documentation/\"><span class=\"underline\">http://developer.blackberry.com/html5/documentation/</span></a></p>\r\n<p><a href=\"http://developer.blackberry.com/html5/documentation/webworks_testing.html\"><span class=\"underline\">http://developer.blackberry.com/html5/documentation/webworks_testing.html</span></a></p>\r\n<p><a href=\"http://developer.blackberry.com/html5/documentation/signing_setup.html\"><span class=\"underline\">http://developer.blackberry.com/html5/documentation/signing_setup.html</span></a></p>\r\n<p><a href=\"http://developer.blackberry.com/html5/documentation/getting_started_with_ripple_1866966_11.html\"><span class=\"underline\">http://developer.blackberry.com/html5/documentation/getting_started_with_ripple_1866966_11.html</span></a></p>\r\n<p><a href=\"http://developer.blackberry.com/html5/documentation/creating_hello_world.html\"><span class=\"underline\">http://developer.blackberry.com/html5/documentation/creating_hello_world.html</span></a></p>\r\n<p>load sound:</p>\r\n<p><a href=\"http://html5doctor.com/html5-audio-the-state-of-play/\"><span class=\"underline\">http://html5doctor.com/html5-audio-the-state-of-play/</span></a></p>\r\n<p><a href=\"http://www.createjs.com/#!/SoundJS\"><span class=\"underline\">http://www.createjs.com/#!/SoundJS</span></a></p>\r\n<p><a href=\"https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Using_HTML5_audio_and_video\"><span class=\"underline\">https://developer.mozilla.org/en-US/docs/Web/Guide/HTML/Using_HTML5_audio_and_video</span></a></p>\r\n<p>http://css-tricks.com/play-sound-on-hover/</p>',0,0,1,1,'8','3','Example',1,'blogs/76/0UjFA8CZDVgUOFMNnsLRqCUcaxfJDGLv0Y953735.webp','en',1,1,'Tools phát triển game HTML5 tốt nhất 2024 | LamGame','http://developer.blackberry.com/html5/documentation/','html5 games,game development,web games,development tools,programming','2025-09-05 00:00:00','2025-09-05 08:00:19','2025-09-20 05:35:02',NULL),(77,'Free Fire Arena - Tổng kết giải đấu FFA b c','free-fire-arena-tong-ket-giai-đau-ffa-b-c','Tổng kết “Free For All”: Ngôi vô địch gọi tên xạ thủ xuất sắc và bản lĩnh nhấtKết quả giải đấu “Free For All”Thân chào các xạ thủ,Giải đấu dành cho những xạ thủ tài năng nhất tháng 10 đã chính thứ...','<p><strong>Tổng kết &ldquo;Free For All&rdquo;: Ng&ocirc;i v&ocirc; địch gọi t&ecirc;n xạ thủ xuất sắc v&agrave; bản lĩnh nhất</strong></p>\r\n<p><em>Kết quả giải đấu &ldquo;Free For All&rdquo;</em></p>\r\n<p>Th&acirc;n ch&agrave;o c&aacute;c xạ thủ,</p>\r\n<p>Giải đấu d&agrave;nh cho những xạ thủ t&agrave;i năng nhất th&aacute;ng 10 đ&atilde; ch&iacute;nh thứ kết th&uacute;c h&ocirc;m 28/10 vừa qua. Với sự g&oacute;p mặt của 150 xạ thủ đến từ mọi miền tổ quốc, giải đấu đ&atilde; chứng kiến rất nhiều pha xử l&yacute; đỉnh cao v&agrave; &ldquo;tay to&rdquo;.</p>\r\n<p>&nbsp;</p>\r\n<p>BQT xin ch&uacute;c mừng c&aacute;c xạ thủ sau đ&atilde; xuất sắc bước v&agrave;o trận chung kết</p>\r\n<table>\r\n<thead>\r\n<tr class=\"header\">\r\n<th>&raquo;NamCON&laquo;</th>\r\n<th>-Team[11]</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&szlig;&iexcl;&aring;H&Ntilde;&not;V&micro;&iexcl;V&euml;</td>\r\n<td>EmXinL&ocirc;iAnh</td>\r\n</tr>\r\n<tr class=\"even\">\r\n<td>AnhSo*MayQua</td>\r\n<td>&szlig;GS&not;&raquo;T&iuml;&euml;&ntilde;&deg;&pound;&yen;</td>\r\n</tr>\r\n<tr class=\"odd\">\r\n<td>PWM_Tris.TT</td>\r\n<td>FL-MyCoi</td>\r\n</tr>\r\n<tr class=\"even\">\r\n<td>-Team[1]</td>\r\n<td>&raquo;D..&macr;&macr;&para;&macr;&macr;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Qua hai trận chung kết đầy căng thẳng, với kinh nghiệm chiến trường, t&acirc;m l&yacute; thi đấu vững v&agrave;ng v&agrave; kĩ năng ho&agrave;n hảo, xạ thủ &raquo;NamCON&laquo; đ&atilde; xuất sắc gi&agrave;nh chức v&ocirc; địch với tổng kill/death hai lượt trận l&agrave; 129/93, c&aacute;c xạ thủ PWM_Tris.TT v&agrave; AnhSo*MayQua cũng kh&ocirc;ng k&eacute;m cạnh khi dừng ch&acirc;n ở vị tr&iacute; thứ hai v&agrave; thứ ba với tổng kill/death lần lượt l&agrave; 109/90 v&agrave; 107/99. C&aacute;c xạ thủ kh&aacute;c cũng b&aacute;m đu&ocirc;i với chỉ số kill/death cực k&igrave; s&aacute;t sao.</p>\r\n<table>\r\n<thead>\r\n<tr class=\"header\">\r\n<th><strong>T&ecirc;n nh&acirc;n vật</strong></th>\r\n<th><strong>Kill lượt 1</strong></th>\r\n<th><strong>Death lượt 1</strong></th>\r\n<th><strong>Kill lượt 2</strong></th>\r\n<th><strong>Death lượt 2</strong></th>\r\n<th><strong>Tổng Kill</strong></th>\r\n<th><strong>Tổng Death</strong></th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr class=\"odd\">\r\n<td>&raquo;NamCON&laquo;</td>\r\n<td>75</td>\r\n<td>56</td>\r\n<td>54</td>\r\n<td>37</td>\r\n<td>129</td>\r\n<td>93</td>\r\n</tr>\r\n<tr class=\"even\">\r\n<td>PWM_Tris.TT</td>\r\n<td>61</td>\r\n<td>53</td>\r\n<td>48</td>\r\n<td>37</td>\r\n<td>109</td>\r\n<td>90</td>\r\n</tr>\r\n<tr class=\"odd\">\r\n<td>AnhSo*MayQua</td>\r\n<td>66</td>\r\n<td>59</td>\r\n<td>41</td>\r\n<td>40</td>\r\n<td>107</td>\r\n<td>99</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><em><span class=\"underline\">Kết quả của 3 xạ thủ xuất sắc lượt trận chung kết</span></em></p>\r\n<p>Một lần nữa xin ch&uacute;c mừng c&aacute;c xạ thủ đ&atilde; gi&agrave;nh giải thưởng của BQT, mong c&aacute;c xạ thủ sẽ tiếp tục gắn b&oacute; v&agrave; đồng h&agrave;nh c&ugrave;ng Đột K&iacute;ch trong thời gian sắp tới.</p>\r\n<p>Ch&uacute;c c&aacute;c xạ thủ chơi game vui vẻ.</p>',0,0,1,16,'16','1','Example',1,'blogs/77/1GbSQyBzykdpPmhnnDfiXfAxoIqoKEE4vZz9zELU.webp','en',1,1,'Free Fire Arena - Tổng kết giải đấu FFA | LamGame','Tổng kết “Free For All”: Ngôi vô địch gọi tên xạ thủ xuất sắc và bản lĩnh nhất Kết quả giải đấu “Free For All”Thân chào các xạ thủ, giải đấu dành cho những xạ thủ tài năng nhất tháng 10 đã chính thứ...','free fire,ffa,battle royale,esports,mobile gaming','2025-09-05 00:00:00','2025-09-05 08:00:27','2025-09-20 04:53:22',NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_product_appointment_slots`
--

DROP TABLE IF EXISTS `booking_product_appointment_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_product_appointment_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_product_id` int unsigned NOT NULL,
  `duration` int DEFAULT NULL,
  `break_time` int DEFAULT NULL,
  `same_slot_all_days` tinyint(1) DEFAULT NULL,
  `slots` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_product_appointment_slots_booking_product_id_foreign` (`booking_product_id`),
  CONSTRAINT `booking_product_appointment_slots_booking_product_id_foreign` FOREIGN KEY (`booking_product_id`) REFERENCES `booking_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_product_appointment_slots`
--

LOCK TABLES `booking_product_appointment_slots` WRITE;
/*!40000 ALTER TABLE `booking_product_appointment_slots` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_product_appointment_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_product_default_slots`
--

DROP TABLE IF EXISTS `booking_product_default_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_product_default_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_product_id` int unsigned NOT NULL,
  `booking_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int DEFAULT NULL,
  `break_time` int DEFAULT NULL,
  `slots` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_product_default_slots_booking_product_id_foreign` (`booking_product_id`),
  CONSTRAINT `booking_product_default_slots_booking_product_id_foreign` FOREIGN KEY (`booking_product_id`) REFERENCES `booking_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_product_default_slots`
--

LOCK TABLES `booking_product_default_slots` WRITE;
/*!40000 ALTER TABLE `booking_product_default_slots` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_product_default_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_product_event_ticket_translations`
--

DROP TABLE IF EXISTS `booking_product_event_ticket_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_product_event_ticket_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_product_event_ticket_id` bigint unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bpet_locale_unique` (`booking_product_event_ticket_id`,`locale`),
  CONSTRAINT `bpet_translations_fk` FOREIGN KEY (`booking_product_event_ticket_id`) REFERENCES `booking_product_event_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_product_event_ticket_translations`
--

LOCK TABLES `booking_product_event_ticket_translations` WRITE;
/*!40000 ALTER TABLE `booking_product_event_ticket_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_product_event_ticket_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_product_event_tickets`
--

DROP TABLE IF EXISTS `booking_product_event_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_product_event_tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_product_id` int unsigned NOT NULL,
  `price` decimal(12,4) DEFAULT '0.0000',
  `qty` int DEFAULT '0',
  `special_price` decimal(12,4) DEFAULT NULL,
  `special_price_from` datetime DEFAULT NULL,
  `special_price_to` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_product_event_tickets_booking_product_id_foreign` (`booking_product_id`),
  CONSTRAINT `booking_product_event_tickets_booking_product_id_foreign` FOREIGN KEY (`booking_product_id`) REFERENCES `booking_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_product_event_tickets`
--

LOCK TABLES `booking_product_event_tickets` WRITE;
/*!40000 ALTER TABLE `booking_product_event_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_product_event_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_product_rental_slots`
--

DROP TABLE IF EXISTS `booking_product_rental_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_product_rental_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_product_id` int unsigned NOT NULL,
  `renting_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `daily_price` decimal(12,4) DEFAULT '0.0000',
  `hourly_price` decimal(12,4) DEFAULT '0.0000',
  `same_slot_all_days` tinyint(1) DEFAULT NULL,
  `slots` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_product_rental_slots_booking_product_id_foreign` (`booking_product_id`),
  CONSTRAINT `booking_product_rental_slots_booking_product_id_foreign` FOREIGN KEY (`booking_product_id`) REFERENCES `booking_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_product_rental_slots`
--

LOCK TABLES `booking_product_rental_slots` WRITE;
/*!40000 ALTER TABLE `booking_product_rental_slots` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_product_rental_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_product_table_slots`
--

DROP TABLE IF EXISTS `booking_product_table_slots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_product_table_slots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_product_id` int unsigned NOT NULL,
  `price_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_limit` int NOT NULL DEFAULT '0',
  `duration` int NOT NULL,
  `break_time` int NOT NULL,
  `prevent_scheduling_before` int NOT NULL,
  `same_slot_all_days` tinyint(1) DEFAULT NULL,
  `slots` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_product_table_slots_booking_product_id_foreign` (`booking_product_id`),
  CONSTRAINT `booking_product_table_slots_booking_product_id_foreign` FOREIGN KEY (`booking_product_id`) REFERENCES `booking_products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_product_table_slots`
--

LOCK TABLES `booking_product_table_slots` WRITE;
/*!40000 ALTER TABLE `booking_product_table_slots` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_product_table_slots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_products`
--

DROP TABLE IF EXISTS `booking_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int DEFAULT '0',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_location` tinyint(1) NOT NULL DEFAULT '0',
  `available_every_week` tinyint(1) DEFAULT NULL,
  `available_from` datetime DEFAULT NULL,
  `available_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_products_product_id_foreign` (`product_id`),
  CONSTRAINT `booking_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_products`
--

LOCK TABLES `booking_products` WRITE;
/*!40000 ALTER TABLE `booking_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned DEFAULT NULL,
  `order_item_id` int unsigned DEFAULT NULL,
  `order_id` int unsigned DEFAULT NULL,
  `qty` int DEFAULT '0',
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `booking_product_event_ticket_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_order_item_id_foreign` (`order_item_id`),
  KEY `bookings_booking_product_event_ticket_id_foreign` (`booking_product_event_ticket_id`),
  KEY `bookings_order_id_foreign` (`order_id`),
  KEY `bookings_product_id_foreign` (`product_id`),
  CONSTRAINT `bookings_booking_product_event_ticket_id_foreign` FOREIGN KEY (`booking_product_event_ticket_id`) REFERENCES `booking_product_event_tickets` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_gift` tinyint(1) NOT NULL DEFAULT '0',
  `items_count` int DEFAULT NULL,
  `items_qty` decimal(12,4) DEFAULT NULL,
  `exchange_rate` decimal(12,4) DEFAULT NULL,
  `global_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grand_total` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total` decimal(12,4) DEFAULT '0.0000',
  `sub_total` decimal(12,4) DEFAULT '0.0000',
  `base_sub_total` decimal(12,4) DEFAULT '0.0000',
  `tax_total` decimal(12,4) DEFAULT '0.0000',
  `base_tax_total` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `shipping_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `checkout_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_guest` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `applied_cart_rule_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int unsigned DEFAULT NULL,
  `channel_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_customer_id_foreign` (`customer_id`),
  KEY `cart_channel_id_foreign` (`channel_id`),
  CONSTRAINT `cart_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_item_inventories`
--

DROP TABLE IF EXISTS `cart_item_inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_item_inventories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `qty` int unsigned NOT NULL DEFAULT '0',
  `inventory_source_id` int unsigned DEFAULT NULL,
  `cart_item_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_item_inventories`
--

LOCK TABLES `cart_item_inventories` WRITE;
/*!40000 ALTER TABLE `cart_item_inventories` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_item_inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `quantity` int unsigned NOT NULL DEFAULT '0',
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_weight` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total_weight` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `price` decimal(12,4) NOT NULL DEFAULT '1.0000',
  `base_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `custom_price` decimal(12,4) DEFAULT NULL,
  `total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `tax_percent` decimal(12,4) DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_percent` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `applied_tax_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `product_id` int unsigned NOT NULL,
  `cart_id` int unsigned NOT NULL,
  `tax_category_id` int unsigned DEFAULT NULL,
  `applied_cart_rule_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_parent_id_foreign` (`parent_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  KEY `cart_items_cart_id_foreign` (`cart_id`),
  KEY `cart_items_tax_category_id_foreign` (`tax_category_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `cart_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_payment`
--

DROP TABLE IF EXISTS `cart_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_payment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_payment_cart_id_foreign` (`cart_id`),
  CONSTRAINT `cart_payment_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_payment`
--

LOCK TABLES `cart_payment` WRITE;
/*!40000 ALTER TABLE `cart_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rule_channels`
--

DROP TABLE IF EXISTS `cart_rule_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rule_channels` (
  `cart_rule_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  PRIMARY KEY (`cart_rule_id`,`channel_id`),
  KEY `cart_rule_channels_channel_id_foreign` (`channel_id`),
  CONSTRAINT `cart_rule_channels_cart_rule_id_foreign` FOREIGN KEY (`cart_rule_id`) REFERENCES `cart_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_rule_channels_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rule_channels`
--

LOCK TABLES `cart_rule_channels` WRITE;
/*!40000 ALTER TABLE `cart_rule_channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rule_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rule_coupon_usage`
--

DROP TABLE IF EXISTS `cart_rule_coupon_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rule_coupon_usage` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `times_used` int NOT NULL DEFAULT '0',
  `cart_rule_coupon_id` int unsigned NOT NULL,
  `customer_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_rule_coupon_usage_cart_rule_coupon_id_foreign` (`cart_rule_coupon_id`),
  KEY `cart_rule_coupon_usage_customer_id_foreign` (`customer_id`),
  CONSTRAINT `cart_rule_coupon_usage_cart_rule_coupon_id_foreign` FOREIGN KEY (`cart_rule_coupon_id`) REFERENCES `cart_rule_coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_rule_coupon_usage_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rule_coupon_usage`
--

LOCK TABLES `cart_rule_coupon_usage` WRITE;
/*!40000 ALTER TABLE `cart_rule_coupon_usage` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rule_coupon_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rule_coupons`
--

DROP TABLE IF EXISTS `cart_rule_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rule_coupons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usage_limit` int unsigned NOT NULL DEFAULT '0',
  `usage_per_customer` int unsigned NOT NULL DEFAULT '0',
  `times_used` int unsigned NOT NULL DEFAULT '0',
  `type` int unsigned NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `expired_at` date DEFAULT NULL,
  `cart_rule_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_rule_coupons_cart_rule_id_foreign` (`cart_rule_id`),
  CONSTRAINT `cart_rule_coupons_cart_rule_id_foreign` FOREIGN KEY (`cart_rule_id`) REFERENCES `cart_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rule_coupons`
--

LOCK TABLES `cart_rule_coupons` WRITE;
/*!40000 ALTER TABLE `cart_rule_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rule_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rule_customer_groups`
--

DROP TABLE IF EXISTS `cart_rule_customer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rule_customer_groups` (
  `cart_rule_id` int unsigned NOT NULL,
  `customer_group_id` int unsigned NOT NULL,
  PRIMARY KEY (`cart_rule_id`,`customer_group_id`),
  KEY `cart_rule_customer_groups_customer_group_id_foreign` (`customer_group_id`),
  CONSTRAINT `cart_rule_customer_groups_cart_rule_id_foreign` FOREIGN KEY (`cart_rule_id`) REFERENCES `cart_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_rule_customer_groups_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rule_customer_groups`
--

LOCK TABLES `cart_rule_customer_groups` WRITE;
/*!40000 ALTER TABLE `cart_rule_customer_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rule_customer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rule_customers`
--

DROP TABLE IF EXISTS `cart_rule_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rule_customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `times_used` bigint unsigned NOT NULL DEFAULT '0',
  `customer_id` int unsigned NOT NULL,
  `cart_rule_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_rule_customers_cart_rule_id_foreign` (`cart_rule_id`),
  KEY `cart_rule_customers_customer_id_foreign` (`customer_id`),
  CONSTRAINT `cart_rule_customers_cart_rule_id_foreign` FOREIGN KEY (`cart_rule_id`) REFERENCES `cart_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_rule_customers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rule_customers`
--

LOCK TABLES `cart_rule_customers` WRITE;
/*!40000 ALTER TABLE `cart_rule_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rule_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rule_translations`
--

DROP TABLE IF EXISTS `cart_rule_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rule_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci,
  `cart_rule_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_rule_translations_cart_rule_id_locale_unique` (`cart_rule_id`,`locale`),
  CONSTRAINT `cart_rule_translations_cart_rule_id_foreign` FOREIGN KEY (`cart_rule_id`) REFERENCES `cart_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rule_translations`
--

LOCK TABLES `cart_rule_translations` WRITE;
/*!40000 ALTER TABLE `cart_rule_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rule_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_rules`
--

DROP TABLE IF EXISTS `cart_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_rules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starts_from` datetime DEFAULT NULL,
  `ends_till` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `coupon_type` int NOT NULL DEFAULT '1',
  `use_auto_generation` tinyint(1) NOT NULL DEFAULT '0',
  `usage_per_customer` int NOT NULL DEFAULT '0',
  `uses_per_coupon` int NOT NULL DEFAULT '0',
  `times_used` int unsigned NOT NULL DEFAULT '0',
  `condition_type` tinyint(1) NOT NULL DEFAULT '1',
  `conditions` json DEFAULT NULL,
  `end_other_rules` tinyint(1) NOT NULL DEFAULT '0',
  `uses_attribute_conditions` tinyint(1) NOT NULL DEFAULT '0',
  `action_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_quantity` int NOT NULL DEFAULT '1',
  `discount_step` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `apply_to_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_rules`
--

LOCK TABLES `cart_rules` WRITE;
/*!40000 ALTER TABLE `cart_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_shipping_rates`
--

DROP TABLE IF EXISTS `cart_shipping_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_shipping_rates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `carrier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carrier_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT '0',
  `base_price` double DEFAULT '0',
  `discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `tax_percent` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `applied_tax_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_calculate_tax` tinyint(1) NOT NULL DEFAULT '1',
  `cart_address_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cart_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_shipping_rates_cart_id_foreign` (`cart_id`),
  CONSTRAINT `cart_shipping_rates_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_shipping_rates`
--

LOCK TABLES `cart_shipping_rates` WRITE;
/*!40000 ALTER TABLE `cart_shipping_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_shipping_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_rule_channels`
--

DROP TABLE IF EXISTS `catalog_rule_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_rule_channels` (
  `catalog_rule_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  PRIMARY KEY (`catalog_rule_id`,`channel_id`),
  KEY `catalog_rule_channels_channel_id_foreign` (`channel_id`),
  CONSTRAINT `catalog_rule_channels_catalog_rule_id_foreign` FOREIGN KEY (`catalog_rule_id`) REFERENCES `catalog_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_channels_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_rule_channels`
--

LOCK TABLES `catalog_rule_channels` WRITE;
/*!40000 ALTER TABLE `catalog_rule_channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_rule_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_rule_customer_groups`
--

DROP TABLE IF EXISTS `catalog_rule_customer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_rule_customer_groups` (
  `catalog_rule_id` int unsigned NOT NULL,
  `customer_group_id` int unsigned NOT NULL,
  PRIMARY KEY (`catalog_rule_id`,`customer_group_id`),
  KEY `catalog_rule_customer_groups_customer_group_id_foreign` (`customer_group_id`),
  CONSTRAINT `catalog_rule_customer_groups_catalog_rule_id_foreign` FOREIGN KEY (`catalog_rule_id`) REFERENCES `catalog_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_customer_groups_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_rule_customer_groups`
--

LOCK TABLES `catalog_rule_customer_groups` WRITE;
/*!40000 ALTER TABLE `catalog_rule_customer_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_rule_customer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_rule_product_prices`
--

DROP TABLE IF EXISTS `catalog_rule_product_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_rule_product_prices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `rule_date` date NOT NULL,
  `starts_from` datetime DEFAULT NULL,
  `ends_till` datetime DEFAULT NULL,
  `product_id` int unsigned NOT NULL,
  `customer_group_id` int unsigned NOT NULL,
  `catalog_rule_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_rule_product_prices_product_id_foreign` (`product_id`),
  KEY `catalog_rule_product_prices_customer_group_id_foreign` (`customer_group_id`),
  KEY `catalog_rule_product_prices_catalog_rule_id_foreign` (`catalog_rule_id`),
  KEY `catalog_rule_product_prices_channel_id_foreign` (`channel_id`),
  CONSTRAINT `catalog_rule_product_prices_catalog_rule_id_foreign` FOREIGN KEY (`catalog_rule_id`) REFERENCES `catalog_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_product_prices_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_product_prices_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_product_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_rule_product_prices`
--

LOCK TABLES `catalog_rule_product_prices` WRITE;
/*!40000 ALTER TABLE `catalog_rule_product_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_rule_product_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_rule_products`
--

DROP TABLE IF EXISTS `catalog_rule_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_rule_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `starts_from` datetime DEFAULT NULL,
  `ends_till` datetime DEFAULT NULL,
  `end_other_rules` tinyint(1) NOT NULL DEFAULT '0',
  `action_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL,
  `customer_group_id` int unsigned NOT NULL,
  `catalog_rule_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_rule_products_product_id_foreign` (`product_id`),
  KEY `catalog_rule_products_customer_group_id_foreign` (`customer_group_id`),
  KEY `catalog_rule_products_catalog_rule_id_foreign` (`catalog_rule_id`),
  KEY `catalog_rule_products_channel_id_foreign` (`channel_id`),
  CONSTRAINT `catalog_rule_products_catalog_rule_id_foreign` FOREIGN KEY (`catalog_rule_id`) REFERENCES `catalog_rules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_products_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_products_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `catalog_rule_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_rule_products`
--

LOCK TABLES `catalog_rule_products` WRITE;
/*!40000 ALTER TABLE `catalog_rule_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_rule_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_rules`
--

DROP TABLE IF EXISTS `catalog_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog_rules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starts_from` date DEFAULT NULL,
  `ends_till` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `condition_type` tinyint(1) NOT NULL DEFAULT '1',
  `conditions` json DEFAULT NULL,
  `end_other_rules` tinyint(1) NOT NULL DEFAULT '0',
  `action_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_rules`
--

LOCK TABLES `catalog_rules` WRITE;
/*!40000 ALTER TABLE `catalog_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `position` int NOT NULL DEFAULT '0',
  `logo_path` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `display_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'products_and_description',
  `_lft` int unsigned NOT NULL DEFAULT '0',
  `_rgt` int unsigned NOT NULL DEFAULT '0',
  `parent_id` int unsigned DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `banner_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories__lft__rgt_parent_id_index` (`_lft`,`_rgt`,`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,1,NULL,1,'products_and_description',2,15,NULL,NULL,NULL,'2025-09-05 17:10:07','2025-09-06 11:36:36'),(2,0,NULL,1,'products_and_description',3,12,1,NULL,NULL,'2025-09-06 11:35:44','2025-09-06 11:36:36'),(3,0,NULL,1,'products_and_description',4,5,2,NULL,NULL,'2025-09-06 11:35:44','2025-09-06 11:36:36'),(4,0,NULL,1,'products_and_description',6,7,2,NULL,NULL,'2025-09-06 11:35:44','2025-09-06 11:36:36'),(5,0,NULL,1,'products_and_description',8,9,2,NULL,NULL,'2025-09-06 11:35:44','2025-09-06 11:36:36'),(6,0,NULL,1,'products_and_description',10,11,2,NULL,NULL,'2025-09-06 11:35:44','2025-09-06 11:36:36'),(102,0,NULL,1,'products_and_description',13,14,1,NULL,NULL,'2025-09-06 11:41:27','2025-09-06 11:41:27');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_filterable_attributes`
--

DROP TABLE IF EXISTS `category_filterable_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_filterable_attributes` (
  `category_id` int unsigned NOT NULL,
  `attribute_id` int unsigned NOT NULL,
  KEY `category_filterable_attributes_category_id_foreign` (`category_id`),
  KEY `category_filterable_attributes_attribute_id_foreign` (`attribute_id`),
  CONSTRAINT `category_filterable_attributes_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_filterable_attributes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_filterable_attributes`
--

LOCK TABLES `category_filterable_attributes` WRITE;
/*!40000 ALTER TABLE `category_filterable_attributes` DISABLE KEYS */;
INSERT INTO `category_filterable_attributes` VALUES (102,25);
/*!40000 ALTER TABLE `category_filterable_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_translations`
--

DROP TABLE IF EXISTS `category_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_path` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `locale_id` int unsigned DEFAULT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_translations_category_id_slug_locale_unique` (`category_id`,`slug`,`locale`),
  KEY `category_translations_locale_id_foreign` (`locale_id`),
  CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_translations_locale_id_foreign` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_translations`
--

LOCK TABLES `category_translations` WRITE;
/*!40000 ALTER TABLE `category_translations` DISABLE KEYS */;
INSERT INTO `category_translations` VALUES (1,1,'Danh mục gốc','root','','Root Category Description','','','',NULL,'vi'),(102,2,'Source Game','source-game','source-game','Source code game vÃ  template','Source Code Game','Source code game vÃ  template','source,code,game',NULL,'vi'),(103,2,'Source Game','source-game','source-game','Source code games and templates','Source Code Game','Source code games and templates','source,code,game',NULL,'en'),(104,3,'Game Unity','unity-games','1/2/3','Game Unity','Unity Games','Game Unity','unity,game',NULL,'vi'),(105,3,'Unity Games','unity-games','1/2/3','Unity Games','Unity Games','Unity Games','unity,game',NULL,'en'),(106,4,'Game Di động','mobile-games','1/2/4','Game di Ä‘á»™ng','Mobile Games','Game di Ä‘á»™ng','mobile,game',NULL,'vi'),(107,4,'Mobile Games','mobile-games','1/2/4','Mobile Games','Mobile Games','Mobile Games','mobile,game',NULL,'en'),(108,5,'Game Web','web-games','1/2/5','Game web','Web Games','Game web','web,game',NULL,'vi'),(109,5,'Web Games','web-games','1/2/5','Web Games','Web Games','Web Games','web,game',NULL,'en'),(110,6,'Game PC','pc-games','1/2/6','Game PC','PC Games','Game PC','pc,game',NULL,'vi'),(111,6,'PC Games','pc-games','1/2/6','PC Games','PC Games','PC Games','pc,game',NULL,'en'),(112,102,'Việc Làm','viec-lam','','<p>Việc L&agrave;m</p>','','','',1,'vi');
/*!40000 ALTER TABLE `category_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel_currencies`
--

DROP TABLE IF EXISTS `channel_currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel_currencies` (
  `channel_id` int unsigned NOT NULL,
  `currency_id` int unsigned NOT NULL,
  PRIMARY KEY (`channel_id`,`currency_id`),
  KEY `channel_currencies_currency_id_foreign` (`currency_id`),
  CONSTRAINT `channel_currencies_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `channel_currencies_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel_currencies`
--

LOCK TABLES `channel_currencies` WRITE;
/*!40000 ALTER TABLE `channel_currencies` DISABLE KEYS */;
INSERT INTO `channel_currencies` VALUES (1,1);
/*!40000 ALTER TABLE `channel_currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel_inventory_sources`
--

DROP TABLE IF EXISTS `channel_inventory_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel_inventory_sources` (
  `channel_id` int unsigned NOT NULL,
  `inventory_source_id` int unsigned NOT NULL,
  UNIQUE KEY `channel_inventory_source_unique` (`channel_id`,`inventory_source_id`),
  KEY `channel_inventory_sources_inventory_source_id_foreign` (`inventory_source_id`),
  CONSTRAINT `channel_inventory_sources_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `channel_inventory_sources_inventory_source_id_foreign` FOREIGN KEY (`inventory_source_id`) REFERENCES `inventory_sources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel_inventory_sources`
--

LOCK TABLES `channel_inventory_sources` WRITE;
/*!40000 ALTER TABLE `channel_inventory_sources` DISABLE KEYS */;
INSERT INTO `channel_inventory_sources` VALUES (1,1);
/*!40000 ALTER TABLE `channel_inventory_sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel_locales`
--

DROP TABLE IF EXISTS `channel_locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel_locales` (
  `channel_id` int unsigned NOT NULL,
  `locale_id` int unsigned NOT NULL,
  PRIMARY KEY (`channel_id`,`locale_id`),
  KEY `channel_locales_locale_id_foreign` (`locale_id`),
  CONSTRAINT `channel_locales_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `channel_locales_locale_id_foreign` FOREIGN KEY (`locale_id`) REFERENCES `locales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel_locales`
--

LOCK TABLES `channel_locales` WRITE;
/*!40000 ALTER TABLE `channel_locales` DISABLE KEYS */;
INSERT INTO `channel_locales` VALUES (1,1);
/*!40000 ALTER TABLE `channel_locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel_translations`
--

DROP TABLE IF EXISTS `channel_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channel_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `maintenance_mode_text` text COLLATE utf8mb4_unicode_ci,
  `home_seo` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_translations_channel_id_locale_unique` (`channel_id`,`locale`),
  KEY `channel_translations_locale_index` (`locale`),
  CONSTRAINT `channel_translations_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel_translations`
--

LOCK TABLES `channel_translations` WRITE;
/*!40000 ALTER TABLE `channel_translations` DISABLE KEYS */;
INSERT INTO `channel_translations` VALUES (1,1,'vi','Default',NULL,'','{\"meta_title\": \"Demo store\", \"meta_keywords\": \"Demo store meta keyword\", \"meta_description\": \"Demo store meta description\"}',NULL,'2025-09-05 18:02:57');
/*!40000 ALTER TABLE `channel_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channels`
--

DROP TABLE IF EXISTS `channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `channels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hostname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_seo` json DEFAULT NULL,
  `is_maintenance_on` tinyint(1) NOT NULL DEFAULT '0',
  `allowed_ips` text COLLATE utf8mb4_unicode_ci,
  `root_category_id` int unsigned DEFAULT NULL,
  `default_locale_id` int unsigned NOT NULL,
  `base_currency_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channels_root_category_id_foreign` (`root_category_id`),
  KEY `channels_default_locale_id_foreign` (`default_locale_id`),
  KEY `channels_base_currency_id_foreign` (`base_currency_id`),
  CONSTRAINT `channels_base_currency_id_foreign` FOREIGN KEY (`base_currency_id`) REFERENCES `currencies` (`id`),
  CONSTRAINT `channels_default_locale_id_foreign` FOREIGN KEY (`default_locale_id`) REFERENCES `locales` (`id`),
  CONSTRAINT `channels_root_category_id_foreign` FOREIGN KEY (`root_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channels`
--

LOCK TABLES `channels` WRITE;
/*!40000 ALTER TABLE `channels` DISABLE KEYS */;
INSERT INTO `channels` VALUES (1,'default',NULL,'emsaigon','https://lamgame.vn',NULL,NULL,NULL,0,'',1,1,1,'2025-09-05 17:10:07','2025-09-05 18:02:57');
/*!40000 ALTER TABLE `channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_page_channels`
--

DROP TABLE IF EXISTS `cms_page_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_page_channels` (
  `cms_page_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  UNIQUE KEY `cms_page_channels_cms_page_id_channel_id_unique` (`cms_page_id`,`channel_id`),
  KEY `cms_page_channels_channel_id_foreign` (`channel_id`),
  CONSTRAINT `cms_page_channels_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cms_page_channels_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_page_channels`
--

LOCK TABLES `cms_page_channels` WRITE;
/*!40000 ALTER TABLE `cms_page_channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_page_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_page_translations`
--

DROP TABLE IF EXISTS `cms_page_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_page_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html_content` longtext COLLATE utf8mb4_unicode_ci,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cms_page_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cms_page_translations_cms_page_id_url_key_locale_unique` (`cms_page_id`,`url_key`,`locale`),
  CONSTRAINT `cms_page_translations_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_page_translations`
--

LOCK TABLES `cms_page_translations` WRITE;
/*!40000 ALTER TABLE `cms_page_translations` DISABLE KEYS */;
INSERT INTO `cms_page_translations` VALUES (1,'About Us','about-us','<div class=\"static-container\"><div class=\"mb-5\">About Us Page Content</div></div>','about us','','aboutus','vi',1),(2,'Return Policy','return-policy','<div class=\"static-container\"><div class=\"mb-5\">Return Policy Page Content</div></div>','return policy','','return, policy','vi',2),(3,'Refund Policy','refund-policy','<div class=\"static-container\"><div class=\"mb-5\">Refund Policy Page Content</div></div>','Refund policy','','refund, policy','vi',3),(4,'Terms & Conditions','terms-conditions','<div class=\"static-container\"><div class=\"mb-5\">Terms & Conditions Page Content</div></div>','Terms & Conditions','','term, conditions','vi',4),(5,'Terms of Use','terms-of-use','<div class=\"static-container\"><div class=\"mb-5\">Terms of Use Page Content</div></div>','Terms of use','','term, use','vi',5),(6,'Customer Service','customer-service','<div class=\"static-container\"><div class=\"mb-5\">Customer Service Page Content</div></div>','Customer Service','','customer, service','vi',6),(7,'What\'s New','whats-new','<div class=\"static-container\"><div class=\"mb-5\">What\'s New page content</div></div>','What\'s New','','new','vi',7),(8,'Payment Policy','payment-policy','<div class=\"static-container\"><div class=\"mb-5\">Payment Policy Page Content</div></div>','Payment Policy','','payment, policy','vi',8),(9,'Shipping Policy','shipping-policy','<div class=\"static-container\"><div class=\"mb-5\">Shipping Policy Page Content</div></div>','Shipping Policy','','shipping, policy','vi',9),(10,'Privacy Policy','privacy-policy','<div class=\"static-container\"><div class=\"mb-5\">Privacy Policy Page Content</div></div>','Privacy Policy','','privacy, policy','vi',10);
/*!40000 ALTER TABLE `cms_page_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_pages`
--

DROP TABLE IF EXISTS `cms_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cms_pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `layout` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_pages`
--

LOCK TABLES `cms_pages` WRITE;
/*!40000 ALTER TABLE `cms_pages` DISABLE KEYS */;
INSERT INTO `cms_pages` VALUES (1,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(2,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(3,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(4,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(5,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(6,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(7,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(8,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(9,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(10,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07');
/*!40000 ALTER TABLE `cms_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compare_items`
--

DROP TABLE IF EXISTS `compare_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compare_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `customer_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compare_items_product_id_foreign` (`product_id`),
  KEY `compare_items_customer_id_foreign` (`customer_id`),
  CONSTRAINT `compare_items_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `compare_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compare_items`
--

LOCK TABLES `compare_items` WRITE;
/*!40000 ALTER TABLE `compare_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `compare_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `core_config`
--

DROP TABLE IF EXISTS `core_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `core_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `core_config`
--

LOCK TABLES `core_config` WRITE;
/*!40000 ALTER TABLE `core_config` DISABLE KEYS */;
INSERT INTO `core_config` VALUES (1,'sales.checkout.shopping_cart.allow_guest_checkout','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(2,'emails.general.notifications.emails.general.notifications.verification','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(3,'emails.general.notifications.emails.general.notifications.registration','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(4,'emails.general.notifications.emails.general.notifications.customer_registration_confirmation_mail_to_admin','0',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(5,'emails.general.notifications.emails.general.notifications.customer_account_credentials','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(6,'emails.general.notifications.emails.general.notifications.new_order','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(7,'emails.general.notifications.emails.general.notifications.new_order_mail_to_admin','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(8,'emails.general.notifications.emails.general.notifications.new_invoice','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(9,'emails.general.notifications.emails.general.notifications.new_invoice_mail_to_admin','0',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(10,'emails.general.notifications.emails.general.notifications.new_refund','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(11,'emails.general.notifications.emails.general.notifications.new_refund_mail_to_admin','0',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(12,'emails.general.notifications.emails.general.notifications.new_shipment','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(13,'emails.general.notifications.emails.general.notifications.new_shipment_mail_to_admin','0',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(14,'emails.general.notifications.emails.general.notifications.new_inventory_source','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(15,'emails.general.notifications.emails.general.notifications.cancel_order','1',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(16,'emails.general.notifications.emails.general.notifications.cancel_order_mail_to_admin','0',NULL,NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(17,'customer.settings.social_login.enable_facebook','1','default',NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(18,'customer.settings.social_login.enable_twitter','1','default',NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(19,'customer.settings.social_login.enable_google','1','default',NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(20,'customer.settings.social_login.enable_linkedin','1','default',NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(21,'customer.settings.social_login.enable_github','1','default',NULL,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(22,'general.magic_ai.settings.enabled','1','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:04:25'),(23,'general.magic_ai.settings.api_key','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','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:04:18'),(24,'general.magic_ai.settings.organization','','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:10:08'),(25,'general.magic_ai.settings.api_domain','','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:04:18'),(26,'general.magic_ai.content_generation.enabled','1',NULL,NULL,'2025-09-12 08:04:18','2025-09-12 08:04:18'),(27,'general.magic_ai.content_generation.product_short_description_prompt','',NULL,'vi','2025-09-12 08:04:18','2025-09-12 08:04:18'),(28,'general.magic_ai.content_generation.product_description_prompt','',NULL,'vi','2025-09-12 08:04:18','2025-09-12 08:04:18'),(29,'general.magic_ai.content_generation.category_description_prompt','',NULL,'vi','2025-09-12 08:04:18','2025-09-12 08:04:18'),(30,'general.magic_ai.content_generation.cms_page_content_prompt','',NULL,'vi','2025-09-12 08:04:18','2025-09-12 08:04:18'),(31,'general.magic_ai.image_generation.enabled','1','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:04:18'),(32,'general.magic_ai.review_translation.enabled','1','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:04:18'),(33,'general.magic_ai.checkout_message.enabled','1','default',NULL,'2025-09-12 08:04:18','2025-09-12 08:04:18'),(34,'general.magic_ai.checkout_message.prompt','','default','vi','2025-09-12 08:04:18','2025-09-12 08:04:18');
/*!40000 ALTER TABLE `core_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'AF','Afghanistan'),(2,'AX','Åland Islands'),(3,'AL','Albania'),(4,'DZ','Algeria'),(5,'AS','American Samoa'),(6,'AD','Andorra'),(7,'AO','Angola'),(8,'AI','Anguilla'),(9,'AQ','Antarctica'),(10,'AG','Antigua & Barbuda'),(11,'AR','Argentina'),(12,'AM','Armenia'),(13,'AW','Aruba'),(14,'AC','Ascension Island'),(15,'AU','Australia'),(16,'AT','Austria'),(17,'AZ','Azerbaijan'),(18,'BS','Bahamas'),(19,'BH','Bahrain'),(20,'BD','Bangladesh'),(21,'BB','Barbados'),(22,'BY','Belarus'),(23,'BE','Belgium'),(24,'BZ','Belize'),(25,'BJ','Benin'),(26,'BM','Bermuda'),(27,'BT','Bhutan'),(28,'BO','Bolivia'),(29,'BA','Bosnia & Herzegovina'),(30,'BW','Botswana'),(31,'BR','Brazil'),(32,'IO','British Indian Ocean Territory'),(33,'VG','British Virgin Islands'),(34,'BN','Brunei'),(35,'BG','Bulgaria'),(36,'BF','Burkina Faso'),(37,'BI','Burundi'),(38,'KH','Cambodia'),(39,'CM','Cameroon'),(40,'CA','Canada'),(41,'IC','Canary Islands'),(42,'CV','Cape Verde'),(43,'BQ','Caribbean Netherlands'),(44,'KY','Cayman Islands'),(45,'CF','Central African Republic'),(46,'EA','Ceuta & Melilla'),(47,'TD','Chad'),(48,'CL','Chile'),(49,'CN','China'),(50,'CX','Christmas Island'),(51,'CC','Cocos (Keeling) Islands'),(52,'CO','Colombia'),(53,'KM','Comoros'),(54,'CG','Congo - Brazzaville'),(55,'CD','Congo - Kinshasa'),(56,'CK','Cook Islands'),(57,'CR','Costa Rica'),(58,'CI','Côte d’Ivoire'),(59,'HR','Croatia'),(60,'CU','Cuba'),(61,'CW','Curaçao'),(62,'CY','Cyprus'),(63,'CZ','Czechia'),(64,'DK','Denmark'),(65,'DG','Diego Garcia'),(66,'DJ','Djibouti'),(67,'DM','Dominica'),(68,'DO','Dominican Republic'),(69,'EC','Ecuador'),(70,'EG','Egypt'),(71,'SV','El Salvador'),(72,'GQ','Equatorial Guinea'),(73,'ER','Eritrea'),(74,'EE','Estonia'),(75,'ET','Ethiopia'),(76,'EZ','Eurozone'),(77,'FK','Falkland Islands'),(78,'FO','Faroe Islands'),(79,'FJ','Fiji'),(80,'FI','Finland'),(81,'FR','France'),(82,'GF','French Guiana'),(83,'PF','French Polynesia'),(84,'TF','French Southern Territories'),(85,'GA','Gabon'),(86,'GM','Gambia'),(87,'GE','Georgia'),(88,'DE','Germany'),(89,'GH','Ghana'),(90,'GI','Gibraltar'),(91,'GR','Greece'),(92,'GL','Greenland'),(93,'GD','Grenada'),(94,'GP','Guadeloupe'),(95,'GU','Guam'),(96,'GT','Guatemala'),(97,'GG','Guernsey'),(98,'GN','Guinea'),(99,'GW','Guinea-Bissau'),(100,'GY','Guyana'),(101,'HT','Haiti'),(102,'HN','Honduras'),(103,'HK','Hong Kong SAR China'),(104,'HU','Hungary'),(105,'IS','Iceland'),(106,'IN','India'),(107,'ID','Indonesia'),(108,'IR','Iran'),(109,'IQ','Iraq'),(110,'IE','Ireland'),(111,'IM','Isle of Man'),(112,'IL','Israel'),(113,'IT','Italy'),(114,'JM','Jamaica'),(115,'JP','Japan'),(116,'JE','Jersey'),(117,'JO','Jordan'),(118,'KZ','Kazakhstan'),(119,'KE','Kenya'),(120,'KI','Kiribati'),(121,'XK','Kosovo'),(122,'KW','Kuwait'),(123,'KG','Kyrgyzstan'),(124,'LA','Laos'),(125,'LV','Latvia'),(126,'LB','Lebanon'),(127,'LS','Lesotho'),(128,'LR','Liberia'),(129,'LY','Libya'),(130,'LI','Liechtenstein'),(131,'LT','Lithuania'),(132,'LU','Luxembourg'),(133,'MO','Macau SAR China'),(134,'MK','Macedonia'),(135,'MG','Madagascar'),(136,'MW','Malawi'),(137,'MY','Malaysia'),(138,'MV','Maldives'),(139,'ML','Mali'),(140,'MT','Malta'),(141,'MH','Marshall Islands'),(142,'MQ','Martinique'),(143,'MR','Mauritania'),(144,'MU','Mauritius'),(145,'YT','Mayotte'),(146,'MX','Mexico'),(147,'FM','Micronesia'),(148,'MD','Moldova'),(149,'MC','Monaco'),(150,'MN','Mongolia'),(151,'ME','Montenegro'),(152,'MS','Montserrat'),(153,'MA','Morocco'),(154,'MZ','Mozambique'),(155,'MM','Myanmar (Burma)'),(156,'NA','Namibia'),(157,'NR','Nauru'),(158,'NP','Nepal'),(159,'NL','Netherlands'),(160,'NC','New Caledonia'),(161,'NZ','New Zealand'),(162,'NI','Nicaragua'),(163,'NE','Niger'),(164,'NG','Nigeria'),(165,'NU','Niue'),(166,'NF','Norfolk Island'),(167,'KP','North Korea'),(168,'MP','Northern Mariana Islands'),(169,'NO','Norway'),(170,'OM','Oman'),(171,'PK','Pakistan'),(172,'PW','Palau'),(173,'PS','Palestinian Territories'),(174,'PA','Panama'),(175,'PG','Papua New Guinea'),(176,'PY','Paraguay'),(177,'PE','Peru'),(178,'PH','Philippines'),(179,'PN','Pitcairn Islands'),(180,'PL','Poland'),(181,'PT','Portugal'),(182,'PR','Puerto Rico'),(183,'QA','Qatar'),(184,'RE','Réunion'),(185,'RO','Romania'),(186,'RU','Russia'),(187,'RW','Rwanda'),(188,'WS','Samoa'),(189,'SM','San Marino'),(190,'ST','São Tomé & Príncipe'),(191,'SA','Saudi Arabia'),(192,'SN','Senegal'),(193,'RS','Serbia'),(194,'SC','Seychelles'),(195,'SL','Sierra Leone'),(196,'SG','Singapore'),(197,'SX','Sint Maarten'),(198,'SK','Slovakia'),(199,'SI','Slovenia'),(200,'SB','Solomon Islands'),(201,'SO','Somalia'),(202,'ZA','South Africa'),(203,'GS','South Georgia & South Sandwich Islands'),(204,'KR','South Korea'),(205,'SS','South Sudan'),(206,'ES','Spain'),(207,'LK','Sri Lanka'),(208,'BL','St. Barthélemy'),(209,'SH','St. Helena'),(210,'KN','St. Kitts & Nevis'),(211,'LC','St. Lucia'),(212,'MF','St. Martin'),(213,'PM','St. Pierre & Miquelon'),(214,'VC','St. Vincent & Grenadines'),(215,'SD','Sudan'),(216,'SR','Suriname'),(217,'SJ','Svalbard & Jan Mayen'),(218,'SZ','Swaziland'),(219,'SE','Sweden'),(220,'CH','Switzerland'),(221,'SY','Syria'),(222,'TW','Taiwan'),(223,'TJ','Tajikistan'),(224,'TZ','Tanzania'),(225,'TH','Thailand'),(226,'TL','Timor-Leste'),(227,'TG','Togo'),(228,'TK','Tokelau'),(229,'TO','Tonga'),(230,'TT','Trinidad & Tobago'),(231,'TA','Tristan da Cunha'),(232,'TN','Tunisia'),(233,'TR','Turkey'),(234,'TM','Turkmenistan'),(235,'TC','Turks & Caicos Islands'),(236,'TV','Tuvalu'),(237,'UM','U.S. Outlying Islands'),(238,'VI','U.S. Virgin Islands'),(239,'UG','Uganda'),(240,'UA','Ukraine'),(241,'AE','United Arab Emirates'),(242,'GB','United Kingdom'),(244,'US','United States'),(245,'UY','Uruguay'),(246,'UZ','Uzbekistan'),(247,'VU','Vanuatu'),(248,'VA','Vatican City'),(249,'VE','Venezuela'),(250,'VN','Vietnam'),(251,'WF','Wallis & Futuna'),(252,'EH','Western Sahara'),(253,'YE','Yemen'),(254,'ZM','Zambia'),(255,'ZW','Zimbabwe');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_state_translations`
--

DROP TABLE IF EXISTS `country_state_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country_state_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `country_state_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_name` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `country_state_translations_country_state_id_foreign` (`country_state_id`),
  CONSTRAINT `country_state_translations_country_state_id_foreign` FOREIGN KEY (`country_state_id`) REFERENCES `country_states` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_state_translations`
--

LOCK TABLES `country_state_translations` WRITE;
/*!40000 ALTER TABLE `country_state_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_state_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_states`
--

DROP TABLE IF EXISTS `country_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country_states` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int unsigned DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `country_states_country_id_foreign` (`country_id`),
  CONSTRAINT `country_states_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=587 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_states`
--

LOCK TABLES `country_states` WRITE;
/*!40000 ALTER TABLE `country_states` DISABLE KEYS */;
INSERT INTO `country_states` VALUES (1,244,'US','AL','Alabama'),(2,244,'US','AK','Alaska'),(3,244,'US','AS','American Samoa'),(4,244,'US','AZ','Arizona'),(5,244,'US','AR','Arkansas'),(6,244,'US','AE','Armed Forces Africa'),(7,244,'US','AA','Armed Forces Americas'),(8,244,'US','AE','Armed Forces Canada'),(9,244,'US','AE','Armed Forces Europe'),(10,244,'US','AE','Armed Forces Middle East'),(11,244,'US','AP','Armed Forces Pacific'),(12,244,'US','CA','California'),(13,244,'US','CO','Colorado'),(14,244,'US','CT','Connecticut'),(15,244,'US','DE','Delaware'),(16,244,'US','DC','District of Columbia'),(17,244,'US','FM','Federated States Of Micronesia'),(18,244,'US','FL','Florida'),(19,244,'US','GA','Georgia'),(20,244,'US','GU','Guam'),(21,244,'US','HI','Hawaii'),(22,244,'US','ID','Idaho'),(23,244,'US','IL','Illinois'),(24,244,'US','IN','Indiana'),(25,244,'US','IA','Iowa'),(26,244,'US','KS','Kansas'),(27,244,'US','KY','Kentucky'),(28,244,'US','LA','Louisiana'),(29,244,'US','ME','Maine'),(30,244,'US','MH','Marshall Islands'),(31,244,'US','MD','Maryland'),(32,244,'US','MA','Massachusetts'),(33,244,'US','MI','Michigan'),(34,244,'US','MN','Minnesota'),(35,244,'US','MS','Mississippi'),(36,244,'US','MO','Missouri'),(37,244,'US','MT','Montana'),(38,244,'US','NE','Nebraska'),(39,244,'US','NV','Nevada'),(40,244,'US','NH','New Hampshire'),(41,244,'US','NJ','New Jersey'),(42,244,'US','NM','New Mexico'),(43,244,'US','NY','New York'),(44,244,'US','NC','North Carolina'),(45,244,'US','ND','North Dakota'),(46,244,'US','MP','Northern Mariana Islands'),(47,244,'US','OH','Ohio'),(48,244,'US','OK','Oklahoma'),(49,244,'US','OR','Oregon'),(50,244,'US','PW','Palau'),(51,244,'US','PA','Pennsylvania'),(52,244,'US','PR','Puerto Rico'),(53,244,'US','RI','Rhode Island'),(54,244,'US','SC','South Carolina'),(55,244,'US','SD','South Dakota'),(56,244,'US','TN','Tennessee'),(57,244,'US','TX','Texas'),(58,244,'US','UT','Utah'),(59,244,'US','VT','Vermont'),(60,244,'US','VI','Virgin Islands'),(61,244,'US','VA','Virginia'),(62,244,'US','WA','Washington'),(63,244,'US','WV','West Virginia'),(64,244,'US','WI','Wisconsin'),(65,244,'US','WY','Wyoming'),(66,40,'CA','AB','Alberta'),(67,40,'CA','BC','British Columbia'),(68,40,'CA','MB','Manitoba'),(69,40,'CA','NL','Newfoundland and Labrador'),(70,40,'CA','NB','New Brunswick'),(71,40,'CA','NS','Nova Scotia'),(72,40,'CA','NT','Northwest Territories'),(73,40,'CA','NU','Nunavut'),(74,40,'CA','ON','Ontario'),(75,40,'CA','PE','Prince Edward Island'),(76,40,'CA','QC','Quebec'),(77,40,'CA','SK','Saskatchewan'),(78,40,'CA','YT','Yukon Territory'),(79,88,'DE','NDS','Niedersachsen'),(80,88,'DE','BAW','Baden-Württemberg'),(81,88,'DE','BAY','Bayern'),(82,88,'DE','BER','Berlin'),(83,88,'DE','BRG','Brandenburg'),(84,88,'DE','BRE','Bremen'),(85,88,'DE','HAM','Hamburg'),(86,88,'DE','HES','Hessen'),(87,88,'DE','MEC','Mecklenburg-Vorpommern'),(88,88,'DE','NRW','Nordrhein-Westfalen'),(89,88,'DE','RHE','Rheinland-Pfalz'),(90,88,'DE','SAR','Saarland'),(91,88,'DE','SAS','Sachsen'),(92,88,'DE','SAC','Sachsen-Anhalt'),(93,88,'DE','SCN','Schleswig-Holstein'),(94,88,'DE','THE','Thüringen'),(95,16,'AT','WI','Wien'),(96,16,'AT','NO','Niederösterreich'),(97,16,'AT','OO','Oberösterreich'),(98,16,'AT','SB','Salzburg'),(99,16,'AT','KN','Kärnten'),(100,16,'AT','ST','Steiermark'),(101,16,'AT','TI','Tirol'),(102,16,'AT','BL','Burgenland'),(103,16,'AT','VB','Vorarlberg'),(104,220,'CH','AG','Aargau'),(105,220,'CH','AI','Appenzell Innerrhoden'),(106,220,'CH','AR','Appenzell Ausserrhoden'),(107,220,'CH','BE','Bern'),(108,220,'CH','BL','Basel-Landschaft'),(109,220,'CH','BS','Basel-Stadt'),(110,220,'CH','FR','Freiburg'),(111,220,'CH','GE','Genf'),(112,220,'CH','GL','Glarus'),(113,220,'CH','GR','Graubünden'),(114,220,'CH','JU','Jura'),(115,220,'CH','LU','Luzern'),(116,220,'CH','NE','Neuenburg'),(117,220,'CH','NW','Nidwalden'),(118,220,'CH','OW','Obwalden'),(119,220,'CH','SG','St. Gallen'),(120,220,'CH','SH','Schaffhausen'),(121,220,'CH','SO','Solothurn'),(122,220,'CH','SZ','Schwyz'),(123,220,'CH','TG','Thurgau'),(124,220,'CH','TI','Tessin'),(125,220,'CH','UR','Uri'),(126,220,'CH','VD','Waadt'),(127,220,'CH','VS','Wallis'),(128,220,'CH','ZG','Zug'),(129,220,'CH','ZH','Zürich'),(130,206,'ES','A Coruсa','A Coruña'),(131,206,'ES','Alava','Alava'),(132,206,'ES','Albacete','Albacete'),(133,206,'ES','Alicante','Alicante'),(134,206,'ES','Almeria','Almeria'),(135,206,'ES','Asturias','Asturias'),(136,206,'ES','Avila','Avila'),(137,206,'ES','Badajoz','Badajoz'),(138,206,'ES','Baleares','Baleares'),(139,206,'ES','Barcelona','Barcelona'),(140,206,'ES','Burgos','Burgos'),(141,206,'ES','Caceres','Caceres'),(142,206,'ES','Cadiz','Cadiz'),(143,206,'ES','Cantabria','Cantabria'),(144,206,'ES','Castellon','Castellon'),(145,206,'ES','Ceuta','Ceuta'),(146,206,'ES','Ciudad Real','Ciudad Real'),(147,206,'ES','Cordoba','Cordoba'),(148,206,'ES','Cuenca','Cuenca'),(149,206,'ES','Girona','Girona'),(150,206,'ES','Granada','Granada'),(151,206,'ES','Guadalajara','Guadalajara'),(152,206,'ES','Guipuzcoa','Guipuzcoa'),(153,206,'ES','Huelva','Huelva'),(154,206,'ES','Huesca','Huesca'),(155,206,'ES','Jaen','Jaen'),(156,206,'ES','La Rioja','La Rioja'),(157,206,'ES','Las Palmas','Las Palmas'),(158,206,'ES','Leon','Leon'),(159,206,'ES','Lleida','Lleida'),(160,206,'ES','Lugo','Lugo'),(161,206,'ES','Madrid','Madrid'),(162,206,'ES','Malaga','Malaga'),(163,206,'ES','Melilla','Melilla'),(164,206,'ES','Murcia','Murcia'),(165,206,'ES','Navarra','Navarra'),(166,206,'ES','Ourense','Ourense'),(167,206,'ES','Palencia','Palencia'),(168,206,'ES','Pontevedra','Pontevedra'),(169,206,'ES','Salamanca','Salamanca'),(170,206,'ES','Santa Cruz de Tenerife','Santa Cruz de Tenerife'),(171,206,'ES','Segovia','Segovia'),(172,206,'ES','Sevilla','Sevilla'),(173,206,'ES','Soria','Soria'),(174,206,'ES','Tarragona','Tarragona'),(175,206,'ES','Teruel','Teruel'),(176,206,'ES','Toledo','Toledo'),(177,206,'ES','Valencia','Valencia'),(178,206,'ES','Valladolid','Valladolid'),(179,206,'ES','Vizcaya','Vizcaya'),(180,206,'ES','Zamora','Zamora'),(181,206,'ES','Zaragoza','Zaragoza'),(182,81,'FR','1','Ain'),(183,81,'FR','2','Aisne'),(184,81,'FR','3','Allier'),(185,81,'FR','4','Alpes-de-Haute-Provence'),(186,81,'FR','5','Hautes-Alpes'),(187,81,'FR','6','Alpes-Maritimes'),(188,81,'FR','7','Ardèche'),(189,81,'FR','8','Ardennes'),(190,81,'FR','9','Ariège'),(191,81,'FR','10','Aube'),(192,81,'FR','11','Aude'),(193,81,'FR','12','Aveyron'),(194,81,'FR','13','Bouches-du-Rhône'),(195,81,'FR','14','Calvados'),(196,81,'FR','15','Cantal'),(197,81,'FR','16','Charente'),(198,81,'FR','17','Charente-Maritime'),(199,81,'FR','18','Cher'),(200,81,'FR','19','Corrèze'),(201,81,'FR','2A','Corse-du-Sud'),(202,81,'FR','2B','Haute-Corse'),(203,81,'FR','21','Côte-d\'Or'),(204,81,'FR','22','Côtes-d\'Armor'),(205,81,'FR','23','Creuse'),(206,81,'FR','24','Dordogne'),(207,81,'FR','25','Doubs'),(208,81,'FR','26','Drôme'),(209,81,'FR','27','Eure'),(210,81,'FR','28','Eure-et-Loir'),(211,81,'FR','29','Finistère'),(212,81,'FR','30','Gard'),(213,81,'FR','31','Haute-Garonne'),(214,81,'FR','32','Gers'),(215,81,'FR','33','Gironde'),(216,81,'FR','34','Hérault'),(217,81,'FR','35','Ille-et-Vilaine'),(218,81,'FR','36','Indre'),(219,81,'FR','37','Indre-et-Loire'),(220,81,'FR','38','Isère'),(221,81,'FR','39','Jura'),(222,81,'FR','40','Landes'),(223,81,'FR','41','Loir-et-Cher'),(224,81,'FR','42','Loire'),(225,81,'FR','43','Haute-Loire'),(226,81,'FR','44','Loire-Atlantique'),(227,81,'FR','45','Loiret'),(228,81,'FR','46','Lot'),(229,81,'FR','47','Lot-et-Garonne'),(230,81,'FR','48','Lozère'),(231,81,'FR','49','Maine-et-Loire'),(232,81,'FR','50','Manche'),(233,81,'FR','51','Marne'),(234,81,'FR','52','Haute-Marne'),(235,81,'FR','53','Mayenne'),(236,81,'FR','54','Meurthe-et-Moselle'),(237,81,'FR','55','Meuse'),(238,81,'FR','56','Morbihan'),(239,81,'FR','57','Moselle'),(240,81,'FR','58','Nièvre'),(241,81,'FR','59','Nord'),(242,81,'FR','60','Oise'),(243,81,'FR','61','Orne'),(244,81,'FR','62','Pas-de-Calais'),(245,81,'FR','63','Puy-de-Dôme'),(246,81,'FR','64','Pyrénées-Atlantiques'),(247,81,'FR','65','Hautes-Pyrénées'),(248,81,'FR','66','Pyrénées-Orientales'),(249,81,'FR','67','Bas-Rhin'),(250,81,'FR','68','Haut-Rhin'),(251,81,'FR','69','Rhône'),(252,81,'FR','70','Haute-Saône'),(253,81,'FR','71','Saône-et-Loire'),(254,81,'FR','72','Sarthe'),(255,81,'FR','73','Savoie'),(256,81,'FR','74','Haute-Savoie'),(257,81,'FR','75','Paris'),(258,81,'FR','76','Seine-Maritime'),(259,81,'FR','77','Seine-et-Marne'),(260,81,'FR','78','Yvelines'),(261,81,'FR','79','Deux-Sèvres'),(262,81,'FR','80','Somme'),(263,81,'FR','81','Tarn'),(264,81,'FR','82','Tarn-et-Garonne'),(265,81,'FR','83','Var'),(266,81,'FR','84','Vaucluse'),(267,81,'FR','85','Vendée'),(268,81,'FR','86','Vienne'),(269,81,'FR','87','Haute-Vienne'),(270,81,'FR','88','Vosges'),(271,81,'FR','89','Yonne'),(272,81,'FR','90','Territoire-de-Belfort'),(273,81,'FR','91','Essonne'),(274,81,'FR','92','Hauts-de-Seine'),(275,81,'FR','93','Seine-Saint-Denis'),(276,81,'FR','94','Val-de-Marne'),(277,81,'FR','95','Val-d\'Oise'),(278,185,'RO','AB','Alba'),(279,185,'RO','AR','Arad'),(280,185,'RO','AG','Argeş'),(281,185,'RO','BC','Bacău'),(282,185,'RO','BH','Bihor'),(283,185,'RO','BN','Bistriţa-Năsăud'),(284,185,'RO','BT','Botoşani'),(285,185,'RO','BV','Braşov'),(286,185,'RO','BR','Brăila'),(287,185,'RO','B','Bucureşti'),(288,185,'RO','BZ','Buzău'),(289,185,'RO','CS','Caraş-Severin'),(290,185,'RO','CL','Călăraşi'),(291,185,'RO','CJ','Cluj'),(292,185,'RO','CT','Constanţa'),(293,185,'RO','CV','Covasna'),(294,185,'RO','DB','Dâmboviţa'),(295,185,'RO','DJ','Dolj'),(296,185,'RO','GL','Galaţi'),(297,185,'RO','GR','Giurgiu'),(298,185,'RO','GJ','Gorj'),(299,185,'RO','HR','Harghita'),(300,185,'RO','HD','Hunedoara'),(301,185,'RO','IL','Ialomiţa'),(302,185,'RO','IS','Iaşi'),(303,185,'RO','IF','Ilfov'),(304,185,'RO','MM','Maramureş'),(305,185,'RO','MH','Mehedinţi'),(306,185,'RO','MS','Mureş'),(307,185,'RO','NT','Neamţ'),(308,185,'RO','OT','Olt'),(309,185,'RO','PH','Prahova'),(310,185,'RO','SM','Satu-Mare'),(311,185,'RO','SJ','Sălaj'),(312,185,'RO','SB','Sibiu'),(313,185,'RO','SV','Suceava'),(314,185,'RO','TR','Teleorman'),(315,185,'RO','TM','Timiş'),(316,185,'RO','TL','Tulcea'),(317,185,'RO','VS','Vaslui'),(318,185,'RO','VL','Vâlcea'),(319,185,'RO','VN','Vrancea'),(320,80,'FI','Lappi','Lappi'),(321,80,'FI','Pohjois-Pohjanmaa','Pohjois-Pohjanmaa'),(322,80,'FI','Kainuu','Kainuu'),(323,80,'FI','Pohjois-Karjala','Pohjois-Karjala'),(324,80,'FI','Pohjois-Savo','Pohjois-Savo'),(325,80,'FI','Etelä-Savo','Etelä-Savo'),(326,80,'FI','Etelä-Pohjanmaa','Etelä-Pohjanmaa'),(327,80,'FI','Pohjanmaa','Pohjanmaa'),(328,80,'FI','Pirkanmaa','Pirkanmaa'),(329,80,'FI','Satakunta','Satakunta'),(330,80,'FI','Keski-Pohjanmaa','Keski-Pohjanmaa'),(331,80,'FI','Keski-Suomi','Keski-Suomi'),(332,80,'FI','Varsinais-Suomi','Varsinais-Suomi'),(333,80,'FI','Etelä-Karjala','Etelä-Karjala'),(334,80,'FI','Päijät-Häme','Päijät-Häme'),(335,80,'FI','Kanta-Häme','Kanta-Häme'),(336,80,'FI','Uusimaa','Uusimaa'),(337,80,'FI','Itä-Uusimaa','Itä-Uusimaa'),(338,80,'FI','Kymenlaakso','Kymenlaakso'),(339,80,'FI','Ahvenanmaa','Ahvenanmaa'),(340,74,'EE','EE-37','Harjumaa'),(341,74,'EE','EE-39','Hiiumaa'),(342,74,'EE','EE-44','Ida-Virumaa'),(343,74,'EE','EE-49','Jõgevamaa'),(344,74,'EE','EE-51','Järvamaa'),(345,74,'EE','EE-57','Läänemaa'),(346,74,'EE','EE-59','Lääne-Virumaa'),(347,74,'EE','EE-65','Põlvamaa'),(348,74,'EE','EE-67','Pärnumaa'),(349,74,'EE','EE-70','Raplamaa'),(350,74,'EE','EE-74','Saaremaa'),(351,74,'EE','EE-78','Tartumaa'),(352,74,'EE','EE-82','Valgamaa'),(353,74,'EE','EE-84','Viljandimaa'),(354,74,'EE','EE-86','Võrumaa'),(355,125,'LV','LV-DGV','Daugavpils'),(356,125,'LV','LV-JEL','Jelgava'),(357,125,'LV','Jēkabpils','Jēkabpils'),(358,125,'LV','LV-JUR','Jūrmala'),(359,125,'LV','LV-LPX','Liepāja'),(360,125,'LV','LV-LE','Liepājas novads'),(361,125,'LV','LV-REZ','Rēzekne'),(362,125,'LV','LV-RIX','Rīga'),(363,125,'LV','LV-RI','Rīgas novads'),(364,125,'LV','Valmiera','Valmiera'),(365,125,'LV','LV-VEN','Ventspils'),(366,125,'LV','Aglonas novads','Aglonas novads'),(367,125,'LV','LV-AI','Aizkraukles novads'),(368,125,'LV','Aizputes novads','Aizputes novads'),(369,125,'LV','Aknīstes novads','Aknīstes novads'),(370,125,'LV','Alojas novads','Alojas novads'),(371,125,'LV','Alsungas novads','Alsungas novads'),(372,125,'LV','LV-AL','Alūksnes novads'),(373,125,'LV','Amatas novads','Amatas novads'),(374,125,'LV','Apes novads','Apes novads'),(375,125,'LV','Auces novads','Auces novads'),(376,125,'LV','Babītes novads','Babītes novads'),(377,125,'LV','Baldones novads','Baldones novads'),(378,125,'LV','Baltinavas novads','Baltinavas novads'),(379,125,'LV','LV-BL','Balvu novads'),(380,125,'LV','LV-BU','Bauskas novads'),(381,125,'LV','Beverīnas novads','Beverīnas novads'),(382,125,'LV','Brocēnu novads','Brocēnu novads'),(383,125,'LV','Burtnieku novads','Burtnieku novads'),(384,125,'LV','Carnikavas novads','Carnikavas novads'),(385,125,'LV','Cesvaines novads','Cesvaines novads'),(386,125,'LV','Ciblas novads','Ciblas novads'),(387,125,'LV','LV-CE','Cēsu novads'),(388,125,'LV','Dagdas novads','Dagdas novads'),(389,125,'LV','LV-DA','Daugavpils novads'),(390,125,'LV','LV-DO','Dobeles novads'),(391,125,'LV','Dundagas novads','Dundagas novads'),(392,125,'LV','Durbes novads','Durbes novads'),(393,125,'LV','Engures novads','Engures novads'),(394,125,'LV','Garkalnes novads','Garkalnes novads'),(395,125,'LV','Grobiņas novads','Grobiņas novads'),(396,125,'LV','LV-GU','Gulbenes novads'),(397,125,'LV','Iecavas novads','Iecavas novads'),(398,125,'LV','Ikšķiles novads','Ikšķiles novads'),(399,125,'LV','Ilūkstes novads','Ilūkstes novads'),(400,125,'LV','Inčukalna novads','Inčukalna novads'),(401,125,'LV','Jaunjelgavas novads','Jaunjelgavas novads'),(402,125,'LV','Jaunpiebalgas novads','Jaunpiebalgas novads'),(403,125,'LV','Jaunpils novads','Jaunpils novads'),(404,125,'LV','LV-JL','Jelgavas novads'),(405,125,'LV','LV-JK','Jēkabpils novads'),(406,125,'LV','Kandavas novads','Kandavas novads'),(407,125,'LV','Kokneses novads','Kokneses novads'),(408,125,'LV','Krimuldas novads','Krimuldas novads'),(409,125,'LV','Krustpils novads','Krustpils novads'),(410,125,'LV','LV-KR','Krāslavas novads'),(411,125,'LV','LV-KU','Kuldīgas novads'),(412,125,'LV','Kārsavas novads','Kārsavas novads'),(413,125,'LV','Lielvārdes novads','Lielvārdes novads'),(414,125,'LV','LV-LM','Limbažu novads'),(415,125,'LV','Lubānas novads','Lubānas novads'),(416,125,'LV','LV-LU','Ludzas novads'),(417,125,'LV','Līgatnes novads','Līgatnes novads'),(418,125,'LV','Līvānu novads','Līvānu novads'),(419,125,'LV','LV-MA','Madonas novads'),(420,125,'LV','Mazsalacas novads','Mazsalacas novads'),(421,125,'LV','Mālpils novads','Mālpils novads'),(422,125,'LV','Mārupes novads','Mārupes novads'),(423,125,'LV','Naukšēnu novads','Naukšēnu novads'),(424,125,'LV','Neretas novads','Neretas novads'),(425,125,'LV','Nīcas novads','Nīcas novads'),(426,125,'LV','LV-OG','Ogres novads'),(427,125,'LV','Olaines novads','Olaines novads'),(428,125,'LV','Ozolnieku novads','Ozolnieku novads'),(429,125,'LV','LV-PR','Preiļu novads'),(430,125,'LV','Priekules novads','Priekules novads'),(431,125,'LV','Priekuļu novads','Priekuļu novads'),(432,125,'LV','Pārgaujas novads','Pārgaujas novads'),(433,125,'LV','Pāvilostas novads','Pāvilostas novads'),(434,125,'LV','Pļaviņu novads','Pļaviņu novads'),(435,125,'LV','Raunas novads','Raunas novads'),(436,125,'LV','Riebiņu novads','Riebiņu novads'),(437,125,'LV','Rojas novads','Rojas novads'),(438,125,'LV','Ropažu novads','Ropažu novads'),(439,125,'LV','Rucavas novads','Rucavas novads'),(440,125,'LV','Rugāju novads','Rugāju novads'),(441,125,'LV','Rundāles novads','Rundāles novads'),(442,125,'LV','LV-RE','Rēzeknes novads'),(443,125,'LV','Rūjienas novads','Rūjienas novads'),(444,125,'LV','Salacgrīvas novads','Salacgrīvas novads'),(445,125,'LV','Salas novads','Salas novads'),(446,125,'LV','Salaspils novads','Salaspils novads'),(447,125,'LV','LV-SA','Saldus novads'),(448,125,'LV','Saulkrastu novads','Saulkrastu novads'),(449,125,'LV','Siguldas novads','Siguldas novads'),(450,125,'LV','Skrundas novads','Skrundas novads'),(451,125,'LV','Skrīveru novads','Skrīveru novads'),(452,125,'LV','Smiltenes novads','Smiltenes novads'),(453,125,'LV','Stopiņu novads','Stopiņu novads'),(454,125,'LV','Strenču novads','Strenču novads'),(455,125,'LV','Sējas novads','Sējas novads'),(456,125,'LV','LV-TA','Talsu novads'),(457,125,'LV','LV-TU','Tukuma novads'),(458,125,'LV','Tērvetes novads','Tērvetes novads'),(459,125,'LV','Vaiņodes novads','Vaiņodes novads'),(460,125,'LV','LV-VK','Valkas novads'),(461,125,'LV','LV-VM','Valmieras novads'),(462,125,'LV','Varakļānu novads','Varakļānu novads'),(463,125,'LV','Vecpiebalgas novads','Vecpiebalgas novads'),(464,125,'LV','Vecumnieku novads','Vecumnieku novads'),(465,125,'LV','LV-VE','Ventspils novads'),(466,125,'LV','Viesītes novads','Viesītes novads'),(467,125,'LV','Viļakas novads','Viļakas novads'),(468,125,'LV','Viļānu novads','Viļānu novads'),(469,125,'LV','Vārkavas novads','Vārkavas novads'),(470,125,'LV','Zilupes novads','Zilupes novads'),(471,125,'LV','Ādažu novads','Ādažu novads'),(472,125,'LV','Ērgļu novads','Ērgļu novads'),(473,125,'LV','Ķeguma novads','Ķeguma novads'),(474,125,'LV','Ķekavas novads','Ķekavas novads'),(475,131,'LT','LT-AL','Alytaus Apskritis'),(476,131,'LT','LT-KU','Kauno Apskritis'),(477,131,'LT','LT-KL','Klaipėdos Apskritis'),(478,131,'LT','LT-MR','Marijampolės Apskritis'),(479,131,'LT','LT-PN','Panevėžio Apskritis'),(480,131,'LT','LT-SA','Šiaulių Apskritis'),(481,131,'LT','LT-TA','Tauragės Apskritis'),(482,131,'LT','LT-TE','Telšių Apskritis'),(483,131,'LT','LT-UT','Utenos Apskritis'),(484,131,'LT','LT-VL','Vilniaus Apskritis'),(485,31,'BR','AC','Acre'),(486,31,'BR','AL','Alagoas'),(487,31,'BR','AP','Amapá'),(488,31,'BR','AM','Amazonas'),(489,31,'BR','BA','Bahia'),(490,31,'BR','CE','Ceará'),(491,31,'BR','ES','Espírito Santo'),(492,31,'BR','GO','Goiás'),(493,31,'BR','MA','Maranhão'),(494,31,'BR','MT','Mato Grosso'),(495,31,'BR','MS','Mato Grosso do Sul'),(496,31,'BR','MG','Minas Gerais'),(497,31,'BR','PA','Pará'),(498,31,'BR','PB','Paraíba'),(499,31,'BR','PR','Paraná'),(500,31,'BR','PE','Pernambuco'),(501,31,'BR','PI','Piauí'),(502,31,'BR','RJ','Rio de Janeiro'),(503,31,'BR','RN','Rio Grande do Norte'),(504,31,'BR','RS','Rio Grande do Sul'),(505,31,'BR','RO','Rondônia'),(506,31,'BR','RR','Roraima'),(507,31,'BR','SC','Santa Catarina'),(508,31,'BR','SP','São Paulo'),(509,31,'BR','SE','Sergipe'),(510,31,'BR','TO','Tocantins'),(511,31,'BR','DF','Distrito Federal'),(512,59,'HR','HR-01','Zagrebačka županija'),(513,59,'HR','HR-02','Krapinsko-zagorska županija'),(514,59,'HR','HR-03','Sisačko-moslavačka županija'),(515,59,'HR','HR-04','Karlovačka županija'),(516,59,'HR','HR-05','Varaždinska županija'),(517,59,'HR','HR-06','Koprivničko-križevačka županija'),(518,59,'HR','HR-07','Bjelovarsko-bilogorska županija'),(519,59,'HR','HR-08','Primorsko-goranska županija'),(520,59,'HR','HR-09','Ličko-senjska županija'),(521,59,'HR','HR-10','Virovitičko-podravska županija'),(522,59,'HR','HR-11','Požeško-slavonska županija'),(523,59,'HR','HR-12','Brodsko-posavska županija'),(524,59,'HR','HR-13','Zadarska županija'),(525,59,'HR','HR-14','Osječko-baranjska županija'),(526,59,'HR','HR-15','Šibensko-kninska županija'),(527,59,'HR','HR-16','Vukovarsko-srijemska županija'),(528,59,'HR','HR-17','Splitsko-dalmatinska županija'),(529,59,'HR','HR-18','Istarska županija'),(530,59,'HR','HR-19','Dubrovačko-neretvanska županija'),(531,59,'HR','HR-20','Međimurska županija'),(532,59,'HR','HR-21','Grad Zagreb'),(533,106,'IN','AN','Andaman and Nicobar Islands'),(534,106,'IN','AP','Andhra Pradesh'),(535,106,'IN','AR','Arunachal Pradesh'),(536,106,'IN','AS','Assam'),(537,106,'IN','BR','Bihar'),(538,106,'IN','CH','Chandigarh'),(539,106,'IN','CT','Chhattisgarh'),(540,106,'IN','DN','Dadra and Nagar Haveli'),(541,106,'IN','DD','Daman and Diu'),(542,106,'IN','DL','Delhi'),(543,106,'IN','GA','Goa'),(544,106,'IN','GJ','Gujarat'),(545,106,'IN','HR','Haryana'),(546,106,'IN','HP','Himachal Pradesh'),(547,106,'IN','JK','Jammu and Kashmir'),(548,106,'IN','JH','Jharkhand'),(549,106,'IN','KA','Karnataka'),(550,106,'IN','KL','Kerala'),(551,106,'IN','LD','Lakshadweep'),(552,106,'IN','MP','Madhya Pradesh'),(553,106,'IN','MH','Maharashtra'),(554,106,'IN','MN','Manipur'),(555,106,'IN','ML','Meghalaya'),(556,106,'IN','MZ','Mizoram'),(557,106,'IN','NL','Nagaland'),(558,106,'IN','OR','Odisha'),(559,106,'IN','PY','Puducherry'),(560,106,'IN','PB','Punjab'),(561,106,'IN','RJ','Rajasthan'),(562,106,'IN','SK','Sikkim'),(563,106,'IN','TN','Tamil Nadu'),(564,106,'IN','TG','Telangana'),(565,106,'IN','TR','Tripura'),(566,106,'IN','UP','Uttar Pradesh'),(567,106,'IN','UT','Uttarakhand'),(568,106,'IN','WB','West Bengal'),(569,176,'PY','PY-16','Alto Paraguay'),(570,176,'PY','PY-10','Alto Paraná'),(571,176,'PY','PY-13','Amambay'),(572,176,'PY','PY-ASU','Asunción'),(573,176,'PY','PY-19','Boquerón'),(574,176,'PY','PY-5','Caaguazú'),(575,176,'PY','PY-6','Caazapá'),(576,176,'PY','PY-14','Canindeyú'),(577,176,'PY','PY-11','Central'),(578,176,'PY','PY-1','Concepción'),(579,176,'PY','PY-3','Cordillera'),(580,176,'PY','PY-4','Guairá'),(581,176,'PY','PY-7','Itapúa'),(582,176,'PY','PY-8','Misiones'),(583,176,'PY','PY-9','Paraguarí'),(584,176,'PY','PY-15','Presidente Hayes'),(585,176,'PY','PY-2','San Pedro'),(586,176,'PY','PY-12','Ñeembucú');
/*!40000 ALTER TABLE `country_states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `country_translations`
--

DROP TABLE IF EXISTS `country_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `country_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `country_translations_country_id_foreign` (`country_id`),
  CONSTRAINT `country_translations_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `country_translations`
--

LOCK TABLES `country_translations` WRITE;
/*!40000 ALTER TABLE `country_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `country_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currencies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decimal` int unsigned NOT NULL DEFAULT '2',
  `group_separator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ',',
  `decimal_separator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '.',
  `currency_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'VND','Vietnamese Dong','₫',2,',','.',NULL,NULL,NULL);
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currency_exchange_rates`
--

DROP TABLE IF EXISTS `currency_exchange_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currency_exchange_rates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `rate` decimal(24,12) NOT NULL,
  `target_currency` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency_exchange_rates_target_currency_unique` (`target_currency`),
  CONSTRAINT `currency_exchange_rates_target_currency_foreign` FOREIGN KEY (`target_currency`) REFERENCES `currencies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currency_exchange_rates`
--

LOCK TABLES `currency_exchange_rates` WRITE;
/*!40000 ALTER TABLE `currency_exchange_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `currency_exchange_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_groups`
--

DROP TABLE IF EXISTS `customer_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_user_defined` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_groups_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_groups`
--

LOCK TABLES `customer_groups` WRITE;
/*!40000 ALTER TABLE `customer_groups` DISABLE KEYS */;
INSERT INTO `customer_groups` VALUES (1,'guest','Guest',0,NULL,NULL),(2,'general','General',0,NULL,NULL),(3,'wholesale','Wholesale',0,NULL,NULL);
/*!40000 ALTER TABLE `customer_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_notes`
--

DROP TABLE IF EXISTS `customer_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_notes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_notified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_notes_customer_id_foreign` (`customer_id`),
  CONSTRAINT `customer_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_notes`
--

LOCK TABLES `customer_notes` WRITE;
/*!40000 ALTER TABLE `customer_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_password_resets`
--

DROP TABLE IF EXISTS `customer_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `customer_password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_password_resets`
--

LOCK TABLES `customer_password_resets` WRITE;
/*!40000 ALTER TABLE `customer_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_social_accounts`
--

DROP TABLE IF EXISTS `customer_social_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_social_accounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int unsigned NOT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_social_accounts_provider_id_unique` (`provider_id`),
  KEY `customer_social_accounts_customer_id_foreign` (`customer_id`),
  CONSTRAINT `customer_social_accounts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_social_accounts`
--

LOCK TABLES `customer_social_accounts` WRITE;
/*!40000 ALTER TABLE `customer_social_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_social_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_group_id` int unsigned DEFAULT NULL,
  `channel_id` int unsigned DEFAULT NULL,
  `subscribed_to_news_letter` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_suspended` tinyint unsigned NOT NULL DEFAULT '0',
  `forum_role` enum('user','moderator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reputation` int NOT NULL DEFAULT '0',
  `banned_until` timestamp NULL DEFAULT NULL,
  `ban_reason` text COLLATE utf8mb4_unicode_ci,
  `banned_by` int unsigned DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_email_unique` (`email`),
  UNIQUE KEY `customers_phone_unique` (`phone`),
  UNIQUE KEY `customers_api_token_unique` (`api_token`),
  KEY `customers_customer_group_id_foreign` (`customer_group_id`),
  KEY `customers_channel_id_foreign` (`channel_id`),
  KEY `customers_banned_by_foreign` (`banned_by`),
  CONSTRAINT `customers_banned_by_foreign` FOREIGN KEY (`banned_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customers_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customers_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Nguyen','Thanh Tung',NULL,NULL,'tung@yopmail.com',NULL,NULL,1,'$2y$10$Q6d8sWKZ6Q/75.6j0YZSTOJoFEOTQu.lLLWLXF52RApTa4BVHhotO',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-23 13:03:50','2025-09-23 13:03:50'),(2,'Nguyen','Tung 2',NULL,NULL,'tung2@yopmail.com',NULL,NULL,1,'$2y$10$IIpszQmIXR6N9Nki8OKfEeuMxm3srdo/OfHxSEH0ibkJp08kU8n9u',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-23 13:21:33','2025-09-23 13:21:33'),(3,'Nguyen','Thanh Tung',NULL,NULL,'tung3@yopmail.com',NULL,NULL,1,'$2y$10$ZSmbLyb.L1G1sU6M3HVeZODkuDq4xgtOfE4y2oX8zJqXNrGqMT.g2',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-23 13:36:43','2025-09-23 13:36:43'),(4,'Gia','Bảo',NULL,NULL,'bao@yopmail.com',NULL,NULL,1,'$2y$10$qpvwnD8ShdL/BjDjIbb82eXjUsKdERRvF11ImLGJs4YMjY6cmzeGC',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-23 13:45:45','2025-09-23 13:45:45'),(5,'Nguyễn','Gia An',NULL,NULL,'an@yopmail.com',NULL,NULL,1,'$2y$10$iwN21tDM.mWs2G33hjKxCeS4djV0Iltl0kER9dyzUK9zEWPkbVyiS',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-23 15:09:05','2025-09-23 15:09:05'),(6,'Test','User',NULL,NULL,'testregistration@yopmail.com',NULL,NULL,1,'$2y$10$1bXlOo5/rjmPsB9QMzdTx.g6gu4wCMUmdXp9m5OjeAA4Vb4LBD81a',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-23 15:12:46','2025-09-23 15:12:46'),(7,'Nguyen','Thanh Tung 1',NULL,NULL,'tung11@yopmail.com',NULL,NULL,1,'$2y$10$bq3eL77GunIJBHwidJGFVunBvvo6XROMtAZDsU2NKjst3LuvNfFzm',NULL,2,1,0,1,0,'user',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,'2025-09-24 15:27:56','2025-09-24 15:27:56');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datagrid_saved_filters`
--

DROP TABLE IF EXISTS `datagrid_saved_filters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `datagrid_saved_filters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `src` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applied` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `datagrid_saved_filters_user_id_name_src_unique` (`user_id`,`name`,`src`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datagrid_saved_filters`
--

LOCK TABLES `datagrid_saved_filters` WRITE;
/*!40000 ALTER TABLE `datagrid_saved_filters` DISABLE KEYS */;
/*!40000 ALTER TABLE `datagrid_saved_filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloadable_link_purchased`
--

DROP TABLE IF EXISTS `downloadable_link_purchased`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `downloadable_link_purchased` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `download_bought` int NOT NULL DEFAULT '0',
  `download_used` int NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int unsigned NOT NULL,
  `order_id` int unsigned NOT NULL,
  `order_item_id` int unsigned NOT NULL,
  `download_canceled` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `downloadable_link_purchased_customer_id_foreign` (`customer_id`),
  KEY `downloadable_link_purchased_order_id_foreign` (`order_id`),
  KEY `downloadable_link_purchased_order_item_id_foreign` (`order_item_id`),
  CONSTRAINT `downloadable_link_purchased_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `downloadable_link_purchased_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `downloadable_link_purchased_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloadable_link_purchased`
--

LOCK TABLES `downloadable_link_purchased` WRITE;
/*!40000 ALTER TABLE `downloadable_link_purchased` DISABLE KEYS */;
/*!40000 ALTER TABLE `downloadable_link_purchased` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_categories`
--

DROP TABLE IF EXISTS `forum_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#667eea',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `posts_count` int NOT NULL DEFAULT '0',
  `comments_count` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forum_categories_slug_unique` (`slug`),
  KEY `forum_categories_is_active_sort_order_index` (`is_active`,`sort_order`),
  KEY `forum_categories_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_categories`
--

LOCK TABLES `forum_categories` WRITE;
/*!40000 ALTER TABLE `forum_categories` DISABLE KEYS */;
INSERT INTO `forum_categories` VALUES (9,'Thảo luận','thao-luan','Thảo luận về game development, kỹ thuật và kinh nghiệm','💭','#667eea',1,1,0,2,17,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(10,'Chia sẻ ý tưởng','chia-se-y-tuong','Chia sẻ ý tưởng game mới và tìm team phát triển','💡','#ffd700',2,1,1,2,17,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(11,'Tìm team','tim-team','Tìm kiếm đồng đội cho dự án game của bạn','👥','#ff6b35',3,1,0,1,10,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(12,'Review khóa học','review-khoa-hoc','Đánh giá và review các khóa học game development','📚','#10b981',4,1,0,1,4,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(13,'Hỗ trợ kỹ thuật','ho-tro-ky-thuat','Hỏi đáp và hỗ trợ kỹ thuật về game development','🛠️','#8b5cf6',5,1,0,1,4,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(14,'Showcase dự án','showcase','Khoe game và dự án của bạn, nhận feedback từ cộng đồng','🎯','#f59e0b',6,1,0,1,3,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(15,'Tuyển dụng','tuyen-dung','Thông tin tuyển dụng và cơ hội việc làm trong ngành game','💼','#06b6d4',7,1,0,0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(16,'Game Jam','game-jam','Thông tin về các cuộc thi Game Jam và sự kiện','🏆','#ec4899',8,1,0,0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10');
/*!40000 ALTER TABLE `forum_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_comments`
--

DROP TABLE IF EXISTS `forum_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('published','pending','hidden','spam') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `likes_count` int NOT NULL DEFAULT '0',
  `dislikes_count` int NOT NULL DEFAULT '0',
  `replies_count` int NOT NULL DEFAULT '0',
  `metadata` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_comments_post_id_status_created_at_index` (`post_id`,`status`,`created_at`),
  KEY `forum_comments_parent_id_created_at_index` (`parent_id`,`created_at`),
  KEY `forum_comments_author_name_created_at_index` (`author_name`,`created_at`),
  KEY `forum_comments_status_index` (`status`),
  CONSTRAINT `forum_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `forum_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_comments`
--

LOCK TABLES `forum_comments` WRITE;
/*!40000 ALTER TABLE `forum_comments` DISABLE KEYS */;
INSERT INTO `forum_comments` VALUES (1,1,NULL,'Mình cũng gặp vấn đề tương tự. Thử giảm draw calls bằng cách merge meshes.','IndieCreator','indie@example.com',NULL,NULL,'published',12,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(2,1,NULL,'Ngoài ra, hãy chú ý đến texture compression settings cho mobile.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',10,0,0,NULL,NULL,NULL,'2025-09-02 06:17:11','2025-09-09 06:17:11'),(3,1,NULL,'Unity Profiler sẽ giúp bạn identify bottlenecks chính xác hơn.','IndieCreator','indie@example.com',NULL,NULL,'published',12,0,1,NULL,NULL,NULL,'2025-09-04 06:17:11','2025-09-09 06:17:11'),(4,1,3,'Đúng rồi, mình quên mất aspect này.','UnityExpert','expert@example.com',NULL,NULL,'published',0,0,0,NULL,NULL,NULL,'2025-09-07 06:17:11','2025-09-09 06:17:11'),(5,1,NULL,'Mình cũng gặp vấn đề tương tự. Thử giảm draw calls bằng cách merge meshes.','IndieCreator','indie@example.com',NULL,NULL,'published',10,0,0,NULL,NULL,NULL,'2025-09-04 06:17:11','2025-09-09 06:17:11'),(6,1,NULL,'Good point! Đã save post để reference sau này.','UnityExpert','expert@example.com',NULL,NULL,'published',1,0,0,NULL,NULL,NULL,'2025-09-04 06:17:11','2025-09-09 06:17:11'),(7,1,NULL,'Good point! Đã save post để reference sau này.','IndieCreator','indie@example.com',NULL,NULL,'published',3,0,0,NULL,NULL,NULL,'2025-09-02 06:17:11','2025-09-09 06:17:11'),(8,1,NULL,'Ngoài ra, hãy chú ý đến texture compression settings cho mobile.','CodeMaster','codemaster@example.com',NULL,NULL,'published',5,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(9,2,NULL,'Try adding some debug logs để trace exact location của error.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',6,0,0,NULL,NULL,NULL,'2025-09-04 06:17:11','2025-09-09 06:17:11'),(10,2,NULL,'Initialization order có thể là vấn đề. Try sử dụng Awake() thay vì Start().','GameOptimizer','optimizer@example.com',NULL,NULL,'published',4,0,0,NULL,NULL,NULL,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(11,2,NULL,'Bạn check null reference chưa? Có thể enemy.GetComponent<Enemy>() return null.','UnityExpert','expert@example.com',NULL,NULL,'published',0,0,0,NULL,NULL,NULL,'2025-09-02 06:17:11','2025-09-09 06:17:11'),(12,2,NULL,'Post full error stack trace để mọi người debug dễ hơn.','UnityExpert','expert@example.com',NULL,NULL,'published',15,0,1,NULL,NULL,NULL,'2025-09-02 06:17:11','2025-09-09 06:17:11'),(13,2,12,'Cảm ơn bạn! Mình sẽ thử method này.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',3,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(14,2,NULL,'Bạn check null reference chưa? Có thể enemy.GetComponent<Enemy>() return null.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',6,0,0,NULL,NULL,NULL,'2025-09-07 06:17:11','2025-09-09 06:17:11'),(15,2,NULL,'Try adding some debug logs để trace exact location của error.','UnityExpert','expert@example.com',NULL,NULL,'published',8,0,0,NULL,NULL,NULL,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(16,2,NULL,'Post full error stack trace để mọi người debug dễ hơn.','IndieCreator','indie@example.com',NULL,NULL,'published',6,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(17,2,NULL,'Bạn check null reference chưa? Có thể enemy.GetComponent<Enemy>() return null.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',5,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(18,3,NULL,'Mình quan tâm position Unity Developer. Portfolio: [link]','GameOptimizer','optimizer@example.com',NULL,NULL,'published',14,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(19,3,NULL,'Mình quan tâm position Unity Developer. Portfolio: [link]','UnityExpert','expert@example.com',NULL,NULL,'published',4,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(20,3,NULL,'Ý tưởng hay đấy! Mình có kinh nghiệm về mobile monetization, có thể support.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',13,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(21,3,NULL,'Mình quan tâm position Unity Developer. Portfolio: [link]','UnityExpert','expert@example.com',NULL,NULL,'published',5,0,1,NULL,NULL,NULL,'2025-09-04 06:17:11','2025-09-09 06:17:11'),(22,3,21,'Cảm ơn bạn! Mình sẽ thử method này.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',5,0,0,NULL,NULL,NULL,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(23,3,NULL,'Art style nào bạn dự định sử dụng? Pixel art sẽ fit với theme này.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',8,0,0,NULL,NULL,NULL,'2025-09-06 06:17:11','2025-09-09 06:17:11'),(24,3,NULL,'Market research shows farming games rất hot ở SEA region.','UnityExpert','expert@example.com',NULL,NULL,'published',13,0,0,NULL,NULL,NULL,'2025-09-06 06:17:11','2025-09-09 06:17:11'),(25,3,NULL,'Ý tưởng hay đấy! Mình có kinh nghiệm về mobile monetization, có thể support.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',15,0,0,NULL,NULL,NULL,'2025-09-02 06:17:11','2025-09-09 06:17:11'),(26,4,NULL,'Concept rất thú vị. Bạn đã research về target audience chưa?','GameOptimizer','optimizer@example.com',NULL,NULL,'published',15,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(27,4,NULL,'Market research shows farming games rất hot ở SEA region.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',12,0,0,NULL,NULL,NULL,'2025-09-07 06:17:11','2025-09-09 06:17:11'),(28,4,NULL,'Art style nào bạn dự định sử dụng? Pixel art sẽ fit với theme này.','CodeMaster','codemaster@example.com',NULL,NULL,'published',12,0,0,NULL,NULL,NULL,'2025-09-05 06:17:11','2025-09-09 06:17:11'),(29,4,NULL,'Art style nào bạn dự định sử dụng? Pixel art sẽ fit với theme này.','UnityExpert','expert@example.com',NULL,NULL,'published',5,0,0,NULL,NULL,NULL,'2025-09-03 06:17:11','2025-09-09 06:17:11'),(30,4,NULL,'Concept rất thú vị. Bạn đã research về target audience chưa?','GameOptimizer','optimizer@example.com',NULL,NULL,'published',15,0,0,NULL,NULL,NULL,'2025-09-08 06:17:11','2025-09-09 06:17:11'),(31,4,NULL,'Market research shows farming games rất hot ở SEA region.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',1,0,0,NULL,NULL,NULL,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(32,4,NULL,'Mình quan tâm position Unity Developer. Portfolio: [link]','IndieCreator','indie@example.com',NULL,NULL,'published',7,0,1,NULL,NULL,NULL,'2025-09-07 06:17:12','2025-09-09 06:17:12'),(33,4,32,'Exactly! Mình cũng nghĩ vậy.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',4,0,0,NULL,NULL,NULL,'2025-09-07 06:17:12','2025-09-09 06:17:12'),(34,4,NULL,'Market research shows farming games rất hot ở SEA region.','UnityExpert','expert@example.com',NULL,NULL,'published',3,0,0,NULL,NULL,NULL,'2025-09-05 06:17:12','2025-09-09 06:17:12'),(35,5,NULL,'Unity Profiler sẽ giúp bạn identify bottlenecks chính xác hơn.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',5,0,1,NULL,NULL,NULL,'2025-09-03 06:17:12','2025-09-09 06:17:12'),(36,5,35,'Appreciate the help! 🙏','IndieCreator','indie@example.com',NULL,NULL,'published',0,0,0,NULL,NULL,NULL,'2025-09-08 06:17:12','2025-09-09 06:17:12'),(37,5,NULL,'Unity Profiler sẽ giúp bạn identify bottlenecks chính xác hơn.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',15,0,0,NULL,NULL,NULL,'2025-09-06 06:17:12','2025-09-09 06:17:12'),(38,5,NULL,'Good point! Đã save post để reference sau này.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',15,0,1,NULL,NULL,NULL,'2025-09-06 06:17:12','2025-09-09 06:17:12'),(39,5,38,'That makes sense, thanks!','UnityExpert','expert@example.com',NULL,NULL,'published',5,0,0,NULL,NULL,NULL,'2025-09-04 06:17:12','2025-09-09 06:17:12'),(40,5,NULL,'Good point! Đã save post để reference sau này.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',11,0,0,NULL,NULL,NULL,'2025-09-08 06:17:12','2025-09-09 06:17:12'),(41,5,NULL,'Good point! Đã save post để reference sau này.','IndieCreator','indie@example.com',NULL,NULL,'published',10,0,0,NULL,NULL,NULL,'2025-09-03 06:17:12','2025-09-09 06:17:12'),(42,5,NULL,'Mình cũng gặp vấn đề tương tự. Thử giảm draw calls bằng cách merge meshes.','CodeMaster','codemaster@example.com',NULL,NULL,'published',7,0,0,NULL,NULL,NULL,'2025-09-04 06:17:12','2025-09-09 06:17:12'),(43,5,NULL,'Unity Profiler sẽ giúp bạn identify bottlenecks chính xác hơn.','CodeMaster','codemaster@example.com',NULL,NULL,'published',1,0,0,NULL,NULL,NULL,'2025-09-07 06:17:12','2025-09-09 06:17:12'),(44,5,NULL,'Good point! Đã save post để reference sau này.','CodeMaster','codemaster@example.com',NULL,NULL,'published',1,0,0,NULL,NULL,NULL,'2025-09-02 06:17:12','2025-09-09 06:17:12'),(45,6,NULL,'Mức lương 12M cho junior dev ở HCM khá ok đấy.','CodeMaster','codemaster@example.com',NULL,NULL,'published',11,0,0,NULL,NULL,NULL,'2025-09-06 06:17:12','2025-09-09 06:17:12'),(46,6,NULL,'Curriculum looks comprehensive. Worth the investment.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',9,0,0,NULL,NULL,NULL,'2025-09-03 06:17:12','2025-09-09 06:17:12'),(47,6,NULL,'Mức lương 12M cho junior dev ở HCM khá ok đấy.','UnityExpert','expert@example.com',NULL,NULL,'published',10,0,0,NULL,NULL,NULL,'2025-09-02 06:17:12','2025-09-09 06:17:12'),(48,6,NULL,'Bạn có recommend online alternatives không?','CodeMaster','codemaster@example.com',NULL,NULL,'published',11,0,0,NULL,NULL,NULL,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(49,7,NULL,'Bạn check null reference chưa? Có thể enemy.GetComponent<Enemy>() return null.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',5,0,1,NULL,NULL,NULL,'2025-09-04 06:17:12','2025-09-09 06:17:12'),(50,7,49,'Good suggestion, thanks for sharing!','CodeMaster','codemaster@example.com',NULL,NULL,'published',2,0,0,NULL,NULL,NULL,'2025-09-05 06:17:12','2025-09-09 06:17:12'),(51,7,NULL,'Initialization order có thể là vấn đề. Try sử dụng Awake() thay vì Start().','UnityExpert','expert@example.com',NULL,NULL,'published',3,0,0,NULL,NULL,NULL,'2025-09-08 06:17:12','2025-09-09 06:17:12'),(52,7,NULL,'Try adding some debug logs để trace exact location của error.','MobileDev2023','mobiledev@example.com',NULL,NULL,'published',8,0,0,NULL,NULL,NULL,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(53,8,NULL,'Amazing work for solo dev! Inspiration cho mình quá.','GameOptimizer','optimizer@example.com',NULL,NULL,'published',11,0,0,NULL,NULL,NULL,'2025-09-07 06:17:12','2025-09-09 06:17:12'),(54,8,NULL,'Amazing work for solo dev! Inspiration cho mình quá.','CodeMaster','codemaster@example.com',NULL,NULL,'published',14,0,0,NULL,NULL,NULL,'2025-09-04 06:17:12','2025-09-09 06:17:12'),(55,8,NULL,'Congrats on shipping! Game nhìn rất polished.','UnityExpert','expert@example.com',NULL,NULL,'published',11,0,0,NULL,NULL,NULL,'2025-09-08 06:17:12','2025-09-09 06:17:12');
/*!40000 ALTER TABLE `forum_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_post_tags`
--

DROP TABLE IF EXISTS `forum_post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_post_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forum_post_tags_post_id_tag_id_unique` (`post_id`,`tag_id`),
  KEY `forum_post_tags_post_id_index` (`post_id`),
  KEY `forum_post_tags_tag_id_index` (`tag_id`),
  CONSTRAINT `forum_post_tags_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_post_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `forum_tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_post_tags`
--

LOCK TABLES `forum_post_tags` WRITE;
/*!40000 ALTER TABLE `forum_post_tags` DISABLE KEYS */;
INSERT INTO `forum_post_tags` VALUES (1,1,7,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(2,1,19,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(3,1,35,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(4,1,11,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(5,2,7,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(6,2,8,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(7,2,47,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(8,2,17,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(9,3,19,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(10,3,29,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(11,3,7,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(12,3,17,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(13,3,37,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(14,4,22,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(15,4,24,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(16,4,8,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(17,4,18,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(18,4,46,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(19,5,7,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(20,5,11,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(21,5,46,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(22,6,7,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(23,6,11,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(24,6,48,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(25,6,47,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(26,7,7,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(27,7,11,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(28,7,47,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(29,8,7,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(30,8,19,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(31,8,18,'2025-09-09 06:17:12','2025-09-09 06:17:12'),(32,9,52,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(33,9,53,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(34,9,54,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(35,10,55,'2025-09-24 15:47:09','2025-09-24 15:47:09');
/*!40000 ALTER TABLE `forum_post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_posts`
--

DROP TABLE IF EXISTS `forum_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `hot_score` int NOT NULL DEFAULT '0',
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `type` enum('discussion','idea','question','showcase','job','review') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'discussion',
  `author_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `status` enum('draft','published','hidden','locked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_sticky` tinyint(1) NOT NULL DEFAULT '0',
  `views_count` int NOT NULL DEFAULT '0',
  `comments_count` int NOT NULL DEFAULT '0',
  `likes_count` int NOT NULL DEFAULT '0',
  `dislikes_count` int NOT NULL DEFAULT '0',
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edit_history` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `last_comment_at` timestamp NULL DEFAULT NULL,
  `last_comment_author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forum_posts_slug_unique` (`slug`),
  KEY `forum_posts_status_is_featured_created_at_index` (`status`,`is_featured`,`created_at`),
  KEY `forum_posts_category_id_status_index` (`category_id`,`status`),
  KEY `forum_posts_slug_index` (`slug`),
  KEY `forum_posts_author_name_created_at_index` (`author_name`,`created_at`),
  KEY `forum_posts_last_comment_at_index` (`last_comment_at`),
  KEY `forum_posts_hot_score_created_at_index` (`hot_score`,`created_at`),
  KEY `forum_posts_views_index` (`views`),
  CONSTRAINT `forum_posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `forum_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_posts`
--

LOCK TABLES `forum_posts` WRITE;
/*!40000 ALTER TABLE `forum_posts` DISABLE KEYS */;
INSERT INTO `forum_posts` VALUES (1,'Làm thế nào để tối ưu performance game Unity?','lam-the-nao-de-toi-uu-performance-game-unity','Chào mọi người,\n\nMình đang phát triển game mobile với Unity nhưng gặp vấn đề về hiệu suất. Game có nhiều object di chuyển và FPS giảm mạnh trên mobile.\n\nCác bạn có kinh nghiệm gì về optimization không? Mình đã thử:\n- Object Pooling cho bullets\n- Giảm texture quality\n- Sử dụng LOD system\n\nNhưng vẫn chưa đủ. Có technique nào khác không ạ?',0,0,'Chào mọi người,\n\nMình đang phát triển game mobile với Unity nhưng gặp vấn đề về hiệu suất. Game có nhiều object di chuyển và FPS giảm mạnh trên mobile.\n\nCác bạn...','discussion','GameDev_VN','gamedev.vn@example.com',NULL,9,'published',1,0,234,8,15,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-07 06:17:11','UnityExpert','2025-08-11 06:17:11','2025-09-09 06:17:11'),(2,'So sánh Unity vs Unreal Engine cho indie developer','so-sanh-unity-vs-unreal-engine-cho-indie-developer','Mình đang băn khoăn không biết chọn Unity hay Unreal Engine để bắt đầu làm game indie.\n\nMình có background lập trình C# và đã làm vài app mobile. Dự án đầu tiên sẽ là game 2D platformer.\n\nMọi người tư vấn giúp mình:\n1. Engine nào dễ học hơn?\n2. Cộng đồng support như thế nào?\n3. Chi phí licensing?\n4. Performance trên mobile?\n\nCảm ơn mọi người!',0,0,'Mình đang băn khoăn không biết chọn Unity hay Unreal Engine để bắt đầu làm game indie.\n\nMình có background lập trình C# và đã làm vài app mobile. Dự án đầu tiên...','question','NewbieDev2024','newbie@example.com',NULL,9,'published',0,0,189,9,8,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-09 06:17:11','UnityExpert','2025-08-15 06:17:11','2025-09-09 06:17:11'),(3,'Game mobile về nông nghiệp Việt Nam','game-mobile-ve-nong-nghiep-viet-nam','Ý tưởng: Game mô phỏng nông nghiệp lấy bối cảnh Việt Nam\n\n## Concept chính:\n- Trồng các loại cây đặc sản VN: lúa, cà phê, cao su, tiêu...\n- Hệ thống thời tiết theo mùa\n- Trade với các tỉnh khác nhau\n- Kết hợp yếu tố văn hóa dân gian\n\n## Gameplay:\n- Idle/clicker game cơ bản\n- Quản lý resources\n- Mini-games cho từng hoạt động\n- Social features: tặng hạt giống, visit farm bạn bè\n\n## Target:\n- Mobile-first\n- Free-to-play với IAP\n- Audience: 25-40 tuổi, nostalgic về quê hương\n\nAi quan tâm join team không? Mình cần:\n- 2D Artist (pixel art style)\n- Unity Developer\n- Game Designer\n\nComment bên dưới nếu interested nhé!',0,0,'Ý tưởng: Game mô phỏng nông nghiệp lấy bối cảnh Việt Nam\n\n## Concept chính:\n- Trồng các loại cây đặc sản VN: lúa, cà phê, cao su, tiêu...\n- Hệ thống thời tiết t...','idea','FarmGameVN','farmgame@example.com',NULL,10,'published',1,0,157,8,23,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-09 06:17:11','GameOptimizer','2025-08-26 06:17:11','2025-09-09 09:20:05'),(4,'RPG Tam Quốc với gameplay mới','rpg-tam-quoc-voi-gameplay-moi','## Concept: Tam Quốc Realtime Strategy RPG\n\n### Core Idea:\nKết hợp giữa Total War và JRPG, lấy bối cảnh Tam Quốc với historical accuracy cao.\n\n### Unique Features:\n1. **Realtime Tactical Combat**: Không phải turn-based như các game Tam Quốc khác\n2. **AI-driven NPCs**: Các tướng lĩnh có AI personality riêng\n3. **Dynamic Weather**: Ảnh hưởng trực tiếp đến combat (mưa làm chậm cavalry, gió ảnh hưởng archers)\n4. **Diplomacy System**: Phức tạp như Crusader Kings\n\n### Technical Stack:\n- Unreal Engine 5 (Lumen + Nanite)\n- Multiplayer với dedicated servers\n- Platform: PC first, console sau\n\n### Team cần tìm:\n- **Lead Programmer** (Unreal C++)\n- **3D Artists** (characters + environments)\n- **Game Designer** (balance + systems)\n- **Historical Researcher**\n\nBudget dự kiến: $200k-500k, timeline 2-3 năm.\n\nAi có kinh nghiệm với large-scale projects ping mình!',0,0,'## Concept: Tam Quốc Realtime Strategy RPG\n\n### Core Idea:\nKết hợp giữa Total War và JRPG, lấy bối cảnh Tam Quốc với historical accuracy cao.\n\n### Unique Featur...','idea','HistoryGamer','historygamer@example.com',NULL,10,'published',0,0,134,9,18,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-09 06:17:11','GameOptimizer','2025-08-15 06:17:11','2025-09-09 06:17:12'),(5,'[Tìm team] Game horror indie cần Unity developer','tim-team-game-horror-indie-can-unity-developer','## Dự án: \"Midnight School\" - Horror Adventure\n\n### Về game:\n- Genre: Horror/Mystery/Adventure\n- Platform: PC (Steam)\n- Art style: Low-poly với lighting atmospher\n- Timeline: 8-12 tháng\n- Budget: Rev-share model\n\n### Progress hiện tại:\n- Design document: 70% complete\n- Art assets: 40% complete\n- Audio: 20% complete\n- Programming: 10% complete\n\n### Cần tìm:\n**Unity Developer (Mid-Senior level)**\n- Kinh nghiệm 2+ năm Unity\n- Biết C# proficiently\n- Có kinh nghiệm với lighting system\n- Bonus: biết shader programming\n\n**Responsibilities:**\n- Character controller\n- Inventory system\n- Save/Load system\n- UI programming\n- Performance optimization\n\n### Team hiện tại:\n- Game Designer/Producer (mình)\n- 2D/3D Artist \n- Sound Designer\n- Writer\n\n### Contact:\nEmail: midnight.school.dev@gmail.com\nDiscord: HorrorGameDev#1234\n\nGửi portfolio + CV nha các bạn!',0,0,'## Dự án: \"Midnight School\" - Horror Adventure\n\n### Về game:\n- Genre: Horror/Mystery/Adventure\n- Platform: PC (Steam)\n- Art style: Low-poly với lighting atmosph...','job','HorrorGameStudio','horror@example.com',NULL,11,'published',0,0,288,10,12,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-08 06:17:12','GameOptimizer','2025-08-29 06:17:12','2025-09-09 08:16:13'),(6,'Review khóa học Unity tại GameDev Academy','review-khoa-hoc-unity-tai-gamedev-academy','Vừa hoàn thành khóa Unity 3 tháng tại GameDev Academy, chia sẻ review chi tiết cho ae.\n\n## Thông tin khóa học:\n- **Tên**: Unity Game Development Bootcamp\n- **Thời gian**: 3 tháng (12 tuần)\n- **Học phí**: 15 triệu VND\n- **Format**: Offline + Online hybrid\n- **Lớp**: 15 học viên\n\n## Curriculum:\n### Week 1-4: Unity Basics\n- Interface và tools\n- C# fundamentals\n- GameObject và Component system\n- Physics và Collision\n\n### Week 5-8: Intermediate Topics\n- Animation system\n- UI/UX design\n- Audio integration\n- Lighting và Post-processing\n\n### Week 9-12: Advanced + Project\n- Optimization techniques\n- Mobile deployment\n- Final project (complete game)\n\n## Pros:\n✅ Giảng viên có kinh nghiệm thực tế (ex-Ubisoft)\n✅ Hands-on practice nhiều\n✅ Support tốt, response nhanh\n✅ Career guidance sau khóa\n✅ Networking với classmates\n\n## Cons:\n❌ Pace hơi nhanh cho beginner\n❌ Thiếu advanced topics (shader, multiplayer)\n❌ Không có guest speakers từ industry\n❌ Lab equipment hơi cũ\n\n## Final project:\nMình làm 2D platformer \"Ninja Cat Adventures\" với:\n- 5 levels\n- Boss fights\n- Achievement system\n- Leaderboard\n\n## Kết quả:\nSau khóa mình apply được 3 company và nhận offer junior Unity developer tại 1 startup game ở TP.HCM với mức lương 12M/tháng.\n\n## Rating: 7.5/10\n\n**Recommend cho**: Người có programming background muốn chuyển sang game dev\n**Không recommend cho**: Complete beginner (nên học C# trước)\n\nAi có câu hỏi comment bên dưới nhé!',0,0,'Vừa hoàn thành khóa Unity 3 tháng tại GameDev Academy, chia sẻ review chi tiết cho ae.\n\n## Thông tin khóa học:\n- **Tên**: Unity Game Development Bootcamp\n- **Th...','review','NewbieCoder','newbiecoder@example.com',NULL,12,'published',0,0,445,4,31,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-09 06:17:12','CodeMaster','2025-08-29 06:17:12','2025-09-09 06:17:12'),(7,'[HELP] Lỗi NullReferenceException khi Instantiate object','help-loi-nullreferenceexception-khi-instantiate-object','Chào mọi người, mình đang gặp lỗi NullReferenceException khi instantiate prefab trong Unity.\n\n## Code hiện tại:\n```csharp\npublic class EnemySpawner : MonoBehaviour\n{\n    public GameObject enemyPrefab;\n    public Transform spawnPoint;\n    \n    void Start()\n    {\n        InvokeRepeating(\"SpawnEnemy\", 1f, 2f);\n    }\n    \n    void SpawnEnemy()\n    {\n        GameObject enemy = Instantiate(enemyPrefab, spawnPoint.position, spawnPoint.rotation);\n        // Error ở dòng này:\n        enemy.GetComponent<Enemy>().Initialize(player.transform);\n    }\n}\n```\n\n## Error message:\n`NullReferenceException: Object reference not set to an instance of an object`\n\n## Đã thử:\n- Check prefab đã assign\n- Check spawnPoint không null\n- Check Enemy script đã attach vào prefab\n\nNhưng vẫn bị lỗi. Ai biết lý do không ạ?\n\n**Unity version**: 2023.1.15f1\n**Platform**: Windows\n\nCảm ơn mọi người!',0,0,'Chào mọi người, mình đang gặp lỗi NullReferenceException khi instantiate prefab trong Unity.\n\n## Code hiện tại:\n```csharp\npublic class EnemySpawner : MonoBehavi...','question','UnityLearner','learner@example.com',NULL,13,'published',0,0,79,4,3,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-09 06:17:12','MobileDev2023','2025-09-04 06:17:12','2025-09-09 15:17:30'),(8,'[SHOWCASE] \\\"Cyber Runner\\\" - Game endless runner hoàn thành','showcase-cyber-runner-game-endless-runner-hoan-thanh','Sau 6 tháng làm việc, mình đã hoàn thành game đầu tiên \"Cyber Runner\"!\n\n## Game Info:\n- **Genre**: Endless Runner\n- **Platform**: Android, iOS\n- **Engine**: Unity 2023.1\n- **Art Style**: Cyberpunk low-poly\n- **Team Size**: Solo developer\n\n## Features:\n🏃‍♂️ Smooth character movement với parkour mechanics\n🌃 Procedural level generation\n💰 Coin collection system\n🛍️ In-app purchases cho characters/power-ups\n📊 Leaderboard integration (Google Play Games)\n🎵 Dynamic music system\n⚡ Particle effects và screen shake\n\n## Technical Highlights:\n- **Object Pooling** cho tất cả moving objects\n- **Addressables** cho asset management\n- **Unity Analytics** cho player behavior tracking\n- **Custom shaders** cho neon effects\n- **Optimized** cho 60 FPS trên mid-range phones\n\n## Screenshots:\n[Link imgur album]\n\n## Trailer:\n[YouTube link]\n\n## Download:\n- Google Play: [Link]\n- App Store: [Link] (pending review)\n\n## Development Stats:\n- **Code lines**: ~8,000 (C#)\n- **Art assets**: 150+ models, 50+ textures\n- **Audio**: 20 SFX, 5 background tracks\n- **Build size**: 45MB\n\n## Lessons Learned:\n1. **Scope creep** là kẻ thù lớn nhất\n2. **Playtesting** càng sớm càng tốt\n3. **Performance** quan trọng hơn graphics fancy\n4. **Marketing** khó hơn development 😅\n\n## Next Steps:\nĐang plan game thứ 2 - một puzzle game với mechanics unique hơn.\n\nMọi người feedback giúp mình với! Đặc biệt là về gameplay balance và monetization.\n\nCảm ơn cộng đồng đã support mình suốt quá trình dev! 🙏',0,0,'Sau 6 tháng làm việc, mình đã hoàn thành game đầu tiên \"Cyber Runner\"!\n\n## Game Info:\n- **Genre**: Endless Runner\n- **Platform**: Android, iOS\n- **Engine**: Uni...','showcase','CyberRunner_Dev','cyberrunner@example.com',NULL,14,'published',1,0,523,3,67,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-08 06:17:12','UnityExpert','2025-09-07 06:17:12','2025-09-09 06:17:12'),(9,'lap trinh game dùng python','lap-trinh-game-dung-python','<ul><li>lap trinh game dùng python</li></ul>',0,0,'lap trinh game dùng python','discussion','Nguyen','tung@yopmail.com',NULL,9,'published',0,0,0,0,0,0,NULL,NULL,NULL,NULL,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',NULL,NULL,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(10,'y tuong lam game dan gian','y-tuong-lam-game-dan-gian','y tuong lam game dan gian',0,0,'y tuong lam game dan gian','idea','Nguyen Thanh Tung 1','tung11@yopmail.com',NULL,10,'published',0,0,0,0,0,0,NULL,NULL,NULL,NULL,'192.168.65.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36',NULL,NULL,'2025-09-24 15:47:09','2025-09-24 15:47:09');
/*!40000 ALTER TABLE `forum_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_reports`
--

DROP TABLE IF EXISTS `forum_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reporter_id` int unsigned NOT NULL,
  `reportable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reportable_id` bigint unsigned NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','reviewed','resolved','dismissed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` int unsigned DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_reports_reporter_id_foreign` (`reporter_id`),
  KEY `forum_reports_reviewed_by_foreign` (`reviewed_by`),
  KEY `forum_reports_reportable_type_reportable_id_index` (`reportable_type`,`reportable_id`),
  KEY `forum_reports_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `forum_reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_reports`
--

LOCK TABLES `forum_reports` WRITE;
/*!40000 ALTER TABLE `forum_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_tags`
--

DROP TABLE IF EXISTS `forum_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6b7280',
  `posts_count` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forum_tags_slug_unique` (`slug`),
  KEY `forum_tags_slug_index` (`slug`),
  KEY `forum_tags_is_featured_posts_count_index` (`is_featured`,`posts_count`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_tags`
--

LOCK TABLES `forum_tags` WRITE;
/*!40000 ALTER TABLE `forum_tags` DISABLE KEYS */;
INSERT INTO `forum_tags` VALUES (7,'Unity','unity','Unity game engine','#f093fb',7,0,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(8,'Unreal Engine','unreal-engine','Unreal Engine game development','#fed6e3',2,0,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(9,'Godot','godot','Godot game engine','#4facfe',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(10,'GameMaker','gamemaker','GameMaker Studio','#00f2fe',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(11,'C#','c','C# programming language','#4facfe',4,0,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(12,'C++','cpp','C++ programming language','#f5576c',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(13,'JavaScript','javascript','JavaScript for game development','#f093fb',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(14,'Python','python','Python game development','#f5576c',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(15,'GDScript','gdscript','Godot scripting language','#38f9d7',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(16,'Blueprint','blueprint','Unreal Engine visual scripting','#a1c4fd',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(17,'2D Games','2d-games','2D game development','#a8edea',2,0,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(18,'3D Games','3d-games','3D game development','#fad0c4',2,0,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(19,'Mobile Games','mobile-games','Mobile game development','#a8edea',3,0,'2025-09-09 06:17:10','2025-09-09 06:17:12'),(20,'Web Games','web-games','Browser game development','#43e97b',0,0,'2025-09-09 06:17:10','2025-09-09 06:17:10'),(21,'VR/AR','vrar','Virtual and Augmented Reality games','#fcb69f',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(22,'RPG','rpg','Role-playing games','#ffd1ff',1,0,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(23,'Action','action','Action games','#a1c4fd',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(24,'Strategy','strategy','Strategy games','#38f9d7',1,0,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(25,'Puzzle','puzzle','Puzzle games','#ffecd2',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(26,'Platformer','platformer','Platform games','#43e97b',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(27,'Shooter','shooter','Shooting games','#a1c4fd',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(28,'Racing','racing','Racing games','#a1c4fd',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(29,'Simulation','simulation','Simulation games','#f5576c',1,0,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(30,'AI','ai','Artificial Intelligence in games','#fcb69f',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(31,'Physics','physics','Game physics','#a1c4fd',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(32,'Graphics','graphics','Game graphics and rendering','#c2e9fb',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(33,'Audio','audio','Game audio and sound','#667eea',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(34,'UI/UX','uiux','User interface and experience','#38f9d7',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(35,'Optimization','optimization','Performance optimization','#38f9d7',1,0,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(36,'Networking','networking','Multiplayer and networking','#ffd1ff',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(37,'Game Design','game-design','Game design concepts','#fcb69f',1,0,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(38,'Art','art','Game art and assets','#fad0c4',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(39,'Animation','animation','Game animation','#fed6e3',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(40,'Testing','testing','Game testing and QA','#667eea',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(41,'Publishing','publishing','Game publishing and marketing','#f093fb',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(42,'Monetization','monetization','Game monetization strategies','#fad0c4',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(43,'Steam','steam','Steam platform','#f093fb',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(44,'Mobile','mobile','Mobile platforms (iOS/Android)','#ffd1ff',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(45,'Console','console','Console gaming platforms','#ffd1ff',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(46,'PC','pc','PC gaming','#667eea',2,0,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(47,'Beginner','beginner','Beginner-friendly content','#ffd1ff',3,1,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(48,'Tutorial','tutorial','Tutorials and guides','#ffd1ff',1,1,'2025-09-09 06:17:11','2025-09-09 06:17:12'),(49,'Tips','tips','Tips and tricks','#764ba2',0,1,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(50,'Resource','resource','Useful resources','#ffecd2',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(51,'Tools','tools','Development tools','#f093fb',0,0,'2025-09-09 06:17:11','2025-09-09 06:17:11'),(52,'Unity','unity-1',NULL,'#6b7280',0,0,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(53,'C#','c-1',NULL,'#6b7280',0,0,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(54,'PC','pc-1',NULL,'#6b7280',0,0,'2025-09-24 15:29:54','2025-09-24 15:29:54'),(55,'Unity','unity-2',NULL,'#6b7280',0,0,'2025-09-24 15:47:09','2025-09-24 15:47:09');
/*!40000 ALTER TABLE `forum_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum_votes`
--

DROP TABLE IF EXISTS `forum_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forum_votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voteable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voteable_id` bigint unsigned NOT NULL,
  `voter_identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vote_type` enum('like','dislike') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `forum_votes_unique_vote` (`voteable_type`,`voteable_id`,`voter_identifier`),
  KEY `forum_votes_voteable_type_voteable_id_index` (`voteable_type`,`voteable_id`),
  KEY `forum_votes_voter_identifier_index` (`voter_identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum_votes`
--

LOCK TABLES `forum_votes` WRITE;
/*!40000 ALTER TABLE `forum_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gdpr_data_request`
--

DROP TABLE IF EXISTS `gdpr_data_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gdpr_data_request` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gdpr_data_request_customer_id_foreign` (`customer_id`),
  CONSTRAINT `gdpr_data_request_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gdpr_data_request`
--

LOCK TABLES `gdpr_data_request` WRITE;
/*!40000 ALTER TABLE `gdpr_data_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `gdpr_data_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `import_batches`
--

DROP TABLE IF EXISTS `import_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `import_batches` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `data` json NOT NULL,
  `summary` json DEFAULT NULL,
  `import_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `import_batches_import_id_foreign` (`import_id`),
  CONSTRAINT `import_batches_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `import_batches`
--

LOCK TABLES `import_batches` WRITE;
/*!40000 ALTER TABLE `import_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `import_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imports`
--

DROP TABLE IF EXISTS `imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imports` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `process_in_queue` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `validation_strategy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `allowed_errors` int NOT NULL DEFAULT '0',
  `processed_rows_count` int NOT NULL DEFAULT '0',
  `invalid_rows_count` int NOT NULL DEFAULT '0',
  `errors_count` int NOT NULL DEFAULT '0',
  `errors` json DEFAULT NULL,
  `field_separator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `images_directory_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` json DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imports`
--

LOCK TABLES `imports` WRITE;
/*!40000 ALTER TABLE `imports` DISABLE KEYS */;
/*!40000 ALTER TABLE `imports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_sources`
--

DROP TABLE IF EXISTS `inventory_sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_sources` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int NOT NULL DEFAULT '0',
  `latitude` decimal(10,5) DEFAULT NULL,
  `longitude` decimal(10,5) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_sources_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_sources`
--

LOCK TABLES `inventory_sources` WRITE;
/*!40000 ALTER TABLE `inventory_sources` DISABLE KEYS */;
INSERT INTO `inventory_sources` VALUES (1,'default','Default',NULL,'Default','warehouse@example.com','1234567899',NULL,'US','MI','Detroit','12th Street','48127',0,NULL,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `inventory_sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_percent` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `product_id` int unsigned DEFAULT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_item_id` int unsigned DEFAULT NULL,
  `invoice_id` int unsigned DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_parent_id_foreign` (`parent_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `invoice_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `increment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT '0',
  `total_qty` int DEFAULT NULL,
  `base_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` decimal(12,4) DEFAULT '0.0000',
  `base_sub_total` decimal(12,4) DEFAULT '0.0000',
  `grand_total` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total` decimal(12,4) DEFAULT '0.0000',
  `shipping_amount` decimal(12,4) DEFAULT '0.0000',
  `base_shipping_amount` decimal(12,4) DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `shipping_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `order_id` int unsigned DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reminders` int NOT NULL DEFAULT '0',
  `next_reminder_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locales`
--

DROP TABLE IF EXISTS `locales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` enum('ltr','rtl') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ltr',
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `locales_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locales`
--

LOCK TABLES `locales` WRITE;
/*!40000 ALTER TABLE `locales` DISABLE KEYS */;
INSERT INTO `locales` VALUES (1,'vi','Tiếng Việt','ltr',NULL,NULL,NULL),(2,'en','en','ltr',NULL,'2025-09-20 07:04:38','2025-09-20 07:04:38');
/*!40000 ALTER TABLE `locales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marketing_campaigns`
--

DROP TABLE IF EXISTS `marketing_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_campaigns` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spooling` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int unsigned DEFAULT NULL,
  `customer_group_id` int unsigned DEFAULT NULL,
  `marketing_template_id` int unsigned DEFAULT NULL,
  `marketing_event_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_campaigns_channel_id_foreign` (`channel_id`),
  KEY `marketing_campaigns_customer_group_id_foreign` (`customer_group_id`),
  KEY `marketing_campaigns_marketing_template_id_foreign` (`marketing_template_id`),
  KEY `marketing_campaigns_marketing_event_id_foreign` (`marketing_event_id`),
  CONSTRAINT `marketing_campaigns_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `marketing_campaigns_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE SET NULL,
  CONSTRAINT `marketing_campaigns_marketing_event_id_foreign` FOREIGN KEY (`marketing_event_id`) REFERENCES `marketing_events` (`id`) ON DELETE SET NULL,
  CONSTRAINT `marketing_campaigns_marketing_template_id_foreign` FOREIGN KEY (`marketing_template_id`) REFERENCES `marketing_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketing_campaigns`
--

LOCK TABLES `marketing_campaigns` WRITE;
/*!40000 ALTER TABLE `marketing_campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `marketing_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marketing_events`
--

DROP TABLE IF EXISTS `marketing_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_events` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketing_events`
--

LOCK TABLES `marketing_events` WRITE;
/*!40000 ALTER TABLE `marketing_events` DISABLE KEYS */;
INSERT INTO `marketing_events` VALUES (1,'Birthday','Birthday',NULL,NULL,NULL);
/*!40000 ALTER TABLE `marketing_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marketing_templates`
--

DROP TABLE IF EXISTS `marketing_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marketing_templates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketing_templates`
--

LOCK TABLES `marketing_templates` WRITE;
/*!40000 ALTER TABLE `marketing_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `marketing_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,NULL,'Trang chủ','/',1,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52'),(2,1,NULL,'Source Game','/source-game',2,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52'),(3,1,NULL,'Forum','/forum',3,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52'),(4,1,NULL,'Blog','/blog',4,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52'),(5,1,NULL,'Việc làm','/viec-lam-game',5,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52'),(6,1,NULL,'Giới thiệu','/gioi-thieu',6,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52'),(7,1,NULL,'Liên hệ','/lien-he',7,'_self',NULL,'2025-09-09 08:37:52','2025-09-09 08:37:52');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_channel_id_foreign` (`channel_id`),
  CONSTRAINT `menus_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Main Menu',1,'2025-09-09 08:37:52','2025-09-09 08:37:52');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=338 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (164,'2014_10_12_000000_create_users_table',1),(165,'2014_10_12_100000_create_admin_password_resets_table',1),(166,'2014_10_12_100000_create_password_resets_table',1),(167,'2018_06_12_111907_create_admins_table',1),(168,'2018_06_13_055341_create_roles_table',1),(169,'2018_07_05_130148_create_attributes_table',1),(170,'2018_07_05_132854_create_attribute_translations_table',1),(171,'2018_07_05_135150_create_attribute_families_table',1),(172,'2018_07_05_135152_create_attribute_groups_table',1),(173,'2018_07_05_140832_create_attribute_options_table',1),(174,'2018_07_05_140856_create_attribute_option_translations_table',1),(175,'2018_07_05_142820_create_categories_table',1),(176,'2018_07_10_055143_create_locales_table',1),(177,'2018_07_20_054426_create_countries_table',1),(178,'2018_07_20_054502_create_currencies_table',1),(179,'2018_07_20_054542_create_currency_exchange_rates_table',1),(180,'2018_07_20_064849_create_channels_table',1),(181,'2018_07_21_142836_create_category_translations_table',1),(182,'2018_07_23_110040_create_inventory_sources_table',1),(183,'2018_07_24_082635_create_customer_groups_table',1),(184,'2018_07_24_082930_create_customers_table',1),(185,'2018_07_27_065727_create_products_table',1),(186,'2018_07_27_070011_create_product_attribute_values_table',1),(187,'2018_07_27_092623_create_product_reviews_table',1),(188,'2018_07_27_113941_create_product_images_table',1),(189,'2018_07_27_113956_create_product_inventories_table',1),(190,'2018_08_30_064755_create_tax_categories_table',1),(191,'2018_08_30_065042_create_tax_rates_table',1),(192,'2018_08_30_065840_create_tax_mappings_table',1),(193,'2018_09_05_150444_create_cart_table',1),(194,'2018_09_05_150915_create_cart_items_table',1),(195,'2018_09_11_064045_customer_password_resets',1),(196,'2018_09_19_093453_create_cart_payment',1),(197,'2018_09_19_093508_create_cart_shipping_rates_table',1),(198,'2018_09_20_060658_create_core_config_table',1),(199,'2018_09_27_113154_create_orders_table',1),(200,'2018_09_27_113207_create_order_items_table',1),(201,'2018_09_27_115022_create_shipments_table',1),(202,'2018_09_27_115029_create_shipment_items_table',1),(203,'2018_09_27_115135_create_invoices_table',1),(204,'2018_09_27_115144_create_invoice_items_table',1),(205,'2018_10_01_095504_create_order_payment_table',1),(206,'2018_10_03_025230_create_wishlist_table',1),(207,'2018_10_12_101803_create_country_translations_table',1),(208,'2018_10_12_101913_create_country_states_table',1),(209,'2018_10_12_101923_create_country_state_translations_table',1),(210,'2018_11_16_173504_create_subscribers_list_table',1),(211,'2018_11_21_144411_create_cart_item_inventories_table',1),(212,'2018_12_06_185202_create_product_flat_table',1),(213,'2018_12_24_123812_create_channel_inventory_sources_table',1),(214,'2018_12_26_165327_create_product_ordered_inventories_table',1),(215,'2019_05_13_024321_create_cart_rules_table',1),(216,'2019_05_13_024322_create_cart_rule_channels_table',1),(217,'2019_05_13_024323_create_cart_rule_customer_groups_table',1),(218,'2019_05_13_024324_create_cart_rule_translations_table',1),(219,'2019_05_13_024325_create_cart_rule_customers_table',1),(220,'2019_05_13_024326_create_cart_rule_coupons_table',1),(221,'2019_05_13_024327_create_cart_rule_coupon_usage_table',1),(222,'2019_06_17_180258_create_product_downloadable_samples_table',1),(223,'2019_06_17_180314_create_product_downloadable_sample_translations_table',1),(224,'2019_06_17_180325_create_product_downloadable_links_table',1),(225,'2019_06_17_180346_create_product_downloadable_link_translations_table',1),(226,'2019_06_21_202249_create_downloadable_link_purchased_table',1),(227,'2019_07_02_180307_create_booking_products_table',1),(228,'2019_07_05_154415_create_booking_product_default_slots_table',1),(229,'2019_07_05_154429_create_booking_product_appointment_slots_table',1),(230,'2019_07_05_154440_create_booking_product_event_tickets_table',1),(231,'2019_07_05_154451_create_booking_product_rental_slots_table',1),(232,'2019_07_05_154502_create_booking_product_table_slots_table',1),(233,'2019_07_30_153530_create_cms_pages_table',1),(234,'2019_07_31_143339_create_category_filterable_attributes_table',1),(235,'2019_08_02_105320_create_product_grouped_products_table',1),(236,'2019_08_20_170510_create_product_bundle_options_table',1),(237,'2019_08_20_170520_create_product_bundle_option_translations_table',1),(238,'2019_08_20_170528_create_product_bundle_option_products_table',1),(239,'2019_09_11_184511_create_refunds_table',1),(240,'2019_09_11_184519_create_refund_items_table',1),(241,'2019_12_03_184613_create_catalog_rules_table',1),(242,'2019_12_03_184651_create_catalog_rule_channels_table',1),(243,'2019_12_03_184732_create_catalog_rule_customer_groups_table',1),(244,'2019_12_06_101110_create_catalog_rule_products_table',1),(245,'2019_12_06_110507_create_catalog_rule_product_prices_table',1),(246,'2019_12_14_000001_create_personal_access_tokens_table',1),(247,'2020_01_14_191854_create_cms_page_translations_table',1),(248,'2020_01_15_130209_create_cms_page_channels_table',1),(249,'2020_02_18_165639_create_bookings_table',1),(250,'2020_02_21_121201_create_booking_product_event_ticket_translations_table',1),(251,'2020_04_16_185147_add_table_addresses',1),(252,'2020_05_06_171638_create_order_comments_table',1),(253,'2020_05_21_171500_create_product_customer_group_prices_table',1),(254,'2020_06_25_162154_create_customer_social_accounts_table',1),(255,'2020_08_07_174804_create_gdpr_data_request_table',1),(256,'2020_11_19_112228_create_product_videos_table',1),(257,'2020_11_26_141455_create_marketing_templates_table',1),(258,'2020_11_26_150534_create_marketing_events_table',1),(259,'2020_11_26_150644_create_marketing_campaigns_table',1),(260,'2020_12_21_000200_create_channel_translations_table',1),(261,'2020_12_27_121950_create_jobs_table',1),(262,'2021_03_11_212124_create_order_transactions_table',1),(263,'2021_04_07_132010_create_product_review_images_table',1),(264,'2021_12_15_104544_notifications',1),(265,'2022_03_15_160510_create_failed_jobs_table',1),(266,'2022_04_01_094622_create_sitemaps_table',1),(267,'2022_10_03_144232_create_product_price_indices_table',1),(268,'2022_10_04_144444_create_job_batches_table',1),(269,'2022_10_08_134150_create_product_inventory_indices_table',1),(270,'2023_03_21_172616_create_blogs_table',1),(271,'2023_03_21_175157_create_blog_categories_table',1),(272,'2023_03_21_175231_create_blog_tags_table',1),(273,'2023_03_21_175251_create_blog_comments_table',1),(274,'2023_05_26_213105_create_wishlist_items_table',1),(275,'2023_05_26_213120_create_compare_items_table',1),(276,'2023_06_27_163529_rename_product_review_images_to_product_review_attachments',1),(277,'2023_07_06_140013_add_logo_path_column_to_locales',1),(278,'2023_07_10_184256_create_theme_customizations_table',1),(279,'2023_07_12_181722_remove_home_page_and_footer_content_column_from_channel_translations_table',1),(280,'2023_07_20_185324_add_column_column_in_attribute_groups_table',1),(281,'2023_07_25_145943_add_regex_column_in_attributes_table',1),(282,'2023_07_25_165945_drop_notes_column_from_customers_table',1),(283,'2023_07_25_171058_create_customer_notes_table',1),(284,'2023_07_31_125232_rename_image_and_category_banner_columns_from_categories_table',1),(285,'2023_09_15_170053_create_theme_customization_translations_table',1),(286,'2023_09_20_102031_add_default_value_column_in_attributes_table',1),(287,'2023_09_20_102635_add_inventories_group_in_attribute_groups_table',1),(288,'2023_09_26_155709_add_columns_to_currencies',1),(289,'2023_10_05_163612_create_visits_table',1),(290,'2023_10_12_090446_add_tax_category_id_column_in_order_items_table',1),(291,'2023_11_08_054614_add_code_column_in_attribute_groups_table',1),(292,'2023_11_08_140116_create_search_terms_table',1),(293,'2023_11_09_162805_create_url_rewrites_table',1),(294,'2023_11_17_150401_create_search_synonyms_table',1),(295,'2023_12_11_054614_add_channel_id_column_in_product_price_indices_table',1),(296,'2024_01_11_154640_create_imports_table',1),(297,'2024_01_11_154741_create_import_batches_table',1),(298,'2024_01_19_170350_add_unique_id_column_in_product_attribute_values_table',1),(299,'2024_01_19_170350_add_unique_id_column_in_product_customer_group_prices_table',1),(300,'2024_01_22_170814_add_unique_index_in_mapping_tables',1),(301,'2024_02_26_153000_add_columns_to_addresses_table',1),(302,'2024_03_07_193421_rename_address1_column_in_addresses_table',1),(303,'2024_04_16_144400_add_cart_id_column_in_cart_shipping_rates_table',1),(304,'2024_04_19_102939_add_incl_tax_columns_in_orders_table',1),(305,'2024_04_19_135405_add_incl_tax_columns_in_cart_items_table',1),(306,'2024_04_19_144641_add_incl_tax_columns_in_order_items_table',1),(307,'2024_04_23_133154_add_incl_tax_columns_in_cart_table',1),(308,'2024_04_23_150945_add_incl_tax_columns_in_cart_shipping_rates_table',1),(309,'2024_04_24_102939_add_incl_tax_columns_in_invoices_table',1),(310,'2024_04_24_102939_add_incl_tax_columns_in_refunds_table',1),(311,'2024_04_24_144641_add_incl_tax_columns_in_invoice_items_table',1),(312,'2024_04_24_144641_add_incl_tax_columns_in_refund_items_table',1),(313,'2024_04_24_144641_add_incl_tax_columns_in_shipment_items_table',1),(314,'2024_05_10_152848_create_saved_filters_table',1),(315,'2024_06_03_174128_create_product_channels_table',1),(316,'2024_06_04_130527_add_channel_id_column_in_customers_table',1),(317,'2024_06_04_134403_add_channel_id_column_in_visits_table',1),(318,'2024_06_13_184426_add_theme_column_into_theme_customizations_table',1),(319,'2024_07_17_172645_add_additional_column_to_sitemaps_table',1),(320,'2024_10_11_135010_create_product_customizable_options_table',1),(321,'2024_10_11_135110_create_product_customizable_option_translations_table',1),(322,'2024_10_11_135228_create_product_customizable_option_prices_table',1),(323,'2025_07_04_152757_create_menus_table',1),(324,'2025_07_04_152804_create_menu_items_table',1),(325,'2024_12_09_000001_create_forum_categories_table',2),(326,'2024_12_09_000002_create_forum_posts_table',2),(327,'2024_12_09_000003_create_forum_comments_table',2),(328,'2024_12_09_000004_create_forum_tags_table',2),(330,'2024_12_09_000005_create_forum_post_tags_table',3),(331,'2024_12_09_000006_create_forum_votes_table',3),(333,'2025_09_09_142418_add_edit_history_to_forum_posts_table',4),(334,'2025_09_09_170743_create_forum_reports_table',5),(335,'2025_09_09_171028_add_user_status_fields_to_users_table',5),(336,'2025_09_26_143304_add_views_to_blogs_table',6),(337,'2025_09_26_144022_add_stats_to_forum_posts_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `order_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_order_id_foreign` (`order_id`),
  CONSTRAINT `notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_comments`
--

DROP TABLE IF EXISTS `order_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_notified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_comments_order_id_foreign` (`order_id`),
  CONSTRAINT `order_comments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_comments`
--

LOCK TABLES `order_comments` WRITE;
/*!40000 ALTER TABLE `order_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` decimal(12,4) DEFAULT '0.0000',
  `total_weight` decimal(12,4) DEFAULT '0.0000',
  `qty_ordered` int DEFAULT '0',
  `qty_shipped` int DEFAULT '0',
  `qty_invoiced` int DEFAULT '0',
  `qty_canceled` int DEFAULT '0',
  `qty_refunded` int DEFAULT '0',
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_invoiced` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total_invoiced` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `amount_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_amount_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_percent` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_discount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `discount_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_discount_refunded` decimal(12,4) DEFAULT '0.0000',
  `tax_percent` decimal(12,4) DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `tax_amount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `tax_amount_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount_refunded` decimal(12,4) DEFAULT '0.0000',
  `price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `product_id` int unsigned DEFAULT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` int unsigned DEFAULT NULL,
  `tax_category_id` int unsigned DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_parent_id_foreign` (`parent_id`),
  KEY `order_items_tax_category_id_foreign` (`tax_category_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_payment`
--

DROP TABLE IF EXISTS `order_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_payment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_payment_order_id_foreign` (`order_id`),
  CONSTRAINT `order_payment_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_payment`
--

LOCK TABLES `order_payment` WRITE;
/*!40000 ALTER TABLE `order_payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_transactions`
--

DROP TABLE IF EXISTS `order_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(12,4) DEFAULT '0.0000',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `invoice_id` int unsigned NOT NULL,
  `order_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `order_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_transactions`
--

LOCK TABLES `order_transactions` WRITE;
/*!40000 ALTER TABLE `order_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `increment_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_guest` tinyint(1) DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_gift` tinyint(1) NOT NULL DEFAULT '0',
  `total_item_count` int DEFAULT NULL,
  `total_qty_ordered` int DEFAULT NULL,
  `base_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grand_total` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total` decimal(12,4) DEFAULT '0.0000',
  `grand_total_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total_invoiced` decimal(12,4) DEFAULT '0.0000',
  `grand_total_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total_refunded` decimal(12,4) DEFAULT '0.0000',
  `sub_total` decimal(12,4) DEFAULT '0.0000',
  `base_sub_total` decimal(12,4) DEFAULT '0.0000',
  `sub_total_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_sub_total_invoiced` decimal(12,4) DEFAULT '0.0000',
  `sub_total_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_sub_total_refunded` decimal(12,4) DEFAULT '0.0000',
  `discount_percent` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_discount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `discount_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_discount_refunded` decimal(12,4) DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `tax_amount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount_invoiced` decimal(12,4) DEFAULT '0.0000',
  `tax_amount_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount_refunded` decimal(12,4) DEFAULT '0.0000',
  `shipping_amount` decimal(12,4) DEFAULT '0.0000',
  `base_shipping_amount` decimal(12,4) DEFAULT '0.0000',
  `shipping_invoiced` decimal(12,4) DEFAULT '0.0000',
  `base_shipping_invoiced` decimal(12,4) DEFAULT '0.0000',
  `shipping_refunded` decimal(12,4) DEFAULT '0.0000',
  `base_shipping_refunded` decimal(12,4) DEFAULT '0.0000',
  `shipping_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_shipping_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `shipping_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shipping_tax_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_tax_refunded` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `customer_id` int unsigned DEFAULT NULL,
  `customer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int unsigned DEFAULT NULL,
  `channel_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_id` int DEFAULT NULL,
  `applied_cart_rule_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_increment_id_unique` (`increment_id`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_channel_id_foreign` (`channel_id`),
  CONSTRAINT `orders_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attribute_values`
--

DROP TABLE IF EXISTS `product_attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_attribute_values` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_value` text COLLATE utf8mb4_unicode_ci,
  `boolean_value` tinyint(1) DEFAULT NULL,
  `integer_value` int DEFAULT NULL,
  `float_value` decimal(12,4) DEFAULT NULL,
  `datetime_value` datetime DEFAULT NULL,
  `date_value` date DEFAULT NULL,
  `json_value` json DEFAULT NULL,
  `product_id` int unsigned NOT NULL,
  `attribute_id` int unsigned NOT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chanel_locale_attribute_value_index_unique` (`channel`,`locale`,`attribute_id`,`product_id`),
  UNIQUE KEY `product_attribute_values_unique_id_unique` (`unique_id`),
  KEY `product_attribute_values_product_id_foreign` (`product_id`),
  KEY `product_attribute_values_attribute_id_foreign` (`attribute_id`),
  CONSTRAINT `product_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=406 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_attribute_values`
--

LOCK TABLES `product_attribute_values` WRITE;
/*!40000 ALTER TABLE `product_attribute_values` DISABLE KEYS */;
INSERT INTO `product_attribute_values` VALUES (1,'vi','default','UNITY_ENDLESS_RUNNER_001',NULL,NULL,NULL,NULL,NULL,NULL,1,1,NULL),(2,'vi','default','Unity 3D Endless Runner Game - Source Code',NULL,NULL,NULL,NULL,NULL,NULL,1,2,NULL),(3,'vi','default','<h2>Unity 3D Endless Runner Game - Complete Source Code</h2>\n                <p>Một game endless runner 3D hoàn chỉnh được phát triển trên Unity, bao gồm đầy đủ source code, assets và documentation. Game có gameplay hấp dẫn với nhiều tính năng:</p>\n                \n                <h3>✅ Tính năng chính:</h3>\n                <ul>\n                <li>🎮 Gameplay endless runner mượt mà</li>\n                <li>🏃‍♂️ Character controller với animation đẹp mắt</li>\n                <li>🌍 Procedural level generation</li>\n                <li>💰 Coin collection system</li>\n                <li>🛍️ In-app purchase integration</li>\n                <li>📊 Leaderboard & achievements</li>\n                <li>🎵 Background music & sound effects</li>\n                <li>📱 Mobile-optimized UI</li>\n                </ul>\n                \n                <h3>🔧 Technical Details:</h3>\n                <ul>\n                <li>🎯 Unity 2022.3 LTS</li>\n                <li>💻 C# programming</li>\n                <li>📱 Ready for Android & iOS</li>\n                <li>🎨 Complete art assets included</li>\n                <li>📖 Documentation & setup guide</li>\n                <li>🛠️ Easy to customize</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,1,10,NULL),(4,'vi','default','Unity 3D Endless Runner game với đầy đủ source code, assets và documentation. Sẵn sàng publish lên Android/iOS.',NULL,NULL,NULL,NULL,NULL,NULL,1,9,NULL),(5,'vi','default',NULL,NULL,NULL,1500000.0000,NULL,NULL,NULL,1,11,NULL),(6,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,1,22,NULL),(7,'vi','default','1',1,1,NULL,NULL,NULL,NULL,1,8,NULL),(8,'vi','default','1',1,1,NULL,NULL,NULL,NULL,1,6,NULL),(9,'vi','default','1',1,1,NULL,NULL,NULL,NULL,1,5,NULL),(10,'vi','default','1',1,1,NULL,NULL,NULL,NULL,1,7,NULL),(11,'vi','default','Unity 3D Endless Runner Game Source Code - LamGame',NULL,NULL,NULL,NULL,NULL,NULL,1,16,NULL),(12,'vi','default','Mua source code Unity 3D Endless Runner game hoàn chỉnh. Bao gồm assets, documentation, sẵn sàng publish Android/iOS.',NULL,NULL,NULL,NULL,NULL,NULL,1,18,NULL),(13,'vi','default','unity, endless runner, source code, mobile game, android, ios',NULL,NULL,NULL,NULL,NULL,NULL,1,17,NULL),(14,'vi','default','10',NULL,NULL,NULL,NULL,NULL,NULL,1,29,NULL),(15,'vi','default','20,21',NULL,NULL,NULL,NULL,NULL,NULL,1,30,NULL),(16,'vi','default','29',NULL,NULL,NULL,NULL,NULL,NULL,1,31,NULL),(17,'vi','default','40',NULL,NULL,NULL,NULL,NULL,NULL,1,32,NULL),(18,'vi','default','51',NULL,NULL,NULL,NULL,NULL,NULL,1,33,NULL),(19,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,1,34,NULL),(20,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,1,35,NULL),(21,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,1,36,NULL),(22,'vi','default','55',NULL,NULL,NULL,NULL,NULL,NULL,1,37,NULL),(23,'vi','default','59',NULL,NULL,NULL,NULL,NULL,NULL,1,38,NULL),(24,'vi','default','JOB_UNITY_DEV_001',NULL,NULL,NULL,NULL,NULL,NULL,2,1,NULL),(25,'vi','default','Unity Developer - Game Studio ABC',NULL,NULL,NULL,NULL,NULL,NULL,2,2,NULL),(26,'vi','default','<h2>Mô tả công việc</h2>\n                <p>Game Studio ABC đang tìm kiếm Unity Developer tài năng để tham gia phát triển các dự án game mobile hấp dẫn.</p>\n                \n                <h3>📋 Trách nhiệm chính:</h3>\n                <ul>\n                <li>🎮 Phát triển game mobile sử dụng Unity Engine</li>\n                <li>💻 Viết code C# clean, tối ưu và dễ maintain</li>\n                <li>🔧 Tối ưu hiệu năng game cho các thiết bị mobile</li>\n                <li>🤝 Làm việc cùng team Art, Design để implement game features</li>\n                <li>🐛 Debug và fix bugs trong quá trình phát triển</li>\n                <li>📝 Viết documentation cho code và features</li>\n                </ul>\n                \n                <h3>✅ Yêu cầu:</h3>\n                <ul>\n                <li>🎓 Tốt nghiệp Đại học chuyên ngành CNTT hoặc tương đương</li>\n                <li>⭐ Tối thiểu 2 năm kinh nghiệm với Unity</li>\n                <li>💡 Thành thạo C# và OOP</li>\n                <li>📱 Kinh nghiệm phát triển mobile game (Android/iOS)</li>\n                <li>🌐 Tiếng Anh giao tiếp tốt</li>\n                <li>🎯 Đam mê game và công nghệ</li>\n                </ul>\n                \n                <h3>🎁 Phúc lợi:</h3>\n                <ul>\n                <li>💰 Lương 20-30 triệu + thưởng dự án</li>\n                <li>🏥 Bảo hiểm sức khỏe cao cấp</li>\n                <li>🏖️ 15 ngày nghỉ phép/năm</li>\n                <li>🎮 Game room & team building</li>\n                <li>💻 Máy tính & thiết bị làm việc hiện đại</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,2,10,NULL),(27,'vi','default','Tuyển Unity Developer kinh nghiệm 2+ năm phát triển mobile game. Lương 20-30 triệu + thưởng.',NULL,NULL,NULL,NULL,NULL,NULL,2,9,NULL),(28,'vi','default',NULL,NULL,NULL,25000000.0000,NULL,NULL,NULL,2,11,NULL),(29,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,2,22,NULL),(30,'vi','default','1',1,1,NULL,NULL,NULL,NULL,2,8,NULL),(31,'vi','default','1',1,1,NULL,NULL,NULL,NULL,2,6,NULL),(32,'vi','default','1',1,1,NULL,NULL,NULL,NULL,2,5,NULL),(33,'vi','default','1',1,1,NULL,NULL,NULL,NULL,2,7,NULL),(34,'vi','default','Unity Developer - Game Studio ABC | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,2,16,NULL),(35,'vi','default','Cơ hội làm Unity Developer tại Game Studio ABC. Lương 20-30 triệu, môi trường chuyên nghiệp.',NULL,NULL,NULL,NULL,NULL,NULL,2,18,NULL),(36,'vi','default','unity developer, game programmer, mobile game, tuyển dụng',NULL,NULL,NULL,NULL,NULL,NULL,2,17,NULL),(37,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,2,40,NULL),(38,'vi','default','71',NULL,NULL,NULL,NULL,NULL,NULL,2,41,NULL),(39,'vi','default','77',NULL,NULL,NULL,NULL,NULL,NULL,2,42,NULL),(40,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,2,43,NULL),(41,'vi','default','92',NULL,NULL,NULL,NULL,NULL,NULL,2,44,NULL),(42,'vi','default','95,97',NULL,NULL,NULL,NULL,NULL,NULL,2,45,NULL),(43,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,2,46,NULL),(44,'vi','default','122',NULL,NULL,NULL,NULL,NULL,NULL,2,47,NULL),(45,'vi','default','125,127,133',NULL,NULL,NULL,NULL,NULL,NULL,2,48,NULL),(46,'vi','default','2025-10-06',NULL,NULL,NULL,NULL,NULL,NULL,2,49,NULL),(47,'vi','default','hr@gamestudioabc.com',NULL,NULL,NULL,NULL,NULL,NULL,2,50,NULL),(48,'vi','default','0123456789',NULL,NULL,NULL,NULL,NULL,NULL,2,51,NULL),(49,'vi','default','https://gamestudioabc.com',NULL,NULL,NULL,NULL,NULL,NULL,2,52,NULL),(50,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,2,53,NULL),(51,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,2,54,NULL),(52,'vi','default','137',NULL,NULL,NULL,NULL,NULL,NULL,2,55,NULL),(53,'vi','default','JOB_UNITY_SENIOR_2025',NULL,NULL,NULL,NULL,NULL,NULL,4,1,NULL),(54,'vi','default','Senior Unity Developer - VNG Corporation',NULL,NULL,NULL,NULL,NULL,NULL,4,2,NULL),(55,'vi','default','<h2>🎮 VNG Corporation - Senior Unity Developer</h2>\n                <p><strong>VNG Corporation</strong> là một trong những công ty game hàng đầu Việt Nam đang tìm kiếm Senior Unity Developer tài năng để tham gia phát triển các dự án game mobile AAA.</p>\n                \n                <h3>📋 Mô tả công việc:</h3>\n                <ul>\n                <li>🎯 Phát triển và maintain mobile games sử dụng Unity Engine</li>\n                <li>💻 Architect và implement game systems phức tạp</li>\n                <li>🚀 Optimize game performance cho các thiết bị mobile</li>\n                <li>👥 Mentor junior developers và review code</li>\n                <li>🔧 Research và implement new technologies</li>\n                <li>📊 Collaborate với cross-functional teams</li>\n                </ul>\n                \n                <h3>✅ Yêu cầu:</h3>\n                <ul>\n                <li>🎓 Bằng Đại học Khoa học máy tính hoặc tương đương</li>\n                <li>⭐ 5+ năm kinh nghiệm Unity development</li>\n                <li>💡 Expert level C# và design patterns</li>\n                <li>📱 Shipped ít nhất 2 mobile games</li>\n                <li>🔥 Kinh nghiệm với networking, multiplayer systems</li>\n                <li>🌐 English proficiency for international collaboration</li>\n                </ul>\n                \n                <h3>🎁 Benefits Package:</h3>\n                <ul>\n                <li>💰 Lương 50-80 triệu + equity + bonuses</li>\n                <li>🏥 Premium healthcare cho cả gia đình</li>\n                <li>🏖️ 20 ngày nghỉ phép + 5 ngày sick leave</li>\n                <li>🎮 Latest MacBook Pro + gaming setup</li>\n                <li>🌟 Annual team trips + quarterly team building</li>\n                <li>📚 Learning budget 20 triệu/năm</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,4,10,NULL),(56,'vi','default','VNG tuyển Senior Unity Developer 5+ năm kinh nghiệm. Lương 50-80 triệu + equity, làm việc với team quốc tế.',NULL,NULL,NULL,NULL,NULL,NULL,4,9,NULL),(57,'vi','default',NULL,NULL,NULL,65000000.0000,NULL,NULL,NULL,4,11,NULL),(58,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,4,22,NULL),(59,'vi','default','1',1,1,NULL,NULL,NULL,NULL,4,8,NULL),(60,'vi','default','1',1,1,NULL,NULL,NULL,NULL,4,6,NULL),(61,'vi','default','1',1,1,NULL,NULL,NULL,NULL,4,5,NULL),(62,'vi','default','1',1,1,NULL,NULL,NULL,NULL,4,7,NULL),(63,'vi','default','Senior Unity Developer - VNG Corporation | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,4,16,NULL),(64,'vi','default','VNG tuyển Senior Unity Developer 5+ năm kinh nghiệm. Lương 50-80 triệu + equity, làm việc với team quốc tế.',NULL,NULL,NULL,NULL,NULL,NULL,4,18,NULL),(65,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,4,17,NULL),(66,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,4,40,NULL),(67,'vi','default','72',NULL,NULL,NULL,NULL,NULL,NULL,4,41,NULL),(68,'vi','default','79',NULL,NULL,NULL,NULL,NULL,NULL,4,42,NULL),(69,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,4,43,NULL),(70,'vi','default','94',NULL,NULL,NULL,NULL,NULL,NULL,4,44,NULL),(71,'vi','default','95,97,111',NULL,NULL,NULL,NULL,NULL,NULL,4,45,NULL),(72,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,4,46,NULL),(73,'vi','default','123',NULL,NULL,NULL,NULL,NULL,NULL,4,47,NULL),(74,'vi','default','125,127,128,133',NULL,NULL,NULL,NULL,NULL,NULL,4,48,NULL),(75,'vi','default','2025-10-21',NULL,NULL,NULL,NULL,NULL,NULL,4,49,NULL),(76,'vi','default','careers@vng.com.vn',NULL,NULL,NULL,NULL,NULL,NULL,4,50,NULL),(77,'vi','default','028-3835-1234',NULL,NULL,NULL,NULL,NULL,NULL,4,51,NULL),(78,'vi','default','https://www.vng.com.vn',NULL,NULL,NULL,NULL,NULL,NULL,4,52,NULL),(79,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,4,53,NULL),(80,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,4,54,NULL),(81,'vi','default','140',NULL,NULL,NULL,NULL,NULL,NULL,4,55,NULL),(82,'vi','default','JOB_GAME_DESIGNER_GAMELOFT',NULL,NULL,NULL,NULL,NULL,NULL,5,1,NULL),(83,'vi','default','Game Designer - Gameloft Vietnam',NULL,NULL,NULL,NULL,NULL,NULL,5,2,NULL),(84,'vi','default','<h2>🎨 Gameloft Vietnam - Game Designer Position</h2>\n                <p>Join <strong>Gameloft Vietnam</strong>, a leading global game publisher, as a Game Designer to create engaging mobile game experiences for millions of players worldwide.</p>\n                \n                <h3>🎯 Key Responsibilities:</h3>\n                <ul>\n                <li>🎮 Design core gameplay mechanics and game economy</li>\n                <li>📝 Create detailed game design documents</li>\n                <li>🎨 Work closely with art and programming teams</li>\n                <li>📊 Analyze player data and optimize game balance</li>\n                <li>🧪 Conduct playtesting sessions and iterate designs</li>\n                <li>🌟 Research market trends and competitor analysis</li>\n                </ul>\n                \n                <h3>👨‍💻 Requirements:</h3>\n                <ul>\n                <li>🎓 Bachelor in Game Design, Computer Science, or related</li>\n                <li>⭐ 3-5 years experience in game design</li>\n                <li>🎮 Strong knowledge of mobile game genres</li>\n                <li>💡 Understanding of F2P monetization</li>\n                <li>📊 Experience with analytics tools</li>\n                <li>🌐 Good English communication skills</li>\n                </ul>\n                \n                <h3>🌟 What We Offer:</h3>\n                <ul>\n                <li>💰 Competitive salary 30-50 million VND</li>\n                <li>🏥 International health insurance</li>\n                <li>🎮 Work on globally successful games</li>\n                <li>📚 Training and career development</li>\n                <li>🌍 Opportunity to work with international teams</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,5,10,NULL),(85,'vi','default','Gameloft tuyển Game Designer 3-5 năm kinh nghiệm. Làm việc với team quốc tế, phát triển game mobile toàn cầu.',NULL,NULL,NULL,NULL,NULL,NULL,5,9,NULL),(86,'vi','default',NULL,NULL,NULL,40000000.0000,NULL,NULL,NULL,5,11,NULL),(87,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,5,22,NULL),(88,'vi','default','1',1,1,NULL,NULL,NULL,NULL,5,8,NULL),(89,'vi','default','1',1,1,NULL,NULL,NULL,NULL,5,6,NULL),(90,'vi','default','1',1,1,NULL,NULL,NULL,NULL,5,5,NULL),(91,'vi','default','1',1,1,NULL,NULL,NULL,NULL,5,7,NULL),(92,'vi','default','Game Designer - Gameloft Vietnam | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,5,16,NULL),(93,'vi','default','Gameloft tuyển Game Designer 3-5 năm kinh nghiệm. Làm việc với team quốc tế, phát triển game mobile toàn cầu.',NULL,NULL,NULL,NULL,NULL,NULL,5,18,NULL),(94,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,5,17,NULL),(95,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,5,40,NULL),(96,'vi','default','71',NULL,NULL,NULL,NULL,NULL,NULL,5,41,NULL),(97,'vi','default','78',NULL,NULL,NULL,NULL,NULL,NULL,5,42,NULL),(98,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,5,43,NULL),(99,'vi','default','94',NULL,NULL,NULL,NULL,NULL,NULL,5,44,NULL),(100,'vi','default','113,95',NULL,NULL,NULL,NULL,NULL,NULL,5,45,NULL),(101,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,5,46,NULL),(102,'vi','default','122',NULL,NULL,NULL,NULL,NULL,NULL,5,47,NULL),(103,'vi','default','125,130,135',NULL,NULL,NULL,NULL,NULL,NULL,5,48,NULL),(104,'vi','default','2025-10-06',NULL,NULL,NULL,NULL,NULL,NULL,5,49,NULL),(105,'vi','default','hr.vietnam@gameloft.com',NULL,NULL,NULL,NULL,NULL,NULL,5,50,NULL),(106,'vi','default','028-3821-0123',NULL,NULL,NULL,NULL,NULL,NULL,5,51,NULL),(107,'vi','default','https://www.gameloft.com',NULL,NULL,NULL,NULL,NULL,NULL,5,52,NULL),(108,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,5,53,NULL),(109,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,5,54,NULL),(110,'vi','default','137',NULL,NULL,NULL,NULL,NULL,NULL,5,55,NULL),(111,'vi','default','JOB_2D_ARTIST_AXIE',NULL,NULL,NULL,NULL,NULL,NULL,6,1,NULL),(112,'vi','default','2D Game Artist - Sky Mavis (Axie Infinity)',NULL,NULL,NULL,NULL,NULL,NULL,6,2,NULL),(113,'vi','default','<h2>🎨 Sky Mavis - 2D Game Artist</h2>\n                <p>Sky Mavis, creator of the world-famous Axie Infinity, is looking for talented 2D Game Artists to join our creative team and shape the future of blockchain gaming.</p>\n                \n                <h3>🖌️ What You\'ll Do:</h3>\n                <ul>\n                <li>🎨 Create stunning 2D character designs and illustrations</li>\n                <li>🖼️ Design UI/UX elements for mobile and web games</li>\n                <li>🎮 Develop visual concepts for new game features</li>\n                <li>📱 Create marketing assets and promotional materials</li>\n                <li>🤝 Collaborate with game designers and developers</li>\n                <li>🔄 Iterate on designs based on feedback</li>\n                </ul>\n                \n                <h3>🎯 We\'re Looking For:</h3>\n                <ul>\n                <li>🎓 Fine Arts, Graphic Design degree preferred</li>\n                <li>⭐ 2+ years experience in game art</li>\n                <li>🎨 Proficient in Photoshop, Illustrator</li>\n                <li>🎮 Understanding of mobile game UI/UX</li>\n                <li>💡 Creative mindset with attention to detail</li>\n                <li>🌐 Basic English for team communication</li>\n                </ul>\n                \n                <h3>🚀 Benefits:</h3>\n                <ul>\n                <li>💰 20-35 million VND + token incentives</li>\n                <li>🏠 Hybrid working arrangement</li>\n                <li>🎮 Work on revolutionary blockchain games</li>\n                <li>🌟 Be part of gaming history</li>\n                <li>📚 Learning opportunities in Web3</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,6,10,NULL),(114,'vi','default','Sky Mavis (Axie Infinity) tuyển 2D Game Artist. Hybrid work, làm việc với blockchain gaming, có token incentives.',NULL,NULL,NULL,NULL,NULL,NULL,6,9,NULL),(115,'vi','default',NULL,NULL,NULL,27500000.0000,NULL,NULL,NULL,6,11,NULL),(116,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,6,22,NULL),(117,'vi','default','1',1,1,NULL,NULL,NULL,NULL,6,8,NULL),(118,'vi','default','1',1,1,NULL,NULL,NULL,NULL,6,6,NULL),(119,'vi','default','1',1,1,NULL,NULL,NULL,NULL,6,5,NULL),(120,'vi','default','1',1,1,NULL,NULL,NULL,NULL,6,7,NULL),(121,'vi','default','2D Game Artist - Sky Mavis (Axie Infinity) | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,6,16,NULL),(122,'vi','default','Sky Mavis (Axie Infinity) tuyển 2D Game Artist. Hybrid work, làm việc với blockchain gaming, có token incentives.',NULL,NULL,NULL,NULL,NULL,NULL,6,18,NULL),(123,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,6,17,NULL),(124,'vi','default','68',NULL,NULL,NULL,NULL,NULL,NULL,6,40,NULL),(125,'vi','default','70',NULL,NULL,NULL,NULL,NULL,NULL,6,41,NULL),(126,'vi','default','77',NULL,NULL,NULL,NULL,NULL,NULL,6,42,NULL),(127,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,6,43,NULL),(128,'vi','default','92',NULL,NULL,NULL,NULL,NULL,NULL,6,44,NULL),(129,'vi','default','107,113',NULL,NULL,NULL,NULL,NULL,NULL,6,45,NULL),(130,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,6,46,NULL),(131,'vi','default','121',NULL,NULL,NULL,NULL,NULL,NULL,6,47,NULL),(132,'vi','default','131,132,130',NULL,NULL,NULL,NULL,NULL,NULL,6,48,NULL),(133,'vi','default','2025-09-27',NULL,NULL,NULL,NULL,NULL,NULL,6,49,NULL),(134,'vi','default','careers@skymavis.com',NULL,NULL,NULL,NULL,NULL,NULL,6,50,NULL),(135,'vi','default','028-7300-8888',NULL,NULL,NULL,NULL,NULL,NULL,6,51,NULL),(136,'vi','default','https://skymavis.com',NULL,NULL,NULL,NULL,NULL,NULL,6,52,NULL),(137,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,6,53,NULL),(138,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,6,54,NULL),(139,'vi','default','138',NULL,NULL,NULL,NULL,NULL,NULL,6,55,NULL),(140,'vi','default','JOB_QA_TESTER_FSOFT',NULL,NULL,NULL,NULL,NULL,NULL,7,1,NULL),(141,'vi','default','Game QA Tester - FPT Software',NULL,NULL,NULL,NULL,NULL,NULL,7,2,NULL),(142,'vi','default','<h2>🧪 FPT Software - Game QA Tester</h2>\n                <p>FPT Software đang tìm kiếm Game QA Tester tỉ mỉ và có đam mê với chất lượng sản phẩm để tham gia testing các dự án game outsourcing quốc tế.</p>\n                \n                <h3>🔍 Công việc chính:</h3>\n                <ul>\n                <li>🎮 Test gameplay, UI/UX trên nhiều platform</li>\n                <li>🐛 Tìm và report bugs với documentation chi tiết</li>\n                <li>📱 Test compatibility trên các thiết bị mobile</li>\n                <li>📋 Viết test cases và test plans</li>\n                <li>🔄 Regression testing và verification</li>\n                <li>📊 Collaborate với development teams</li>\n                </ul>\n                \n                <h3>✅ Yêu cầu:</h3>\n                <ul>\n                <li>🎓 Tốt nghiệp Đại học/Cao đẳng IT</li>\n                <li>⭐ 1-2 năm kinh nghiệm QA/Testing</li>\n                <li>🎮 Đam mê chơi game nhiều thể loại</li>\n                <li>🔍 Tỉ mỉ, kiên nhẫn trong công việc</li>\n                <li>📝 Kỹ năng viết báo cáo tốt</li>\n                <li>🌐 Đọc hiểu tiếng Anh cơ bản</li>\n                </ul>\n                \n                <h3>💼 Quyền lợi:</h3>\n                <ul>\n                <li>💰 15-25 triệu + allowances</li>\n                <li>🏥 Bảo hiểm FPT Care</li>\n                <li>📚 Training programs và certifications</li>\n                <li>🎮 Access to latest games và devices</li>\n                <li>🌟 Clear career progression path</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,7,10,NULL),(143,'vi','default','FPT Software tuyển Game QA Tester, làm việc với các dự án game quốc tế. Phù hợp fresher có đam mê game.',NULL,NULL,NULL,NULL,NULL,NULL,7,9,NULL),(144,'vi','default',NULL,NULL,NULL,20000000.0000,NULL,NULL,NULL,7,11,NULL),(145,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,7,22,NULL),(146,'vi','default','1',1,1,NULL,NULL,NULL,NULL,7,8,NULL),(147,'vi','default','0',0,0,NULL,NULL,NULL,NULL,7,6,NULL),(148,'vi','default','1',1,1,NULL,NULL,NULL,NULL,7,5,NULL),(149,'vi','default','1',1,1,NULL,NULL,NULL,NULL,7,7,NULL),(150,'vi','default','Game QA Tester - FPT Software | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,7,16,NULL),(151,'vi','default','FPT Software tuyển Game QA Tester, làm việc với các dự án game quốc tế. Phù hợp fresher có đam mê game.',NULL,NULL,NULL,NULL,NULL,NULL,7,18,NULL),(152,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,7,17,NULL),(153,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,7,40,NULL),(154,'vi','default','70',NULL,NULL,NULL,NULL,NULL,NULL,7,41,NULL),(155,'vi','default','76',NULL,NULL,NULL,NULL,NULL,NULL,7,42,NULL),(156,'vi','default','83',NULL,NULL,NULL,NULL,NULL,NULL,7,43,NULL),(157,'vi','default','94',NULL,NULL,NULL,NULL,NULL,NULL,7,44,NULL),(158,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,7,46,NULL),(159,'vi','default','121',NULL,NULL,NULL,NULL,NULL,NULL,7,47,NULL),(160,'vi','default','125,130,134',NULL,NULL,NULL,NULL,NULL,NULL,7,48,NULL),(161,'vi','default','2025-09-20',NULL,NULL,NULL,NULL,NULL,NULL,7,49,NULL),(162,'vi','default','recruitment@fsoft.com.vn',NULL,NULL,NULL,NULL,NULL,NULL,7,50,NULL),(163,'vi','default','024-7300-8866',NULL,NULL,NULL,NULL,NULL,NULL,7,51,NULL),(164,'vi','default','https://www.fpt-software.com',NULL,NULL,NULL,NULL,NULL,NULL,7,52,NULL),(165,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,7,53,NULL),(166,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,7,54,NULL),(167,'vi','default','140',NULL,NULL,NULL,NULL,NULL,NULL,7,55,NULL),(168,'vi','default','JOB_PROJECT_MANAGER_UBISOFT',NULL,NULL,NULL,NULL,NULL,NULL,8,1,NULL),(169,'vi','default','Game Project Manager - Ubisoft Vietnam',NULL,NULL,NULL,NULL,NULL,NULL,8,2,NULL),(170,'vi','default','<h2>📊 Ubisoft Vietnam - Game Project Manager</h2>\n                <p>Ubisoft Vietnam is seeking an experienced Game Project Manager to oversee the development of AAA game projects and ensure successful delivery to global markets.</p>\n                \n                <h3>🎯 Key Responsibilities:</h3>\n                <ul>\n                <li>📋 Manage full game development lifecycle</li>\n                <li>👥 Lead cross-functional teams (20+ members)</li>\n                <li>📅 Create and maintain project schedules</li>\n                <li>💰 Monitor budgets and resource allocation</li>\n                <li>🚀 Ensure quality and timely delivery</li>\n                <li>🌍 Coordinate with global Ubisoft studios</li>\n                </ul>\n                \n                <h3>📚 Requirements:</h3>\n                <ul>\n                <li>🎓 Bachelor in Project Management/Business</li>\n                <li>⭐ 7+ years project management experience</li>\n                <li>🎮 5+ years in game development</li>\n                <li>📊 PMP or Agile certification preferred</li>\n                <li>👑 Strong leadership and communication</li>\n                <li>🌐 Fluent English for global collaboration</li>\n                </ul>\n                \n                <h3>🏆 Outstanding Package:</h3>\n                <ul>\n                <li>💰 80+ million VND + performance bonus</li>\n                <li>🌍 International career opportunities</li>\n                <li>🏥 Premium healthcare + family coverage</li>\n                <li>✈️ Annual leave + travel allowance</li>\n                <li>🎮 Work on world-class AAA games</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,8,10,NULL),(171,'vi','default','Ubisoft Vietnam tuyển Game Project Manager 7+ năm kinh nghiệm. Lương 80+ triệu, cơ hội làm việc quốc tế.',NULL,NULL,NULL,NULL,NULL,NULL,8,9,NULL),(172,'vi','default',NULL,NULL,NULL,90000000.0000,NULL,NULL,NULL,8,11,NULL),(173,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,8,22,NULL),(174,'vi','default','1',1,1,NULL,NULL,NULL,NULL,8,8,NULL),(175,'vi','default','1',1,1,NULL,NULL,NULL,NULL,8,6,NULL),(176,'vi','default','1',1,1,NULL,NULL,NULL,NULL,8,5,NULL),(177,'vi','default','1',1,1,NULL,NULL,NULL,NULL,8,7,NULL),(178,'vi','default','Game Project Manager - Ubisoft Vietnam | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,8,16,NULL),(179,'vi','default','Ubisoft Vietnam tuyển Game Project Manager 7+ năm kinh nghiệm. Lương 80+ triệu, cơ hội làm việc quốc tế.',NULL,NULL,NULL,NULL,NULL,NULL,8,18,NULL),(180,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,8,17,NULL),(181,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,8,40,NULL),(182,'vi','default','73',NULL,NULL,NULL,NULL,NULL,NULL,8,41,NULL),(183,'vi','default','80',NULL,NULL,NULL,NULL,NULL,NULL,8,42,NULL),(184,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,8,43,NULL),(185,'vi','default','94',NULL,NULL,NULL,NULL,NULL,NULL,8,44,NULL),(186,'vi','default','112',NULL,NULL,NULL,NULL,NULL,NULL,8,45,NULL),(187,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,8,46,NULL),(188,'vi','default','123',NULL,NULL,NULL,NULL,NULL,NULL,8,47,NULL),(189,'vi','default','125,128,127',NULL,NULL,NULL,NULL,NULL,NULL,8,48,NULL),(190,'vi','default','2025-11-05',NULL,NULL,NULL,NULL,NULL,NULL,8,49,NULL),(191,'vi','default','vietnam.careers@ubisoft.com',NULL,NULL,NULL,NULL,NULL,NULL,8,50,NULL),(192,'vi','default','028-3824-9999',NULL,NULL,NULL,NULL,NULL,NULL,8,51,NULL),(193,'vi','default','https://www.ubisoft.com',NULL,NULL,NULL,NULL,NULL,NULL,8,52,NULL),(194,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,8,53,NULL),(195,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,8,54,NULL),(196,'vi','default','138',NULL,NULL,NULL,NULL,NULL,NULL,8,55,NULL),(197,'vi','default','JOB_FREELANCE_UNITY_DEV',NULL,NULL,NULL,NULL,NULL,NULL,9,1,NULL),(198,'vi','default','Freelance Unity Developer - Multiple Projects',NULL,NULL,NULL,NULL,NULL,NULL,9,2,NULL),(199,'vi','default','<h2>💼 Freelance Unity Developer Opportunities</h2>\n                <p>We connect talented Unity developers with exciting freelance projects from startups to established studios. Work remotely on diverse gaming projects.</p>\n                \n                <h3>🎮 Project Types:</h3>\n                <ul>\n                <li>📱 Mobile puzzle and casual games</li>\n                <li>🕹️ 2D platformer and arcade games</li>\n                <li>🎯 Hyper-casual gaming prototypes</li>\n                <li>🔧 Game tool and editor development</li>\n                <li>📊 Analytics integration projects</li>\n                <li>🚀 Game optimization and porting</li>\n                </ul>\n                \n                <h3>💻 Requirements:</h3>\n                <ul>\n                <li>⭐ 2+ years Unity development experience</li>\n                <li>💡 Strong C# programming skills</li>\n                <li>📱 Mobile game development knowledge</li>\n                <li>🔧 Experience with Git version control</li>\n                <li>🏠 Reliable home office setup</li>\n                <li>📞 Good communication skills</li>\n                </ul>\n                \n                <h3>💰 Compensation:</h3>\n                <ul>\n                <li>💵 500-800k VND per day rate</li>\n                <li>📅 Flexible working hours</li>\n                <li>🏠 100% remote work</li>\n                <li>🚀 Multiple project opportunities</li>\n                <li>🌟 Build diverse portfolio</li>\n                <li>📈 Performance-based rate increases</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,9,10,NULL),(200,'vi','default','Cơ hội freelance Unity Developer remote với mức 500-800k/ngày. Đa dạng dự án từ startup đến studio lớn.',NULL,NULL,NULL,NULL,NULL,NULL,9,9,NULL),(201,'vi','default',NULL,NULL,NULL,15000000.0000,NULL,NULL,NULL,9,11,NULL),(202,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,9,22,NULL),(203,'vi','default','1',1,1,NULL,NULL,NULL,NULL,9,8,NULL),(204,'vi','default','0',0,0,NULL,NULL,NULL,NULL,9,6,NULL),(205,'vi','default','1',1,1,NULL,NULL,NULL,NULL,9,5,NULL),(206,'vi','default','1',1,1,NULL,NULL,NULL,NULL,9,7,NULL),(207,'vi','default','Freelance Unity Developer - Multiple Projects | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,9,16,NULL),(208,'vi','default','Cơ hội freelance Unity Developer remote với mức 500-800k/ngày. Đa dạng dự án từ startup đến studio lớn.',NULL,NULL,NULL,NULL,NULL,NULL,9,18,NULL),(209,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,9,17,NULL),(210,'vi','default','65',NULL,NULL,NULL,NULL,NULL,NULL,9,40,NULL),(211,'vi','default','70',NULL,NULL,NULL,NULL,NULL,NULL,9,41,NULL),(212,'vi','default','76',NULL,NULL,NULL,NULL,NULL,NULL,9,42,NULL),(213,'vi','default','88',NULL,NULL,NULL,NULL,NULL,NULL,9,43,NULL),(214,'vi','default','91',NULL,NULL,NULL,NULL,NULL,NULL,9,44,NULL),(215,'vi','default','95,97,111',NULL,NULL,NULL,NULL,NULL,NULL,9,45,NULL),(216,'vi','default','115',NULL,NULL,NULL,NULL,NULL,NULL,9,46,NULL),(217,'vi','default','122',NULL,NULL,NULL,NULL,NULL,NULL,9,47,NULL),(218,'vi','default','131,132',NULL,NULL,NULL,NULL,NULL,NULL,9,48,NULL),(219,'vi','default','2025-12-05',NULL,NULL,NULL,NULL,NULL,NULL,9,49,NULL),(220,'vi','default','freelance@gamejobs.vn',NULL,NULL,NULL,NULL,NULL,NULL,9,50,NULL),(221,'vi','default','0901234567',NULL,NULL,NULL,NULL,NULL,NULL,9,51,NULL),(222,'vi','default','https://gamejobs.vn',NULL,NULL,NULL,NULL,NULL,NULL,9,52,NULL),(223,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,9,53,NULL),(224,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,9,54,NULL),(225,'vi','default','137',NULL,NULL,NULL,NULL,NULL,NULL,9,55,NULL),(226,'vi','default','JOB_MOBILE_DEVELOPER_NEXON',NULL,NULL,NULL,NULL,NULL,NULL,10,1,NULL),(227,'vi','default','Mobile Game Developer - Nexon Vietnam',NULL,NULL,NULL,NULL,NULL,NULL,10,2,NULL),(228,'vi','default','<h2>📱 Nexon Vietnam - Mobile Game Developer</h2>\n                <p>Nexon Vietnam đang tìm kiếm Mobile Game Developer để phát triển thế hệ game mobile tiếp theo với công nghệ cutting-edge.</p>\n                \n                <h3>🚀 Nhiệm vụ chính:</h3>\n                <ul>\n                <li>📱 Phát triển game mobile cho Android và iOS</li>\n                <li>⚡ Optimize performance cho low-end devices</li>\n                <li>🔌 Integrate with backend services và APIs</li>\n                <li>💾 Implement data persistence và caching</li>\n                <li>🎮 Work with game engines (Unity/Unreal)</li>\n                <li>🔄 Collaborate trong Agile environment</li>\n                </ul>\n                \n                <h3>🎯 Skill Requirements:</h3>\n                <ul>\n                <li>📱 3+ years mobile game development</li>\n                <li>💻 Native Android (Java/Kotlin) hoặc iOS (Swift)</li>\n                <li>🎮 Experience với Unity hoặc Unreal Engine</li>\n                <li>🌐 RESTful API integration experience</li>\n                <li>⚡ Performance optimization expertise</li>\n                <li>🔧 Git, CI/CD pipeline knowledge</li>\n                </ul>\n                \n                <h3>🎁 Attractive Package:</h3>\n                <ul>\n                <li>💰 35-55 million VND base salary</li>\n                <li>🎯 Annual performance bonus up to 4 months</li>\n                <li>🏥 Comprehensive medical insurance</li>\n                <li>🎮 Company-provided gaming devices</li>\n                <li>🌴 Annual company trip to Korea/Japan</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,10,10,NULL),(229,'vi','default','Nexon Vietnam tuyển Mobile Game Developer 3+ năm. Lương 35-55 triệu + bonus 4 tháng, trip nước ngoài.',NULL,NULL,NULL,NULL,NULL,NULL,10,9,NULL),(230,'vi','default',NULL,NULL,NULL,45000000.0000,NULL,NULL,NULL,10,11,NULL),(231,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,10,22,NULL),(232,'vi','default','1',1,1,NULL,NULL,NULL,NULL,10,8,NULL),(233,'vi','default','1',1,1,NULL,NULL,NULL,NULL,10,6,NULL),(234,'vi','default','1',1,1,NULL,NULL,NULL,NULL,10,5,NULL),(235,'vi','default','1',1,1,NULL,NULL,NULL,NULL,10,7,NULL),(236,'vi','default','Mobile Game Developer - Nexon Vietnam | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,10,16,NULL),(237,'vi','default','Nexon Vietnam tuyển Mobile Game Developer 3+ năm. Lương 35-55 triệu + bonus 4 tháng, trip nước ngoài.',NULL,NULL,NULL,NULL,NULL,NULL,10,18,NULL),(238,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,10,17,NULL),(239,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,10,40,NULL),(240,'vi','default','71',NULL,NULL,NULL,NULL,NULL,NULL,10,41,NULL),(241,'vi','default','78',NULL,NULL,NULL,NULL,NULL,NULL,10,42,NULL),(242,'vi','default','83',NULL,NULL,NULL,NULL,NULL,NULL,10,43,NULL),(243,'vi','default','94',NULL,NULL,NULL,NULL,NULL,NULL,10,44,NULL),(244,'vi','default','95,101,103,102',NULL,NULL,NULL,NULL,NULL,NULL,10,45,NULL),(245,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,10,46,NULL),(246,'vi','default','122',NULL,NULL,NULL,NULL,NULL,NULL,10,47,NULL),(247,'vi','default','125,127,128,133',NULL,NULL,NULL,NULL,NULL,NULL,10,48,NULL),(248,'vi','default','2025-10-06',NULL,NULL,NULL,NULL,NULL,NULL,10,49,NULL),(249,'vi','default','hr@nexon.vn',NULL,NULL,NULL,NULL,NULL,NULL,10,50,NULL),(250,'vi','default','024-3936-1234',NULL,NULL,NULL,NULL,NULL,NULL,10,51,NULL),(251,'vi','default','https://www.nexon.com',NULL,NULL,NULL,NULL,NULL,NULL,10,52,NULL),(252,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,10,53,NULL),(253,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,10,54,NULL),(254,'vi','default','140',NULL,NULL,NULL,NULL,NULL,NULL,10,55,NULL),(255,'vi','default','JOB_LEVEL_DESIGNER_INDIE',NULL,NULL,NULL,NULL,NULL,NULL,11,1,NULL),(256,'vi','default','Level Designer - Indie Studio Collective',NULL,NULL,NULL,NULL,NULL,NULL,11,2,NULL),(257,'vi','default','<h2>🗺️ Level Designer - Indie Studio Collective</h2>\n                <p>Tham gia Indie Studio Collective - nơi quy tụ các indie game developers tài năng. Chúng tôi đang tìm Level Designer sáng tạo cho dự án platformer 2D mới.</p>\n                \n                <h3>🎨 Công việc bao gồm:</h3>\n                <ul>\n                <li>🗺️ Thiết kế levels cho 2D platformer game</li>\n                <li>🎮 Create challenging yet fair gameplay experiences</li>\n                <li>🔧 Use Unity editor và custom level tools</li>\n                <li>🧪 Playtest và iterate level designs</li>\n                <li>📝 Document level design rationale</li>\n                <li>🤝 Collaborate với artists và programmers</li>\n                </ul>\n                \n                <h3>🎯 Tìm kiếm:</h3>\n                <ul>\n                <li>🎮 1-3 năm experience level design</li>\n                <li>💡 Strong understanding game flow và pacing</li>\n                <li>🔧 Unity editor proficiency</li>\n                <li>🎨 Eye for visual composition</li>\n                <li>📚 Knowledge của platformer game mechanics</li>\n                <li>💭 Creative problem-solving mindset</li>\n                </ul>\n                \n                <h3>🌟 Why Join Us:</h3>\n                <ul>\n                <li>💰 20-30 triệu competitive salary</li>\n                <li>🏠 Remote-first culture</li>\n                <li>🎮 Work on passionate indie projects</li>\n                <li>📈 Revenue sharing opportunities</li>\n                <li>🎨 Creative freedom và ownership</li>\n                <li>👥 Supportive indie community</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,11,10,NULL),(258,'vi','default','Indie Studio tuyển Level Designer cho 2D platformer. Remote work, creative freedom, revenue sharing.',NULL,NULL,NULL,NULL,NULL,NULL,11,9,NULL),(259,'vi','default',NULL,NULL,NULL,25000000.0000,NULL,NULL,NULL,11,11,NULL),(260,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,11,22,NULL),(261,'vi','default','1',1,1,NULL,NULL,NULL,NULL,11,8,NULL),(262,'vi','default','0',0,0,NULL,NULL,NULL,NULL,11,6,NULL),(263,'vi','default','1',1,1,NULL,NULL,NULL,NULL,11,5,NULL),(264,'vi','default','1',1,1,NULL,NULL,NULL,NULL,11,7,NULL),(265,'vi','default','Level Designer - Indie Studio Collective | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,11,16,NULL),(266,'vi','default','Indie Studio tuyển Level Designer cho 2D platformer. Remote work, creative freedom, revenue sharing.',NULL,NULL,NULL,NULL,NULL,NULL,11,18,NULL),(267,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,11,17,NULL),(268,'vi','default','67',NULL,NULL,NULL,NULL,NULL,NULL,11,40,NULL),(269,'vi','default','70',NULL,NULL,NULL,NULL,NULL,NULL,11,41,NULL),(270,'vi','default','77',NULL,NULL,NULL,NULL,NULL,NULL,11,42,NULL),(271,'vi','default','88',NULL,NULL,NULL,NULL,NULL,NULL,11,43,NULL),(272,'vi','default','90',NULL,NULL,NULL,NULL,NULL,NULL,11,44,NULL),(273,'vi','default','114,95,113',NULL,NULL,NULL,NULL,NULL,NULL,11,45,NULL),(274,'vi','default','115',NULL,NULL,NULL,NULL,NULL,NULL,11,46,NULL),(275,'vi','default','121',NULL,NULL,NULL,NULL,NULL,NULL,11,47,NULL),(276,'vi','default','131,132,127',NULL,NULL,NULL,NULL,NULL,NULL,11,48,NULL),(277,'vi','default','2025-10-04',NULL,NULL,NULL,NULL,NULL,NULL,11,49,NULL),(278,'vi','default','hello@indiestudio.games',NULL,NULL,NULL,NULL,NULL,NULL,11,50,NULL),(279,'vi','default','0912345678',NULL,NULL,NULL,NULL,NULL,NULL,11,51,NULL),(280,'vi','default','https://indiestudio.games',NULL,NULL,NULL,NULL,NULL,NULL,11,52,NULL),(281,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,11,53,NULL),(282,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,11,54,NULL),(283,'vi','default','137',NULL,NULL,NULL,NULL,NULL,NULL,11,55,NULL),(284,'vi','default','JOB_TECHNICAL_ARTIST_RIOT',NULL,NULL,NULL,NULL,NULL,NULL,12,1,NULL),(285,'vi','default','Technical Artist - Riot Games Vietnam',NULL,NULL,NULL,NULL,NULL,NULL,12,2,NULL),(286,'vi','default','<h2>⚡ Riot Games Vietnam - Technical Artist</h2>\n                <p>Riot Games Vietnam is looking for a Technical Artist to bridge the gap between art and engineering, working on next-generation gaming experiences.</p>\n                \n                <h3>🔬 Technical Responsibilities:</h3>\n                <ul>\n                <li>🎨 Create and optimize art pipelines</li>\n                <li>🛠️ Develop Maya/3ds Max tools và scripts</li>\n                <li>⚡ Optimize 3D assets for performance</li>\n                <li>🔧 Implement shader effects và materials</li>\n                <li>📊 Profile và debug rendering issues</li>\n                <li>👥 Mentor artists on technical workflows</li>\n                </ul>\n                \n                <h3>⚙️ Technical Skills:</h3>\n                <ul>\n                <li>🎨 5+ years experience art + programming</li>\n                <li>💻 Python, C#, MEL scripting</li>\n                <li>🎮 Unity hoặc Unreal Engine expertise</li>\n                <li>🔺 3D software: Maya, 3ds Max, Blender</li>\n                <li>🎯 Shader programming (HLSL/GLSL)</li>\n                <li>⚡ Performance optimization experience</li>\n                </ul>\n                \n                <h3>🏆 Elite Benefits:</h3>\n                <ul>\n                <li>💰 70-100 million VND + equity</li>\n                <li>🏥 Premium health + dental + vision</li>\n                <li>🎮 Unlimited game budget</li>\n                <li>🌍 Global Riot events access</li>\n                <li>📚 $2000 annual learning budget</li>\n                <li>⚡ Top-tier hardware và software</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,12,10,NULL),(287,'vi','default','Riot Games Vietnam tuyển Technical Artist 5+ năm. Lương 70-100 triệu + equity, benefits cao cấp.',NULL,NULL,NULL,NULL,NULL,NULL,12,9,NULL),(288,'vi','default',NULL,NULL,NULL,85000000.0000,NULL,NULL,NULL,12,11,NULL),(289,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,12,22,NULL),(290,'vi','default','1',1,1,NULL,NULL,NULL,NULL,12,8,NULL),(291,'vi','default','1',1,1,NULL,NULL,NULL,NULL,12,6,NULL),(292,'vi','default','1',1,1,NULL,NULL,NULL,NULL,12,5,NULL),(293,'vi','default','1',1,1,NULL,NULL,NULL,NULL,12,7,NULL),(294,'vi','default','Technical Artist - Riot Games Vietnam | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,12,16,NULL),(295,'vi','default','Riot Games Vietnam tuyển Technical Artist 5+ năm. Lương 70-100 triệu + equity, benefits cao cấp.',NULL,NULL,NULL,NULL,NULL,NULL,12,18,NULL),(296,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,12,17,NULL),(297,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,12,40,NULL),(298,'vi','default','72',NULL,NULL,NULL,NULL,NULL,NULL,12,41,NULL),(299,'vi','default','80',NULL,NULL,NULL,NULL,NULL,NULL,12,42,NULL),(300,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,12,43,NULL),(301,'vi','default','94',NULL,NULL,NULL,NULL,NULL,NULL,12,44,NULL),(302,'vi','default','109,108,95,100,97',NULL,NULL,NULL,NULL,NULL,NULL,12,45,NULL),(303,'vi','default','117',NULL,NULL,NULL,NULL,NULL,NULL,12,46,NULL),(304,'vi','default','123',NULL,NULL,NULL,NULL,NULL,NULL,12,47,NULL),(305,'vi','default','125,133,130',NULL,NULL,NULL,NULL,NULL,NULL,12,48,NULL),(306,'vi','default','2025-10-21',NULL,NULL,NULL,NULL,NULL,NULL,12,49,NULL),(307,'vi','default','vietnam.jobs@riotgames.com',NULL,NULL,NULL,NULL,NULL,NULL,12,50,NULL),(308,'vi','default','028-3827-8888',NULL,NULL,NULL,NULL,NULL,NULL,12,51,NULL),(309,'vi','default','https://www.riotgames.com',NULL,NULL,NULL,NULL,NULL,NULL,12,52,NULL),(310,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,12,53,NULL),(311,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,12,54,NULL),(312,'vi','default','138',NULL,NULL,NULL,NULL,NULL,NULL,12,55,NULL),(313,'vi','default','JOB_GAME_TESTER_OUTSOURCE',NULL,NULL,NULL,NULL,NULL,NULL,13,1,NULL),(314,'vi','default','Game Tester - Outsourcing Projects',NULL,NULL,NULL,NULL,NULL,NULL,13,2,NULL),(315,'vi','default','<h2>🧪 Game Tester - International Outsourcing</h2>\n                <p>Join our QA team testing games for major international publishers. Great opportunity for gaming enthusiasts to start their career in the game industry.</p>\n                \n                <h3>🎮 Daily Tasks:</h3>\n                <ul>\n                <li>🕹️ Test mobile games trên iOS và Android</li>\n                <li>🐛 Find và document bugs clearly</li>\n                <li>📱 Test trên multiple devices và screen sizes</li>\n                <li>📋 Follow detailed test plans</li>\n                <li>🔄 Perform regression testing</li>\n                <li>📊 Provide feedback on game balance</li>\n                </ul>\n                \n                <h3>👨‍💻 Perfect For:</h3>\n                <ul>\n                <li>🎮 Gaming enthusiasts với attention to detail</li>\n                <li>🎓 Recent graduates looking to enter gaming</li>\n                <li>💡 People với analytical thinking</li>\n                <li>📱 Mobile gaming experience</li>\n                <li>📝 Good communication skills</li>\n                <li>🌐 Basic English reading comprehension</li>\n                </ul>\n                \n                <h3>💼 What We Provide:</h3>\n                <ul>\n                <li>💰 12-18 million VND starting salary</li>\n                <li>📈 Clear promotion path to Senior QA</li>\n                <li>📱 Access to latest games và devices</li>\n                <li>🎓 Training trên industry-standard tools</li>\n                <li>🏢 Modern office environment</li>\n                <li>🎮 Game allowance for research</li>\n                </ul>',NULL,NULL,NULL,NULL,NULL,NULL,13,10,NULL),(316,'vi','default','Tuyển Game Tester cho dự án outsourcing quốc tế. Entry level, lương 12-18 triệu, phù hợp fresh graduate.',NULL,NULL,NULL,NULL,NULL,NULL,13,9,NULL),(317,'vi','default',NULL,NULL,NULL,15000000.0000,NULL,NULL,NULL,13,11,NULL),(318,'vi','default','0',NULL,NULL,NULL,NULL,NULL,NULL,13,22,NULL),(319,'vi','default','1',1,1,NULL,NULL,NULL,NULL,13,8,NULL),(320,'vi','default','0',0,0,NULL,NULL,NULL,NULL,13,6,NULL),(321,'vi','default','1',1,1,NULL,NULL,NULL,NULL,13,5,NULL),(322,'vi','default','1',1,1,NULL,NULL,NULL,NULL,13,7,NULL),(323,'vi','default','Game Tester - Outsourcing Projects | LamGame Jobs',NULL,NULL,NULL,NULL,NULL,NULL,13,16,NULL),(324,'vi','default','Tuyển Game Tester cho dự án outsourcing quốc tế. Entry level, lương 12-18 triệu, phù hợp fresh graduate.',NULL,NULL,NULL,NULL,NULL,NULL,13,18,NULL),(325,'vi','default','game jobs, tuyển dụng game, việc làm game',NULL,NULL,NULL,NULL,NULL,NULL,13,17,NULL),(326,'vi','default','62',NULL,NULL,NULL,NULL,NULL,NULL,13,40,NULL),(327,'vi','default','69',NULL,NULL,NULL,NULL,NULL,NULL,13,41,NULL),(328,'vi','default','76',NULL,NULL,NULL,NULL,NULL,NULL,13,42,NULL),(329,'vi','default','82',NULL,NULL,NULL,NULL,NULL,NULL,13,43,NULL),(330,'vi','default','92',NULL,NULL,NULL,NULL,NULL,NULL,13,44,NULL),(331,'vi','default','116',NULL,NULL,NULL,NULL,NULL,NULL,13,46,NULL),(332,'vi','default','121',NULL,NULL,NULL,NULL,NULL,NULL,13,47,NULL),(333,'vi','default','130,134,136',NULL,NULL,NULL,NULL,NULL,NULL,13,48,NULL),(334,'vi','default','2025-09-27',NULL,NULL,NULL,NULL,NULL,NULL,13,49,NULL),(335,'vi','default','qa.jobs@gameoutsource.vn',NULL,NULL,NULL,NULL,NULL,NULL,13,50,NULL),(336,'vi','default','028-3848-1234',NULL,NULL,NULL,NULL,NULL,NULL,13,51,NULL),(337,'vi','default','https://gameoutsource.vn',NULL,NULL,NULL,NULL,NULL,NULL,13,52,NULL),(338,'vi','default',NULL,1,1,NULL,NULL,NULL,NULL,13,53,NULL),(339,'vi','default',NULL,0,0,NULL,NULL,NULL,NULL,13,54,NULL),(340,'vi','default','139',NULL,NULL,NULL,NULL,NULL,NULL,13,55,NULL),(341,'vi','default','Super Mario Clone',NULL,NULL,NULL,NULL,NULL,NULL,16,2,NULL),(342,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,16,8,NULL),(343,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,16,7,NULL),(344,'vi','default',NULL,NULL,NULL,0.0000,NULL,NULL,NULL,16,11,NULL),(345,'vi','default','Space Shooter 2D',NULL,NULL,NULL,NULL,NULL,NULL,17,2,NULL),(346,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,17,8,NULL),(347,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,17,7,NULL),(348,'vi','default',NULL,NULL,NULL,0.0000,NULL,NULL,NULL,17,11,NULL),(349,'vi','default','RPG Inventory System',NULL,NULL,NULL,NULL,NULL,NULL,18,2,NULL),(350,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,18,8,NULL),(351,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,18,7,NULL),(352,'vi','default',NULL,NULL,NULL,0.0000,NULL,NULL,NULL,18,11,NULL),(353,'vi','default','Mobile Puzzle Game',NULL,NULL,NULL,NULL,NULL,NULL,19,2,'vi|19|2'),(354,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,19,8,'default|19|8'),(355,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,19,7,'19|7'),(356,'vi','default',NULL,NULL,NULL,0.0000,NULL,NULL,NULL,19,11,'19|11'),(357,'vi','default','3D Platformer Demo',NULL,NULL,NULL,NULL,NULL,NULL,20,2,NULL),(358,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,20,8,NULL),(359,'vi','default',NULL,1,NULL,NULL,NULL,NULL,NULL,20,7,NULL),(360,'vi','default',NULL,NULL,NULL,0.0000,NULL,NULL,NULL,20,11,NULL),(361,'vi','default','super-mario-clone',NULL,NULL,NULL,NULL,NULL,NULL,16,1,NULL),(362,'vi','default','space-shooter-2d',NULL,NULL,NULL,NULL,NULL,NULL,17,1,NULL),(363,'vi','default','rpg-inventory-system',NULL,NULL,NULL,NULL,NULL,NULL,18,1,NULL),(364,'vi','default','mobile-puzzle-game',NULL,NULL,NULL,NULL,NULL,NULL,19,1,'19|1'),(365,'vi','default','3d-platformer-demo',NULL,NULL,NULL,NULL,NULL,NULL,20,1,NULL),(366,'vi','default','Source code hoàn chỉnh của game Mario kinh điển',NULL,NULL,NULL,NULL,NULL,NULL,16,9,NULL),(367,'vi','default','Game bắn phi thuyền 2D với AI và power-ups',NULL,NULL,NULL,NULL,NULL,NULL,17,9,NULL),(368,'vi','default','Hệ thống inventory hoàn chỉnh cho game RPG',NULL,NULL,NULL,NULL,NULL,NULL,18,9,NULL),(369,'vi','default','<p>Game puzzle di động với touch controls v&agrave; level editor</p>',NULL,NULL,NULL,NULL,NULL,NULL,19,9,'vi|19|9'),(370,'vi','default','Demo game 3D platformer với physics-based gameplay',NULL,NULL,NULL,NULL,NULL,NULL,20,9,NULL),(371,'vi','default','Source code hoàn chỉnh của game Mario kinh điển với đầy đủ tính năng di chuyển, thu thập coin, enemy AI và level design. Phù hợp cho việc học tập và nghiên cứu game development.',NULL,NULL,NULL,NULL,NULL,NULL,16,10,NULL),(372,'vi','default','Game bắn phi thuyền 2D với AI thông minh, power-ups đa dạng, hệ thống điểm số và nhiều level khác nhau. Source code được tổ chức rõ ràng và có comment chi tiết.',NULL,NULL,NULL,NULL,NULL,NULL,17,10,NULL),(373,'vi','default','Hệ thống inventory hoàn chỉnh cho game RPG với drag & drop, item stacking, equipment system, và UI tương tác trực quan. Được xây dựng với Unreal Engine Blueprint system.',NULL,NULL,NULL,NULL,NULL,NULL,18,10,NULL),(374,'vi','default','<p>Game puzzle di động với touch controls, level editor, progression system v&agrave; monetization features. Được tối ưu cho cả Android v&agrave; iOS.</p>',NULL,NULL,NULL,NULL,NULL,NULL,19,10,'vi|19|10'),(375,'vi','default','Demo game 3D platformer với character controller, physics-based gameplay, collectibles system và beautiful 3D environments. Ideal cho việc học 3D game development.',NULL,NULL,NULL,NULL,NULL,NULL,20,10,NULL),(376,'vi','default','super-mario-clone',NULL,NULL,NULL,NULL,NULL,NULL,16,3,NULL),(377,'vi','default','space-shooter-2d',NULL,NULL,NULL,NULL,NULL,NULL,17,3,NULL),(378,'vi','default','rpg-inventory-system',NULL,NULL,NULL,NULL,NULL,NULL,18,3,NULL),(379,'vi','default','mobile-puzzle-game',NULL,NULL,NULL,NULL,NULL,NULL,19,3,'vi|19|3'),(380,'vi','default','3d-platformer-demo',NULL,NULL,NULL,NULL,NULL,NULL,20,3,NULL),(381,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,48,'19|48'),(382,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,49,'19|49'),(383,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,50,'19|50'),(384,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,51,'19|51'),(385,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,52,'19|52'),(386,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,30,'19|30'),(387,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,27,'19|27'),(388,NULL,'default',NULL,0,NULL,NULL,NULL,NULL,NULL,19,28,'default|19|28'),(389,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,53,'19|53'),(390,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,54,'19|54'),(391,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,45,'19|45'),(392,'vi',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,16,'vi|19|16'),(393,'vi',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,17,'vi|19|17'),(394,'vi',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,18,'vi|19|18'),(395,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,12,'19|12'),(396,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,13,'19|13'),(397,NULL,'default',NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,14,'default|19|14'),(398,NULL,'default',NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,15,'default|19|15'),(399,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,5,'19|5'),(400,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,6,'19|6'),(401,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,26,'19|26'),(402,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,19,32,'19|32'),(403,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,34,'19|34'),(404,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,35,'19|35'),(405,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,19,36,'19|36');
/*!40000 ALTER TABLE `product_attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_bundle_option_products`
--

DROP TABLE IF EXISTS `product_bundle_option_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_bundle_option_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `product_bundle_option_id` int unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `is_user_defined` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `bundle_option_products_product_id_bundle_option_id_unique` (`product_id`,`product_bundle_option_id`),
  KEY `product_bundle_option_id_foreign` (`product_bundle_option_id`),
  CONSTRAINT `product_bundle_option_id_foreign` FOREIGN KEY (`product_bundle_option_id`) REFERENCES `product_bundle_options` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_bundle_option_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_bundle_option_products`
--

LOCK TABLES `product_bundle_option_products` WRITE;
/*!40000 ALTER TABLE `product_bundle_option_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_bundle_option_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_bundle_option_translations`
--

DROP TABLE IF EXISTS `product_bundle_option_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_bundle_option_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_bundle_option_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_bundle_option_translations_option_id_locale_unique` (`product_bundle_option_id`,`locale`),
  UNIQUE KEY `bundle_option_translations_locale_label_bundle_option_id_unique` (`locale`,`label`,`product_bundle_option_id`),
  CONSTRAINT `product_bundle_option_translations_option_id_foreign` FOREIGN KEY (`product_bundle_option_id`) REFERENCES `product_bundle_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_bundle_option_translations`
--

LOCK TABLES `product_bundle_option_translations` WRITE;
/*!40000 ALTER TABLE `product_bundle_option_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_bundle_option_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_bundle_options`
--

DROP TABLE IF EXISTS `product_bundle_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_bundle_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_bundle_options_product_id_foreign` (`product_id`),
  CONSTRAINT `product_bundle_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_bundle_options`
--

LOCK TABLES `product_bundle_options` WRITE;
/*!40000 ALTER TABLE `product_bundle_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_bundle_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_categories` (
  `product_id` int unsigned NOT NULL,
  `category_id` int unsigned NOT NULL,
  UNIQUE KEY `product_categories_product_id_category_id_unique` (`product_id`,`category_id`),
  KEY `product_categories_category_id_foreign` (`category_id`),
  CONSTRAINT `product_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_categories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_categories`
--

LOCK TABLES `product_categories` WRITE;
/*!40000 ALTER TABLE `product_categories` DISABLE KEYS */;
INSERT INTO `product_categories` VALUES (16,2),(17,2),(18,2),(19,2),(16,3),(17,3),(20,3),(19,4);
/*!40000 ALTER TABLE `product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_channels`
--

DROP TABLE IF EXISTS `product_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_channels` (
  `product_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  UNIQUE KEY `product_channels_product_id_channel_id_unique` (`product_id`,`channel_id`),
  KEY `product_channels_channel_id_foreign` (`channel_id`),
  CONSTRAINT `product_channels_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_channels_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_channels`
--

LOCK TABLES `product_channels` WRITE;
/*!40000 ALTER TABLE `product_channels` DISABLE KEYS */;
INSERT INTO `product_channels` VALUES (16,1),(17,1),(18,1),(19,1),(20,1);
/*!40000 ALTER TABLE `product_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_cross_sells`
--

DROP TABLE IF EXISTS `product_cross_sells`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_cross_sells` (
  `parent_id` int unsigned NOT NULL,
  `child_id` int unsigned NOT NULL,
  UNIQUE KEY `product_cross_sells_parent_id_child_id_unique` (`parent_id`,`child_id`),
  KEY `product_cross_sells_child_id_foreign` (`child_id`),
  CONSTRAINT `product_cross_sells_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_cross_sells_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_cross_sells`
--

LOCK TABLES `product_cross_sells` WRITE;
/*!40000 ALTER TABLE `product_cross_sells` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_cross_sells` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_customer_group_prices`
--

DROP TABLE IF EXISTS `product_customer_group_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_customer_group_prices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qty` int NOT NULL DEFAULT '0',
  `value_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `product_id` int unsigned NOT NULL,
  `customer_group_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unique_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_customer_group_prices_unique_id_unique` (`unique_id`),
  KEY `product_customer_group_prices_product_id_foreign` (`product_id`),
  KEY `product_customer_group_prices_customer_group_id_foreign` (`customer_group_id`),
  CONSTRAINT `product_customer_group_prices_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_customer_group_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_customer_group_prices`
--

LOCK TABLES `product_customer_group_prices` WRITE;
/*!40000 ALTER TABLE `product_customer_group_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_customer_group_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_customizable_option_prices`
--

DROP TABLE IF EXISTS `product_customizable_option_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_customizable_option_prices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `label` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `product_customizable_option_id` int unsigned NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pcop_product_customizable_option_id_foreign` (`product_customizable_option_id`),
  CONSTRAINT `pcop_product_customizable_option_id_foreign` FOREIGN KEY (`product_customizable_option_id`) REFERENCES `product_customizable_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_customizable_option_prices`
--

LOCK TABLES `product_customizable_option_prices` WRITE;
/*!40000 ALTER TABLE `product_customizable_option_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_customizable_option_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_customizable_option_translations`
--

DROP TABLE IF EXISTS `product_customizable_option_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_customizable_option_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` text COLLATE utf8mb4_unicode_ci,
  `product_customizable_option_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_customizable_option_id_locale_unique` (`product_customizable_option_id`,`locale`),
  CONSTRAINT `pcot_product_customizable_option_id_foreign` FOREIGN KEY (`product_customizable_option_id`) REFERENCES `product_customizable_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_customizable_option_translations`
--

LOCK TABLES `product_customizable_option_translations` WRITE;
/*!40000 ALTER TABLE `product_customizable_option_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_customizable_option_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_customizable_options`
--

DROP TABLE IF EXISTS `product_customizable_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_customizable_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1',
  `max_characters` text COLLATE utf8mb4_unicode_ci,
  `supported_file_extensions` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_customizable_options_product_id_foreign` (`product_id`),
  CONSTRAINT `product_customizable_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_customizable_options`
--

LOCK TABLES `product_customizable_options` WRITE;
/*!40000 ALTER TABLE `product_customizable_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_customizable_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_downloadable_link_translations`
--

DROP TABLE IF EXISTS `product_downloadable_link_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_downloadable_link_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_downloadable_link_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `link_translations_link_id_foreign` (`product_downloadable_link_id`),
  CONSTRAINT `link_translations_link_id_foreign` FOREIGN KEY (`product_downloadable_link_id`) REFERENCES `product_downloadable_links` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_downloadable_link_translations`
--

LOCK TABLES `product_downloadable_link_translations` WRITE;
/*!40000 ALTER TABLE `product_downloadable_link_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_downloadable_link_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_downloadable_links`
--

DROP TABLE IF EXISTS `product_downloadable_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_downloadable_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sample_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sample_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sample_file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sample_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `downloads` int NOT NULL DEFAULT '0',
  `sort_order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_downloadable_links_product_id_foreign` (`product_id`),
  CONSTRAINT `product_downloadable_links_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_downloadable_links`
--

LOCK TABLES `product_downloadable_links` WRITE;
/*!40000 ALTER TABLE `product_downloadable_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_downloadable_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_downloadable_sample_translations`
--

DROP TABLE IF EXISTS `product_downloadable_sample_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_downloadable_sample_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_downloadable_sample_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `sample_translations_sample_id_foreign` (`product_downloadable_sample_id`),
  CONSTRAINT `sample_translations_sample_id_foreign` FOREIGN KEY (`product_downloadable_sample_id`) REFERENCES `product_downloadable_samples` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_downloadable_sample_translations`
--

LOCK TABLES `product_downloadable_sample_translations` WRITE;
/*!40000 ALTER TABLE `product_downloadable_sample_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_downloadable_sample_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_downloadable_samples`
--

DROP TABLE IF EXISTS `product_downloadable_samples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_downloadable_samples` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_downloadable_samples_product_id_foreign` (`product_id`),
  CONSTRAINT `product_downloadable_samples_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_downloadable_samples`
--

LOCK TABLES `product_downloadable_samples` WRITE;
/*!40000 ALTER TABLE `product_downloadable_samples` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_downloadable_samples` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_flat`
--

DROP TABLE IF EXISTS `product_flat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_flat` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `url_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new` tinyint(1) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,4) DEFAULT NULL,
  `special_price` decimal(12,4) DEFAULT NULL,
  `special_price_from` date DEFAULT NULL,
  `special_price_to` date DEFAULT NULL,
  `weight` decimal(12,4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_family_id` int unsigned DEFAULT NULL,
  `product_id` int unsigned NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `visible_individually` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_flat_unique_index` (`product_id`,`channel`,`locale`),
  KEY `product_flat_attribute_family_id_foreign` (`attribute_family_id`),
  KEY `product_flat_parent_id_foreign` (`parent_id`),
  CONSTRAINT `product_flat_attribute_family_id_foreign` FOREIGN KEY (`attribute_family_id`) REFERENCES `attribute_families` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `product_flat_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_flat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_flat_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_flat`
--

LOCK TABLES `product_flat` WRITE;
/*!40000 ALTER TABLE `product_flat` DISABLE KEYS */;
INSERT INTO `product_flat` VALUES (1,'UNITY_ENDLESS_RUNNER_001','simple',NULL,'Unity 3D Endless Runner Game - Source Code','Unity 3D Endless Runner game với đầy đủ source code, assets và documentation. Sẵn sàng publish lên Android/iOS.','<h2>Unity 3D Endless Runner Game - Complete Source Code</h2>\n                <p>Một game endless runner 3D hoàn chỉnh được phát triển trên Unity, bao gồm đầy đủ source code, assets và documentation. Game có gameplay hấp dẫn với nhiều tính năng:</p>\n                \n                <h3>✅ Tính năng chính:</h3>\n                <ul>\n                <li>🎮 Gameplay endless runner mượt mà</li>\n                <li>🏃‍♂️ Character controller với animation đẹp mắt</li>\n                <li>🌍 Procedural level generation</li>\n                <li>💰 Coin collection system</li>\n                <li>🛍️ In-app purchase integration</li>\n                <li>📊 Leaderboard & achievements</li>\n                <li>🎵 Background music & sound effects</li>\n                <li>📱 Mobile-optimized UI</li>\n                </ul>\n                \n                <h3>🔧 Technical Details:</h3>\n                <ul>\n                <li>🎯 Unity 2022.3 LTS</li>\n                <li>💻 C# programming</li>\n                <li>📱 Ready for Android & iOS</li>\n                <li>🎨 Complete art assets included</li>\n                <li>📖 Documentation & setup guide</li>\n                <li>🛠️ Easy to customize</li>\n                </ul>',NULL,1,1,1,'Unity 3D Endless Runner Game Source Code - LamGame','unity, endless runner, source code, mobile game, android, ios','Mua source code Unity 3D Endless Runner game hoàn chỉnh. Bao gồm assets, documentation, sẵn sàng publish Android/iOS.',1500000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:27','vi','default',1,1,'2025-09-06 19:59:10',NULL,1),(2,'JOB_UNITY_DEV_001','simple',NULL,'Unity Developer - Game Studio ABC','Tuyển Unity Developer kinh nghiệm 2+ năm phát triển mobile game. Lương 20-30 triệu + thưởng.','<h2>Mô tả công việc</h2>\n                <p>Game Studio ABC đang tìm kiếm Unity Developer tài năng để tham gia phát triển các dự án game mobile hấp dẫn.</p>\n                \n                <h3>📋 Trách nhiệm chính:</h3>\n                <ul>\n                <li>🎮 Phát triển game mobile sử dụng Unity Engine</li>\n                <li>💻 Viết code C# clean, tối ưu và dễ maintain</li>\n                <li>🔧 Tối ưu hiệu năng game cho các thiết bị mobile</li>\n                <li>🤝 Làm việc cùng team Art, Design để implement game features</li>\n                <li>🐛 Debug và fix bugs trong quá trình phát triển</li>\n                <li>📝 Viết documentation cho code và features</li>\n                </ul>\n                \n                <h3>✅ Yêu cầu:</h3>\n                <ul>\n                <li>🎓 Tốt nghiệp Đại học chuyên ngành CNTT hoặc tương đương</li>\n                <li>⭐ Tối thiểu 2 năm kinh nghiệm với Unity</li>\n                <li>💡 Thành thạo C# và OOP</li>\n                <li>📱 Kinh nghiệm phát triển mobile game (Android/iOS)</li>\n                <li>🌐 Tiếng Anh giao tiếp tốt</li>\n                <li>🎯 Đam mê game và công nghệ</li>\n                </ul>\n                \n                <h3>🎁 Phúc lợi:</h3>\n                <ul>\n                <li>💰 Lương 20-30 triệu + thưởng dự án</li>\n                <li>🏥 Bảo hiểm sức khỏe cao cấp</li>\n                <li>🏖️ 15 ngày nghỉ phép/năm</li>\n                <li>🎮 Game room & team building</li>\n                <li>💻 Máy tính & thiết bị làm việc hiện đại</li>\n                </ul>',NULL,1,1,1,'Unity Developer - Game Studio ABC | LamGame Jobs','unity developer, game programmer, mobile game, tuyển dụng','Cơ hội làm Unity Developer tại Game Studio ABC. Lương 20-30 triệu, môi trường chuyên nghiệp.',25000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:27','vi','default',1,2,'2025-09-06 19:59:10',NULL,1),(3,'JOB_UNITY_SENIOR_2025','simple',NULL,'Senior Unity Developer - VNG Corporation','VNG tuyển Senior Unity Developer 5+ năm kinh nghiệm. Lương 50-80 triệu + equity, làm việc với team quốc tế.','<h2>🎮 VNG Corporation - Senior Unity Developer</h2>\n                <p><strong>VNG Corporation</strong> là một trong những công ty game hàng đầu Việt Nam đang tìm kiếm Senior Unity Developer tài năng để tham gia phát triển các dự án game mobile AAA.</p>\n                \n                <h3>📋 Mô tả công việc:</h3>\n                <ul>\n                <li>🎯 Phát triển và maintain mobile games sử dụng Unity Engine</li>\n                <li>💻 Architect và implement game systems phức tạp</li>\n                <li>🚀 Optimize game performance cho các thiết bị mobile</li>\n                <li>👥 Mentor junior developers và review code</li>\n                <li>🔧 Research và implement new technologies</li>\n                <li>📊 Collaborate với cross-functional teams</li>\n                </ul>\n                \n                <h3>✅ Yêu cầu:</h3>\n                <ul>\n                <li>🎓 Bằng Đại học Khoa học máy tính hoặc tương đương</li>\n                <li>⭐ 5+ năm kinh nghiệm Unity development</li>\n                <li>💡 Expert level C# và design patterns</li>\n                <li>📱 Shipped ít nhất 2 mobile games</li>\n                <li>🔥 Kinh nghiệm với networking, multiplayer systems</li>\n                <li>🌐 English proficiency for international collaboration</li>\n                </ul>\n                \n                <h3>🎁 Benefits Package:</h3>\n                <ul>\n                <li>💰 Lương 50-80 triệu + equity + bonuses</li>\n                <li>🏥 Premium healthcare cho cả gia đình</li>\n                <li>🏖️ 20 ngày nghỉ phép + 5 ngày sick leave</li>\n                <li>🎮 Latest MacBook Pro + gaming setup</li>\n                <li>🌟 Annual team trips + quarterly team building</li>\n                <li>📚 Learning budget 20 triệu/năm</li>\n                </ul>',NULL,1,1,1,'Senior Unity Developer - VNG Corporation | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','VNG tuyển Senior Unity Developer 5+ năm kinh nghiệm. Lương 50-80 triệu + equity, làm việc với team quốc tế.',65000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:27','vi','default',1,4,'2025-09-06 19:59:10',NULL,1),(4,'JOB_GAME_DESIGNER_GAMELOFT','simple',NULL,'Game Designer - Gameloft Vietnam','Gameloft tuyển Game Designer 3-5 năm kinh nghiệm. Làm việc với team quốc tế, phát triển game mobile toàn cầu.','<h2>🎨 Gameloft Vietnam - Game Designer Position</h2>\n                <p>Join <strong>Gameloft Vietnam</strong>, a leading global game publisher, as a Game Designer to create engaging mobile game experiences for millions of players worldwide.</p>\n                \n                <h3>🎯 Key Responsibilities:</h3>\n                <ul>\n                <li>🎮 Design core gameplay mechanics and game economy</li>\n                <li>📝 Create detailed game design documents</li>\n                <li>🎨 Work closely with art and programming teams</li>\n                <li>📊 Analyze player data and optimize game balance</li>\n                <li>🧪 Conduct playtesting sessions and iterate designs</li>\n                <li>🌟 Research market trends and competitor analysis</li>\n                </ul>\n                \n                <h3>👨‍💻 Requirements:</h3>\n                <ul>\n                <li>🎓 Bachelor in Game Design, Computer Science, or related</li>\n                <li>⭐ 3-5 years experience in game design</li>\n                <li>🎮 Strong knowledge of mobile game genres</li>\n                <li>💡 Understanding of F2P monetization</li>\n                <li>📊 Experience with analytics tools</li>\n                <li>🌐 Good English communication skills</li>\n                </ul>\n                \n                <h3>🌟 What We Offer:</h3>\n                <ul>\n                <li>💰 Competitive salary 30-50 million VND</li>\n                <li>🏥 International health insurance</li>\n                <li>🎮 Work on globally successful games</li>\n                <li>📚 Training and career development</li>\n                <li>🌍 Opportunity to work with international teams</li>\n                </ul>',NULL,1,1,1,'Game Designer - Gameloft Vietnam | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Gameloft tuyển Game Designer 3-5 năm kinh nghiệm. Làm việc với team quốc tế, phát triển game mobile toàn cầu.',40000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,5,'2025-09-06 19:59:10',NULL,1),(5,'JOB_2D_ARTIST_AXIE','simple',NULL,'2D Game Artist - Sky Mavis (Axie Infinity)','Sky Mavis (Axie Infinity) tuyển 2D Game Artist. Hybrid work, làm việc với blockchain gaming, có token incentives.','<h2>🎨 Sky Mavis - 2D Game Artist</h2>\n                <p>Sky Mavis, creator of the world-famous Axie Infinity, is looking for talented 2D Game Artists to join our creative team and shape the future of blockchain gaming.</p>\n                \n                <h3>🖌️ What You\'ll Do:</h3>\n                <ul>\n                <li>🎨 Create stunning 2D character designs and illustrations</li>\n                <li>🖼️ Design UI/UX elements for mobile and web games</li>\n                <li>🎮 Develop visual concepts for new game features</li>\n                <li>📱 Create marketing assets and promotional materials</li>\n                <li>🤝 Collaborate with game designers and developers</li>\n                <li>🔄 Iterate on designs based on feedback</li>\n                </ul>\n                \n                <h3>🎯 We\'re Looking For:</h3>\n                <ul>\n                <li>🎓 Fine Arts, Graphic Design degree preferred</li>\n                <li>⭐ 2+ years experience in game art</li>\n                <li>🎨 Proficient in Photoshop, Illustrator</li>\n                <li>🎮 Understanding of mobile game UI/UX</li>\n                <li>💡 Creative mindset with attention to detail</li>\n                <li>🌐 Basic English for team communication</li>\n                </ul>\n                \n                <h3>🚀 Benefits:</h3>\n                <ul>\n                <li>💰 20-35 million VND + token incentives</li>\n                <li>🏠 Hybrid working arrangement</li>\n                <li>🎮 Work on revolutionary blockchain games</li>\n                <li>🌟 Be part of gaming history</li>\n                <li>📚 Learning opportunities in Web3</li>\n                </ul>',NULL,1,1,1,'2D Game Artist - Sky Mavis (Axie Infinity) | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Sky Mavis (Axie Infinity) tuyển 2D Game Artist. Hybrid work, làm việc với blockchain gaming, có token incentives.',27500000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,6,'2025-09-06 19:59:10',NULL,1),(6,'JOB_QA_TESTER_FSOFT','simple',NULL,'Game QA Tester - FPT Software','FPT Software tuyển Game QA Tester, làm việc với các dự án game quốc tế. Phù hợp fresher có đam mê game.','<h2>🧪 FPT Software - Game QA Tester</h2>\n                <p>FPT Software đang tìm kiếm Game QA Tester tỉ mỉ và có đam mê với chất lượng sản phẩm để tham gia testing các dự án game outsourcing quốc tế.</p>\n                \n                <h3>🔍 Công việc chính:</h3>\n                <ul>\n                <li>🎮 Test gameplay, UI/UX trên nhiều platform</li>\n                <li>🐛 Tìm và report bugs với documentation chi tiết</li>\n                <li>📱 Test compatibility trên các thiết bị mobile</li>\n                <li>📋 Viết test cases và test plans</li>\n                <li>🔄 Regression testing và verification</li>\n                <li>📊 Collaborate với development teams</li>\n                </ul>\n                \n                <h3>✅ Yêu cầu:</h3>\n                <ul>\n                <li>🎓 Tốt nghiệp Đại học/Cao đẳng IT</li>\n                <li>⭐ 1-2 năm kinh nghiệm QA/Testing</li>\n                <li>🎮 Đam mê chơi game nhiều thể loại</li>\n                <li>🔍 Tỉ mỉ, kiên nhẫn trong công việc</li>\n                <li>📝 Kỹ năng viết báo cáo tốt</li>\n                <li>🌐 Đọc hiểu tiếng Anh cơ bản</li>\n                </ul>\n                \n                <h3>💼 Quyền lợi:</h3>\n                <ul>\n                <li>💰 15-25 triệu + allowances</li>\n                <li>🏥 Bảo hiểm FPT Care</li>\n                <li>📚 Training programs và certifications</li>\n                <li>🎮 Access to latest games và devices</li>\n                <li>🌟 Clear career progression path</li>\n                </ul>',NULL,1,0,1,'Game QA Tester - FPT Software | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','FPT Software tuyển Game QA Tester, làm việc với các dự án game quốc tế. Phù hợp fresher có đam mê game.',20000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,7,'2025-09-06 19:59:10',NULL,1),(7,'JOB_PROJECT_MANAGER_UBISOFT','simple',NULL,'Game Project Manager - Ubisoft Vietnam','Ubisoft Vietnam tuyển Game Project Manager 7+ năm kinh nghiệm. Lương 80+ triệu, cơ hội làm việc quốc tế.','<h2>📊 Ubisoft Vietnam - Game Project Manager</h2>\n                <p>Ubisoft Vietnam is seeking an experienced Game Project Manager to oversee the development of AAA game projects and ensure successful delivery to global markets.</p>\n                \n                <h3>🎯 Key Responsibilities:</h3>\n                <ul>\n                <li>📋 Manage full game development lifecycle</li>\n                <li>👥 Lead cross-functional teams (20+ members)</li>\n                <li>📅 Create and maintain project schedules</li>\n                <li>💰 Monitor budgets and resource allocation</li>\n                <li>🚀 Ensure quality and timely delivery</li>\n                <li>🌍 Coordinate with global Ubisoft studios</li>\n                </ul>\n                \n                <h3>📚 Requirements:</h3>\n                <ul>\n                <li>🎓 Bachelor in Project Management/Business</li>\n                <li>⭐ 7+ years project management experience</li>\n                <li>🎮 5+ years in game development</li>\n                <li>📊 PMP or Agile certification preferred</li>\n                <li>👑 Strong leadership and communication</li>\n                <li>🌐 Fluent English for global collaboration</li>\n                </ul>\n                \n                <h3>🏆 Outstanding Package:</h3>\n                <ul>\n                <li>💰 80+ million VND + performance bonus</li>\n                <li>🌍 International career opportunities</li>\n                <li>🏥 Premium healthcare + family coverage</li>\n                <li>✈️ Annual leave + travel allowance</li>\n                <li>🎮 Work on world-class AAA games</li>\n                </ul>',NULL,1,1,1,'Game Project Manager - Ubisoft Vietnam | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Ubisoft Vietnam tuyển Game Project Manager 7+ năm kinh nghiệm. Lương 80+ triệu, cơ hội làm việc quốc tế.',90000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,8,'2025-09-06 19:59:10',NULL,1),(8,'JOB_FREELANCE_UNITY_DEV','simple',NULL,'Freelance Unity Developer - Multiple Projects','Cơ hội freelance Unity Developer remote với mức 500-800k/ngày. Đa dạng dự án từ startup đến studio lớn.','<h2>💼 Freelance Unity Developer Opportunities</h2>\n                <p>We connect talented Unity developers with exciting freelance projects from startups to established studios. Work remotely on diverse gaming projects.</p>\n                \n                <h3>🎮 Project Types:</h3>\n                <ul>\n                <li>📱 Mobile puzzle and casual games</li>\n                <li>🕹️ 2D platformer and arcade games</li>\n                <li>🎯 Hyper-casual gaming prototypes</li>\n                <li>🔧 Game tool and editor development</li>\n                <li>📊 Analytics integration projects</li>\n                <li>🚀 Game optimization and porting</li>\n                </ul>\n                \n                <h3>💻 Requirements:</h3>\n                <ul>\n                <li>⭐ 2+ years Unity development experience</li>\n                <li>💡 Strong C# programming skills</li>\n                <li>📱 Mobile game development knowledge</li>\n                <li>🔧 Experience with Git version control</li>\n                <li>🏠 Reliable home office setup</li>\n                <li>📞 Good communication skills</li>\n                </ul>\n                \n                <h3>💰 Compensation:</h3>\n                <ul>\n                <li>💵 500-800k VND per day rate</li>\n                <li>📅 Flexible working hours</li>\n                <li>🏠 100% remote work</li>\n                <li>🚀 Multiple project opportunities</li>\n                <li>🌟 Build diverse portfolio</li>\n                <li>📈 Performance-based rate increases</li>\n                </ul>',NULL,1,0,1,'Freelance Unity Developer - Multiple Projects | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Cơ hội freelance Unity Developer remote với mức 500-800k/ngày. Đa dạng dự án từ startup đến studio lớn.',15000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,9,'2025-09-06 19:59:10',NULL,1),(9,'JOB_MOBILE_DEVELOPER_NEXON','simple',NULL,'Mobile Game Developer - Nexon Vietnam','Nexon Vietnam tuyển Mobile Game Developer 3+ năm. Lương 35-55 triệu + bonus 4 tháng, trip nước ngoài.','<h2>📱 Nexon Vietnam - Mobile Game Developer</h2>\n                <p>Nexon Vietnam đang tìm kiếm Mobile Game Developer để phát triển thế hệ game mobile tiếp theo với công nghệ cutting-edge.</p>\n                \n                <h3>🚀 Nhiệm vụ chính:</h3>\n                <ul>\n                <li>📱 Phát triển game mobile cho Android và iOS</li>\n                <li>⚡ Optimize performance cho low-end devices</li>\n                <li>🔌 Integrate with backend services và APIs</li>\n                <li>💾 Implement data persistence và caching</li>\n                <li>🎮 Work with game engines (Unity/Unreal)</li>\n                <li>🔄 Collaborate trong Agile environment</li>\n                </ul>\n                \n                <h3>🎯 Skill Requirements:</h3>\n                <ul>\n                <li>📱 3+ years mobile game development</li>\n                <li>💻 Native Android (Java/Kotlin) hoặc iOS (Swift)</li>\n                <li>🎮 Experience với Unity hoặc Unreal Engine</li>\n                <li>🌐 RESTful API integration experience</li>\n                <li>⚡ Performance optimization expertise</li>\n                <li>🔧 Git, CI/CD pipeline knowledge</li>\n                </ul>\n                \n                <h3>🎁 Attractive Package:</h3>\n                <ul>\n                <li>💰 35-55 million VND base salary</li>\n                <li>🎯 Annual performance bonus up to 4 months</li>\n                <li>🏥 Comprehensive medical insurance</li>\n                <li>🎮 Company-provided gaming devices</li>\n                <li>🌴 Annual company trip to Korea/Japan</li>\n                </ul>',NULL,1,1,1,'Mobile Game Developer - Nexon Vietnam | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Nexon Vietnam tuyển Mobile Game Developer 3+ năm. Lương 35-55 triệu + bonus 4 tháng, trip nước ngoài.',45000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,10,'2025-09-06 19:59:10',NULL,1),(10,'JOB_LEVEL_DESIGNER_INDIE','simple',NULL,'Level Designer - Indie Studio Collective','Indie Studio tuyển Level Designer cho 2D platformer. Remote work, creative freedom, revenue sharing.','<h2>🗺️ Level Designer - Indie Studio Collective</h2>\n                <p>Tham gia Indie Studio Collective - nơi quy tụ các indie game developers tài năng. Chúng tôi đang tìm Level Designer sáng tạo cho dự án platformer 2D mới.</p>\n                \n                <h3>🎨 Công việc bao gồm:</h3>\n                <ul>\n                <li>🗺️ Thiết kế levels cho 2D platformer game</li>\n                <li>🎮 Create challenging yet fair gameplay experiences</li>\n                <li>🔧 Use Unity editor và custom level tools</li>\n                <li>🧪 Playtest và iterate level designs</li>\n                <li>📝 Document level design rationale</li>\n                <li>🤝 Collaborate với artists và programmers</li>\n                </ul>\n                \n                <h3>🎯 Tìm kiếm:</h3>\n                <ul>\n                <li>🎮 1-3 năm experience level design</li>\n                <li>💡 Strong understanding game flow và pacing</li>\n                <li>🔧 Unity editor proficiency</li>\n                <li>🎨 Eye for visual composition</li>\n                <li>📚 Knowledge của platformer game mechanics</li>\n                <li>💭 Creative problem-solving mindset</li>\n                </ul>\n                \n                <h3>🌟 Why Join Us:</h3>\n                <ul>\n                <li>💰 20-30 triệu competitive salary</li>\n                <li>🏠 Remote-first culture</li>\n                <li>🎮 Work on passionate indie projects</li>\n                <li>📈 Revenue sharing opportunities</li>\n                <li>🎨 Creative freedom và ownership</li>\n                <li>👥 Supportive indie community</li>\n                </ul>',NULL,1,0,1,'Level Designer - Indie Studio Collective | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Indie Studio tuyển Level Designer cho 2D platformer. Remote work, creative freedom, revenue sharing.',25000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,11,'2025-09-06 19:59:10',NULL,1),(11,'JOB_TECHNICAL_ARTIST_RIOT','simple',NULL,'Technical Artist - Riot Games Vietnam','Riot Games Vietnam tuyển Technical Artist 5+ năm. Lương 70-100 triệu + equity, benefits cao cấp.','<h2>⚡ Riot Games Vietnam - Technical Artist</h2>\n                <p>Riot Games Vietnam is looking for a Technical Artist to bridge the gap between art and engineering, working on next-generation gaming experiences.</p>\n                \n                <h3>🔬 Technical Responsibilities:</h3>\n                <ul>\n                <li>🎨 Create and optimize art pipelines</li>\n                <li>🛠️ Develop Maya/3ds Max tools và scripts</li>\n                <li>⚡ Optimize 3D assets for performance</li>\n                <li>🔧 Implement shader effects và materials</li>\n                <li>📊 Profile và debug rendering issues</li>\n                <li>👥 Mentor artists on technical workflows</li>\n                </ul>\n                \n                <h3>⚙️ Technical Skills:</h3>\n                <ul>\n                <li>🎨 5+ years experience art + programming</li>\n                <li>💻 Python, C#, MEL scripting</li>\n                <li>🎮 Unity hoặc Unreal Engine expertise</li>\n                <li>🔺 3D software: Maya, 3ds Max, Blender</li>\n                <li>🎯 Shader programming (HLSL/GLSL)</li>\n                <li>⚡ Performance optimization experience</li>\n                </ul>\n                \n                <h3>🏆 Elite Benefits:</h3>\n                <ul>\n                <li>💰 70-100 million VND + equity</li>\n                <li>🏥 Premium health + dental + vision</li>\n                <li>🎮 Unlimited game budget</li>\n                <li>🌍 Global Riot events access</li>\n                <li>📚 $2000 annual learning budget</li>\n                <li>⚡ Top-tier hardware và software</li>\n                </ul>',NULL,1,1,1,'Technical Artist - Riot Games Vietnam | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Riot Games Vietnam tuyển Technical Artist 5+ năm. Lương 70-100 triệu + equity, benefits cao cấp.',85000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,12,'2025-09-06 19:59:10',NULL,1),(12,'JOB_GAME_TESTER_OUTSOURCE','simple',NULL,'Game Tester - Outsourcing Projects','Tuyển Game Tester cho dự án outsourcing quốc tế. Entry level, lương 12-18 triệu, phù hợp fresh graduate.','<h2>🧪 Game Tester - International Outsourcing</h2>\n                <p>Join our QA team testing games for major international publishers. Great opportunity for gaming enthusiasts to start their career in the game industry.</p>\n                \n                <h3>🎮 Daily Tasks:</h3>\n                <ul>\n                <li>🕹️ Test mobile games trên iOS và Android</li>\n                <li>🐛 Find và document bugs clearly</li>\n                <li>📱 Test trên multiple devices và screen sizes</li>\n                <li>📋 Follow detailed test plans</li>\n                <li>🔄 Perform regression testing</li>\n                <li>📊 Provide feedback on game balance</li>\n                </ul>\n                \n                <h3>👨‍💻 Perfect For:</h3>\n                <ul>\n                <li>🎮 Gaming enthusiasts với attention to detail</li>\n                <li>🎓 Recent graduates looking to enter gaming</li>\n                <li>💡 People với analytical thinking</li>\n                <li>📱 Mobile gaming experience</li>\n                <li>📝 Good communication skills</li>\n                <li>🌐 Basic English reading comprehension</li>\n                </ul>\n                \n                <h3>💼 What We Provide:</h3>\n                <ul>\n                <li>💰 12-18 million VND starting salary</li>\n                <li>📈 Clear promotion path to Senior QA</li>\n                <li>📱 Access to latest games và devices</li>\n                <li>🎓 Training trên industry-standard tools</li>\n                <li>🏢 Modern office environment</li>\n                <li>🎮 Game allowance for research</li>\n                </ul>',NULL,1,0,1,'Game Tester - Outsourcing Projects | LamGame Jobs','game jobs, tuyển dụng game, việc làm game','Tuyển Game Tester cho dự án outsourcing quốc tế. Entry level, lương 12-18 triệu, phù hợp fresh graduate.',15000000.0000,NULL,NULL,NULL,0.0000,'2025-09-06 00:55:28','vi','default',1,13,'2025-09-06 19:59:10',NULL,1),(13,'SRC_PAC_MAN_CLONE_COMPLETE_SOURCE_CODE','simple',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-09-06 01:32:38','vi','default',1,14,'2025-09-06 19:59:10',NULL,NULL),(14,'super-mario-clone','downloadable',NULL,'Super Mario Clone','Source code hoàn chỉnh của game Mario kinh điển','Source code hoàn chỉnh của game Mario kinh điển với đầy đủ tính năng di chuyển, thu thập coin, enemy AI và level design. Phù hợp cho việc học tập và nghiên cứu game development.','super-mario-clone',NULL,NULL,1,NULL,NULL,NULL,0.0000,NULL,NULL,NULL,NULL,'2025-09-06 18:10:53','vi','default',1,16,'2025-09-06 19:59:10',NULL,1),(15,'space-shooter-2d','downloadable',NULL,'Space Shooter 2D','Game bắn phi thuyền 2D với AI và power-ups','Game bắn phi thuyền 2D với AI thông minh, power-ups đa dạng, hệ thống điểm số và nhiều level khác nhau. Source code được tổ chức rõ ràng và có comment chi tiết.','space-shooter-2d',NULL,NULL,1,NULL,NULL,NULL,0.0000,NULL,NULL,NULL,NULL,'2025-09-06 18:10:53','vi','default',1,17,'2025-09-06 19:59:10',NULL,1),(16,'rpg-inventory-system','downloadable',NULL,'RPG Inventory System','Hệ thống inventory hoàn chỉnh cho game RPG','Hệ thống inventory hoàn chỉnh cho game RPG với drag & drop, item stacking, equipment system, và UI tương tác trực quan. Được xây dựng với Unreal Engine Blueprint system.','rpg-inventory-system',NULL,NULL,1,NULL,NULL,NULL,0.0000,NULL,NULL,NULL,NULL,'2025-09-06 18:10:53','vi','default',1,18,'2025-09-06 19:59:10',NULL,1),(17,'mobile-puzzle-game','downloadable','','Mobile Puzzle Game','<p>Game puzzle di động với touch controls v&agrave; level editor</p>','<p>Game puzzle di động với touch controls, level editor, progression system v&agrave; monetization features. Được tối ưu cho cả Android v&agrave; iOS.</p>','mobile-puzzle-game',0,0,1,'','','',0.0000,NULL,NULL,NULL,NULL,'2025-09-06 18:10:53','vi','default',1,19,'2025-09-19 12:33:05',NULL,1),(18,'3d-platformer-demo','downloadable',NULL,'3D Platformer Demo','Demo game 3D platformer với physics-based gameplay','Demo game 3D platformer với character controller, physics-based gameplay, collectibles system và beautiful 3D environments. Ideal cho việc học 3D game development.','3d-platformer-demo',NULL,NULL,1,NULL,NULL,NULL,0.0000,NULL,NULL,NULL,NULL,'2025-09-06 18:10:53','vi','default',1,20,'2025-09-06 19:59:10',NULL,1);
/*!40000 ALTER TABLE `product_flat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_grouped_products`
--

DROP TABLE IF EXISTS `product_grouped_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_grouped_products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `associated_product_id` int unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `grouped_products_product_id_associated_product_id_unique` (`product_id`,`associated_product_id`),
  KEY `product_grouped_products_associated_product_id_foreign` (`associated_product_id`),
  CONSTRAINT `product_grouped_products_associated_product_id_foreign` FOREIGN KEY (`associated_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_grouped_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_grouped_products`
--

LOCK TABLES `product_grouped_products` WRITE;
/*!40000 ALTER TABLE `product_grouped_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_grouped_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int unsigned NOT NULL,
  `position` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,'images','product/19/g3UiUv2OOBWS16sDpuw98TznUi5Lkw4QhGZAn8CE.webp',19,1);
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_inventories`
--

DROP TABLE IF EXISTS `product_inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_inventories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `qty` int NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL,
  `vendor_id` int NOT NULL DEFAULT '0',
  `inventory_source_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_source_vendor_index_unique` (`product_id`,`inventory_source_id`,`vendor_id`),
  KEY `product_inventories_inventory_source_id_foreign` (`inventory_source_id`),
  CONSTRAINT `product_inventories_inventory_source_id_foreign` FOREIGN KEY (`inventory_source_id`) REFERENCES `inventory_sources` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_inventories`
--

LOCK TABLES `product_inventories` WRITE;
/*!40000 ALTER TABLE `product_inventories` DISABLE KEYS */;
INSERT INTO `product_inventories` VALUES (1,999999,16,1,1),(2,999999,17,1,1),(3,999999,18,1,1),(4,999999,19,1,1),(5,999999,20,1,1);
/*!40000 ALTER TABLE `product_inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_inventory_indices`
--

DROP TABLE IF EXISTS `product_inventory_indices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_inventory_indices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `qty` int NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_inventory_indices_product_id_channel_id_unique` (`product_id`,`channel_id`),
  KEY `product_inventory_indices_channel_id_foreign` (`channel_id`),
  CONSTRAINT `product_inventory_indices_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_inventory_indices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_inventory_indices`
--

LOCK TABLES `product_inventory_indices` WRITE;
/*!40000 ALTER TABLE `product_inventory_indices` DISABLE KEYS */;
INSERT INTO `product_inventory_indices` VALUES (1,999999,16,1,NULL,NULL),(2,999999,17,1,NULL,NULL),(3,999999,18,1,NULL,NULL),(4,999999,19,1,NULL,NULL),(5,999999,20,1,NULL,NULL);
/*!40000 ALTER TABLE `product_inventory_indices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_ordered_inventories`
--

DROP TABLE IF EXISTS `product_ordered_inventories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_ordered_inventories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `qty` int NOT NULL DEFAULT '0',
  `product_id` int unsigned NOT NULL,
  `channel_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_ordered_inventories_product_id_channel_id_unique` (`product_id`,`channel_id`),
  KEY `product_ordered_inventories_channel_id_foreign` (`channel_id`),
  CONSTRAINT `product_ordered_inventories_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_ordered_inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_ordered_inventories`
--

LOCK TABLES `product_ordered_inventories` WRITE;
/*!40000 ALTER TABLE `product_ordered_inventories` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_ordered_inventories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_price_indices`
--

DROP TABLE IF EXISTS `product_price_indices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_price_indices` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `customer_group_id` int unsigned DEFAULT NULL,
  `channel_id` int unsigned NOT NULL DEFAULT '1',
  `min_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `regular_min_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `max_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `regular_max_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `price_indices_product_id_customer_group_id_channel_id_unique` (`product_id`,`customer_group_id`,`channel_id`),
  KEY `product_price_indices_customer_group_id_foreign` (`customer_group_id`),
  KEY `product_price_indices_channel_id_foreign` (`channel_id`),
  CONSTRAINT `product_price_indices_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_price_indices_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_price_indices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_price_indices`
--

LOCK TABLES `product_price_indices` WRITE;
/*!40000 ALTER TABLE `product_price_indices` DISABLE KEYS */;
INSERT INTO `product_price_indices` VALUES (1,16,1,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL),(2,17,1,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL),(3,18,1,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL),(4,19,1,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL),(5,20,1,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL),(6,19,2,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL),(7,19,3,1,0.0000,0.0000,0.0000,0.0000,NULL,NULL);
/*!40000 ALTER TABLE `product_price_indices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_relations`
--

DROP TABLE IF EXISTS `product_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_relations` (
  `parent_id` int unsigned NOT NULL,
  `child_id` int unsigned NOT NULL,
  UNIQUE KEY `product_relations_parent_id_child_id_unique` (`parent_id`,`child_id`),
  KEY `product_relations_child_id_foreign` (`child_id`),
  CONSTRAINT `product_relations_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_relations_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_relations`
--

LOCK TABLES `product_relations` WRITE;
/*!40000 ALTER TABLE `product_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_review_attachments`
--

DROP TABLE IF EXISTS `product_review_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_review_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `review_id` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_review_images_review_id_foreign` (`review_id`),
  CONSTRAINT `product_review_images_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `product_reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_review_attachments`
--

LOCK TABLES `product_review_attachments` WRITE;
/*!40000 ALTER TABLE `product_review_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_review_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int unsigned NOT NULL,
  `customer_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_super_attributes`
--

DROP TABLE IF EXISTS `product_super_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_super_attributes` (
  `product_id` int unsigned NOT NULL,
  `attribute_id` int unsigned NOT NULL,
  UNIQUE KEY `product_super_attributes_product_id_attribute_id_unique` (`product_id`,`attribute_id`),
  KEY `product_super_attributes_attribute_id_foreign` (`attribute_id`),
  CONSTRAINT `product_super_attributes_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `product_super_attributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_super_attributes`
--

LOCK TABLES `product_super_attributes` WRITE;
/*!40000 ALTER TABLE `product_super_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_super_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_up_sells`
--

DROP TABLE IF EXISTS `product_up_sells`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_up_sells` (
  `parent_id` int unsigned NOT NULL,
  `child_id` int unsigned NOT NULL,
  UNIQUE KEY `product_up_sells_parent_id_child_id_unique` (`parent_id`,`child_id`),
  KEY `product_up_sells_child_id_foreign` (`child_id`),
  CONSTRAINT `product_up_sells_child_id_foreign` FOREIGN KEY (`child_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_up_sells_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_up_sells`
--

LOCK TABLES `product_up_sells` WRITE;
/*!40000 ALTER TABLE `product_up_sells` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_up_sells` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_videos`
--

DROP TABLE IF EXISTS `product_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_videos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_videos_product_id_foreign` (`product_id`),
  CONSTRAINT `product_videos_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_videos`
--

LOCK TABLES `product_videos` WRITE;
/*!40000 ALTER TABLE `product_videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `attribute_family_id` int unsigned DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_attribute_family_id_foreign` (`attribute_family_id`),
  KEY `products_parent_id_foreign` (`parent_id`),
  CONSTRAINT `products_attribute_family_id_foreign` FOREIGN KEY (`attribute_family_id`) REFERENCES `attribute_families` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `products_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'UNITY_ENDLESS_RUNNER_001','simple',NULL,1,NULL,'2025-09-05 17:13:48','2025-09-05 17:13:48'),(2,'JOB_UNITY_DEV_001','simple',NULL,1,NULL,'2025-09-05 17:34:06','2025-09-05 17:34:06'),(4,'JOB_UNITY_SENIOR_2025','simple',NULL,1,NULL,'2025-09-05 17:46:16','2025-09-05 17:46:16'),(5,'JOB_GAME_DESIGNER_GAMELOFT','simple',NULL,1,NULL,'2025-09-05 17:46:16','2025-09-05 17:46:16'),(6,'JOB_2D_ARTIST_AXIE','simple',NULL,1,NULL,'2025-09-05 17:46:16','2025-09-05 17:46:16'),(7,'JOB_QA_TESTER_FSOFT','simple',NULL,1,NULL,'2025-09-05 17:46:16','2025-09-05 17:46:16'),(8,'JOB_PROJECT_MANAGER_UBISOFT','simple',NULL,1,NULL,'2025-09-05 17:46:16','2025-09-05 17:46:16'),(9,'JOB_FREELANCE_UNITY_DEV','simple',NULL,1,NULL,'2025-09-05 17:46:17','2025-09-05 17:46:17'),(10,'JOB_MOBILE_DEVELOPER_NEXON','simple',NULL,1,NULL,'2025-09-05 17:46:17','2025-09-05 17:46:17'),(11,'JOB_LEVEL_DESIGNER_INDIE','simple',NULL,1,NULL,'2025-09-05 17:46:17','2025-09-05 17:46:17'),(12,'JOB_TECHNICAL_ARTIST_RIOT','simple',NULL,1,NULL,'2025-09-05 17:46:17','2025-09-05 17:46:17'),(13,'JOB_GAME_TESTER_OUTSOURCE','simple',NULL,1,NULL,'2025-09-05 17:46:17','2025-09-05 17:46:17'),(14,'SRC_PAC_MAN_CLONE_COMPLETE_SOURCE_CODE','simple',NULL,1,NULL,'2025-09-05 18:32:38','2025-09-05 18:32:38'),(16,'super-mario-clone','downloadable',NULL,1,NULL,'2025-09-06 09:50:47','2025-09-06 09:50:47'),(17,'space-shooter-2d','downloadable',NULL,1,NULL,'2025-09-06 11:04:23','2025-09-06 11:04:23'),(18,'rpg-inventory-system','downloadable',NULL,1,NULL,'2025-09-06 11:04:23','2025-09-06 11:04:23'),(19,'mobile-puzzle-game','downloadable',NULL,1,NULL,'2025-09-06 11:04:23','2025-09-06 11:04:23'),(20,'3d-platformer-demo','downloadable',NULL,1,NULL,'2025-09-06 11:04:23','2025-09-06 11:04:23');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund_items`
--

DROP TABLE IF EXISTS `refund_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_percent` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `product_id` int unsigned DEFAULT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_item_id` int unsigned DEFAULT NULL,
  `refund_id` int unsigned DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refund_items_parent_id_foreign` (`parent_id`),
  KEY `refund_items_order_item_id_foreign` (`order_item_id`),
  KEY `refund_items_refund_id_foreign` (`refund_id`),
  CONSTRAINT `refund_items_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refund_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `refund_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refund_items_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_items`
--

LOCK TABLES `refund_items` WRITE;
/*!40000 ALTER TABLE `refund_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refunds` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `increment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT '0',
  `total_qty` int DEFAULT NULL,
  `base_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjustment_refund` decimal(12,4) DEFAULT '0.0000',
  `base_adjustment_refund` decimal(12,4) DEFAULT '0.0000',
  `adjustment_fee` decimal(12,4) DEFAULT '0.0000',
  `base_adjustment_fee` decimal(12,4) DEFAULT '0.0000',
  `sub_total` decimal(12,4) DEFAULT '0.0000',
  `base_sub_total` decimal(12,4) DEFAULT '0.0000',
  `grand_total` decimal(12,4) DEFAULT '0.0000',
  `base_grand_total` decimal(12,4) DEFAULT '0.0000',
  `shipping_amount` decimal(12,4) DEFAULT '0.0000',
  `base_shipping_amount` decimal(12,4) DEFAULT '0.0000',
  `tax_amount` decimal(12,4) DEFAULT '0.0000',
  `base_tax_amount` decimal(12,4) DEFAULT '0.0000',
  `discount_percent` decimal(12,4) DEFAULT '0.0000',
  `discount_amount` decimal(12,4) DEFAULT '0.0000',
  `base_discount_amount` decimal(12,4) DEFAULT '0.0000',
  `shipping_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_tax_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_sub_total_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_shipping_amount_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `order_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refunds_order_id_foreign` (`order_id`),
  CONSTRAINT `refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refunds`
--

LOCK TABLES `refunds` WRITE;
/*!40000 ALTER TABLE `refunds` DISABLE KEYS */;
/*!40000 ALTER TABLE `refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permission_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrator','This role users will have all the access','all',NULL,NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_synonyms`
--

DROP TABLE IF EXISTS `search_synonyms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_synonyms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_synonyms`
--

LOCK TABLES `search_synonyms` WRITE;
/*!40000 ALTER TABLE `search_synonyms` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_synonyms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_terms`
--

DROP TABLE IF EXISTS `search_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_terms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `results` int NOT NULL DEFAULT '0',
  `uses` int NOT NULL DEFAULT '0',
  `redirect_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_in_suggested_terms` tinyint(1) NOT NULL DEFAULT '0',
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `search_terms_channel_id_foreign` (`channel_id`),
  CONSTRAINT `search_terms_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_terms`
--

LOCK TABLES `search_terms` WRITE;
/*!40000 ALTER TABLE `search_terms` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_items`
--

DROP TABLE IF EXISTS `shipment_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `price` decimal(12,4) DEFAULT '0.0000',
  `base_price` decimal(12,4) DEFAULT '0.0000',
  `total` decimal(12,4) DEFAULT '0.0000',
  `base_total` decimal(12,4) DEFAULT '0.0000',
  `price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `base_price_incl_tax` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `product_id` int unsigned DEFAULT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_item_id` int unsigned DEFAULT NULL,
  `shipment_id` int unsigned NOT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipment_items_shipment_id_foreign` (`shipment_id`),
  CONSTRAINT `shipment_items_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_items`
--

LOCK TABLES `shipment_items` WRITE;
/*!40000 ALTER TABLE `shipment_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipments`
--

DROP TABLE IF EXISTS `shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_qty` int DEFAULT NULL,
  `total_weight` int DEFAULT NULL,
  `carrier_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrier_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `track_number` text COLLATE utf8mb4_unicode_ci,
  `email_sent` tinyint(1) NOT NULL DEFAULT '0',
  `customer_id` int unsigned DEFAULT NULL,
  `customer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` int unsigned NOT NULL,
  `order_address_id` int unsigned DEFAULT NULL,
  `inventory_source_id` int unsigned DEFAULT NULL,
  `inventory_source_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipments_order_id_foreign` (`order_id`),
  KEY `shipments_inventory_source_id_foreign` (`inventory_source_id`),
  CONSTRAINT `shipments_inventory_source_id_foreign` FOREIGN KEY (`inventory_source_id`) REFERENCES `inventory_sources` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipments`
--

LOCK TABLES `shipments` WRITE;
/*!40000 ALTER TABLE `shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sitemaps`
--

DROP TABLE IF EXISTS `sitemaps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sitemaps` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional` json DEFAULT NULL,
  `generated_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sitemaps`
--

LOCK TABLES `sitemaps` WRITE;
/*!40000 ALTER TABLE `sitemaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `sitemaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers_list`
--

DROP TABLE IF EXISTS `subscribers_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscribers_list` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int unsigned DEFAULT NULL,
  `channel_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscribers_list_customer_id_foreign` (`customer_id`),
  KEY `subscribers_list_channel_id_foreign` (`channel_id`),
  CONSTRAINT `subscribers_list_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscribers_list_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers_list`
--

LOCK TABLES `subscribers_list` WRITE;
/*!40000 ALTER TABLE `subscribers_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscribers_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_categories`
--

DROP TABLE IF EXISTS `tax_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_categories_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_categories`
--

LOCK TABLES `tax_categories` WRITE;
/*!40000 ALTER TABLE `tax_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_categories_tax_rates`
--

DROP TABLE IF EXISTS `tax_categories_tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_categories_tax_rates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tax_category_id` int unsigned NOT NULL,
  `tax_rate_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_map_index_unique` (`tax_category_id`,`tax_rate_id`),
  KEY `tax_categories_tax_rates_tax_rate_id_foreign` (`tax_rate_id`),
  CONSTRAINT `tax_categories_tax_rates_tax_category_id_foreign` FOREIGN KEY (`tax_category_id`) REFERENCES `tax_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tax_categories_tax_rates_tax_rate_id_foreign` FOREIGN KEY (`tax_rate_id`) REFERENCES `tax_rates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_categories_tax_rates`
--

LOCK TABLES `tax_categories_tax_rates` WRITE;
/*!40000 ALTER TABLE `tax_categories_tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_categories_tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tax_rates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_zip` tinyint(1) NOT NULL DEFAULT '0',
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_rate` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tax_rates_identifier_unique` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_rates`
--

LOCK TABLES `tax_rates` WRITE;
/*!40000 ALTER TABLE `tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `theme_customization_translations`
--

DROP TABLE IF EXISTS `theme_customization_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `theme_customization_translations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `theme_customization_id` int unsigned NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `theme_customization_id_foreign` (`theme_customization_id`),
  CONSTRAINT `theme_customization_id_foreign` FOREIGN KEY (`theme_customization_id`) REFERENCES `theme_customizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `theme_customization_translations`
--

LOCK TABLES `theme_customization_translations` WRITE;
/*!40000 ALTER TABLE `theme_customization_translations` DISABLE KEYS */;
INSERT INTO `theme_customization_translations` VALUES (1,1,'vi','{\"images\": [{\"link\": \"\", \"image\": \"storage/theme/1/7q0Yv0ISB1aQ6SkkwqgkYKrsVpB5orIhLvgLyRAV.webp\", \"title\": \"Get Ready For New Collection\"}, {\"link\": \"\", \"image\": \"storage/theme/1/7BYfWtgCISKbLHVnWgQOPSqwWSF2GwFz4J3wrQeT.webp\", \"title\": \"Get Ready For New Collection\"}, {\"link\": \"\", \"image\": \"storage/theme/1/kvjqNB8lvnKs2TvwdzZf9oyFtWWJOfHKRnJfvC11.webp\", \"title\": \"Get Ready For New Collection\"}, {\"link\": \"\", \"image\": \"storage/theme/1/j6vnelrMAAYN6Iohw4Qyucd2tgfTfjP8ZEmMN03v.webp\", \"title\": \"Get Ready For New Collection\"}]}'),(2,2,'vi','{\"css\": \".home-offer h1 {display: block;font-weight: 500;text-align: center;font-size: 22px;font-family: DM Serif Display;background-color: #E8EDFE;padding-top: 20px;padding-bottom: 20px;}@media (max-width:768px){.home-offer h1 {font-size:18px;padding-top: 10px;padding-bottom: 10px;}@media (max-width:525px) {.home-offer h1 {font-size:14px;padding-top: 6px;padding-bottom: 6px;}}\", \"html\": \"<div class=\\\"home-offer\\\"><h1>Get UPTO 40% OFF on your 1st order SHOP NOW</h1></div>\"}'),(3,3,'vi','{\"filters\": {\"sort\": \"asc\", \"limit\": 10, \"parent_id\": 1}}'),(4,4,'vi','{\"title\": \"New Products\", \"filters\": {\"new\": 1, \"sort\": \"name-asc\", \"limit\": 12}}'),(5,5,'vi','{\"css\": \".top-collection-container {overflow: hidden;}.top-collection-header {padding-left: 15px;padding-right: 15px;text-align: center;font-size: 70px;line-height: 90px;color: #060C3B;margin-top: 80px;}.top-collection-header h2 {max-width: 595px;margin-left: auto;margin-right: auto;font-family: DM Serif Display;}.top-collection-grid {display: flex;flex-wrap: wrap;gap: 32px;justify-content: center;margin-top: 60px;width: 100%;margin-right: auto;margin-left: auto;padding-right: 90px;padding-left: 90px;}.top-collection-card {position: relative;background: #f9fafb;overflow:hidden;border-radius:20px;}.top-collection-card img {border-radius: 16px;max-width: 100%;text-indent:-9999px;transition: transform 300ms ease;transform: scale(1);}.top-collection-card:hover img {transform: scale(1.05);transition: all 300ms ease;}.top-collection-card h3 {color: #060C3B;font-size: 30px;font-family: DM Serif Display;transform: translateX(-50%);width: max-content;left: 50%;bottom: 30px;position: absolute;margin: 0;font-weight: inherit;}@media not all and (min-width: 525px) {.top-collection-header {margin-top: 28px;font-size: 20px;line-height: 1.5;}.top-collection-grid {gap: 10px}}@media not all and (min-width: 768px) {.top-collection-header {margin-top: 30px;font-size: 28px;line-height: 3;}.top-collection-header h2 {line-height:2; margin-bottom:20px;} .top-collection-grid {gap: 14px}} @media not all and (min-width: 1024px) {.top-collection-grid {padding-left: 30px;padding-right: 30px;}}@media (max-width: 768px) {.top-collection-grid { row-gap:15px; column-gap:0px;justify-content: space-between;margin-top: 0px;} .top-collection-card{width:48%} .top-collection-card img {width:100%;} .top-collection-card h3 {font-size:24px; bottom: 16px;}}@media (max-width:520px) { .top-collection-grid{padding-left: 15px;padding-right: 15px;} .top-collection-card h3 {font-size:18px; bottom: 10px;}}\", \"html\": \"<div class=\\\"top-collection-container\\\"><div class=\\\"top-collection-header\\\"><h2>The game with our new additions!</h2></div><div class=\\\"top-collection-grid container\\\"><div class=\\\"top-collection-card\\\"><img src=\\\"\\\" data-src=\\\"storage/theme/5/fMQBrzp3CxnBIiYZc5m8cXym9V05DPu04DWRUym3.webp\\\" class=\\\"lazy\\\" width=\\\"396\\\" height=\\\"396\\\" alt=\\\"The game with our new additions!\\\"><h3>Our Collections</h3></div><div class=\\\"top-collection-card\\\"><img src=\\\"\\\" data-src=\\\"storage/theme/5/YmJmj6AtoqtU9pLI1cCQQOYYaC7TWcTck0N4Kmor.webp\\\" class=\\\"lazy\\\" width=\\\"396\\\" height=\\\"396\\\" alt=\\\"The game with our new additions!\\\"><h3>Our Collections</h3></div><div class=\\\"top-collection-card\\\"><img src=\\\"\\\" data-src=\\\"storage/theme/5/SCdoIKqbvByIr9QlMvgtKUE88mSz2SLwZMc0bpiu.webp\\\" class=\\\"lazy\\\" width=\\\"396\\\" height=\\\"396\\\" alt=\\\"The game with our new additions!\\\"><h3>Our Collections</h3></div><div class=\\\"top-collection-card\\\"><img src=\\\"\\\" data-src=\\\"storage/theme/5/A7RUgzvhh2IGQGkZY9MbcuSHoOnrkcoSkRAWhHGh.webp\\\" class=\\\"lazy\\\" width=\\\"396\\\" height=\\\"396\\\" alt=\\\"The game with our new additions!\\\"><h3>Our Collections</h3></div><div class=\\\"top-collection-card\\\"><img src=\\\"\\\" data-src=\\\"storage/theme/5/n7N3VW6UhOFBDaKxgaxRmjKsLj6pecGAtHifNDBH.webp\\\" class=\\\"lazy\\\" width=\\\"396\\\" height=\\\"396\\\" alt=\\\"The game with our new additions!\\\"><h3>Our Collections</h3></div><div class=\\\"top-collection-card\\\"><img src=\\\"\\\" data-src=\\\"storage/theme/5/wcdwSmVbAtvIfPFxavGOmaB7wUZr6VyG82wF3lMe.webp\\\" class=\\\"lazy\\\" width=\\\"396\\\" height=\\\"396\\\" alt=\\\"The game with our new additions!\\\"><h3>Our Collections</h3></div></div></div>\"}'),(6,6,'vi','{\"css\": \".section-gap{margin-top:80px}.direction-ltr{direction:ltr}.direction-rtl{direction:rtl}.inline-col-wrapper{display:grid;grid-template-columns:auto 1fr;grid-gap:60px;align-items:center}.inline-col-wrapper .inline-col-image-wrapper{overflow:hidden}.inline-col-wrapper .inline-col-image-wrapper img{max-width:100%;height:auto;border-radius:16px;text-indent:-9999px}.inline-col-wrapper .inline-col-content-wrapper{display:flex;flex-wrap:wrap;gap:20px;max-width:464px}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{max-width:442px;font-size:60px;font-weight:400;color:#060c3b;line-height:70px;font-family:DM Serif Display;margin:0}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description{margin:0;font-size:18px;color:#6e6e6e;font-family:Poppins}@media (max-width:991px){.inline-col-wrapper{grid-template-columns:1fr;grid-gap:16px}.inline-col-wrapper .inline-col-content-wrapper{gap:10px}} @media (max-width:768px){.inline-col-wrapper .inline-col-image-wrapper img {width:100%;} .inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:28px !important;line-height:normal !important}} @media (max-width:525px){.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:20px !important;} .inline-col-description{font-size:16px} .inline-col-wrapper{grid-gap:10px}}\", \"html\": \"<div class=\\\"section-gap bold-collections container\\\"> <div class=\\\"inline-col-wrapper\\\"> <div class=\\\"inline-col-image-wrapper\\\"> <img src=\\\"\\\" data-src=\\\"storage/theme/6/n7pn6mPo43IyTHyqA9G8DiXo3m4ZECKWotoDKjeJ.webp\\\" class=\\\"lazy\\\" width=\\\"632\\\" height=\\\"510\\\" alt=\\\"Get Ready for our new Bold Collections!\\\"> </div> <div class=\\\"inline-col-content-wrapper\\\"> <h2 class=\\\"inline-col-title\\\"> Get Ready for our new Bold Collections! </h2> <p class=\\\"inline-col-description\\\">Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!</p> <button class=\\\"primary-button max-md:rounded-lg max-md:px-4 max-md:py-2.5 max-md:text-sm\\\">View Collections</button> </div> </div> </div>\"}'),(7,7,'vi','{\"title\": \"Featured Products\", \"filters\": {\"sort\": \"name-desc\", \"limit\": 12, \"featured\": 1}}'),(8,8,'vi','{\"css\": \".section-game {overflow: hidden;}.section-title,.section-title h2{font-weight:400;font-family:DM Serif Display}.section-title{margin-top:80px;padding-left:15px;padding-right:15px;text-align:center;line-height:90px}.section-title h2{font-size:70px;color:#060c3b;max-width:595px;margin:auto}.collection-card-wrapper{display:flex;flex-wrap:wrap;justify-content:center;gap:30px}.collection-card-wrapper .single-collection-card{position:relative}.collection-card-wrapper .single-collection-card img{border-radius:16px;background-color:#f5f5f5;max-width:100%;height:auto;text-indent:-9999px}.collection-card-wrapper .single-collection-card .overlay-text{font-size:50px;font-weight:400;max-width:234px;font-style:italic;color:#060c3b;font-family:DM Serif Display;position:absolute;bottom:30px;left:30px;margin:0}@media (max-width:1024px){.section-title{padding:0 30px}}@media (max-width:991px){.collection-card-wrapper{flex-wrap:wrap}}@media (max-width:768px) {.collection-card-wrapper .single-collection-card .overlay-text{font-size:32px; bottom:20px}.section-title{margin-top:32px}.section-title h2{font-size:28px;line-height:normal}} @media (max-width:525px){.collection-card-wrapper .single-collection-card .overlay-text{font-size:18px; bottom:10px} .section-title{margin-top:28px}.section-title h2{font-size:20px;} .collection-card-wrapper{gap:10px; 15px; row-gap:15px; column-gap:0px;justify-content: space-between;margin-top: 15px;} .collection-card-wrapper .single-collection-card {width:48%;}}\", \"html\": \"<div class=\\\"section-game\\\"><div class=\\\"section-title\\\"> <h2>The game with our new additions!</h2> </div> <div class=\\\"section-gap container\\\"> <div class=\\\"collection-card-wrapper\\\"> <div class=\\\"single-collection-card\\\"> <img src=\\\"\\\" data-src=\\\"storage/theme/8/RZYShhW1tDxLmhApHPJPBfEu6iGXE6f7sVcUskEO.webp\\\" class=\\\"lazy\\\" width=\\\"615\\\" height=\\\"600\\\" alt=\\\"The game with our new additions!\\\"> <h3 class=\\\"overlay-text\\\">Our Collections</h3> </div> <div class=\\\"single-collection-card\\\"> <img src=\\\"\\\" data-src=\\\"storage/theme/8/xvaGDca7lqB27lGZSskAdldbAC8AIyYU4NVGebbY.webp\\\" class=\\\"lazy\\\" width=\\\"615\\\" height=\\\"600\\\" alt=\\\"The game with our new additions!\\\"> <h3 class=\\\"overlay-text\\\"> Our Collections </h3> </div> </div> </div> </div>\"}'),(9,9,'vi','{\"title\": \"All Products\", \"filters\": {\"sort\": \"name-desc\", \"limit\": 12}}'),(10,10,'vi','{\"css\": \".section-gap{margin-top:80px}.direction-ltr{direction:ltr}.direction-rtl{direction:rtl}.inline-col-wrapper{display:grid;grid-template-columns:auto 1fr;grid-gap:60px;align-items:center}.inline-col-wrapper .inline-col-image-wrapper{overflow:hidden}.inline-col-wrapper .inline-col-image-wrapper img{max-width:100%;height:auto;border-radius:16px;text-indent:-9999px}.inline-col-wrapper .inline-col-content-wrapper{display:flex;flex-wrap:wrap;gap:20px;max-width:464px}.inline-col-wrapper .inline-col-content-wrapper .inline-col-title{max-width:442px;font-size:60px;font-weight:400;color:#060c3b;line-height:70px;font-family:DM Serif Display;margin:0}.inline-col-wrapper .inline-col-content-wrapper .inline-col-description{margin:0;font-size:18px;color:#6e6e6e;font-family:Poppins}@media (max-width:991px){.inline-col-wrapper{grid-template-columns:1fr;grid-gap:16px}.inline-col-wrapper .inline-col-content-wrapper{gap:10px}}@media (max-width:768px) {.inline-col-wrapper .inline-col-image-wrapper img {max-width:100%;}.inline-col-wrapper .inline-col-content-wrapper{max-width:100%;justify-content:center; text-align:center} .section-gap{padding:0 30px; gap:20px;margin-top:24px} .bold-collections{margin-top:32px;}} @media (max-width:525px){.inline-col-wrapper .inline-col-content-wrapper{gap:10px} .inline-col-wrapper .inline-col-content-wrapper .inline-col-title{font-size:20px;line-height:normal} .section-gap{padding:0 15px; gap:15px;margin-top:10px} .bold-collections{margin-top:28px;}  .inline-col-description{font-size:16px !important} .inline-col-wrapper{grid-gap:15px}\", \"html\": \"<div class=\\\"section-gap bold-collections container\\\"> <div class=\\\"inline-col-wrapper direction-rtl\\\"> <div class=\\\"inline-col-image-wrapper\\\"> <img src=\\\"\\\" data-src=\\\"storage/theme/10/HG64D9SkfpLlktPnVMb67fISz8g4Q1JTepHroj1e.webp\\\" class=\\\"lazy\\\" width=\\\"632\\\" height=\\\"510\\\" alt=\\\"Get Ready for our new Bold Collections!\\\"> </div> <div class=\\\"inline-col-content-wrapper direction-ltr\\\"> <h2 class=\\\"inline-col-title\\\"> Get Ready for our new Bold Collections! </h2> <p class=\\\"inline-col-description\\\">Introducing Our New Bold Collections! Elevate your style with daring designs and vibrant statements. Explore striking patterns and bold colors that redefine your wardrobe. Get ready to embrace the extraordinary!</p> <button class=\\\"primary-button max-md:rounded-lg max-md:px-4 max-md:py-2.5 max-md:text-sm\\\">View Collections</button> </div> </div> </div>\"}'),(11,11,'vi','{\"column_1\": [{\"url\": \"https://lamgame.vn/page/about-us\", \"title\": \"About Us\", \"sort_order\": \"1\"}, {\"url\": \"https://lamgame.vn/contact-us\", \"title\": \"Contact Us\", \"sort_order\": \"2\"}, {\"url\": \"https://lamgame.vn/page/customer-service\", \"title\": \"Customer Service\", \"sort_order\": \"3\"}, {\"url\": \"https://lamgame.vn/page/whats-new\", \"title\": \"What\'s New\", \"sort_order\": \"4\"}, {\"url\": \"https://lamgame.vn/page/terms-of-use\", \"title\": \"Terms of Use\", \"sort_order\": \"5\"}, {\"url\": \"https://lamgame.vn/page/terms-conditions\", \"title\": \"Terms & Conditions\", \"sort_order\": \"6\"}], \"column_2\": [{\"url\": \"https://lamgame.vn/page/privacy-policy\", \"title\": \"Privacy Policy\", \"sort_order\": \"1\"}, {\"url\": \"https://lamgame.vn/page/payment-policy\", \"title\": \"Payment Policy\", \"sort_order\": \"2\"}, {\"url\": \"https://lamgame.vn/page/shipping-policy\", \"title\": \"Shipping Policy\", \"sort_order\": \"3\"}, {\"url\": \"https://lamgame.vn/page/refund-policy\", \"title\": \"Refund Policy\", \"sort_order\": \"4\"}, {\"url\": \"https://lamgame.vn/page/return-policy\", \"title\": \"Return Policy\", \"sort_order\": \"5\"}]}'),(12,12,'vi','{\"services\": [{\"title\": \"Free Shipping\", \"description\": \"Enjoy free shipping on all orders\", \"service_icon\": \"icon-truck\"}, {\"title\": \"Product Replace\", \"description\": \"Easy Product Replacement Available!\", \"service_icon\": \"icon-product\"}, {\"title\": \"Emi Available\", \"description\": \"No cost EMI available on all major credit cards\", \"service_icon\": \"icon-dollar-sign\"}, {\"title\": \"24/7 Support\", \"description\": \"Dedicated 24/7 support via chat and email\", \"service_icon\": \"icon-support\"}]}');
/*!40000 ALTER TABLE `theme_customization_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `theme_customizations`
--

DROP TABLE IF EXISTS `theme_customizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `theme_customizations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `theme_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `channel_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `theme_customizations_channel_id_foreign` (`channel_id`),
  CONSTRAINT `theme_customizations_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `theme_customizations`
--

LOCK TABLES `theme_customizations` WRITE;
/*!40000 ALTER TABLE `theme_customizations` DISABLE KEYS */;
INSERT INTO `theme_customizations` VALUES (1,'default','image_carousel','Image Carousel',1,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(2,'default','static_content','Offer Information',2,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(3,'default','category_carousel','Categories Collections',3,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(4,'default','product_carousel','New Products',4,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(5,'default','static_content','Top Collections',5,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(6,'default','static_content','Bold Collections',6,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(7,'default','product_carousel','Featured Collections',7,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(8,'default','static_content','Game Container',8,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(9,'default','product_carousel','All Products',9,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(10,'default','static_content','Bold Collections',10,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07'),(11,'default','footer_links','Footer Links',11,0,1,'2025-09-05 17:10:07','2025-09-20 13:45:25'),(12,'default','services_content','Services Content',12,1,1,'2025-09-05 17:10:07','2025-09-05 17:10:07');
/*!40000 ALTER TABLE `theme_customizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `url_rewrites`
--

DROP TABLE IF EXISTS `url_rewrites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `url_rewrites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `url_rewrites`
--

LOCK TABLES `url_rewrites` WRITE;
/*!40000 ALTER TABLE `url_rewrites` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_rewrites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visits`
--

DROP TABLE IF EXISTS `visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request` mediumtext COLLATE utf8mb4_unicode_ci,
  `url` mediumtext COLLATE utf8mb4_unicode_ci,
  `referer` mediumtext COLLATE utf8mb4_unicode_ci,
  `languages` text COLLATE utf8mb4_unicode_ci,
  `useragent` text COLLATE utf8mb4_unicode_ci,
  `headers` text COLLATE utf8mb4_unicode_ci,
  `device` text COLLATE utf8mb4_unicode_ci,
  `platform` text COLLATE utf8mb4_unicode_ci,
  `browser` text COLLATE utf8mb4_unicode_ci,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitable_id` bigint unsigned DEFAULT NULL,
  `visitor_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visitor_id` bigint unsigned DEFAULT NULL,
  `channel_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `visits_visitable_type_visitable_id_index` (`visitable_type`,`visitable_id`),
  KEY `visits_visitor_type_visitor_id_index` (`visitor_type`,`visitor_id`),
  KEY `visits_channel_id_foreign` (`channel_id`),
  CONSTRAINT `visits_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visits`
--

LOCK TABLES `visits` WRITE;
/*!40000 ALTER TABLE `visits` DISABLE KEYS */;
INSERT INTO `visits` VALUES (1,'GET','[]','http://lamgame.localhost','http://lamgame.localhost/blog','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/blog\"],\"cookie\":[\"lamgame_session=eyJpdiI6IkNLS2R6TFNFeUNZcXlFWmg2cVpYN2c9PSIsInZhbHVlIjoiNWVxUFYyRU5Qc2VyWUUvampjVWtNOXlSSUFZeWpqeVRmK2JuZS9VSndxRmUvTTZGejcxUHZqZTlMUUNQQ1JMazFXc29PNitRZHBBWFRMcXhBbHdQNEgxQ1JrVDhsSmphQnhwUG9TOGpaU3ZNdnpra1RVU2g2T2NyNDI3Tit6VDMiLCJtYWMiOiJlYjIxODhmYmEyY2YxODdlNzA5ZDE4ZjRlNWM0Mzg3NWU5YjUzMWViNjQ5Y2EwY2M2ZmVhNjRkY2VhZGU2MjE1IiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6ImNDalRoTEFaUXNCTGczaGNHSFBQU0E9PSIsInZhbHVlIjoia1FXcTYycGM2cDlIVGlwbnFrWVkwZ1ZYLzJpdWxzeDVYTFpHYk41R3JXZXVUbFMzbDlGZ2xpdnRKSTlFbjlaWHI1dWQ0dHI2Ulowc2NwbEt4cE5seUlvN0hkeGwvREVaVGQ2Sk03VVFScEpuVkl0N0Y0VlBNbjdzaTRkWHNzaVEiLCJtYWMiOiJjODQ0YWI3YTg5NWQ3MmI5OTc0OTk4OGE5ZDkwNzQ0MmZmYzExNzUxZWIzMzYwMjI5NTk1ZTExMTVlZDNmYTU4IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6ImVQOVppTEhZOUF3QklZUXAyTkJUcXc9PSIsInZhbHVlIjoiZ0Zrb1F2NUJEck5hbndMTnJWMnAzWVhqYis1VGpEckdRZWhEVmdrbzl4Qk1nUHVybFV2SGlEM1pBRUlPSUU0SUNmWFpRenFObDJUamgrbFFCeW1aa2E0MWx0cXk3c2Y0K1NubWppWmU1MDJWRmpFazhVSG4xWEZ5RHFaWDlyWGciLCJtYWMiOiIwYjJlM2FkMTU3ZDBkMmIwZmFjMDIxYTJmNDk1YzNjN2I0YzU2NzUyMmJhMDliZGI3MmI1MzZiMWUyMWUwMjViIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-05 17:21:00','2025-09-05 17:21:00'),(2,'GET','[]','http://lamgame.localhost/themes/default/assets/css/admin.css','http://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/admin\\/login\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Im0wUXdOVk1qRGdRRGpINDB1WnF5L1E9PSIsInZhbHVlIjoickVzU093eUhtc3ZmcUxlVUc0aklremtGTmdEaDJCdkdyTWk5c3Z4ZWdCTFB1ZmVMYnBlU0NwS1pvb1ZINTN0TTlydnk5dnEzZlkwQm9RY09HTXc0UGREem5XUTBQREUySWxlZGNjYXhrRFE3Q1VBclZvcXR5Z3ZQZXVvc042WHkiLCJtYWMiOiJjOTVjZWI2NDAwNWQ1NTM2MzFiNDU0N2YwNTg4NzBiNWNiMTI3N2IwYTU3MWUwNmNlMTg2NjJlOTRlMGU5OGYxIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjYzYkVTWXduai9JSFNnZXFTS1B3VWc9PSIsInZhbHVlIjoiYnJuSC9CdHkwNWEwQjhNOVNyUWdndWN0blpHdVcvUHJXMHNKazNYTHI2V3o4L2cybStYNHI3c2xFRnJ2MVBwMnVXWFFEbFFPZFVzSzN4S1NCZmJ4K2w1eFVONjFjVnZ4a2RXcXdYekEvWTJBOUlCbG5PV1E1TFdwUmhnSkFvcUQiLCJtYWMiOiJhYzBlOThkOGIyMDRlNjE5MDg1OTk5OWU0NzRkZGEzYjIzZWQzZDAwOTJjYmE1YjEwMTNiOWEyZGJhYzQ5Njk4IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-06 03:09:32','2025-09-06 03:09:32'),(3,'HEAD','[]','http://lamgame.localhost',NULL,'[]','curl/8.4.0','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-06 06:35:02','2025-09-06 06:35:02'),(4,'GET','[]','http://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6ImdXMXZDbDNiaTNjbFpVbHpMdHBLaHc9PSIsInZhbHVlIjoiQ0thMGNLNjBZdDlNTEtyZnNFdlVxN1preVZOR3U4dVNRR0NjMzhIMllKRlFkenBkazhzQjM2WjFQRGFKcyt5RGUycmxHclJlOTVIelhnU2wxemtYSjlFL0w1ckM2RW1IajhqUTc0OWQ4a3NVa3NrWC93STErMlFtdEVUcTNjRkgiLCJtYWMiOiIzOTU5N2Q5MTZhNWExNTc4YmQ0ZTQzY2Y4YmNjMzM5ODBmNTg4YTUwOWE2NzRjNzVkMTU3ZWYzMzFlNzQ4MGQ5IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IkZSL09KblN0UUJXTFdHL28wVTFwcUE9PSIsInZhbHVlIjoiUEFEYnFIT2JuMm9hY20ra1ZvYTJ5UHVmU3VHNFlzakNWSWRrZldTMk5sQ1lQcXZza2dvNjhCdjdpVTIwVDZFK2xqSUJ4elZJa2lPYjZ3WGcwMWxHY3FHU09TbERqV3pJNjg3SkRKNlU2S09uOEowREJrc0dPY2o0M2U1cHA4cU4iLCJtYWMiOiI1NDUxMGU0MzQyMDk2N2Y4NzYzNjgyNGM4NzIwY2M1YTNjM2FmYmM3Nzg3MmYwNTI1MTgxMWI5ZWYyZTViN2VkIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-06 09:09:20','2025-09-06 09:09:20'),(5,'GET','[]','http://lamgame.localhost/rpg-inventory-system',NULL,'[]','curl/8.4.0','{\"accept-encoding\":[\"gzip\"],\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1','Webkul\\Product\\Models\\Product',18,NULL,NULL,1,'2025-09-07 03:50:40','2025-09-07 03:50:40'),(6,'GET','[]','http://lamgame.localhost/source-code-game',NULL,'[]','curl/8.4.0','{\"accept-encoding\":[\"gzip\"],\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1','Webkul\\Category\\Models\\Category',2,NULL,NULL,1,'2025-09-07 10:13:26','2025-09-07 10:13:26'),(7,'GET','[]','http://lamgame.localhost/space-shooter-2d',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkEwTmorQ3ZhTHYvQkcwdzFEbkFUeEE9PSIsInZhbHVlIjoiSC9lL3pkNVFNdGlmelJpME56cUpRTDdkSjgvTTU2S1EyU0wvKzFNNmJHMDRkaXZHSnhWa0hNZVUyUFdCU3BJRW04UHlIc0V0eWJJQ1pNWXVtdjcwL0FreFFETkVtWFYwWXQ1czhZRmxJMXp2SDNLUmRtak45QVkvUjZWeGlnRHkiLCJtYWMiOiI1ZGE4MjAwMzE1NjRkNjQyYjRlODQ2ZjI3Y2Y3N2I5YzU4MzYxNThhMDU4NmY3ZWY0NzU2YjA1MGZjMGZiYmJhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IlJjR3k1Ym1xd2h1MVAzNlFHUGF3WkE9PSIsInZhbHVlIjoiZWQwajM0Sjh0SkdBWXNLZWsyRnFmaHVXcnFwR3BPUFVYYzdQNkd1WnZCOEFHNW5lK3crVXZnWDBFOHNyTkZyVE1qRURNZTlub24vSzkrd1FCQWZINGJtWWVWaE1zWHpLM0hXdFF6NVVnUjhDcVRvWERWZ3gybWV5VVNsRFNnbzUiLCJtYWMiOiI5ZjA2MDI0YzZmMzVmNzdhNjRkY2Y3ZGQyYzNjMjcyOTNkMzVhMDMwNjlkZTU5ODRmZGExMDE1OGE5YmVlN2EwIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',17,NULL,NULL,1,'2025-09-07 10:18:40','2025-09-07 10:18:40'),(8,'GET','[]','http://lamgame.localhost/super-mario-clone',NULL,'[]','curl/8.4.0','{\"accept-encoding\":[\"gzip\"],\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1','Webkul\\Product\\Models\\Product',16,NULL,NULL,1,'2025-09-07 10:32:06','2025-09-07 10:32:06'),(9,'GET','[]','http://lamgame.localhost/themes/default/assets/css/admin.css','http://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/admin\\/login\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IllGTmZvM3JlWEtQTzRzSmtxcUpNbmc9PSIsInZhbHVlIjoiQzdDY3RyVDBOV0tDU0xycmxsd0ExU3hWRVY0aGxWN1dDK1FOblpVa1J0ZkVaSUpzL0lWWTM2NkphMzNsZnRXZzhwT1R1anVEVmFFSHUwKzNEUzljOW5sSWE2Wmpxb2JWWHc5akt1M055cnpwRlBYRlpWcFpnZGNjSmJQN2F2TTMiLCJtYWMiOiI3NWE0NWM1NTU5NzA2NTNhNDRjOWQxNGE2OGUyNjFmNDk4OWExYTNiYjFhMWY2MmJiYzNjNDY0YTE0MjhmYWU0IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IlhuYTZrSk90RmN6V01MOE9PZEhHaEE9PSIsInZhbHVlIjoickFvZGJaRjRja0F2QUZwbVh4TlBrakttb3FIYkRNY1RPMXl4TjBNNXNERkRrZGNxR0ZLcC9oeG84MzIvVVlUb0Q2V2tIYmhHREh0TzNMa1dCb24yeG4rRjNvZi9GYTRPRHduQXZlQnpBYlR6QWw1aEo0YmxxYUQxNUdqelpwVUUiLCJtYWMiOiJhYjljNWM5YjA3MWU4ZmUyN2NiNzFiOWY1ZTZkZGUxNzA5NTE1NDQyZjJiZjBjOTAxNDZiYWZkMzk5OWI0OTkwIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-07 14:12:30','2025-09-07 14:12:30'),(10,'GET','[]','http://lamgame.localhost/source-code-game','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Category\\Models\\Category',2,NULL,NULL,1,'2025-09-08 00:56:24','2025-09-08 00:56:24'),(11,'GET','[]','http://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Imt4WjBtTUQ2RVRoRnhPSW96UGxiWFE9PSIsInZhbHVlIjoicm1JcTZrVm0xa1hDSzJ6b1pCU0JISk5VM0hZbm8xLzY2OWFiL1dLR1ZoTU9rUHNJUFFENGNab2cxODlwNFdWd2VUcDFkMmVIUzBiUjE3Nlc1QStNS0ZxdDYxYmU2c2w1SlJkQk5CQ0QxN2xVVW5yN3dIUDJVcFQ2L3Z5b2svK3giLCJtYWMiOiJjNDY2ZGNjZDA5NmYzOGE2MzdlMjE5OTljMjY3MzFhYjZlMWYyOWQ3NWYxOWRlZjQ5ZGU4Zjg4NWI5NWI0MzljIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjNtbUtMY3RKdWowWXZSSTU4RDA4Q2c9PSIsInZhbHVlIjoid0FHZHJTOWsyTzlOYmkwaHJOZmc5Y1cyaElSTU1udzZHYzVwcDFDWCtNYllic0g0M0szUzN5cjVxMzVaNG1GY1hpaHF6aUQ4dFNwanFFQTFUV1NFV2JEYW5XeFZBQjJPdzBzUFh1TXdja0M0NEkwYW0vNkQ0dGJKVm5xMSsrOFYiLCJtYWMiOiJjMTRkNmRhMjJiOWQzZTk0NGFkNjE5MDg1OTNkYzM0OTMzMTdlZWQ4MGU3YzIxOGFlM2VmNTlmMjJhZGJjYmExIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-08 00:56:50','2025-09-08 00:56:50'),(12,'GET','[]','http://lamgame.localhost/space-shooter-2d',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IndQNTJBWlNGcWFncFN3WU1LZDdmQWc9PSIsInZhbHVlIjoiUVpyRzB4b3Q3aFUweXh4clB2Ym1EYStySldXMWl4WmhTTTgzMGx3VllyK3FMT2pxNTAyV21BVG1Cemk4QjBBajk5VUhJMFhqaDY3b3UzcEFVb0hnRFdvMmxlSG5zRGVKU1ZQN1QvK2xlYUN6MkZVSzRST3NBaHJyOVRWUFhhWW8iLCJtYWMiOiJhYmVjMGJhZDc2ZTk2NGZiMzIyODA4OTk3MWE4YjRiZTZhNDhmYmJmYjc1MzU1NDQ4M2YwMWM1NjBiMmU5MzNlIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Imc5ZXRWcVZXOVVFQnd2TERkVkJRM0E9PSIsInZhbHVlIjoiQmNGb3NITWpiTXl3U21HVjdiN0xQaHozNDljalc3emEvdHhrRDZwZTQ1NkViVUl0VjBFU1E0ZDZ0RXZUd3VFeEpYRU1tL2xrY1l2VXJvME9hOTQwUG5lTXRNb0pLYkZTVDlIalFFT28vOFBqTWx6UmZKa0tjUWhPWXhkaW1qWEIiLCJtYWMiOiJjODc4NzgwNjA2MmZkMDkwMGRmNzRiNWFmMDIzMzAxMzljYmM4ZDcxZDc2MTA1MTM5ZTQ5NjJmYzA3NDI5M2MwIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',17,NULL,NULL,1,'2025-09-08 06:32:12','2025-09-08 06:32:12'),(13,'GET','[]','http://lamgame.localhost/rpg-inventory-system','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"ebcd7943760f\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkI3RVl1aUxiR3RSbVpqZTdOditiYXc9PSIsInZhbHVlIjoibmlCK0FmdHdzVHlvdm9PL1VhNWZ0bnZqa2dWcDM3RFZyc0dCeCtsU29jNGVCOEFTMkdxY1Y4cVBuTGRnNlJqbUVyUDlyVEdySG9UWW42SzBrNzF2aGFxUnVjYnl3TlNJdnJQNGFSMGZYRS9OYmVrSzdRbXM5RTJDSUFPMm1Zc0oiLCJtYWMiOiJjZDM3NWU0OTk1NjQ3OWJjNGM1ZDlkOWNkY2VjMjFhNjIxYWM0Y2UwYzQ2MTVhNDlhYjg0ZmFlNjJhZmVhZGNiIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IittV1l5L2xpS1pnNXFwTXN3dityK0E9PSIsInZhbHVlIjoiR0V4aFF3QUw3ekJ5cnlNSWN5eStXc0FSMWx4Uys4Tk9OYnozTkh3SnQxdEhwTHgxWE5LMk5HTjA0NGhUZkpCODc5NE9jY1ByZnFnb0RzNyttOU0yM3VrRHFHaVhUVlJNMDBVb0oyTEVhaUo2eDlMbENPR21abER1YnF5bExHQlkiLCJtYWMiOiJhOGUwOWMzNWNiNzBkZjMyNmYxNzU4MmMxOTg1ZGEyYTk5MmY0NDA5M2YxODM4NDgyNzk4Mzk2OGM0MzIzMzZkIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',18,NULL,NULL,1,'2025-09-08 08:09:16','2025-09-08 08:09:16'),(14,'HEAD','[]','http://lamgame.localhost',NULL,'[]','curl/8.4.0','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"0a5aa0694a94\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-09 03:30:08','2025-09-09 03:30:08'),(15,'GET','[]','http://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"0a5aa0694a94\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"if-none-match\":[\"\\\"65cce457-267\\\"\"],\"if-modified-since\":[\"Wed, 14 Feb 2024 16:03:35 GMT\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-09 03:30:09','2025-09-09 03:30:09'),(16,'GET','[]','http://lamgame.localhost/space-shooter-2d','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"0a5aa0694a94\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkJmWHdoYWJCcVBDdWNLM00vcjlqa0E9PSIsInZhbHVlIjoidW5SVElURDdYMmI4WEVwWGgxeGh5aWFYQUo5VTNrNFozdmhpa1pUdU9PeHUzM3dMWVlmSXpRbGpTYXJkc2ExWHZoSnVOUDMwa1VubkxLaTNCcVZrZk1Wd1gyS0pITERhQkNhRzdNRGE5aXRhb2w4bjZwUmdtR0RPRVk3SHZVajkiLCJtYWMiOiI3ZDMzMTE2Mjk4MGY2MjNjZmNjYmU0MTM2M2JkMjNhNGZlYjk2YjQ5ZTBlM2VhMGVlOTM4NDg0ZDRjYzJkMzc0IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IlAyZXlkS29nS3lhcnkzUEFBQi9oalE9PSIsInZhbHVlIjoickowUWJ3MmNlRTNQUHNlTk5wdXlOdjZDWFdkYkF2VklHaDJ3ZjUveHdkV2dCRS9rYnVzaGNhaFozaVJrMEp1akhVK1JYN2xCV2FZMGZEaGFiYkxuQ1JnZmZlSkhhWGpnc050QnFabm9Rayt5MUpRdzN0dTNKVlZYcGF2aGVINzYiLCJtYWMiOiJhOGYwM2NiMjZmYmEyOTBhOTZkZTQwM2M2NDg0NmQwYzI2M2Q3Njc1MmE1MjIwZjhkMWNkNjYzMzNlNzM3ZjdhIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',17,NULL,NULL,1,'2025-09-09 05:23:53','2025-09-09 05:23:53'),(17,'GET','[]','http://lamgame.localhost/3d-platformer-demo','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"0a5aa0694a94\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IjJmOS8vV3ZMRElJZU0zMmhSMHhmVkE9PSIsInZhbHVlIjoiQVBXd1VCTllGVUdyMkl5SDFPenhMZDdZYm9OR2QrcC83V1dGQlVzRThlTXR2QkYvSTdHYjk5RjJiY2k5UWVSdVhVQTkxZ25XdTZIcGVZS0NqU1pUalFlWVppV2tnQytWMkNJNjhwY0ZUamlPL3VEaG51L3JSK3JjSnFNRGpDaG8iLCJtYWMiOiIyYjViNzE2MDEzNGNkMzA4YzBlYTI3OTQ3NTM3NTIzYzIwZDQzOWEwYzE1MjNhZTRjNjAzMDQxY2QzODBkMTEzIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjU3YlRBaTFpeDNnZkNDUXlkWTB5YWc9PSIsInZhbHVlIjoiN3A4djR1RUx6L3h2SUZPZTZYTGI0aE9neVNYNEp3SHQ2WXdhd2RRL0Rmazg2Nmc4a1ZmVVNTbnVVcEVOcjdtVkxUdzdOUE51eURBNzdGZmQrWFdxNXZEOU5IWWlxRDlwc2VlQ3JYMlU3UlhrNXY2dkNSaGRVZnZNU2JVdlFyOXciLCJtYWMiOiIxZTA2ZjhjMzlmYjkzOTYzNGVkZWJmNmViZjdmZTZjZWIxM2MwMGZmY2I1MDk2N2E3ZDg3MjliMDE4YzMyYjE2IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',20,NULL,NULL,1,'2025-09-09 05:24:32','2025-09-09 05:24:32'),(18,'GET','[]','http://lamgame.localhost/super-mario-clone','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"0a5aa0694a94\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Iml2VWdQa0FhTjhpc1hXWUpVVEx1ZlE9PSIsInZhbHVlIjoiRFBUai9NbFNHRzhHc25JM2F3N1ZyNnBNSXROcnZMaFNUdzJmbVBpblBrSFN3eWhwS21hZXVTdzBXenI4dTJLbnZFLzk1OEJjaHkzYXI3bGdXZkpHZkpYR2RmRVozMlVYc1J2TjlNdUxZeUs4NlJ4UUcvNVc1QWtCRDYwRm95bXoiLCJtYWMiOiJhYTEyMWJmMDgzMzIzMThkODVkN2QyNjY5NDE1M2EwZDkzZWI4OGJkYjUwZjgwZGU2MzFmMTBmZGZiYWRkYjZiIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IisxSVhtUDJRa2duVUg4QnJRcUtYQmc9PSIsInZhbHVlIjoiMVU5T0RFbU44MHgrY0pBTVhld0FtOFczZ0t4ZFFUYm5aMHMwUkpYbFI5cnRnelI4Vnp1b2ZvWjJQU1FpUHZiRDdDYjcvaHlVa2RNTTliOW5jU3ZNTVRPMFVScVVIRFQvbXdCdGVIbkRoSUVYNUhYWS9leHZsdjdTUXBzVEdPU1oiLCJtYWMiOiI2OGE2YmFjYzU2ZWU3MGMzNzMyNjRjNjc3ZWRiYzFhZTE3NmE3N2MxNDMzMGM0YTNjYTYwMWU2YTg0M2NiYzg1IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',16,NULL,NULL,1,'2025-09-09 05:24:36','2025-09-09 05:24:36'),(19,'GET','[]','http://lamgame.localhost/rpg-inventory-system','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Ilo5aWRwQmNBRmNBU3VUZWpIWlNleUE9PSIsInZhbHVlIjoiVHhKUEZNQmJNK01COEFFU1BqTnhJWVVhTEs4ZTEyWlhxYmtvbTFJczAzNHZEYVIrbUxhaDJ3WURoRVlCUjJCRkc1eHd0bGhXT3QvYTdVTzg5OTJGOGUwS3ZaRFFMVktkREFKSzdMbW92dDBrK0cwanlyZUlMQVpkWG5CaGhDKzkiLCJtYWMiOiIxMzhjOTNiNDA3MTRhNjY5NzJlYzgwOWNjMjVkNTMzODg0YWFjODJmOWZmYTQ5OWRlNTI1NTk5NmQyOGZlMmUyIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IlIycDhmbVhzRlE0cjVuNnNNa2k5NEE9PSIsInZhbHVlIjoiWi9DSzVCTDZTdVJOUmVUdTlieU5qN0U2S1lHbTRHb20xZHcwbkZKMmtiTWhMbUNyenExM08xUmo0elV1czdKKy84aEJIa1Q1NFFlck0zTmlwL09USmJqbnQ4THJBbzdIeUh5dWtjRHo3dnI4Vi9ualZwZG41QWY5VHRxRVBqRGsiLCJtYWMiOiI3YzM0OTQ1YzU1M2U4Y2IwZTJmZmE0ZTYxZDFlODA1YmIyMDY4OTUzY2YyZTgwZDExYjYwYzZkNTU1ZWVkNjZlIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',18,NULL,NULL,1,'2025-09-09 15:17:39','2025-09-09 15:17:39'),(20,'GET','[]','http://lamgame.localhost/themes/default/assets/css/admin.css','http://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/admin\\/login\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkgvcEJUWmxrTkVRZGlRajFBTTIrQWc9PSIsInZhbHVlIjoiR21mR3FzTWllMkV2eDU0TE0rdnA0OVhNZUpyRHV2N09YWnZuTVdWR0M0bUdtQnh0a3ZheVgyVFE0NHBObFlXeVdXY3dLaDZiandJRm5ET2NvVzFNU2tPMWZJTTlENmlJSE1Ud2ZjTCthTERTUWo5UktVbHZEVElac3VlYS9UVC8iLCJtYWMiOiJmNmFmZTc0MTAxMWI3NjNiOTdlNmRhNDU1YzQ5ZGExMjFhMGE2OGEwMzAwMjlmZWNlOTQ4ZGQ0ZTk1MDM0OGNkIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IkdvWXdtSEZzZUJMQ292Tkl0ZGhJaFE9PSIsInZhbHVlIjoidklaSS8zUzRKTnFsYzBXZ0w2UnplR2tWS29yRW5sTGlwcnZxMUtTYitEamlxVmFkbUMveitkSHFaTnE0dGJ0RTRzWTdWRkNSMEd5c25TcWFOYUIxeHVENEYzSTYvejRCMS9TZm8xelQ5MGthUUJTVlpXeDJEZ0hFSk94bWdzQk8iLCJtYWMiOiJmZGVmODVhZDBkNTU0MjViOTcwYmViM2NhNzkxZWYyZGIwM2VkODY4YzU4MDlhYzY5OTU5ZTVmNjFmY2FmODE3IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-09 15:18:44','2025-09-09 15:18:44'),(21,'GET','[]','http://lamgame.localhost/themes/default/assets/css/admin.css','http://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/admin\\/login\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IjdoNVpZK1l3UXBYWHFLcmw4WE1rdEE9PSIsInZhbHVlIjoiSlZuc3cyam00Ly9YU1ViVGc1Y3ZzZzdtZlhrWUFKcE5FNVlJV0VUK1ZxTlBCQVZwd200YlJqekNnRDdidVF1TVhyZUhrYWVRbUNYbWZ1VWE3MXNuT3JNWHkrYzVoNjVTTHAzSWNVcEQxVDlhZ2VacWlXMmlmTCtZdFhTbkJ4MGEiLCJtYWMiOiJhNDk0YmUwMjQyM2Y3NjNjMzQyYTk0NzU5NDBmY2Y1YWU1NDVhMmI4NTI3ODA2ZWZmNmNlYjhlYzEwNDFlYWM1IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6ImFmM1laUmU1VWQya25DdkhvNXgxcUE9PSIsInZhbHVlIjoiTXhFcis0M1pBWWp4TVZaME81V0VrYjE2dmxhZFQ5bStGRndkb3lRVnZxZnp1TzhWOGk4U2dpVHJOcFZFVXFKOG5oQnhSZ1VGNzVTRVhRVUV1VGtpUVFNaHF3eTlCUFhFY1oyb1k3ODVGc3hzbjhRZURPN3FxdXg2SHo1d01lUGUiLCJtYWMiOiJkNzkyZTJjYzNlZDA0ZTI1NWZjNGE5Y2Y2ZWZmMDQ4YjJmOGVlOTNhMzkyOWRlODFlNWM1YmZkMzViZGU3OTYyIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-10 00:21:36','2025-09-10 00:21:36'),(22,'GET','[]','http://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Im45OC9ySVhndE55WlJ5S09RbXpnUkE9PSIsInZhbHVlIjoiRlNTYXFETHREeDhPMkNwanFqbkRZbEJHYjQ2djhmcVhadk5ENmlIbDdSbkFSVmx5MWxrM3RwS2hpdVhJRjVBYjg2WlF4Z2l3N094N3Q0ajdESm1CU2VnT2h5anp1bXNQYytMZHVmOElqODlpeHRKUnp4WVNkWVdTNzQ0bEhCSUkiLCJtYWMiOiI1YWI0NjIwYWM4N2Q4NzIyZDY3ZmE4ODU2MjQxZjNjNmNhMDY2YWJlZmRkNjhiNzI0MDk1Mzk3MGI0MGE3NmU3IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IkpGc1c0Q3hFdmloMVVYeHZEd2dET1E9PSIsInZhbHVlIjoiczZ4MzR6S0NKY0U0UmpuSTZvU3NPWmJ4WU1BTFF3VkZMdmVuc0ZaZjNWc2F3YUlQc0FsZ0loeDV0UlQ3eVh2ODdRVGlMNFc1aGVvV1V3b1N2U3dNSjk2VEdwSW00OFUyejBjcDlKNFFKaDR1ZjNLZXdlMGZoOGZxTFlheEk2Wk8iLCJtYWMiOiI1Y2M4MDhmNWI2YThmYWMwY2ZiNmFkNjM1YTQ2ODUwOGY5YjQ1NmFmMThhNjMxYzVhMDdlMjVlNzQ2OGEyZWJiIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-10 00:22:36','2025-09-10 00:22:36'),(23,'GET','[]','http://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InR5RUREV0lnK0tMcks5U0cxUnkyRHc9PSIsInZhbHVlIjoiVmo4WkFJVEYvWlVnMWx2MlVITnlzS0lZampZeEhlVmM0a0xhU21sblNRYk1qd1U0b3F4eVJDT1BIOEdPRjBUUk1oN1BVa1VSdnRZRkNnMmMrVjNjY0dqQk1LcWQxSzhmbGd0NEdRWTYyV2FGWklDaGFNbVExNjdCc1poUitoWTEiLCJtYWMiOiIzMDZkMTdkNzJjODI3NzRhNjExNzVkOTZhY2U2ZTQ3MmE5NTNmM2FlMThmOTE4OTg1NGZjNDUxM2FkNWJiZmY4IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjV5V3RGeldhTmZybzZ2Ti9qcG5rdFE9PSIsInZhbHVlIjoiWXpiVzJIbWlnckFMa3pmU3phb2FGeEsyalYrU3ZnL083U3o1QjVBRUFUdjFyNCtEZ3lCM094ejlYREZtT2h5aEVnNVpwRXcydEMyQ0pmUXBKOHFWZjVLa1NWZko2ZmwwUW5obXJFNmZKT3EzdDFldUtpaE1OUFhkc2cvaDRONS8iLCJtYWMiOiI5NTdhNDlmNzRiZjFiZGQ3MDAzNWNlZmE1OTI4MTk3YzU2NTQ0MTA2YTZiYjM5MDBiMjkyMjQxYzA0YzA1ZGNmIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-10 10:21:36','2025-09-10 10:21:36'),(24,'HEAD','[]','http://lamgame.localhost',NULL,'[]','curl/8.4.0','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-10 13:16:05','2025-09-10 13:16:05'),(25,'GET','[]','http://lamgame.localhost/rpg-inventory-system','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkE3T21nYTVENmJnN2xkUUljM2YzUXc9PSIsInZhbHVlIjoicGNyRkp1d2JwQUw3WVpnaEdxeEdEREdkeWM4ZHJ4UWhnUkRzSXMxTi8xQzRqdUFWU0Z1WVlCR3FTKzJ3T3ZOd2JCcjdBRTBkaDNmNWJkRVo5RFpWOUZCVUV4TXh2dG5zSm1Ya0dpZHpSMGFCUmVxMzhxaytjUy9PVDRTQmRoVFoiLCJtYWMiOiJhM2MxZTdhZDQ1MGM3YWM1YzMwMjA2MTYzZTRkYTFjYzk2ODg1YmVhODE2ZGVlNWNiMjc2YWNmMTM0NjM1YjczIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IlFLS3VZREp5K2xRQWErZXhkK1VQTGc9PSIsInZhbHVlIjoibnBvanNZcEg4N2ZJck5DeDNIRDkyMDlCRGJPMlNMeWdjSHRrYTRBSlZwUEpVOStnQlVkNVErTDJmQi90THZuQ3Z0L2pncjhESFRuUmhBVURVNmlJaTdid09oZlIxcTRFQUFpWTlsRXVXYWYyclV1NFM2RnBJaUFvZkxBdXVtZ00iLCJtYWMiOiIxZTEzMjYwYWE0NTU1ZjI3MDk4YTJiNjBlNjlmZTQ0MDFiMjI5YzE5MTAwYmRmNmYyMTEwM2FhNmYyYWJlZDMxIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',18,NULL,NULL,1,'2025-09-10 15:20:44','2025-09-10 15:20:44'),(26,'GET','[]','http://lamgame.localhost/space-shooter-2d','http://lamgame.localhost/source-game','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"http:\\/\\/lamgame.localhost\\/source-game\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InZSQ2dReU1pSmZSWjc4VFBZKzFvYVE9PSIsInZhbHVlIjoiOURBVWh2MGRIaVBxd2J0RW1KOGRIeFBDT3lKbjE0R0JBbFkwTlh6cGt0RWpHS05tejNGRXJTWVFBc2ZoQU0xT2tkTGE5aXhJT3pqVmpOd0ZEb2dYbTU2bWcvWDJzZmZCTmZMam96SGVMbWx1eGZJc1FLd3pyUXhzaC8zUzRtODIiLCJtYWMiOiI4YTBhNDkzOTc4YzQzODIwMzYxMGFhYTRkODI5MDBmNTU1ZWJlZWYwMDZiYjNlZGMyYzc3MWQzOGVmNDQ0ZjUzIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjR2eUFEUkZ4VUdtZU9HNTR0bGxyc1E9PSIsInZhbHVlIjoiV0Vhd3Bkb1ZFQjArWDg1ZnFvdnIwcnZJc1AyTm9mVlJLSmljbnQ2YUtrUVRLRTFtODZmNkQ1YVFKUWRCQ0xiYVgycSt2dkxEcVdlajdLWCtlZ0VxSnNXR29zN2k4RkE4ejFhck52WitMdjNBOWUyS2FpcVJlVnRFbENFQS9kQ1YiLCJtYWMiOiJlYzkxNTZjMDg3OWE0NGJmN2NjODIzNGVhNTI0ZWY1ZDk0YmRlMTUyN2Y3MDNkMzVlZWYwNDNkYzA5NWE3NjJiIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1','Webkul\\Product\\Models\\Product',17,NULL,NULL,1,'2025-09-10 15:34:10','2025-09-10 15:34:10'),(27,'GET','[]','http://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-11 05:10:36','2025-09-11 05:10:36'),(28,'GET','[]','http://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"92ba74d78e1b\"],\"x-forwarded-proto\":[\"http\"],\"x-forwarded-port\":[\"80\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlJ4VmlncXZ0SWZubDFkRkYyS2FJREE9PSIsInZhbHVlIjoiektTQjV6RUVyVXh4ZlhpTHA3MHFHOUZuL2VhMGFKMnlrSStXTXFDTldncXIvcnJTKzA2Qkx0anNNY0VOd1JsK1UwZE0yNWpPZWxvS05vd0Y3aFlmb1pqeU9WdnpqYktEd0FoZkE3N3BJcUxsWXBqM2ZpTzJka0VPY244dTBrUnYiLCJtYWMiOiJmMDk0OWViZmY1NDhkNjBhOTAyYWFlNzI4MjNhYTViMjZiNThkZjA1MmNiZDI4NzA1ZjM4YjFiY2RhNjE1OGFmIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IklhYXQvNm54b01CQjlIU2xib2FnZVE9PSIsInZhbHVlIjoieWRaaW1KRUZjMHF5KzZPa29nTllFR24yR2xDcHdwU0ZZZi9EOStVZzFwaG9wRmJvdEFoMzJ5ZFpTWTdUcmQ3VVJLcmtTWHBvazhnUUpVTlJWRlNGemI1VTJqbUtNekI0YjdmVzZpVmxoS0RIeEtHenlHUllTeWFFeGk1UTRqMEMiLCJtYWMiOiJiMjQ0MmRiMDI1ZTg0ZjhlZTM2M2QxZTQwNDc1OTdiZDA5ODgxNTlhMzJmYTQ0YWFhY2ZkMzcxNzlkZGU3NTRjIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-11 05:33:14','2025-09-11 05:33:14'),(29,'HEAD','[]','http://localhost:3000',NULL,'[]','curl/8.4.0','{\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"localhost:3000\"]}','','','','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-11 09:43:48','2025-09-11 09:43:48'),(30,'HEAD','[]','http://lamgame.localhost:3000',NULL,'[]','curl/8.4.0','{\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost:3000\"]}','','','','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-11 09:46:00','2025-09-11 09:46:00'),(31,'GET','[]','http://lamgame.localhost:3000',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"sec-fetch-dest\":[\"document\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-site\":[\"none\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"connection\":[\"keep-alive\"],\"host\":[\"lamgame.localhost:3000\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-11 09:46:46','2025-09-11 09:46:46'),(32,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"172.19.0.1\"],\"x-forwarded-server\":[\"9883bea863f4\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"172.19.0.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InN2Qmd5VFdkdEhVcXJHSkd3VUhUS2c9PSIsInZhbHVlIjoiTWlHSFRDVmNHYkRCaERNRnU5WGJkaWhVM0lCVmxFeEl1SUEvTmx1aHpBdTJLcDVNbE9ZRjdkVzZHME8zKzg3UmtVSkVKSDFlU3lZa1RSSE1uWGtwekhDc3dkYnRuSkpvTjlXbHlUamlGQjVjTDR1TGM2RzRZdzlCQUVKcitYY1IiLCJtYWMiOiJhZTEwODI0MDhkYjc2NTNjZTExOGY0NGJhM2JlYjY4YjExYmQ4ZGZjZWU2NzU5MDZmNDQxMGQyNTI2M2E1MWZmIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6InhQa3ZvNTBzRkdydXBQdVMyVFY1SVE9PSIsInZhbHVlIjoiVG5NUUxQS0RJYUhXMVU3ZHdXMGNRTDVDU1V4ZDhoQkYwbjJUb2xxUldlQVE2YTRoMFNickRSMjJSZ3ZZY0FQbVVvaTBWazlyVWZ0R3lPTmNkdkZhOHlQaTVVTzFrVGtnanF2bjJTSTB1TzJQNFBSeUptaDBFaTV5NEJZT2U0eUkiLCJtYWMiOiI0NjhjNGY4YWIyMmQzMDdiMDQ2NTg1ZmEwYTNkYjVlZGNhYTdhZGNiZGM1YTQyMGYwZjU2YTkwNDE2N2I5N2I3IiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','172.19.0.1',NULL,NULL,NULL,NULL,1,'2025-09-12 03:38:34','2025-09-12 03:38:34'),(33,'HEAD','[]','https://lamgame.localhost',NULL,'[]','curl/8.4.0','{\"x-real-ip\":[\"172.19.0.1\"],\"x-forwarded-server\":[\"9883bea863f4\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"172.19.0.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','172.19.0.1',NULL,NULL,NULL,NULL,1,'2025-09-12 04:32:51','2025-09-12 04:32:51'),(34,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36','{\"x-real-ip\":[\"172.19.0.1\"],\"x-forwarded-server\":[\"9883bea863f4\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"172.19.0.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Not;A=Brand\\\";v=\\\"99\\\", \\\"Google Chrome\\\";v=\\\"139\\\", \\\"Chromium\\\";v=\\\"139\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6ImsybDNIekJrL1Q2aWI1Y2w3cnF5RUE9PSIsInZhbHVlIjoiWkhXeExtMU9mdm1SVWdvdG1RYXVkTEg0OVlFMTIzVmNETjU4UDUvZFNBZDBsbmFJREN5UzRuMXZZSkhhbXBDbUJCeGtuVk9wWnJ3bm1CU3QzdU5IU2J6d0svcjczWGZ1Rkp0NTZXdXZvVXh4MHNUcDlRTE1TSlhZYlRWOEsyZVEiLCJtYWMiOiJiYzE1YjQ0MGJhZjgwNmZlYWY2OTFiNjI3ZTkxYmRhMzg5OTUwMzhiNmQ1N2ViMGJiYTY3YTAxNzAzYjE4OGJhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Ii8yVTNsL3ZkVTFib1dTaHd6TGtsK1E9PSIsInZhbHVlIjoidWJlc3VDemNYRzNSQ3NGdTVQcXY4RVBWbmtZRnNjdTkvR1lWSUtnRTBFZW1sWGdNTGFzaTVaNVVKWTFyRmRLQ3NNM3hzNXQ4WnEySzdkSHYxdmxVcmd5Vzl5Tm1BamNlVldxSDN6aHVsN1NWMGt4Qy9lc0RvY1FyaVNMU2RidW4iLCJtYWMiOiJjZTI1ZWU3MmNmN2NkNDdlZjBjM2M4MGQyMjY1OTZhMWZkMTEwYzliYjNhOTJjOTg2NGVhMDVlNWEwMzE1NTIzIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/139.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','172.19.0.1',NULL,NULL,NULL,NULL,1,'2025-09-12 08:02:40','2025-09-12 08:02:40'),(35,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56b07bab7078\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Im4wWFpMRTFUNWNHS2RNNkwxdllDZ1E9PSIsInZhbHVlIjoiemQ4MFU1TC8weEFscEdTMVJYSVFuL0U1d2ZkNHVRc3NYRW0rNG8vM1BQQ001cVZyWkNaYW1zVG9hZmN6R1V0MTdObW04OXlDcFp2TEVBM3IvMzFnWEJ3UjZMZG5VV01xckkvdmRzNEFQb2lrb1dnVDZmb0xRcUVZdkdyZDhGa3giLCJtYWMiOiI4NGU3YzM4OTFhYmY5YjBlYjkyYTlkZTEwYTgyNjc3NDYwYjI5NDY2MTUzZjg2MmEzMjQ4ZGI4MjFmMGNkYzhlIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Ijk5MFZxRXMrd2k5UDM0Lzc4MXhBZkE9PSIsInZhbHVlIjoiNGw1MUtrbEJWbGtGdmFPLzluSW5aUE1Fa3Z2UlFSOVdQOUhVbm54dzJMK3lwLzhhM3EvMDJ0b0s5dWJuZUNoWnFrU3BIYzJudDB0NVhEalZKSDBLek5aWXlscGJNcGRjT1J0L0xGMFNpbGRhTXBxZlV4a0RvY2JlcDNOWXlPb0IiLCJtYWMiOiI5Nzg3MDBmZjk3YjlhMzM2N2Q5YzhiNzU0ZDg0MWRlY2M1YmIyZDA0ZjRjZGY3MjdmYjJkMWM3YzFiMzQxN2MyIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-12 13:16:41','2025-09-12 13:16:41'),(36,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56b07bab7078\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlQ0MWh3aVIwRHcwTXpzUzBUTmFEREE9PSIsInZhbHVlIjoiUndpRUlXa3NuUDlvd3ZMdnNOT2VUZWdWSTVMcUNyNzQ5STFBcTJ5Rm5rbVpGY0h5UG9GZkx2QlBUaGRjeld6RGpUd0JYNERZS1RYN3FIVUxqLytxbGwrcGhPZmNtNHZmMDhhL3lKSFpmV2E0WWFPREFkOEc5b1VuMDNvbzcxaEgiLCJtYWMiOiI0ODRmY2YxMWZlZDc4ZGM3ODViMzA1ZWM2NzVhNjE3ZDJhOGM0NzM2YjRhMGM5YjA5MGU4MGQzODc4YWE4ZTE2IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6ImFFTjBVbE0wdkxnd3pzVVllbWwrdWc9PSIsInZhbHVlIjoiNlU0N0hTcEdRZWNaQzFGU3NjVTVlNTkwdkl6SXlmMXJxY0tZTEJzeEViYm44R2lmckx2K1JYYndYU1NyUFp2aUhBcFgxYklWV0lPOSsybjRpZW1Hc2MxaEE0dG91Ni9IL3JXQUY1SXBKL1hWQTNPajArUmh1Q2c0V2kzR3JwbXYiLCJtYWMiOiJkYzc4NzIxYmFlN2UyYWQzMGQ5MGNkNDZhODliYzk4ODllNzcxOWZkZTc1NzE2ZGE1NTllM2I1YmQzZDJjYWY2IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-12 13:29:26','2025-09-12 13:29:26'),(37,'GET','[]','https://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56b07bab7078\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"priority\":[\"u=4, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Iit1TnlVK0hVUVhNb0ZPTENjV2crcHc9PSIsInZhbHVlIjoiMlJ4MEFLRTI2YmwwRDRsVFdvcXBzS3daVkk2b0QzeEJCelVRWStPNExnL1pqZjRYQ3ZiZHIxSFhUWDBsYjk4RjJEazV5N0haRW9HSHl0VXhoRWgvdmNTVGhDMDIrZDluZ0xsTUF4VmtWUVVvcEJrT1JPZTZJNERReEYxMjg5eGwiLCJtYWMiOiI1Mjc3ZmQ4MmRhMDdlZTkwOGU4NWI3NGYxNzkzMzBlYmIwZWVkZTI4YjBhYjMwOGJlZjUzYmU3ZmZjNmQ2MWFhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjEzYm15eHVZWExnbW9tdityNWd4R2c9PSIsInZhbHVlIjoiMFNNViswejFSNlJoTUF1ZDA1eEo5NklhR3J1MmlKeURLSnpZSDN0b2p2cmJZL1ZiZTRWZVdNZ1hWOHBaUXlzSncvZEl4TFNXamsvVWdIUnZrUDU0NzlKUm9GOVhJTXFndktRNDdYRWdpbVQ0eFNGUjhkRWhWQ2ZsUEwwYmhnS3QiLCJtYWMiOiI5MDg0YTk3NjhmNTNiYjQ5NzNmNTliMzFmZTIzYWZlZGYzODViMzY2NjQ0MmJlMTA0M2FlOTAyNGI4YmRlZmY1IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-12 13:34:40','2025-09-12 13:34:40'),(38,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"172.19.0.1\"],\"x-forwarded-server\":[\"56b07bab7078\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"172.19.0.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','172.19.0.1',NULL,NULL,NULL,NULL,1,'2025-09-14 07:21:41','2025-09-14 07:21:41'),(39,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-17 05:16:57','2025-09-17 05:16:57'),(40,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlVGN0s1SkJ0eDZoaUVpMzlHWUdCNmc9PSIsInZhbHVlIjoiZUFPRXpCb0MybjBzVUdjU0dudW5oOXZwaHZPakNhZnVMWGQvSC8xSUFpUjBoZDBZZEF2eEwxaW5ZMm14aG9PMnF2VVJKdmNkMm5wVkhWWkt4NDlJOHF1ZjgvM25iTHZVYUtoakhucTZoRWI0WGFmNG5MTGhyVXBycTVWaitvNG8iLCJtYWMiOiJhYTQ1M2MxZjBjOWUyOTdlOTg3Yzg0MjJlZDEwMGNiODY2MDBlZTVhNThmYzA3MzNhYjc3OWZmMGU0NjIwY2M0IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6InNXeFhLTEEwc2lZcS9VQi93Sk15VUE9PSIsInZhbHVlIjoiaGtkM3NIR0FaSGJqSFZNTnNGRGxqSDNhR2I4eGc0SnpVOXpkbHcvbXg1cVByMmZzdTcxU3IyK1lnOXk2ZkJ1aktVNk1DaUdzbmQrclVTU2paazFJd1JLejlDbG1QMSs3YUlFY0lYL1k2cTFpRC9IcE92TjhsQ3lQYTk4RlFIVWgiLCJtYWMiOiJmN2NhZGNhNmU0ZmM0ODRmMDNjYjdhY2QyNTQ2NTE4YmYzN2YyMGFmMjAyODk4Y2MwMWM3OTkzMDJjMGZkMWU1IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-17 08:00:34','2025-09-17 08:00:34'),(41,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-18 05:10:34','2025-09-18 05:10:34'),(42,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IklPNitpR1FRRmg3OEdTNVFvTkg4Zmc9PSIsInZhbHVlIjoiUEFVM245VmRhYlAvWkg2WHEvRll0VmpwRGhkVmpmK3l0bWt5SlEzOEpoKzNxVTQ4UEsvd3hrTkdIblFJVU9wRjlXaG5qOTl1SVJobkRwS0YxZGZRKzBxeXhMdzZ5MjQrY0VjczZRajhXSXY2eSsyRytDeHd5dW1TcmxUcC8vclciLCJtYWMiOiJjODNiZmY5YzUwYjg2NzhjNTYzZWZkNTFmZWQ0ODA2MTgwOWEzNjhmZjUxYWFlZWYxZTc4ODM0YWM4OWU3YmFkIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6InozQ1RFQ002NDJzcXFIUFZhbko4N3c9PSIsInZhbHVlIjoiWTZIOEM4MXo2WnpNYnZJcVZQZHFRQk5ncTA5cWtGUWFBTkpRTWZvZVBsTG5IM2swbG5XeGlXRHpISUxnb0g1TE1JQUFRMW5EQkNKK01jUVFrWTRFcmJndkFLekJHZ2dwOWpXWkh4WFBVaWFHYTJNNnEwa3V0OGpTZCtHYnJJTzkiLCJtYWMiOiJkOWQwZTk4NjdhNGMyMjViZjVjOWJiY2ZjMWUyMjIwMDM5MzBmNzBlNzc0NDRkOWRkMTNjN2E5MjA2ZGRhNTJkIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-18 05:10:50','2025-09-18 05:10:50'),(43,'GET','[]','https://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"151.101.194.132\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"151.101.194.132\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"priority\":[\"u=4, i\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Ijg1Mklrd3VjSGZyVUQ4MG54SXdydmc9PSIsInZhbHVlIjoidjhJM3ZPT1VwQXRQQ2xrZ3B2ZTZMTWR2N08zMlE3VWhXOStGOVBwa2xwWWg4U0xxelJtYlp4WEk1Qm52SjdtSjJWdHk3eml1OEtObXRiRTYwNzFKcmZpVitqdS9rVkZEemcyc3Y3QUpXNGp0R1J6LytjQkVMQ3YvRE92R1dQK1oiLCJtYWMiOiI0MjQyMWI3ZDUxNzMwNTNiMjk2ZDY0NTg2MGY1MWVjOWYzZDFkNjk2YzYyZDYxYWVhOWZiMjJkNGE0N2YyYTVhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6ImFjcm91TDIzamRLcWZCMXhqS0c0bWc9PSIsInZhbHVlIjoiZ2oyUGdWazc0c0UvYWcxVWNrdVkrNlJxUVNTVFdVZGpnMXFHNk1ZRkQ5WjBEc0wxdmRkTmR6ckZiRGpvL3dNQ2dEL21yeXFpODY4U0ltcDQ1c1UrbzNBZHg0eHF2TnhIQjh2MkgvSVJBYUc5TGpCZlJlR3kvcmRVeUYzTElYZVciLCJtYWMiOiI4NjliN2FjNGZlMmNiOTJlNDI0MzllOTkwNGRiNjU4NjM1OGQ2NGY2YTc0ZmJiYmI4YWQ0MDQ2ZTg5MTVhN2RlIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','151.101.194.132',NULL,NULL,NULL,NULL,1,'2025-09-18 05:53:58','2025-09-18 05:53:58'),(44,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlNHRTdoN3VVbjdRZTh0Qk9HSTdGL0E9PSIsInZhbHVlIjoidWFzUUo5Zmt6QkcrbWFNRkZESUp0QVc3eTZ2TEVCMlpzT1FPSFh6NUsvdS8yYXZpdzFYVzRPcEF3TFBleG5mQ2p6VzQvRXZGYUdlRmdheTVuenVnWXE3TVNaTjVpMkdZVjd6eS9CWWlMSzBlMG1qZWFQKzdEMzk4ajZUNXBCK2kiLCJtYWMiOiI4MzJhMDFjYWVmOWZmZjU5MDc4ODJmZGY5ODZiNDA5Y2NlMWYxMDBlNzM2NjE3MGY1ZGI4YjEzZDdkYThlMzdmIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Ild6L0N1S3RPMzFZMXA1RVArNXRDV3c9PSIsInZhbHVlIjoiVzJiREFFMkNQdWxiN0QySStjb0prQXB5WmwzcjIwU3JyTUZpNno3NjdHZzZsOE1BWkVIOTY0R1VvR0s5VzVqWmVJNlFTMkNmWWxjSUlZVTU1Y2RNajVhVjZ4SkgvUllCbmlnL2hZNDJKZCtORTdaZk1OMUdicGxIenZiek5kdlkiLCJtYWMiOiJmNTk5NDdhZTk5ZTYxYmIzNzI2YWNhM2VlM2ZiNzA0ZTIxZmM2MjYxZTFiYTAxYmIwYzNhOGNmZjI1MWEwMzRiIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-18 07:28:40','2025-09-18 07:28:40'),(45,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InRSZ2x2OHZnRDIzTVNDMXdldElXOGc9PSIsInZhbHVlIjoiN0ZIaUh0OThiMm8xUndOSGNxVEhoTUdPVG1jOXVqR2JJVzlFcW54NDZzNG1pR3FxcHdJRXQ1UlEwM0dhVXVtNnhXQWliaE8xaVBMTk5EeFEvZGZJL1FtZ2prSEEvMkZaTGs1NkhUa3AwRExiekNGMi9EL2tqaTgwQ2tCYWpScDMiLCJtYWMiOiJmNTIzYTY4MzFjYzY2OTk1OTIxMmI4NmQ0MjJmMTNjODViMmE0YjQxNWEzNTc4MWRhN2E4NzRjZjg5MWM0NzM3IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6InAwNU5jQzNUYkNYSUdKUGFYM1dTUmc9PSIsInZhbHVlIjoiV0lvTkFoQ3pJRHcxaTBuWktIM2VteCtFM21RUjFIY2xXeUU5c2kvYit4V3BQeDhNRitMUlFQOEx0c1ZtSS9JRG53QXZqTjVTNzJldzNHcTBVL2tjWmVHeUlXRXFOZ2lqYk53V3cvSUI4VisxTjdyS0VHYng5TDNKL3FPNmNHSkoiLCJtYWMiOiJkMTg3MzE4NDcxMDRkMDIyYTNmZjM2ODFiYjkzMTA1Y2U4MWYyYmIzMzY3YWYwMmViMzUyYThlZmNjZTliNDYwIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-18 13:00:36','2025-09-18 13:00:36'),(46,'GET','[]','https://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"priority\":[\"u=4, i\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InZ0dFhtRUxPY2s3K1doemgxSW9sZmc9PSIsInZhbHVlIjoicEU4ZEFSblRQOHlUZ25mZzF0bUpRQ3dsa1BDUXNJYUhGU3JrNlo3djZuOEZFVGJqaXlkbVVINHhkR2FoSFZGSTFYWCtYOEIzQldwVVR5RDB6TTU0M2IzSXo4cGVQRGE3Y3hBdlNldVNXUmEzOFFYQ0xPZmp6akM4Z3hLZnNYSlUiLCJtYWMiOiJkMGFmY2U4YTVmMThjOWEzYWEyOTIxY2U2ZTk0MGYxZjg1MGY4NTBlOTU2MDRiMjc3Y2IyNTAyNjY3ZjAyYjU0IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IkJuK1FXanZVT2JKSWZ2bnJJUS90WXc9PSIsInZhbHVlIjoienc0U211UHY0REhjdlhjYnFrdURsZEdrblUwTU1FWFdubUxIb0F1Z3NtS0pydjZDOVcxRmd2ZmxaV1Bpd0g0NHB3d2hnM1BVdWJRUmNKV3lXcG9rblRzQ2R3cGs1eTFkbGpLWHpRU1NTcWN3eWtsNStPeU00ZnRITGNWNy9EY2ciLCJtYWMiOiI1NDIzNWVmZmI5NGE5ZmJlMjcyYTE4ZDRiMzE5YzMwM2MwZjM0ZGU1NDNlMDY2NTNlNDE5YTMxYzAxYjFiNDVhIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-18 13:01:37','2025-09-18 13:01:37'),(47,'GET','[]','https://lamgame.localhost//storage//magic-ai//optimized//2025//09//18//blog-medium-1758208602-x0r2um4z.webp',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Ii9rOUM1RW5ESS9CZWQ5cmJ6UzlheWc9PSIsInZhbHVlIjoiZjZzajI5MFVxKzJXWTdUQnlvZzJzcnVLeVdleXpRRTRyR3g0Y2hWTGFLMTFhSUxSQzhGWEtrdlBsbUJyczFJM29LOHpSTFpGd1E2UXJkVjZod2ltR1Y5TzRYMTdROXlTRWcrNWttY1JHUzd0WHRvMzFycDhySDVQc0Jsa2VIdVYiLCJtYWMiOiI5NjhmNGNkNzQ2YWFhMzM4YzM1MGY5NGU3YzE3YjMzZDQyYWJiY2JlN2NiMjAxM2VjNWI3YzI3NDQwZTM0YTliIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Im9ybXMvRjdWSy9nYUh4aEtKSGhWOUE9PSIsInZhbHVlIjoicWh0MVBsNFN4aGtxU0p3QjJOMU1LelBvT3dEd2R1aEZZa2hnMTV0RS9CdnA4d0h6NTFRQUR5UFpSQ1VCLzVQT2NUd2h5Wm1Pblg3UXVIUEZyL2d4YzFLekgxVmVEYWU1T3dudlJ3dnI2cFh6RHZxUFA1UFh6dTV5SjFLV3YySXoiLCJtYWMiOiI3Y2VmNGFkYjBiZTM3YjE2N2MyNDdmYmQxMGRlZGNhYzRkYWYzMDI5ZDJiYjBkNjE2YzllYWNlMjdiOGEwZjZjIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-18 15:17:05','2025-09-18 15:17:05'),(48,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkFUMVdRRTNRaURMNmpFdEJGRzFtM2c9PSIsInZhbHVlIjoiajh5RDhJQThaT2phTmFudmowMVhhTWJYOUVYUjJKQVo5UmphalpvNjc1VGZ1aEwyWFNkSUtDN00wdXIzZkZ0Z3NkQmp2V0wzek1rR3pVYk5mNEFjVUpTL2NlUllkR3BIZXIyRmdNVUMrOHl5ZTVuQzZENTZhaGpzRWl4VEw4ZVEiLCJtYWMiOiI2ZmVkMzU1MmJmNTIyNjgyZTdmMDAxZjFiMDQ1N2I5MmUxZjljYWE1MzQ5MzcwZDViMGJlZTk4ZWU0ODQyMjBkIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IktGSUVmejBwWEl5ZTVTU1pvL3UzTFE9PSIsInZhbHVlIjoicmR2SGl5UEgzUkRYaWNEZHhEdGwzbi9BdXVCM050Z05ZckhZSUpEQUpoOHd2dW5VYlAwdXhhdmhXS1hCTWRrZzRBL1hrTUQ3em1uN3J6aWRFamdXRHFsNFRDekI0bEFnY3ViTDNIVEJCK2JOMnRmN1NYcTg5Vzh1WW56VG8zcjAiLCJtYWMiOiI3NGRjNGE0MDYxN2EwZTMyM2E0ZmM5NmU5NzgwNTgzMDdiNWM4ZWU2YjE2MWY1YjhmMDFmMjI0NzY2MjY3OTE2IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-19 00:10:37','2025-09-19 00:10:37'),(49,'GET','[]','https://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"priority\":[\"u=4, i\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IjBRS2FJVDVYSE55djlsbmozRkgrWVE9PSIsInZhbHVlIjoiSGgvTmtkRGplL2s4RG4yL2xVVzg1QXBJWkFqdzJ0aFkyT2xLYVZkOUhVbGtGeEx0TFUzNThLamxpaXpha3I1V1Rnb1B6bHlveUNKL3B2OEJsYzZKUzhuR0dxM0xWRVFTNlZvcFp6clB3RFBETnBWeitlTnl0SldrS2E5Z29EK3YiLCJtYWMiOiI3NzcyMDBkNmFkOTQ1MjhhZDVlNWIxMTI0ZDliNTk2Y2Y1MTA5NWE1YzM3ODk2ZTk1NTA0ZWI4MzhmY2Y4NTVkIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IldsYldCemd0TjZ1RmhyTGNrTy9HTVE9PSIsInZhbHVlIjoibEZzOGF4UVMrejRnOU9GUWUwK01NK3ZDL0U0dHd0OGd0ZzFzL0FSMEZtRndMd3NGOXA3dFdQcERZUWdWSDRKT3lDcHVSNE5sbGpYMElDQ012UFdTMmhIdmFnNjlJZ0Z5T0M1ZlJkS1EwZitGMlAwVVZ6dVg0TXg0MlRoV2VZbUciLCJtYWMiOiI1NWNkNDNjMWYyOTk4Mjc4OTRjMjUyZWNmMWI0MGY2OGMyODNjODQyMzgzNjA4OGY3YjNjZGYzMzU0YWIwOGJlIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-19 00:40:46','2025-09-19 00:40:46'),(50,'HEAD','[]','https://lamgame.localhost',NULL,'[]','curl/8.4.0','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"56c26c5df1f9\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-19 05:33:21','2025-09-19 05:33:21'),(51,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlliQ09zWDVNN3dSZHQ5NUdHNjI0ZVE9PSIsInZhbHVlIjoiOFdlMVk0WE5KUEcrRjBncnBDK3hLNW03NGFxa1lielY4ZkpqTVhHTko2ckhveEd4bmZGQktsU3NEeDh5d1RTTDJNODdSNVhLTWN1c3Z0Y1lUSGVUYTVyekdwT2k2ME40cjFFVm9lbFQwM3Jtb3NldXdVdzYxS0ErUVlKbkZvbWciLCJtYWMiOiI2Njc0OWVmY2ZmZGU2ZmZmYWMxMTMwYmQyMDdiNDZmNGQzMzYzMjNlNGNmZDQ1ZGFlNzk1M2YzMzFjYjI2NWYxIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IjRmQXBDbHJjNitLREZBd29XYlB5TVE9PSIsInZhbHVlIjoiQXhsRDc3NmVwcU5DWmU2Sm9hODZ2cTJhNG5SOHlYQUVNQ3ZWQWR1UjRHYVRUVW8wWGd5ZVN6RlUwKytqVG9BcXJUNFZJdCttYnJaTVJsekdoYlNrbDRyekdTcVBXMC9zRzRBZEF2VUUzWGRKL3U0RmQ4NWJ1eGdQN2ZzMjJVWXMiLCJtYWMiOiI4ZjU0ZGRiNzUxZDFlZjY3MTFjOGUzMzZmMTA2MmM2NGM1ZGZhYjhhOWZlMDdmOTVmYTgzMTg3MzdhY2U0NjAzIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-19 12:37:02','2025-09-19 12:37:02'),(52,'GET','[]','https://lamgame.localhost/blog-images/77/VLTWKOswwl3xKrwwB6mwlMdszC60myaJZ6LrvNIc.webp',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InhnM25BZDBaRllVb2cwaWpLWXhyNEE9PSIsInZhbHVlIjoiU3UxZ05KaS81eThIQW54am9kN0VtMmxzZm1QTGRPUFBHZ0YrSGNZT2hWcGNjLzRDdjhaQW1sdGhRcEIvYmxwc3h1aDBrN1JUa1ozVGRCSjcxVk1Mb2dQZ1NUNUtOMHdrYWNrc3FWYWxWcnR4Y1lxZlE5WURRYlZwWWVBcTdlWisiLCJtYWMiOiI1ZjcwZmVhYmJhNjBkYmRmM2I0NWQ2NTMwZTgyYjcyMTBiNDc5MGNkYjg4MTI3ODI2OWNmMDRkODk3NTAwOWVlIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IkViekRXSWNIRElVc21DYWdiVUxCa2c9PSIsInZhbHVlIjoiUVpsYlBKMGE3SGdLVWNjUUplRzEyN1ZWbUdEWkVrTi8zMEc4WWFlZExyeG1PUWtCSDhyUXQ3byt2NmtoY3FmdTV1b1ltSjV2cndjcWNuVG01REFaTmJnREphOE4rWkhOYmNWbVJNeURsNnB0RFVuaUYrN2pQL1E2dXRZUTZib2MiLCJtYWMiOiI3MTI3NDkyYmIwMDhkMmM1YWJiYThhZmEwYTg3NjQyOGZjMjFmZmMyM2ZhMTdhNjI3MzA5Y2U1MDFiMDQyMzU5IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-19 13:34:21','2025-09-19 13:34:21'),(53,'GET','[]','https://lamgame.localhost/themes/default/assets/css/admin.css','https://lamgame.localhost/admin/login','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"style\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/admin\\/login\"],\"priority\":[\"u=0\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Im56SDBsNk5rTTc2Y3FwRVdwYk9CaVE9PSIsInZhbHVlIjoiUlhtbGhFN3U5RHNQKzlhNWl6a3ZlVlZNeGh5QTZReVp5OGpJZU0yOVNHT01MUld3N2NacGFlN2ZPMllKckJSRGcwbFpsemRETmlJaVlmbXVRa0lIa0lGTmdyR3c0anBKRXlyVE5pVGdRWHhsU3Y2V2hBY01qOUJWL1VnYkljTWwiLCJtYWMiOiI0MDVkYWM2ZWE0ZDFmOWFkMDgzMTNlMWU0N2UwN2U4NWIwMGUwNWRjYjBiNWEzMjI0MmQyMTI4OTU3ZjVlZGRhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6InphczV5dUdqNFNlNXhYeU1XNFEzR3c9PSIsInZhbHVlIjoiOU9UalA0ZVFRVHlZNkNOZ25UZkdhYTdreGJzWTl5Q0NOS09SaXVQNUpBQjR5VlZ5WmZRTGFKRGs0M0pwWTg5ZUdwNi9lWVI1eFVROVdacy9qalNJbWdYVXFCYUN2eGhHeEZjTERKTVloS0xBM1NHUzZ0aHJjNXNsd1lMaEc5dmoiLCJtYWMiOiIwMTc3NGE3OWZlMGY2ZGUzYjA1Yjk3MjEwMzA2MmI2YmQzZjhmM2QwMDIxNjg0MGY2ZmY1MDU0ZjA4YjBmZjQ3IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/css,*\\/*;q=0.1\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:17:38','2025-09-20 04:17:38'),(54,'GET','[]','https://lamgame.localhost/blog-images/77/VLTWKOswwl3xKrwwB6mwlMdszC60myaJZ6LrvNIc.webp',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlZ4YnZkQ1A1OTl4VElEbWlEVUNIbmc9PSIsInZhbHVlIjoiV04xZjVJZTFJd3lqM0FQYWs2cEVrZXAvRkdhcDJiU2FnUEVLMGVaRGgxVEtQZlpBbWhURExJQUNZenQrYmlhL2FqQm1jUy80UVdmS0psWHBsVTZYcTRrMlZGUDBPTHFlWEdvVXh4OTBnWFVKd1NQRXFNb1RYQTV2aEF1NEdtYlYiLCJtYWMiOiJiN2Y2Nzc0ZmI1MzRiZjRiZTdmNDZlNDU1YzAwNWJiMjU3Y2ZkM2FkYTUyZmYwOTljZTg4NGZkNTVlMzg2NDZhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6InlKL0M1ZWZRM1VpV3NSa0ozOVo2S0E9PSIsInZhbHVlIjoibEw4YmYvbjBBRFhNWndoYXM3VDNPRGQycmkyZ012YUlOazdVRmlSOUJldUFVYjdYdmV3bHB0Y1MyNW00emZGV3pkMDBxaW43SkdCcE1RVTRVZy9CMVRoL0RqdytkelVTaFFudm5EN3l4bjdHZ01uYVphbVdaeWNBdnBoMW5XYmEiLCJtYWMiOiJjY2ZhM2M2Mzg5ZDdhNTJlNTE0YmM4MzViMWU2M2U5OTEyYzYwZGQyMzI0YjRlY2YxNjUwOGU3OTAyZDBlYWI4IiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:44:14','2025-09-20 04:44:14'),(55,'GET','[]','https://lamgame.localhost/blogs/77/VLTWKOswwl3xKrwwB6mwlMdszC60myaJZ6LrvNIc.webp',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IllqbytKSUlOUnhhS25xT0tBV0J3Z1E9PSIsInZhbHVlIjoiOHNEVFFQNE1UcnFUdEhtdlNuWkFLdGd4enFWVUovWXQwL0U0L3d4bFFXVTlWSEdTUWs4VzdjTHJVcUlibE1GNm5Eb3JSRUxwMmpuK1pEUzhqUFcvb2FxZUJvV0hQNWNYeWE4bWxGR3BibnVzaXRGdFVjcVRzT3lwN1pDM0haL1ciLCJtYWMiOiIzOGZhM2FmMzcwY2MzNWJhMmEwMGE1ZDYzMzMwZmExYmFkOGJhYTFhNjkzNzU2YzI3MjgzZmYwZWY2NTdmYmM3IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IkN3cXhYR2o2N0RHZEV1UHg3QTY2Tmc9PSIsInZhbHVlIjoiNDJsUTJ3Q0x6cnNlWmxaTGhNUk5SK0FpR0xKN2RRN2loR0E5K2N1dHErdXNVT0k1RXJKNGJCOWE1b2tFSzBMU29BRU5RTFgzamZyTm1xcVdpcXZPejkvclhkNVJyZjk0UzVBdTFhVHVzUGpudUxCUVUzTVlxOEtmMyt4THBmTU4iLCJtYWMiOiIzZTkzMjlhZTM0OTAzNGY5MjY3ZjhhNzgxMTMyZGVkNmM3YzMxYzk4OWI3OWNhYTI3ZGFiODg2OWE4YTQ0YmMyIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:44:21','2025-09-20 04:44:21'),(56,'GET','[]','https://lamgame.localhost/blogs/77/7Hyb4ywWvebKX0e97cM0czKm1VHcKjpqHKWj50X2.webp',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IjBvR3FVdEVodnlLMVY0VDZiQlBaTmc9PSIsInZhbHVlIjoicjhjS0xEZmxVbW0xdVhrc1B6dGE2MHRweFNoWGlra3FodlprMDZVZG9uRGttVkpXMk5EaWVCclI0WDYxbVZjUkorNm8rNDN5UHZFcUZzTXpEWHZIcG9VdXA5ekpDQjUrN29tM2dRTmIxelVVYmxzS1ppSW8zbHlRTmdWZ296RFgiLCJtYWMiOiIyOTQ5ZWRlOTU3NmVmODg5YTBhNGI4YTFkMWUxYmYwYjIyNjAxZDc2Mzk5ZGRhNWM4ZjkwNGY5MDQ0NzA3YTliIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6ImxUYXFIdnhXak1NN2lPbGs0em5lWlE9PSIsInZhbHVlIjoiS2l6LzVHS3RQRXhyVS9DeVNhOG0vY0JWTEcwQ0VMRm9PdjRxQlZyNVlYMnRyVEJ5M0xXbHE5K2llNTMrb1hIRk9ZSTZVQnByektpUVBZK2hPYTFlYnZ2UWNSNk82YkUxc09sdzFTeEw4UTdqT2pNQ0NINGNKc2pCWlpyM1RuT1giLCJtYWMiOiIxZDA2ZGJkNGVlMmZkMjU5OTZhZWU5NmU1ZmI3NzdiNzM2YzE0YmRhMTQ2YzZhMGM2YTQwM2Q5NjEyODFjNDZkIiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:44:57','2025-09-20 04:44:57'),(57,'GET','[]','https://lamgame.localhost/app/blogs/77/7Hyb4ywWvebKX0e97cM0czKm1VHcKjpqHKWj50X2.webp',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InhlUmZEU0Vkc1IrRUVFTVBZYm5xV1E9PSIsInZhbHVlIjoiemZ3QW9WZ1hLTzVVMFFxRHQ2Y3RGSDNUWWJ0cVVheDluQnJQei9FUVVpOXhEbDFVKytTKzN0WG5BUXE1aHJXeGhCSmFXTkR3Z2tlOHp0SHE1c3ZLa2lKSnVRaDNzS1dYbkFUdHNNSmxveVY3ZHV3Z0k5Nzg4eTA3bVk1SkRyWloiLCJtYWMiOiJiYzliMzA3OWU1ZWY2MWE5YWZlMmFhMzRiYjllODUxMjI3MDcxMTdmOTBhZTM4MDg3Mzk1MWZlYmU0ODQ5OTRhIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Im9BcVJlOW1jMXk1WXhCUHdHTGJZY2c9PSIsInZhbHVlIjoicE9uRjBuM2ZXc2lwek5VSEpWOENjdWhWaUthRFl2dHJCaW1MVS92dzh2L2R3d005UzhmNUZFYy9wekdTbzlQV2RSbU1Zbmt2QXUyL21qSnJqZzBxb3hjUUFFQXExQ0d4WkVnQVhSM3FWZEx0K2I1cjhjVlFIOU5sRnZVTWRNN0EiLCJtYWMiOiIxMDA1ZjcyZTYwZjlhMDhjZTA0M2UzYTRjOWUyMGFmOTNhYWJhYzJmOTExMjlhNjg2NjU2NmY0YjQ5YjVmOTE4IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:45:39','2025-09-20 04:45:39'),(58,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6InhUUE5tbjk0SFVXUHZWUDJPTkNLZFE9PSIsInZhbHVlIjoiMGRNUEhoeDMxRFZtUnVlNnNTUlA5K1I5ODgwL2poUE5nald6L05rRWJES3ZhRWZLQlVFSkdRMVJlbStBaHBON3hSZzRDTzJqSmpKYjQyTjFjVkIxa1k3Y1FWUWh4Zy93Z0pBdjFCc1hoc1N2M0xaSzVtMXNFc2hhM1dWd29LY3IiLCJtYWMiOiI4MTYyM2VhYTBhZTliZTU1ODA5ODVlOGJhMDcwMjljNjE2NzAxN2U5ZTY4ZTE2MzI5NTJlNjFhMDdjNTA1YzRlIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Ik4yQlZuSnQ4eVRpYTNnTXdMcVNybWc9PSIsInZhbHVlIjoiVWFwcFVUUW1oUXgyRjFtT2xWcHAzUzZNMmtQMDZiY2RRelV5dW5qdnJXTGQ5MnV0NXgza0xSRGRkVm5nbkRNd2kyZFB3ZFgwSDFjL2V4SGZDYTZ5K2F4ckdLazZNaGZWcHUzczZoMkl2MXNOdU90aTI0RXhzVkpybzYyWkM1SlMiLCJtYWMiOiI0MjIyZWEwMWQyZjJhZjNhNDEyNTFiNDhiMGM4OGE4MGNiNmYwZWYwNDU2Y2Q2ZjYwNzM2NjhlYmUxZTc5ZTk4IiwidGFnIjoiIn0%3D\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:53:54','2025-09-20 04:53:54'),(59,'GET','[]','https://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"priority\":[\"u=4, i\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkdaaXQrOVVPVFBKWHNUYWVDVkVFaGc9PSIsInZhbHVlIjoiOGdrNWtyRFVFalkyNTJWYzZic3pjbkpXMkM1dHc5T3FBSlhLT2tuZ3l2VHNyNFZWaW52bDhZeWttYVZ6aGRrZ1ZlYmVoMkRCQ3F4SXVpU01BTkNjRkg2VjNabi9OQlJGdk5DY1JobHZxRlhaYlpnZGVRK0ZsRUNGUk0xajl0U0IiLCJtYWMiOiIyMzkxOTJmM2FjMTNjZDE1Y2RiNDhhZGVjOWIyMjlkMmQ1ZTJlMTA2NzdkMTY3YTNkMDY2NDFlNWZkN2JlZTk5IiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6Im9NRkpPZUw5VE1iZFAwUnN5M3Z2OWc9PSIsInZhbHVlIjoiTnFxa2NCcWVrTm5xaFE2bzNoRHUzd0hkbzB2Q2x3NUxsM1V6THJWN3hSNnd1N25OVlpGbm1RNmhaN1dTMVM4ajJkV29XaDBnNzdjWEkxNUZJczRrdFRaMDhFWjdzZ3VzdW5teWU2Yk9BZ1BCZTJDN05tUHhkZXNRS2Z6NHNLT1MiLCJtYWMiOiJjNGZhYWVhZWU3NGYyZDY1NzMzOTI3YTRkYzI2Yzk2ZDgzYjM3OGMxN2Y4ODU5NDMyNzM2MDkyOTNjNGZlYTdhIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-20 04:59:44','2025-09-20 04:59:44'),(60,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-21 06:29:19','2025-09-21 06:29:19'),(61,'GET','[]','https://lamgame.localhost/.well-known/appspecific/com.chrome.devtools.json',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"185.85.0.29\"],\"x-forwarded-server\":[\"1fa0baf7a661\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"185.85.0.29\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"no-cors\"],\"sec-fetch-dest\":[\"empty\"],\"priority\":[\"u=4, i\"],\"pragma\":[\"no-cache\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IlRwUURzR3hNbFluWk9rQXM0Z0NDanc9PSIsInZhbHVlIjoiRFBSRlVrR3JWV0tucXpjOVlaeTZFT01xNkpvVDVuajUvbmszOW1zeXI1Z1MyNERVM1JRYUVrR2VEZDJ1VlJlM2NBQzRkaFZ4ODhuR3FIOGxHK1ZsOERUa3IxaVJGTVg3N20xVis4TUhGb055bEF6aGhTWkx2bUtHb0RuZlJjenMiLCJtYWMiOiI5YzRjNjU5MjJlZmE3MDUzNWU1ZTM4NTFkMWIwMzNiNjlmNjFmZGE5ZmFmNDA2NTViODU4MzcyZGZmNzMyNTEyIiwidGFnIjoiIn0%3D; bagisto_e_commerce_session=eyJpdiI6IlVMZ1A2WlJhdjdaUm41QUFhU3ZKNVE9PSIsInZhbHVlIjoiUGsyWko1R3ZoUCt2ak5ySXVhL2RJSzI1YTZrclZidktlWmVUMzcwMk1FdUEvK1ZjZzJYQ0hIN0xNM3NPYzYyV3JpTUw4dkFpL2dSRWg5d3JmcVZnQzVpQWlzazNxeGMwaW5YN2dPMWtoZ2I3ZER1Mno1bjlmcmFoYnFlRDdRaFYiLCJtYWMiOiI4M2NhN2ZmZDI1ZGZhMGI1ZDUyOTEyZTY3ZTA4NDNiZDVjNTEzZjRlMjQwZGExMTUyMGY1YjAyNGZkYTc4MWNmIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"no-cache\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','185.85.0.29',NULL,NULL,NULL,NULL,1,'2025-09-21 06:38:17','2025-09-21 06:38:17'),(62,'HEAD','[]','https://lamgame.localhost',NULL,'[]','curl/8.4.0','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"accept\":[\"*\\/*\"],\"user-agent\":[\"curl\\/8.4.0\"],\"host\":[\"lamgame.localhost\"]}','','','','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-23 08:34:34','2025-09-23 08:34:34'),(63,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-23 08:34:35','2025-09-23 08:34:35'),(64,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/auth/register','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/auth\\/register\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6ImZYNWdIZmZ2d3hsT2xpZUY1Y1pFeEE9PSIsInZhbHVlIjoibUFHTEUvQWFYd09JdXVXNHVlVFh5VU5QVy9keUJyLy9XMXJGR0dxbWV1RWF6eVh1bVJmcE43Z1hVRGk2Zm5jWTEyKzB3TXV6WloyamR2TWRkTFpiZnhQSnRESTFWV0JERmxlNVlMb2w3Smt2bGtVenhnbVVCSFN6ZWRCNTNKclgiLCJtYWMiOiJkNzZjYzViMDhiNmIxOWFiYTI4YTA1MTEyNmZlODU1ODMzYmNjZmU0OWI5NmZjZmExNTllZWYxNDZiNGM0ZGM0IiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IitKWjVuei9TbUNMdmVud0FjSjRZTEE9PSIsInZhbHVlIjoiL2xBYVNPd3NJRWgrRFhpdGxHa21xYUlCeTBKYXd0SEh1WkU2TlZmMDlFWkluNGZ6bjFKSHF1MGRSa1BJTkJFaHhkcnFLV01YNm1IMjFMNzFoaVhDSHovaCtKYUl2ZHcwcFFrZXJNak9zeC9lWndqci9ROEkyN1E3Z0kzQ29tYkIiLCJtYWMiOiJlNGRiNzg4ZDhhMWNiNWNmMDc5YTAzZDQzMmI5NTc1ZmQzOWZiMjAwNjMyZWU4ODNkZjYyMjBiZjk0ZGU0OGEwIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,'Webkul\\Customer\\Models\\Customer',1,1,'2025-09-23 13:03:51','2025-09-23 13:03:51'),(65,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/auth/register','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/auth\\/register\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6ImxNSFFia1FTcUl0a01tcWpCVVpMb3c9PSIsInZhbHVlIjoiYjhtdm9ReWhMSkhoTk5CUjBndnJlbFhDV2I4T2VhUnZybDcvcWt0Q3FlSkduTWlxcHRRQkZLbkpTNVdGY2t3cGtIMnB0bHE0TmpTVnhBS1JEd0xEajdIQVllSzB1aU9BejlscDF5UDVrS0pZSzl6ZEJBNHZDZGthVFMyMlVlMmIiLCJtYWMiOiJiNDAwYzgwYmE0NjljNDdhNjA2MzQ2MDJiMWM3YzZmNjNjOTFhNGY0ZTQyMjYyNjgwMDZmZjk2OTE1Njk0ZDc1IiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IjkwTXRTeUdOZXBDby9DMkRYYjJocGc9PSIsInZhbHVlIjoiZWFHNHRYVnBjTFdxV1BudXN2ZktUcnRScGlsZDdsK3FPYk1yY05CQ0VXK2E4bGhrVnhnVkFOeDBXL0hqUnA2blBNS003eTYzSk5wOGpEZzVWbENKcjR2K3NXc001WVhBSlpQQ2NWQTZTVWFnTHFWZlNXQ0dKYkdlYVh3WmdQTC8iLCJtYWMiOiJmY2IwODMyMGNlZGZkZDAzZDNkNDFiMTE2MDdlYjZhOTg0YTEzZjIzMzRhNTNlNTBkMTZhZGIwN2NjZWVmMDMxIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,'Webkul\\Customer\\Models\\Customer',2,1,'2025-09-23 13:21:34','2025-09-23 13:21:34'),(66,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/auth/register','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/auth\\/register\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IkJKS3duUDhOWnNTU1BmR2cxZk1icXc9PSIsInZhbHVlIjoicE5YbjJRUUwvU0hsMUJLMmlxSVMzYmJSVWdpdjBHRHY3UFlYVjA5eGFiS1ZqZ0FDQU9vTHE3dVdPWFRYS0lmWXhGZWNoa1laUmFsd0c5eFZhaDRqOE1sV1NCLzE2ZWZyTVRxMStZQW5hdlVNamI5bkRGY2JsNWlmdzhQc2s2N1oiLCJtYWMiOiJjYzg2YzE0YjRjZjliZDlkZmMyZTUzMmRlZjY4YzM0NjFiNTE4MDE3ZWUxOTg5YTM4Mjc2YWVlMDg2NmVjYTdjIiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IjlrWlBJai9ZY1lnbSs1TXRsY0M5R0E9PSIsInZhbHVlIjoiNWhhdVhtRS9temJocGtOZ0JlTE14RjNlWWRxYjhMNE51THBQbFI4RHlUSkUvbkNqZWFUWG1xSmtvMmdqbXc2OHlsc3ZIYURHSDF0TVNKSEx1Q0IzVm1EQmtOUHAxZXgrdm84NGhzSmU4bkp5SXZuRWdJSVZ4dlZqbFViamFzblMiLCJtYWMiOiJlNjAzNGExYmRkZmMzODkxMzcwNTQyNzFmODc5NTNiNDEwYzRlNmE0N2VmNzA5NDkzMzk2ODAyZWRjYTE4YjI0IiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,'Webkul\\Customer\\Models\\Customer',3,1,'2025-09-23 13:36:45','2025-09-23 13:36:45'),(67,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/auth/register','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/auth\\/register\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Imx1bk95d0gzVjZweEt0NVJyVGdadlE9PSIsInZhbHVlIjoiM1o0Y2k3Y3JjRGlpeHFXMWlCaGJOcStWZmVPNCszbWpGbTMyMWozc0EzTTRYYTZ6QUdNRktBV25kT2wwb0FaV0tRcVZZbmdFNDhJME1tcFlBZnZsUmlTWWxlSE03SnlOTW1RLzlRZXhwRlZieXNsMU1LVUxiNDJRK2dYdXVqeG4iLCJtYWMiOiIwY2RkMDFhYTVlOWMyMDMyYTE0MTZhZjQ5MGFmODZkNWQ0N2QzNGY1NzUwMGRkOGNhODRlNGI2OWE0MTAyNjY1IiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IkJYMHhyN21FSm1wYVd1eldZN3hldVE9PSIsInZhbHVlIjoiZlE4N3dUZ2VhckhGQkxWUytrWTB3NTNpQzFXTUdvZjhFc0NUVW4zUDJqOWZKMmxBVEl3SDhET29GY0EzSlZBM2JYUjdHWXEyb1p0d3RJcVBnTDVQTDY2ZjE1M3o1Mlk2MzJVTGd6UmQ0bXlrMzBCaEgwMWU0bStxQzNTcFE4eU0iLCJtYWMiOiIwN2VhNDI3YWYxNGQ5NjM1Y2EwM2YwYTA0NDdhZTM2NjBjZjZiNDdmNzNiMmExZDY1N2I1YWJiNjQzYzhmMDZkIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,'Webkul\\Customer\\Models\\Customer',4,1,'2025-09-23 13:45:57','2025-09-23 13:45:57'),(68,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/auth/register','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/auth\\/register\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6IjhaY2VLRGo4NVRFOVBsRWNnaGdoeGc9PSIsInZhbHVlIjoiMGlWMHJ0eVQrME4wdmR0RWp1cTNvVjBPbnRjV2R2Y0NxUGdiM25CUkJMRUVDQml3Z2hIYTU0aXROakY1NFVDWUtXYU85MHFqblBSb1BFSklUekRSa2pOQkhXSWwwK2E0SzhSTUxKZ0tFL044ZnFVS0J1Z2JRWWMzQXBQSkpXclgiLCJtYWMiOiJlY2JiYjJhNzVkMjc3YTUwZjdmNmMyOGQ0YWI0MDU1ZGFhODc4ZmY3YjNiODYzOTE0OTcwOGU3ZDdhNWEwZTA3IiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IjY3U0FEd1plWnhGVy9sVHhoa0hPd3c9PSIsInZhbHVlIjoiRTg2Zm1QNGkyeExiUjVBZ2ozMVdqVW9pWmYxVzlmZDN5RkY5cEpwWjNWYWE3QkVzNTJLdGpTTjFqOXI0TElxN1FxMy93WTM3ZGtHR0krR3FVVHRaQkRIb2kyMkUwTS9rSzNOM0wzamVCLzhXaklTUFkxK2pDVGp2MkRQeUVwaDIiLCJtYWMiOiI1YTI4MWFlNzU3NTUzNGRkMmMxNmIwNDY5NDViYWVmNWMyNDc1YTE2NGU1MzU3M2E3ZWNhMTlhNzVlNGI0NTNiIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9,vi;q=0.8\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,'Webkul\\Customer\\Models\\Customer',5,1,'2025-09-23 15:09:11','2025-09-23 15:09:11'),(69,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/auth/register','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/auth\\/register\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Im9waytRRXJDWFFNQmFERmZHMGxpQXc9PSIsInZhbHVlIjoiaTljdlpJSkNqTmJheDlzSkRwNWpjWUNNeThNTnlxMGZpTk5rT0ZGWVVseTJsSUhxWGR6NXdETG5LTGZzblU4dzR0dDdhL1RJRjFxVHhvcWZnT0cwYVRMT3JsT1RUWlRpa3dwWkdGdVhBVk1wOFNhTHUvcXNPbS9yaW9QR1VpQS8iLCJtYWMiOiI4NzNkMTc1OGYwZWMyNTg4OWI0ZGQ1OTkwNzE0YzZjMDJiODEzNzczODYyM2M3M2RjMmQyZjRlMjJhMzYwY2I0IiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IjFPVlNrMFRvdXRiUWxQSXpHZ281eEE9PSIsInZhbHVlIjoidTVlYTdBdGt0N1g5SzBMbnNNeGJEcnl3NzZzVEl2aVFXS1NRTFNZWUd1eTdRL3NzZDMyV2llU25lRm9mVEdHTlE5a2gzQXNYQWk3RzlEOUpZTnFReXMwK0Rtdml5N2lGSVU1VVg2b3dKcVlEc0FQTXpjSDJ3NUU4OUJyTHMwTVQiLCJtYWMiOiJiYmQ2NjMwMDgxZWUzYTk2YWYwYjdkZjU3YzQ2YzQ3ZjBlZDJiNWNkZTA3ODFkYjQwNDllNWNkYzc4OGRkMjRmIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,'Webkul\\Customer\\Models\\Customer',7,1,'2025-09-24 15:28:05','2025-09-24 15:28:05'),(70,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/forum/posts/y-tuong-lam-game-dan-gian','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/forum\\/posts\\/y-tuong-lam-game-dan-gian\"],\"priority\":[\"u=0, i\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-25 00:51:40','2025-09-25 00:51:40'),(71,'GET','[]','https://lamgame.localhost','https://lamgame.localhost/forum/posts/y-tuong-lam-game-dan-gian','[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"same-origin\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"referer\":[\"https:\\/\\/lamgame.localhost\\/forum\\/posts\\/y-tuong-lam-game-dan-gian\"],\"priority\":[\"u=0, i\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-26 06:42:42','2025-09-26 06:42:42'),(72,'GET','[]','https://lamgame.localhost',NULL,'[]','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','{\"x-real-ip\":[\"192.168.65.1\"],\"x-forwarded-server\":[\"1b4f5ae31810\"],\"x-forwarded-proto\":[\"https\"],\"x-forwarded-port\":[\"443\"],\"x-forwarded-host\":[\"lamgame.localhost\"],\"x-forwarded-for\":[\"192.168.65.1\"],\"upgrade-insecure-requests\":[\"1\"],\"sec-fetch-user\":[\"?1\"],\"sec-fetch-site\":[\"none\"],\"sec-fetch-mode\":[\"navigate\"],\"sec-fetch-dest\":[\"document\"],\"sec-ch-ua-platform\":[\"\\\"macOS\\\"\"],\"sec-ch-ua-mobile\":[\"?0\"],\"sec-ch-ua\":[\"\\\"Chromium\\\";v=\\\"140\\\", \\\"Not=A?Brand\\\";v=\\\"24\\\", \\\"Google Chrome\\\";v=\\\"140\\\"\"],\"priority\":[\"u=0, i\"],\"cookie\":[\"XSRF-TOKEN=eyJpdiI6Im8wVFI4TDFVTDhDdkFQSjlsZHExOVE9PSIsInZhbHVlIjoiRzdWSHBrbThaaHAxNnVhcHBjNWx6WWxTSHBrc01KQis0ZHpPS3dDK2Y1Z3hRYWJoUGZMYjVkZEdONnAvTythSW14T3hjL2J1RWp1bWVOR2RCZ084eTFNWncwYTR6NVNnLzJZaVE4ZjdXTVNDSm1FOHFzZHA3QzNqZys5VXJIaloiLCJtYWMiOiI3N2U0MjdjYmE4NzIwNjQxZjhiZWNhNjMyNzU5ZTUzNGFiMGZlODhlZjc1NzViZDc3NjI3ZmMyODQ3Yzk1ZTFhIiwidGFnIjoiIn0%3D; lamgame_game_development_training_session=eyJpdiI6IjVMRkVyb0ZMSzVOYW96VGp5L2JPanc9PSIsInZhbHVlIjoiN3hiRzZmU3VQSVJPbDBNOVcySTROZis1cHFJMUV4M0xZT3JEWlZXRnJEUjBreVE1MXBqQ05pTnhrZmYvdHVWdHRZZ1dOaVI4aWhuTTU0MUZOV3BtWFFraFE3NzV5eTF0U3U0MzRhV1lXUENpUGp3Ynp0MnhoK1g5UERGMDlVQUciLCJtYWMiOiI4MzI4NTkwMjQzYzBmOWRkZDQ2OTc4ZDZhMWVhZTk0ZjM0NjllNzM3YTRmMTgzN2RiZTU0MWM4YTM3NzE5MmVlIiwidGFnIjoiIn0%3D\"],\"cache-control\":[\"max-age=0\"],\"accept-language\":[\"en-US,en;q=0.9\"],\"accept-encoding\":[\"gzip, deflate, br, zstd\"],\"accept\":[\"text\\/html,application\\/xhtml+xml,application\\/xml;q=0.9,image\\/avif,image\\/webp,image\\/apng,*\\/*;q=0.8,application\\/signed-exchange;v=b3;q=0.7\"],\"user-agent\":[\"Mozilla\\/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/140.0.0.0 Safari\\/537.36\"],\"host\":[\"lamgame.localhost\"]}','Macintosh','OS X','Chrome','192.168.65.1',NULL,NULL,NULL,NULL,1,'2025-09-26 17:10:14','2025-09-26 17:10:14');
/*!40000 ALTER TABLE `visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `customer_id` int unsigned NOT NULL,
  `item_options` json DEFAULT NULL,
  `moved_to_cart` date DEFAULT NULL,
  `shared` tinyint(1) DEFAULT NULL,
  `time_of_moving` date DEFAULT NULL,
  `additional` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlist_channel_id_foreign` (`channel_id`),
  KEY `wishlist_product_id_foreign` (`product_id`),
  KEY `wishlist_customer_id_foreign` (`customer_id`),
  CONSTRAINT `wishlist_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `customer_id` int unsigned NOT NULL,
  `additional` json DEFAULT NULL,
  `moved_to_cart` date DEFAULT NULL,
  `shared` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wishlist_items_channel_id_foreign` (`channel_id`),
  KEY `wishlist_items_product_id_foreign` (`product_id`),
  KEY `wishlist_items_customer_id_foreign` (`customer_id`),
  CONSTRAINT `wishlist_items_channel_id_foreign` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_items_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_items`
--

LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'lamgame'
--

--
-- Dumping routines for database 'lamgame'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-27  0:17:58
