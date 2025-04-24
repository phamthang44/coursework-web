-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 24, 2025 at 10:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_stack_overflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentvotes`
--

CREATE TABLE `commentvotes` (
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `vote_type` tinyint(4) DEFAULT NULL CHECK (`vote_type` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentvotes`
--

INSERT INTO `commentvotes` (`user_id`, `comment_id`, `vote_type`) VALUES
(4, 2, 1),
(5, 2, 1),
(4, 4, 1),
(4, 7, 1),
(4, 24, 1),
(4, 29, 1),
(10, 35, 1);

-- --------------------------------------------------------

--
-- Table structure for table `message_from_users`
--

CREATE TABLE `message_from_users` (
  `message_from_user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_from_users`
--

INSERT INTO `message_from_users` (`message_from_user_id`, `title`, `content`, `user_id`, `create_date`) VALUES
(1, 'test', 'test', 4, '2025-03-18 16:53:42'),
(2, 'test 3', 'test 3', 4, '2025-03-18 16:54:42'),
(3, 'Apology to the administration', 'pls admin I just for making a small mistake in selecting content', 5, '2025-03-23 14:48:47'),
(4, 'Send to any admin', 'please unban for me', 5, '2025-03-24 08:44:37'),
(5, 'test', 'test', 4, '2025-03-30 15:13:48'),
(6, 'test', 'test', 4, '2025-03-30 15:15:21'),
(7, 'test', 'test2', 4, '2025-03-30 15:15:54'),
(8, 'tesdtsat', 'test', 10, '2025-04-01 16:55:41'),
(9, 'test send email', 'test send email message 23/4/2025', 4, '2025-04-23 15:16:34'),
(10, 'test send email', 'test send email message 23/4/2025', 4, '2025-04-23 15:17:37'),
(11, 'Send message email', 'Send message email', 10, '2025-04-23 22:49:19');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `module_name`, `description`) VALUES
(1, 'Programming', 'Discussion about coding and programming'),
(2, 'Design', 'Graphic and UI/UX design topics'),
(3, 'Gaming', 'Gaming news and discussions'),
(4, 'Marketing', 'Nothing here'),
(5, 'test add new ajax', 'test add new ajax'),
(6, 'test add new module by ajax', 'test add new module by ajax'),
(7, 'test add new module by ajax 2', 'test add new module by ajax 2'),
(8, 'add new module by ajax 4', 'add new module by ajax 5'),
(9, 'new module', 'new module'),
(10, 'new module 2', 'new module 2'),
(11, 'test new module ajax', 'test new module ajax'),
(12, 'test new ajax', 'test new ajax'),
(13, 'test reload if 10', 'test reload if 12'),
(14, 'test reload if 12', 'test 123'),
(15, 'test', 'test 3'),
(16, 'test reload page', 'test reload page'),
(17, 'test', 'test'),
(18, 'test', 'test 18'),
(19, 'trick lỏ', 'trick lỏ');

-- --------------------------------------------------------

--
-- Table structure for table `postassets`
--

