-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ai_creator_marketplace
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brief_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `pitch` text NOT NULL,
  `proposed_rate` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_app_brief` (`brief_id`),
  KEY `fk_app_creator` (`creator_id`),
  CONSTRAINT `fk_app_brief` FOREIGN KEY (`brief_id`) REFERENCES `briefs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_app_creator` FOREIGN KEY (`creator_id`) REFERENCES `creator_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES (1,1,1,'I can generate cinematic 4K Runway footage with custom color grading within 4 days.','$3,000','Pending','2026-10-06 21:19:20'),(2,2,4,'Experienced in viral TikTok skincare commercials with AI voice narration and rapid pacing.','$1,600','Accepted','2026-10-06 21:19:20'),(3,6,1,'Hello GreenWave Creative Agency,\n\nI would love to helm your 30-second Cinematic Nature Campaign. As a verified AI Video Director specializing in 9:16 commercial ads, I have generated nature and landscape visuals using Runway Gen-3 Alpha, Kling 1.5, and Midjourney v6.1.\n\nProposed Price: ₹35,000 (inclusive of commercial buyout and 2 social cuts)\nDelivery: 8 business days with iterative review milestones\nTools: Runway Gen-3 Alpha, Kling 1.5, Midjourney v6.1, Topaz Video AI (4K Upscale), DaVinci Resolve Studio.','₹35,000','Pending','2026-10-07 00:21:14');
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `briefs`
--