CREATE TABLE `postassets` (
  `post_asset_id` int(11) NOT NULL,
  `media_key` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postassets`
--

INSERT INTO `postassets` (`post_asset_id`, `media_key`, `post_id`) VALUES
(2, 'uploads/bd1fa0748aadcc13365f8be654d4dbc7.png', 7),
(3, 'uploads/6c3a9fedc692dbd7c09216b53a49a9b6.png', 11),
(4, 'uploads/d32ca89f554330ee5a810735db54c86d.jpg', 12),
(5, 'uploads/75b23b8e207c469fa7741c301c43075b.png', 13),
(6, 'uploads/75afd411a297230091621bd5c2d95821.png', 14),
(9, 'uploads/b518f74827c72d22356955c37f9c1d97.png', 25),
(11, 'uploads/c6995f60e9812a15e5a5a0688aeaacd5.png', 27),
(15, 'uploads/472de48ed1f18a6b84fd1fbe1b24b831.png', 28),
(16, 'uploads/b077067279e1b2c760e4a2b702d3a8f9.png', 29),
(17, 'uploads/bd31530b38433951bc2fa184120e5c5f.png', 30),
(18, 'uploads/192ea664e214fbefccbe474b398f569f.png', 17),
(19, 'uploads/9b3710a14825052af7ab79fd021b8a6b.jpg', 43),
(21, 'uploads/9179db760c62397ca89821e439894ba5.png', 46);

-- --------------------------------------------------------

--
-- Table structure for table `postcomments`
--

CREATE TABLE `postcomments` (
  `post_comment_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `vote_score` int(11) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postcomments`
--

INSERT INTO `postcomments` (`post_comment_id`, `title`, `content`, `vote_score`, `user_id`, `parent_comment_id`, `create_date`, `update_date`, `post_id`) VALUES
(2, NULL, 'test reply comment', 0, 4, NULL, '2025-03-22 13:12:15', '2025-03-22 19:12:15', 38),
(4, NULL, 'test reply level 2', 0, 4, NULL, '2025-03-22 13:27:30', '2025-03-23 15:22:49', 38),
(5, NULL, 'test reply', 0, 5, NULL, '2025-03-22 14:32:22', '2025-03-22 20:32:22', 38),
(6, NULL, 'test reply to me', 0, 4, NULL, '2025-03-23 09:03:01', '2025-03-23 15:03:01', 35),
(7, NULL, 'test reply nè 2', 0, 4, 2, '2025-03-23 09:23:09', '2025-03-23 15:23:15', 38),
(24, NULL, 'test', 0, 4, 2, '2025-03-23 09:54:00', '2025-03-23 15:54:00', 38),
(26, NULL, 'test', 0, 4, 2, '2025-03-23 09:54:24', '2025-03-23 15:54:24', 38),
(27, NULL, 'test', 0, 4, NULL, '2025-03-23 10:04:43', '2025-03-23 16:04:43', 38),
(28, NULL, 'no comment', 0, 4, NULL, '2025-03-24 02:46:31', '2025-03-24 08:46:31', 37),
(29, NULL, 'test', 0, 4, NULL, '2025-03-27 03:20:49', '2025-03-27 09:20:49', 43),
(30, NULL, 'reply', 0, 4, 6, '2025-03-27 03:22:16', '2025-03-27 09:22:16', 35),
(31, NULL, 're', 0, 4, 6, '2025-03-27 03:22:21', '2025-03-27 09:22:21', 35),
(32, NULL, 'test', 0, 4, NULL, '2025-03-30 09:47:18', '2025-03-30 14:47:18', 43),
(33, NULL, 'test', 0, 4, 29, '2025-03-30 09:47:22', '2025-03-30 14:47:22', 43),
(34, NULL, 'test', 0, 10, NULL, '2025-04-01 08:29:00', '2025-04-01 13:29:00', 42),
(35, NULL, '-1', 0, 10, NULL, '2025-04-01 11:27:42', '2025-04-01 16:27:42', 42),
(37, NULL, 'hellu', 0, 10, NULL, '2025-04-04 11:19:36', '2025-04-04 16:19:36', 17),
(39, NULL, 'test create comment', 0, 10, NULL, '2025-04-08 06:35:45', '2025-04-08 11:35:45', 50);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `vote_score` int(11) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `content`, `vote_score`, `user_id`, `module_id`, `create_date`, `update_date`) VALUES
(1, 'Introduction to JavaScript', 'test change content nè', 0, 1, 2, '2025-03-07 11:51:03', '2025-03-11 10:48:48'),
(2, 'How to use Figma', 'Figma is a powerful design tool...', 0, 2, 2, '2025-03-07 11:51:03', '2025-03-07 11:51:03'),
(3, 'Best RPG Games of 2024', 'Here are some of the best RPG games...', 0, 3, 3, '2025-03-07 11:51:03', '2025-03-07 11:51:03'),
(7, 'test', 'test thayu dioop', 0, 1, 2, '2025-03-10 15:21:44', '2025-03-11 10:50:31'),
(8, '', 'test post no title but image', 0, 1, 1, '2025-03-10 20:34:28', '2025-03-10 20:34:28'),
(9, 'test', 'test', 0, 1, 1, '2025-03-10 20:37:08', '2025-03-10 20:37:08'),
(10, 'test test changed aloooooo', 'test test changed', 0, 1, 2, '2025-03-10 20:38:32', '2025-03-14 17:33:15'),
(11, 'edit thay doi theo thu tu moi nhat', 'test no title but has image tai sao bi loi edit vay nhi', 0, 1, 3, '2025-03-10 20:40:49', '2025-03-14 17:56:16'),
(12, 'test', 'test thay đổi time', 0, 1, 3, '2025-03-11 09:18:54', '2025-03-11 12:08:47'),
(13, '', '&lt;h1&gt; hello world &lt;/h1&gt;', 0, 1, 1, '2025-03-11 09:21:30', '2025-03-11 09:21:30'),
(14, 'thêm title', 'test thay đổi lần 5 thêm ảnh', 0, 1, 2, '2025-03-11 09:22:13', '2025-03-11 10:59:55'),
(16, 'test long content EDIT CHANGED', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut augue erat, tristique ultrices sagittis vel, porta ac quam. Vestibulum at vulputate ex, sed auctor ex. Etiam placerat elit dapibus ipsum congue, vel gravida diam hendrerit. Cras rutrum massa lorem. Donec vitae quam magna. Duis euismod, sapien et dapibus egestas, lectus enim rutrum nibh, vitae dictum leo ex non ipsum. Nam sodales mauris nisi, sed tempor lorem finibus non. Curabitur a eros eget quam pharetra placerat. Mauris nec ipsum justo. Ut accumsan risus at libero vestibulum porttitor.\r\n\r\nMauris tortor quam, rutrum eget nibh nec, ornare semper mi. Morbi libero lorem, cursus non lorem a, eleifend suscipit mi. Mauris pulvinar turpis vitae lacus ultricies tempor. Sed at enim et mi pharetra pellentesque a sed lectus. Quisque quis ornare orci, vitae dapibus libero. Nullam ultricies fringilla luctus. Integer leo mauris, suscipit vitae lacinia ut, lobortis eget ipsum. Phasellus aliquet diam id ex maximus luctus. Integer aliquam ornare aliquet. Mauris mollis, quam et sollicitudin consequat, mauris magna sollicitudin est, placerat bibendum lacus felis vel elit. Phasellus euismod, ligula eget fermentum ultricies, ex enim elementum ante, eu volutpat lectus ipsum nec urna.\r\n\r\nIn tellus lorem, commodo commodo commodo non, fringilla eu dolor. In hac habitasse platea dictumst. Nullam pharetra ligula in leo accumsan vehicula. Sed mattis ullamcorper orci, vitae rutrum sapien convallis nec. Sed pharetra pharetra ipsum ut ornare. Suspendisse pretium, felis a dapibus ultricies, magna massa volutpat libero, id ultricies justo arcu sed ex. In sit amet tincidunt lacus. Quisque mattis orci sed justo posuere, pretium condimentum dui fringilla. Vestibulum imperdiet, lorem nec ultricies maximus, mi velit pellentesque augue, ut rutrum ligula est ac eros. Duis consectetur nisl felis, sit amet lobortis mi tempor scelerisque. Nam sed vehicula dui. Suspendisse euismod erat lacus, non mollis est blandit at. Aenean metus purus, malesuada non volutpat eget, consectetur ut dolor.\r\n\r\nFusce id nibh id sapien ullamcorper maximus. Integer finibus, dui eget feugiat aliquam, neque sapien convallis ante, sit amet tincidunt nibh nisl nec purus. Nam laoreet eros et dui sollicitudin fermentum. Nam tempor odio et metus gravida, nec dictum risus congue. Nulla quis porttitor mi. Fusce dignissim scelerisque ornare. Nullam venenatis faucibus mi nec vestibulum. Nunc nec eros neque. Suspendisse nec mi ante. Phasellus pellentesque tristique felis, non pharetra lorem tempus in. Nam finibus ullamcorper sodales. Morbi in pharetra urna, nec viverra nisl. Ut tempor eget lorem vel porttitor. Suspendisse sit amet neque nunc. Morbi sagittis ligula quis turpis tincidunt, nec interdum tellus tincidunt. Pellentesque id ligula fermentum, lobortis tortor ac, tempus nulla.\r\n\r\nQuisque ac accumsan ex, rhoncus lobortis odio. Vestibulum ac urna non orci convallis eleifend ac in eros. Cras finibus eleifend eros at semper. Sed eu vestibulum mi, ac vestibulum mauris. Donec hendrerit viverra enim a suscipit. Donec hendrerit volutpat eros in venenatis. Maecenas turpis nulla, faucibus tristique dui non, placerat faucibus libero. Praesent porta magna sit amet velit pretium, at varius nulla lacinia. Suspendisse vitae elit non neque rhoncus vulputate. Pellentesque a felis vel magna imperdiet fringilla at nec tortor. Nullam varius justo pulvinar metus posuere volutpat.\r\n\r\nNullam id interdum nunc. Suspendisse sit amet augue volutpat, pretium ipsum vitae, consequat felis. Nullam quis ligula a diam efficitur laoreet at aliquam ligula. Etiam pretium sem sed ex porta pretium. Nulla dictum augue eu tristique aliquam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean id nisl pretium, vestibulum mi quis, sodales sapien.\r\n\r\nProin malesuada sit amet quam in consequat. Curabitur porta ipsum id tellus efficitur, malesuada congue turpis fringilla. Etiam tincidunt augue turpis. Vestibulum vitae nisi arcu. Aenean gravida orci et enim consectetur condimentum. In ac dapibus nibh, vitae tempus nulla. Fusce sed velit lorem. Mauris dapibus varius tortor in aliquam. Nulla lobortis maximus enim, eu tempor metus molestie non. Aliquam lacus sapien, feugiat nec rutrum ac, consectetur vitae felis.\r\n\r\nSuspendisse eget orci sit amet nisl euismod ullamcorper at sit amet elit. Donec bibendum malesuada metus, in ullamcorper neque. Aliquam ornare quis justo a luctus. Pellentesque et ante lacus. Donec rutrum eu risus ac dignissim. Donec a risus egestas, dictum ex quis, vestibulum mauris. Aenean a fermentum lorem. Cras lacinia bibendum lorem. Cras accumsan elementum dolor et accumsan.\r\n\r\nNulla a ante dignissim, tempor orci nec, laoreet nulla. Nulla lacus elit, vestibulum vel aliquam ac, elementum eget justo. Donec tincidunt quam ligula. Donec egestas ultrices imperdiet. Vestibulum tristique enim et dolor faucibus ultricies. Vivamus eu consectetur massa, sit amet commodo nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque aliquet, odio ac ullamcorper placerat, libero tellus mattis dui, aliquam tristique neque est quis enim. Nam ac fermentum orci. Phasellus facilisis ligula metus, vitae ullamcorper mauris rhoncus ac. Ut aliquet neque eget bibendum egestas. Quisque aliquet sagittis efficitur. Aliquam eget laoreet nisl. Aenean sed libero vel velit posuere finibus. Nullam efficitur nulla ipsum, sed consectetur quam condimentum condimentum.\r\n\r\nQuisque eu lacus augue. Phasellus posuere accumsan ultricies. Donec rutrum felis id justo dignissim eleifend. Nunc ullamcorper est eros, varius imperdiet erat gravida vel. Fusce eget tincidunt nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed ut pulvinar arcu, in tempus enim. Nunc fringilla turpis sit amet lorem lobortis, malesuada blandit nibh euismod. Pellentesque bibendum condimentum neque, nec eleifend diam efficitur vel. Fusce nisi leo, facilisis dignissim lobortis at, blandit a ante. Sed fringilla augue ac porttitor maximus. Sed nunc nisl, tempus quis ante id, iaculis sollicitudin turpis. Aenean finibus sed eros at vulputate. Phasellus consectetur vel purus eu fermentum. Morbi tempus dapibus sodales. Donec lobortis felis purus, quis suscipit eros congue et.\r\n\r\nSed pharetra metus justo, nec ultricies ligula dictum et. Donec at leo ac orci blandit facilisis. Etiam interdum varius sapien sit amet pellentesque. Nunc eros dolor, elementum quis vulputate a, interdum et urna. Vestibulum in sagittis risus. In nisi lectus, tristique vitae tempus a, mollis sed mi. Donec non aliquet sapien, et ultrices ex. Morbi et mollis velit, et blandit magna.\r\n\r\nUt iaculis pretium orci, at bibendum metus. Aliquam erat volutpat. Donec facilisis est mi, eget sollicitudin purus vulputate ac. Praesent lobortis neque vitae mi pretium, pretium semper orci rutrum. Cras accumsan pretium ante at facilisis. Suspendisse sit amet cursus massa, nec molestie erat. Cras luctus elementum risus, quis finibus quam porttitor nec. Donec vel elit elit. Quisque mattis bibendum mi, ut auctor nibh cursus vitae.', 0, 1, 1, '2025-03-13 13:58:41', '2025-03-13 14:15:05'),
(17, 'test long content casdasdcasdadsadasd', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut augue erat, tristique ultrices sagittis vel, porta ac quam. Vestibulum at vulputate ex, sed auctor ex. Etiam placerat elit dapibus ipsum congue, vel gravida diam hendrerit. Cras rutrum massa lorem. Donec vitae quam magna. Duis euismod, sapien et dapibus egestas, lectus enim rutrum nibh, vitae dictum leo ex non ipsum. Nam sodales mauris nisi, sed tempor lorem finibus non. Curabitur a eros eget quam pharetra placerat. Mauris nec ipsum justo. Ut accumsan risus at libero vestibulum porttitor.\r\n\r\nMauris tortor quam, rutrum eget nibh nec, ornare semper mi. Morbi libero lorem, cursus non lorem a, eleifend suscipit mi. Mauris pulvinar turpis vitae lacus ultricies tempor. Sed at enim et mi pharetra pellentesque a sed lectus. Quisque quis ornare orci, vitae dapibus libero. Nullam ultricies fringilla luctus. Integer leo mauris, suscipit vitae lacinia ut, lobortis eget ipsum. Phasellus aliquet diam id ex maximus luctus. Integer aliquam ornare aliquet. Mauris mollis, quam et sollicitudin consequat, mauris magna sollicitudin est, placerat bibendum lacus felis vel elit. Phasellus euismod, ligula eget fermentum ultricies, ex enim elementum ante, eu volutpat lectus ipsum nec urna.\r\n\r\nIn tellus lorem, commodo commodo commodo non, fringilla eu dolor. In hac habitasse platea dictumst. Nullam pharetra ligula in leo accumsan vehicula. Sed mattis ullamcorper orci, vitae rutrum sapien convallis nec. Sed pharetra pharetra ipsum ut ornare. Suspendisse pretium, felis a dapibus ultricies, magna massa volutpat libero, id ultricies justo arcu sed ex. In sit amet tincidunt lacus. Quisque mattis orci sed justo posuere, pretium condimentum dui fringilla. Vestibulum imperdiet, lorem nec ultricies maximus, mi velit pellentesque augue, ut rutrum ligula est ac eros. Duis consectetur nisl felis, sit amet lobortis mi tempor scelerisque. Nam sed vehicula dui. Suspendisse euismod erat lacus, non mollis est blandit at. Aenean metus purus, malesuada non volutpat eget, consectetur ut dolor.\r\n\r\nFusce id nibh id sapien ullamcorper maximus. Integer finibus, dui eget feugiat aliquam, neque sapien convallis ante, sit amet tincidunt nibh nisl nec purus. Nam laoreet eros et dui sollicitudin fermentum. Nam tempor odio et metus gravida, nec dictum risus congue. Nulla quis porttitor mi. Fusce dignissim scelerisque ornare. Nullam venenatis faucibus mi nec vestibulum. Nunc nec eros neque. Suspendisse nec mi ante. Phasellus pellentesque tristique felis, non pharetra lorem tempus in. Nam finibus ullamcorper sodales. Morbi in pharetra urna, nec viverra nisl. Ut tempor eget lorem vel porttitor. Suspendisse sit amet neque nunc. Morbi sagittis ligula quis turpis tincidunt, nec interdum tellus tincidunt. Pellentesque id ligula fermentum, lobortis tortor ac, tempus nulla.\r\n\r\nQuisque ac accumsan ex, rhoncus lobortis odio. Vestibulum ac urna non orci convallis eleifend ac in eros. Cras finibus eleifend eros at semper. Sed eu vestibulum mi, ac vestibulum mauris. Donec hendrerit viverra enim a suscipit. Donec hendrerit volutpat eros in venenatis. Maecenas turpis nulla, faucibus tristique dui non, placerat faucibus libero. Praesent porta magna sit amet velit pretium, at varius nulla lacinia. Suspendisse vitae elit non neque rhoncus vulputate. Pellentesque a felis vel magna imperdiet fringilla at nec tortor. Nullam varius justo pulvinar metus posuere volutpat.\r\n\r\nNullam id interdum nunc. Suspendisse sit amet augue volutpat, pretium ipsum vitae, consequat felis. Nullam quis ligula a diam efficitur laoreet at aliquam ligula. Etiam pretium sem sed ex porta pretium. Nulla dictum augue eu tristique aliquam. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean id nisl pretium, vestibulum mi quis, sodales sapien.\r\n\r\nProin malesuada sit amet quam in consequat. Curabitur porta ipsum id tellus efficitur, malesuada congue turpis fringilla. Etiam tincidunt augue turpis. Vestibulum vitae nisi arcu. Aenean gravida orci et enim consectetur condimentum. In ac dapibus nibh, vitae tempus nulla. Fusce sed velit lorem. Mauris dapibus varius tortor in aliquam. Nulla lobortis maximus enim, eu tempor metus molestie non. Aliquam lacus sapien, feugiat nec rutrum ac, consectetur vitae felis.\r\n\r\nSuspendisse eget orci sit amet nisl euismod ullamcorper at sit amet elit. Donec bibendum malesuada metus, in ullamcorper neque. Aliquam ornare quis justo a luctus. Pellentesque et ante lacus. Donec rutrum eu risus ac dignissim. Donec a risus egestas, dictum ex quis, vestibulum mauris. Aenean a fermentum lorem. Cras lacinia bibendum lorem. Cras accumsan elementum dolor et accumsan.\r\n\r\nNulla a ante dignissim, tempor orci nec, laoreet nulla. Nulla lacus elit, vestibulum vel aliquam ac, elementum eget justo. Donec tincidunt quam ligula. Donec egestas ultrices imperdiet. Vestibulum tristique enim et dolor faucibus ultricies. Vivamus eu consectetur massa, sit amet commodo nunc. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque aliquet, odio ac ullamcorper placerat, libero tellus mattis dui, aliquam tristique neque est quis enim. Nam ac fermentum orci. Phasellus facilisis ligula metus, vitae ullamcorper mauris rhoncus ac. Ut aliquet neque eget bibendum egestas. Quisque aliquet sagittis efficitur. Aliquam eget laoreet nisl. Aenean sed libero vel velit posuere finibus. Nullam efficitur nulla ipsum, sed consectetur quam condimentum condimentum.\r\n\r\nQuisque eu lacus augue. Phasellus posuere accumsan ultricies. Donec rutrum felis id justo dignissim eleifend. Nunc ullamcorper est eros, varius imperdiet erat gravida vel. Fusce eget tincidunt nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed ut pulvinar arcu, in tempus enim. Nunc fringilla turpis sit amet lorem lobortis, malesuada blandit nibh euismod. Pellentesque bibendum condimentum neque, nec eleifend diam efficitur vel. Fusce nisi leo, facilisis dignissim lobortis at, blandit a ante. Sed fringilla augue ac porttitor maximus. Sed nunc nisl, tempus quis ante id, iaculis sollicitudin turpis. Aenean finibus sed eros at vulputate. Phasellus consectetur vel purus eu fermentum. Morbi tempus dapibus sodales. Donec lobortis felis purus, quis suscipit eros congue et.\r\n\r\nSed pharetra metus justo, nec ultricies ligula dictum et. Donec at leo ac orci blandit facilisis. Etiam interdum varius sapien sit amet pellentesque. Nunc eros dolor, elementum quis vulputate a, interdum et urna. Vestibulum in sagittis risus. In nisi lectus, tristique vitae tempus a, mollis sed mi. Donec non aliquet sapien, et ultrices ex. Morbi et mollis velit, et blandit magna.\r\n\r\nUt iaculis pretium orci, at bibendum metus. Aliquam erat volutpat. Donec facilisis est mi, eget sollicitudin purus vulputate ac. Praesent lobortis neque vitae mi pretium, pretium semper orci rutrum. Cras accumsan pretium ante at facilisis. Suspendisse sit amet cursus massa, nec molestie erat. Cras luctus elementum risus, quis finibus quam porttitor nec. Donec vel elit elit. Quisque mattis bibendum mi, ut auctor nibh cursus vitae.', 0, 1, 1, '2025-03-13 13:59:52', '2025-03-13 14:16:18'),
(18, 'test', 'alo 1234 content test modal', 0, 1, 1, '2025-03-13 16:55:29', '2025-03-13 16:55:29'),
(25, 'test tai sao bi loi edit change vay', 'test aaaaa', 0, 4, 1, '2025-03-14 11:14:00', '2025-03-14 17:32:12'),
(27, 'testte', 'testtsarasfa', 0, 4, 1, '2025-03-14 17:40:15', '2025-03-14 17:40:15'),
(28, 'testteteataet', 'teasraasasdasdasd them anh moi', 0, 4, 1, '2025-03-14 18:05:57', '2025-03-14 19:31:22'),
(29, 'test in profile page', 'test in profile page', 0, 4, 2, '2025-03-16 12:58:53', '2025-03-16 12:58:53'),
(30, 'test in profile page', 'test in profile page', 0, 4, 2, '2025-03-16 13:02:20', '2025-03-16 13:02:20'),
(35, 'test', 'test', 0, 4, 1, '2025-03-18 14:11:38', '2025-03-18 14:11:38'),
(37, 'test', 'test 2', 0, 6, 8, '2025-03-19 11:17:19', '2025-03-19 11:17:19'),
(38, 'test', 'test user different view', 0, 5, 4, '2025-03-20 13:55:24', '2025-03-20 13:55:24'),
(42, 'test 2 update', 'test', 0, 10, 1, '2025-03-24 20:38:48', '2025-04-01 14:39:21'),
(43, 'test', 'test 2222222', 0, 4, 6, '2025-03-25 17:31:26', '2025-03-25 17:31:26'),
(45, '', 'Hello world', 0, 10, 1, '2025-04-07 20:34:31', '2025-04-07 20:34:31'),
(46, 'Test create a title post', 'Test create a content in post', 0, 10, 1, '2025-04-07 20:53:11', '2025-04-07 20:53:11'),
(47, '; OR 1=1;', '; SELECT * FROM posts;', 0, 10, 1, '2025-04-07 21:04:01', '2025-04-07 21:04:01'),
(48, 'Test XSS attack', '&lt;script&gt;alert(&#039;XSS&#039;);&lt;/script&gt;\r\n&lt;img src=&quot;x&quot; onerror=&quot;alert(&#039;XSS&#039;)&quot;&gt;', 0, 10, 1, '2025-04-07 21:23:34', '2025-04-07 21:23:34'),
(49, 'test XSS 2 in quick create post', '&lt;script&gt;', 0, 10, 1, '2025-04-07 21:36:29', '2025-04-07 21:36:29'),
(50, 'Test upload file', 'Test upload file', 0, 10, 2, '2025-04-07 21:50:07', '2025-04-07 21:50:07'),
(51, 'Test HTML Injection', '&lt;h1&gt; heading &lt;/h1&gt;', 0, 10, 2, '2025-04-07 21:54:29', '2025-04-07 21:54:29'),
(52, '&amp;lt;h1&amp;gt;Test html injection 2&amp;lt;/h1&amp;gt;', '&amp;amp;lt;script&amp;amp;gt;alert(&amp;amp;#039;test XSS, html injection&amp;amp;#039;)&amp;amp;lt;/script&amp;amp;gt;', 0, 10, 1, '2025-04-07 21:54:42', '2025-04-08 10:30:12');

-- --------------------------------------------------------

--
-- Table structure for table `postvotes`
--

CREATE TABLE `postvotes` (
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `vote_type` tinyint(4) DEFAULT NULL CHECK (`vote_type` in (-1,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postvotes`
--

INSERT INTO `postvotes` (`user_id`, `post_id`, `vote_type`) VALUES
(4, 3, 1),
(4, 17, 1),
(10, 17, 1),
(4, 28, 1),
(5, 28, 1),
(5, 30, -1),
(10, 30, -1),
(4, 35, 1),
(5, 35, 1),
(4, 37, -1),
(5, 37, -1),
(10, 37, -1),
(4, 38, 1),
(5, 38, 1),
(10, 38, 1),
(4, 42, 1),
(10, 42, 1),
(10, 43, 1),
(4, 50, 1),
(10, 50, 1),
(10, 51, -1),
(4, 52, 1),
(10, 52, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image_path` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `account_create_date` datetime DEFAULT current_timestamp(),
  `dob` date DEFAULT NULL,
  `status` enum('active','banned') DEFAULT 'active',
  `rememberme` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `last_name`, `first_name`, `email`, `password`, `profile_image_path`, `bio`, `role`, `account_create_date`, `dob`, `status`, `rememberme`) VALUES
(1, 'john_doe', 'Doe', 'John', 'john.doe@example.com', '12345678', NULL, 'Loves coding', 'user', '2025-03-07 11:51:03', '1995-08-15', 'active', NULL),
(2, 'smith.jane.1', 'Smith', 'Jane', 'jane.smith@example.com', '12345678', 'uploads/0b5dd8daa53b6d4f2971bbc6091b6a7a.png', 'Admin of the site hehehehe', 'user', '2025-03-07 11:51:03', '1992-05-21', 'active', NULL),
(3, 'alice_user', 'Johnson', 'Alice', 'alice.johnson@example.com', '12345678', NULL, 'Enjoys blogging', 'user', '2025-03-07 11:51:03', '2000-01-10', 'active', NULL),
(4, 'thang.pham.1', 'Thang', 'Pham', 'phamthang2005@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$cXB4a1hMbUFteERXZTVvYw$3ULfsFQBRL15Di/kuJRdmG50g1ivzORUAQIaxJhFgCI', 'uploads/e1fb875d8ad4713514918e80918c74d3.jpg', 'Test change bio lan 13 with bio and toast message', 'admin', '2025-03-12 12:06:20', '2005-08-16', 'active', NULL),
(5, 'pham.thang', 'Pham', 'Thang', 'phamthang2004@gmail.com', '16082005', NULL, NULL, 'user', '2025-03-17 10:06:56', NULL, 'active', NULL),
(6, 'thang.pham', 'Thang', 'Pham', 'phamthang2003@gmail.com', '16082005', NULL, NULL, 'user', '2025-03-17 10:13:07', NULL, 'banned', NULL),
(10, 'phạm.thắng.13', 'Phạm', 'Thắng', 'ptl2005@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$LkQ1SzRock82YlQ2bThKNg$wFGaG2KBzsD6f4/mysnEex3Locccfplgcov9jPzJQE8', 'uploads/177f3da20798ddbef86b3560de3b7b7a.jpg', 'Test edit bio 34', 'user', '2025-03-24 20:11:26', '2005-08-16', 'active', NULL),
(11, 'pham.thang.1', 'Pham', 'Thang', 'ptl2004@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$czl2aXFlZkxDSGtiRktUWg$g22SCVxFZ8wvszxlZmSAWgDfDtpcOANB1GrSe+0VDMc', NULL, NULL, 'user', '2025-04-06 10:31:52', NULL, 'active', NULL),
(12, 'test20charactersaaaa.test20charactersaaaa', 'test20charactersaaaa', 'test20charactersaaaa', 'ptl2004a@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$T0IwME1rUzZ1ck9KaFZmMA$kxeyiwd+xnHc71uaT192xs1sYWOY48S6PpTFPhWEMGg', NULL, NULL, 'user', '2025-04-06 15:23:33', NULL, 'active', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentvotes`
--
ALTER TABLE `commentvotes`
  ADD PRIMARY KEY (`user_id`,`comment_id`),
  ADD KEY `idx_comment_votes` (`comment_id`,`vote_type`);

--
-- Indexes for table `message_from_users`
--
ALTER TABLE `message_from_users`
  ADD PRIMARY KEY (`message_from_user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `postassets`
--
ALTER TABLE `postassets`
  ADD PRIMARY KEY (`post_asset_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `postcomments`
--
ALTER TABLE `postcomments`
  ADD PRIMARY KEY (`post_comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `postvotes`
--
ALTER TABLE `postvotes`
  ADD PRIMARY KEY (`user_id`,`post_id`),
  ADD KEY `idx_post_votes` (`post_id`,`vote_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message_from_users`
--
ALTER TABLE `message_from_users`
  MODIFY `message_from_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `postassets`
--
ALTER TABLE `postassets`
  MODIFY `post_asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `postcomments`
--
ALTER TABLE `postcomments`
  MODIFY `post_comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commentvotes`
--
ALTER TABLE `commentvotes`
  ADD CONSTRAINT `commentvotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentvotes_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `postcomments` (`post_comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `message_from_users`
--
ALTER TABLE `message_from_users`
  ADD CONSTRAINT `message_from_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `postassets`
--
ALTER TABLE `postassets`
  ADD CONSTRAINT `postassets_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `postcomments`
--
ALTER TABLE `postcomments`
  ADD CONSTRAINT `postcomments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `postcomments_ibfk_2` FOREIGN KEY (`parent_comment_id`) REFERENCES `postcomments` (`post_comment_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `postcomments_ibfk_3` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE;

--
-- Constraints for table `postvotes`
--
ALTER TABLE `postvotes`
  ADD CONSTRAINT `postvotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `postvotes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