DROP TABLE IF EXISTS `briefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `briefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `campaign_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `content_type` varchar(100) NOT NULL DEFAULT 'Video',
  `style` varchar(100) NOT NULL DEFAULT 'Cinematic',
  `platform` varchar(100) NOT NULL DEFAULT 'Instagram',
  `format` varchar(50) NOT NULL DEFAULT '16:9',
  `commercial_use` varchar(50) NOT NULL DEFAULT 'Yes',
  `budget` varchar(100) DEFAULT '$2,500',
  `deadline` varchar(100) DEFAULT NULL,
  `reference_url` varchar(500) DEFAULT NULL,
  `ad_video_url` varchar(500) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_brief_brand` (`brand_id`),
  CONSTRAINT `fk_brief_brand` FOREIGN KEY (`brand_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `briefs`
--

LOCK TABLES `briefs` WRITE;
/*!40000 ALTER TABLE `briefs` DISABLE KEYS */;
INSERT INTO `briefs` VALUES (1,1,'Summer Launch Campaign','We need a 30-second cinematic promotional video for our new product launch with a premium, modern feel.','Video','Cinematic','Instagram','16:9','Yes','$2,500 - $4,000','2026-11-15','https://example.com/reference-board',NULL,'Active','2026-10-06 21:19:20','2026-10-06 21:19:20'),(2,1,'Spring Cosmetic Social Series','Series of high-converting vertical 15-30 second video snippets showcasing skincare transformation.','Video','Minimal','TikTok','9:16','Yes','$1,800','2026-11-01','https://example.com/moodboard',NULL,'Active','2026-10-06 21:19:20','2026-10-06 21:19:20'),(3,1,'AI Cyberpunk Sneaker Commercial','3D futuristic aesthetic 30s ad highlighting lightweight materials and neon cityscape.','Animation','Luxury','YouTube','16:9','Yes','$3,200','2026-11-20','https://example.com/sneaker-ref',NULL,'Active','2026-10-06 21:19:20','2026-10-06 21:19:20'),(4,1,'SaaS Platform Explainer Video','Clean, high-energy UI motion graphic showing AI workflow for creative agencies.','Video','Professional','Website','16:9','Yes','$2,000','2026-12-05','https://example.com/saas-ref',NULL,'Active','2026-10-06 21:19:20','2026-10-06 21:19:20'),(5,1,'Summer 30s Ad Launch Campaign','We need a high-converting 30-second cinematic video advertisement showcasing our new product launch with dynamic motion and synthetic voiceover.','Video','Fun','Instagram','16:9','Yes','$2,500 - $4,000',NULL,'https://example.com/moodboard','uploads/videos/upload_6ac56a1acf4ab9.26933061.mp4','Active','2026-10-06 21:37:30','2026-10-06 21:37:30'),(6,14,'Cinematic Nature Campaign for a Tourism Brand','We are working with a premium tourism brand and are looking for an AI video creator to produce a cinematic visual campaign showcasing beautiful natural landscapes.\n\nObjective: Create an emotionally engaging nature-focused video that inspires viewers to travel and experience the destination.\n\nWhat We Need:\n• 30-second cinematic AI-generated video\n• Mountains, forests, waterfalls, lakes, and sunrise visuals\n• Smooth cinematic camera movements\n• Realistic and visually consistent environments\n• Premium advertising quality\n\nDeliverables:\n• 30-second final video (9:16 Instagram Reels / Social Media)\n• 2 short social-media versions\n• High-resolution final output (4K 60fps)\n• Commercial usage rights & paid advertising buyout','Advertisement / Tourism Video','Cinematic, Natural, Emotional, Premium','Instagram Reels / Social Media','9:16','Commercial Rights','₹25,000–₹50,000','7–10 days',NULL,NULL,'Active','2026-10-07 00:21:14','2026-10-07 00:21:14');
/*!40000 ALTER TABLE `briefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creator_profiles`
--

DROP TABLE IF EXISTS `creator_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creator_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_title` varchar(100) NOT NULL DEFAULT 'AI Creator',
  `bio` text DEFAULT NULL,
  `specialization` varchar(150) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `tools` text DEFAULT NULL,
  `rating` decimal(3,1) NOT NULL DEFAULT 4.8,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `projects_count` int(11) NOT NULL DEFAULT 0,
  `avatar_url` varchar(500) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 1,
  `hourly_rate` varchar(50) DEFAULT '$75/hr',
  `location` varchar(100) DEFAULT 'Remote',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_types` varchar(255) DEFAULT 'Video, Advertisement',
  `aspect_ratios` varchar(100) DEFAULT '16:9, 9:16',
  `budget_tier` varchar(100) DEFAULT '₹25,000–₹50,000',
  `price_num` int(11) NOT NULL DEFAULT 25000,
  `experience_level` varchar(50) DEFAULT 'Advanced',
  `availability` varchar(50) DEFAULT 'Available Now',
  `commercial_rights` varchar(255) DEFAULT 'Commercial Use, Paid Advertising',
  `verification_tags` varchar(255) DEFAULT 'Tools Verified, Portfolio Verified, Commercial Ready',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `fk_creator_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creator_profiles`
--

LOCK TABLES `creator_profiles` WRITE;
/*!40000 ALTER TABLE `creator_profiles` DISABLE KEYS */;
INSERT INTO `creator_profiles` VALUES (1,3,'AI Video Creator','AI video creator specializing in 30-second product advertisements and viral short-form videos.','AI Video Creator','AI Video, Prompt Engineering, Video Editing, Advertising','Runway, Kling, Sora, ElevenLabs',4.9,24,20,'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=80',1,'₹35,000 / ad','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Video, Advertisement, Product Video, Social Media','16:9, 9:16','₹25,000–₹50,000',35000,'Expert','Available Now','Commercial Use, Paid Advertising, Exclusive Rights','Tools Verified, Portfolio Verified, Commercial Ready'),(2,4,'Generative Designer','Visual artist utilizing cutting-edge generative AI to deliver iconic brand identities and concept art.','Generative Designer','Image Generation, Generative Design, Character Design, Storyboarding','Midjourney, Flux, Adobe Firefly, ComfyUI',4.8,19,26,'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=500&q=80',1,'₹18,000 / project','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Image, Product Image, Social Media','1:1, 4:5, 16:9','₹10,000–₹25,000',18000,'Advanced','Available This Week','Commercial Use, Licensing Available','Portfolio Verified, Workflow Verified'),(3,5,'AI Animator','Specializing in 3D AI animation, seamless character movement, and 30s VFX commercials.','AI Animator','AI Animation, 3D Generation, Motion Graphics, Prompt Engineering','Runway, Veo, Stable Diffusion, ComfyUI',4.9,28,25,'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80',1,'₹65,000 / ad','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Animation, 3D, Short Film, Video','16:9, 9:16','₹50,000–₹1,00,000',65000,'Expert','Available Now','Commercial Use, Paid Advertising, Exclusive Rights','Tools Verified, Portfolio Verified, Workflow Verified, Commercial Ready'),(4,6,'Product Visualization Artist','Product visualization artist delivering high-converting ecommerce visuals and 3D product reels.','Product Visualization Artist','Product Visualization, 3D Generation, Image Generation, Advertising','Midjourney, Flux, Kling, ChatGPT',4.7,15,18,'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=500&q=80',1,'₹9,500 / reel','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Product Video, Product Image, Advertisement','9:16, 1:1','Under ₹10,000',9500,'Intermediate','Available Now','Commercial Use, Paid Advertising','Tools Verified, Commercial Ready'),(5,7,'AI Filmmaker','Award-winning AI filmmaker producing cinematic short films, commercials, and brand anthems.','AI Filmmaker','AI Video, Storyboarding, Video Editing, AI Animation, Prompt Engineering','Runway, Sora, Veo, ElevenLabs, ComfyUI',5.0,34,38,'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80',1,'₹1,25,000 / campaign','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Short Film, Advertisement, Video','16:9','₹1,00,000+',125000,'Expert','Available This Week','Commercial Use, Paid Advertising, Exclusive Rights, Licensing Available','Tools Verified, Portfolio Verified, Workflow Verified, Commercial Ready'),(6,8,'Advertising Creative','Creative director & motion designer blending AI video, typography, and viral social pacing.','Advertising Creative','Advertising, Motion Graphics, AI Video, Social Media','Kling, Runway, ElevenLabs, ChatGPT',4.8,22,21,'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80',1,'₹32,000 / ad','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Advertisement, Social Media, Product Video','9:16, 16:9','₹25,000–₹50,000',32000,'Advanced','Currently Booked','Commercial Use, Paid Advertising','Portfolio Verified, Commercial Ready'),(7,9,'AI Motion Designer','AI motion designer crafting kinetic typography and viral AI video loops for modern brands.','AI Motion Designer','Motion Graphics, Video Editing, AI Video, Prompt Engineering','Runway, ComfyUI, ElevenLabs, ChatGPT',4.7,12,14,'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=500&q=80',1,'₹16,000 / reel','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Social Media, Video, Animation','9:16, 1:1','₹10,000–₹25,000',16000,'Intermediate','Available Now','Commercial Use, Paid Advertising','Tools Verified, Workflow Verified'),(8,10,'AI Artist','Generative artist crafting bespoke character designs, illustrations, and 3D visual concepts.','AI Artist','Image Generation, Character Design, Generative Design, 3D Generation','Midjourney, Stable Diffusion, Flux, Adobe Firefly',4.6,9,11,'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80',1,'₹8,500 / set','Remote','2026-10-06 21:19:20','2026-10-06 21:19:20','Image, 3D, Social Media','1:1, 4:5','Under ₹10,000',8500,'Beginner','Available This Week','Commercial Use, Licensing Available','Portfolio Verified'),(9,11,'AI Content Creator','AI content creator: Generates engaging, tailored content—from ideas and copy to visuals and videos—using artificial intelligence.','AI Video Creator','AI Video, Runway, Kling','Runway, Kling, ElevenLabs Voice',5.0,0,1,'uploads/avatars/upload_6ac56af57e89f1.67231847.jpeg',1,'$75/hr','coimbatore','2026-10-06 21:25:30','2026-10-06 21:41:09','Video, Advertisement','16:9, 9:16','Under ₹10,000',25000,'Beginner','Available Now','Commercial Use, Paid Advertising','Tools Verified, Portfolio Verified, Commercial Ready'),(10,12,'AI Content Creator','AI creator specializing in short-form video advertisements and digital content.','AI Advertisements','AI Video, Runway, Kling','Runway, Kling',5.0,0,0,NULL,1,'$75/hr','Remote','2026-10-06 21:34:17','2026-10-06 21:34:17','Video, Advertisement','16:9, 9:16','₹25,000–₹50,000',25000,'Advanced','Available Now','Commercial Use, Paid Advertising','Tools Verified, Portfolio Verified, Commercial Ready');
/*!40000 ALTER TABLE `creator_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_projects`
--

DROP TABLE IF EXISTS `portfolio_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content_type` varchar(100) NOT NULL DEFAULT 'Video',
  `description` text DEFAULT NULL,
  `tools` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `preview_url` varchar(500) NOT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `video_duration` int(11) NOT NULL DEFAULT 0,
  `is_ad_video` tinyint(1) NOT NULL DEFAULT 0,
  `external_link` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_portfolio_creator` (`creator_id`),
  CONSTRAINT `fk_portfolio_creator` FOREIGN KEY (`creator_id`) REFERENCES `creator_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_projects`
--

LOCK TABLES `portfolio_projects` WRITE;
/*!40000 ALTER TABLE `portfolio_projects` DISABLE KEYS */;
INSERT INTO `portfolio_projects` VALUES (1,1,'AI Shoe Advertisement','Video','15-second high-energy product advertisement created for a sports shoe campaign.','Runway, Kling','AI Video, Advertisement','https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80','https://assets.mixkit.co/videos/preview/mixkit-running-shoes-in-a-neon-room-41908-large.mp4',15,1,'https://example.com/shoes-ad','2026-10-06 21:19:20','2026-10-06 21:19:20'),(2,1,'Food Product Ad (30s)','Video','30-second dynamic food commercial blending generative AI video and voice synthesis.','Runway, ElevenLabs','AI Video, Social Ads','https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80','https://assets.mixkit.co/videos/preview/mixkit-hands-holding-a-delicious-hamburger-41851-large.mp4',30,1,'https://example.com/food-ad','2026-10-06 21:19:20','2026-10-06 21:19:20'),(3,2,'AI Fashion Campaign','Image','High-fashion visual lookbook developed entirely using Midjourney and Photoshop retouching.','Midjourney, Adobe Firefly','AI Image, Branding','https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80',NULL,0,0,'https://example.com/fashion','2026-10-06 21:19:20','2026-10-06 21:19:20'),(4,3,'Cyberpunk Hologram Commercial','Animation','24-second sci-fi animation advertisement with fluid camera motion and synthetic lighting.','Runway, Blender','AI Animation, 3D','https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=900&q=80','https://assets.mixkit.co/videos/preview/mixkit-digital-animation-of-screens-with-code-31911-large.mp4',24,1,'https://example.com/cyber-ad','2026-10-06 21:19:20','2026-10-06 21:19:20'),(5,4,'Viral SaaS Launch Reel (20s)','Video','20-second short-form promotional advertisement video generating 250K+ organic impressions.','Runway, ElevenLabs','AI Video, Social Media','https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=900&q=80','https://assets.mixkit.co/videos/preview/mixkit-hands-of-a-man-typing-on-a-computer-keyboard-41712-large.mp4',20,1,'https://example.com/saas-clip','2026-10-06 21:19:20','2026-10-06 21:19:20'),(6,9,'krish','Video','AI Content Creator — create smarter, publish faster, captivate everywhere.','gemini','AI Video, Advertisement','https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80','uploads/videos/upload_6ac56898b7b3a4.20898168.mp4',10,1,NULL,'2026-10-06 21:31:04','2026-10-06 21:31:04');
/*!40000 ALTER TABLE `portfolio_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` enum('creator','brand','admin') NOT NULL DEFAULT 'creator',
  `avatar_url` varchar(500) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'strawshats@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','straws hats','brand','uploads/avatars/upload_6ac56a44aa2468.69413845.jpeg','Leading futuristic lifestyle brand creating cutting-edge digital campaigns.','https://strawhatsbrand.com','2026-10-06 21:19:20','2026-10-06 21:38:12'),(2,'nike@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Apex Athletics','brand','https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80','Next-gen performance sports gear combining AI design with high fashion.','https://apexathletics.com','2026-10-06 21:19:20','2026-10-06 21:19:20'),(3,'arun@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Arun Kumar','creator','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=500&q=80','AI video creator specializing in 30-second product advertisements and viral short-form videos.','https://arunkumar.ai','2026-10-06 21:19:20','2026-10-06 21:19:20'),(4,'priya@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Priya Nair','creator','https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=500&q=80','Visual artist utilizing cutting-edge generative AI to deliver iconic brand identities and concept art.','https://priyanair.design','2026-10-06 21:19:20','2026-10-06 21:19:20'),(5,'rahul@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Rahul Singh','creator','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=500&q=80','Specializing in 3D AI animation, seamless character movement, and 30s VFX commercials.','https://rahulsingh.art','2026-10-06 21:19:20','2026-10-06 21:19:20'),(6,'sneha@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Sneha Iyer','creator','https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=500&q=80','Product visualization artist delivering high-converting ecommerce visuals and 3D product reels.','https://snehaiyer.media','2026-10-06 21:19:20','2026-10-06 21:19:20'),(7,'vikram@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Vikram Malhotra','creator','https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=500&q=80','Award-winning AI filmmaker producing cinematic short films, commercials, and brand anthems.','https://vikramfilms.ai','2026-10-06 21:19:20','2026-10-06 21:19:20'),(8,'ananya@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Ananya Deshmukh','creator','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=500&q=80','Creative director & motion designer blending AI video, typography, and viral social pacing.','https://ananyacreative.com','2026-10-06 21:19:20','2026-10-06 21:19:20'),(9,'dev@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Dev Patel','creator','https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=500&q=80','AI motion designer crafting kinetic typography and viral AI video loops for modern brands.','https://devmotion.ai','2026-10-06 21:19:20','2026-10-06 21:19:20'),(10,'tara@example.com','$2y$10$uH0jU2cZkfqv09kQ7E8k8OhkZ1rVf2jBw3j.D8z4qWw9Z8sH.x.u6','Tara Sharma','creator','https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=500&q=80','Generative artist crafting bespoke character designs, illustrations, and 3D visual concepts.','https://taraart.ai','2026-10-06 21:19:20','2026-10-06 21:19:20'),(11,'madhusridevaraj698@gmail.com','$2y$10$61RG8lxldZciiy8ibBJCvO04r2IXPrlbCga9.gZpcuVT6uEgqXvOe','strawhats','creator','uploads/avatars/upload_6ac56af57e89f1.67231847.jpeg',NULL,NULL,'2026-10-06 21:25:30','2026-10-06 21:41:09'),(12,'madhusridevaraj9@gmail.com','$2y$10$5gu4rCNMnH2tswskaK.5uuf92e2WTyhY2h4oM4VPtTLkMVSVtDiFa','content craves','creator',NULL,NULL,NULL,'2026-10-06 21:34:17','2026-10-06 21:34:17'),(13,'santhoshkumar88921@gmail.com','$2y$10$rnvRJ22ptfHdGKiItv8SvOA2LQj987PY6FMHap0RoMsaIG2VpYIPy','content craves','brand','uploads/avatars/upload_6ac56c50cb2ab8.03013819.jpeg','AI Content Creator — create smarter, publish faster, captivate everywhere.','https://strawhatsbrand.com','2026-10-06 21:45:43','2026-10-06 21:46:56'),(14,'greenwave@example.com','$2y$10$rOI83b4JAWJKs82ZC2qLn.MGgrir1PqSwACRWS.xy37XqkPh6wBku','GreenWave Creative Agency','brand',NULL,'Creative agency specializing in premium tourism and sustainable travel campaigns.',NULL,'2026-10-07 00:21:14','2026-10-07 00:21:14');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-07  5:51:34
