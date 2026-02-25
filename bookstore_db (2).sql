-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2025 at 11:12 AM
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
-- Database: `bookstore_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `price`, `stock`, `description`, `category_id`, `created_at`) VALUES
(22, 'The Lord of the Rings', 'J.R.R. Tolkien', 18.01, 30, 'The Lord of the Rings is an epic fantasy masterpiece by J.R.R. Tolkien, taking readers on a legendary journey through Middle-earth filled with adventure, courage, and the timeless battle between light and darkness.', 10, '2025-09-18 15:23:19'),
(23, 'A Game of Thrones', 'George R.R. Martin', 11.20, 60, 'The first novel in the epic fantasy series A Song of Ice and Fire. Set in the Seven Kingdoms of Westeros, where \"summers span decades and winters can last a lifetime,\" the novel tells the story of the noble House Stark.', 10, '2025-09-18 15:23:19'),
(24, 'The Chronicles of Narnia', 'C.S. Lewis', 12.80, 50, 'A series of seven fantasy novels by C.S. Lewis. It is considered a classic of children\'s literature and has sold over 100 million copies in 47 languages. The series is set in the fictional land of Narnia, a magical world full of talking animals and mythical creatures.', 10, '2025-09-18 15:23:19'),
(25, 'The Alchemist', 'Paulo Coelho', 3.92, 118, 'A fable about a young Andalusian shepherd boy who journeys from his homeland in Spain to the Egyptian desert in search of a hidden treasure near the pyramids. Along the way, he meets an alchemist and other mentors who help him on his quest.', 2, '2025-09-18 15:23:19'),
(26, 'The 7 Habits of Highly Effective People', 'Stephen Covey', 7.00, 84, 'A business and self-help book. Covey presents an approach to being effective in attaining goals by aligning oneself to what he calls \"true north\" principles of a character ethic that he presents as universal and timeless.', 2, '2025-09-18 15:23:19'),
(27, 'The Subtle Art of Not Giving a F*ck', 'Mark Manson', 4.60, 110, 'A self-help book. Manson argues that life\'s struggles give it meaning, and that the mindless positivity of typical self-help books is neither practical nor helpful.', 2, '2025-09-18 15:23:19'),
(28, 'The Lean Startup', 'Eric Ries', 7.60, 40, 'A business book that focuses on an approach for creating and managing successful startups in a world of uncertainty. It promotes the use of customer feedback and iterative development to build a sustainable business.', 3, '2025-09-18 15:23:19'),
(29, 'Start with Why', 'Simon Sinek', 6.40, 70, 'A book by Simon Sinek. He says that the most successful and influential leaders all think, act, and communicate in the exact same way, and it\'s the opposite of everyone else.', 3, '2025-09-18 15:23:19'),
(30, 'Zero to One', 'Peter Thiel', 5.60, 55, 'A book about startups. It argues that progress in technology is not a given, and that entrepreneurs need to create new and unique things rather than just copying existing business models.', 3, '2025-09-18 15:23:19'),
(32, 'Dune Messiah', 'Frank Herbert', 10.40, 50, 'The second novel in the Dune saga. The book continues the story of Paul Atreides, now the emperor of the universe, and his struggles to control the vast empire and the prophecy that he has fulfilled.', 5, '2025-09-18 15:23:19'),
(34, 'Ender\'s Game', 'Orson Scott Card', 6.00, 80, 'A science fiction novel about a young boy named Ender Wiggin who is trained at a military school in outer space to lead the fight against an alien race.', 5, '2025-09-18 15:23:19'),
(38, 'A Brief History of Time', 'Stephen Hawking', 7.96, 90, 'A popular science book that covers the basics of cosmology, including the big bang, black holes, and the nature of time. It is a landmark in the genre and has sold over 10 million copies.', 11, '2025-09-18 15:23:19'),
(39, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', 9.60, 75, 'A non-fiction book that examines the history of Homo sapiens, from the evolution of archaic human species in the Stone Age to the modern era. It explores how our species has evolved and what our future holds.', 6, '2025-09-18 15:23:19'),
(50, 'The Very Hungry Caterpillar', 'Eric Carle', 2.60, 180, 'A children\'s picture book that tells the story of a caterpillar who eats his way through a variety of foods before transforming into a butterfly.', 15, '2025-09-18 15:23:19'),
(52, 'Charlie and the Chocolate Factory', 'Roald Dahl', 4.00, 90, 'A children\'s novel that tells the story of a young boy named Charlie Bucket who wins a tour of the most magnificent chocolate factory in the world.', 15, '2025-09-18 15:23:19'),
(53, 'The Little Prince', 'Antoine de Saint-Exupéry', 3.40, 120, 'A novella that tells the story of a pilot who crashes in the Sahara Desert and meets a young prince from another planet. The book is a philosophical and poetic tale about the importance of friendship and love.', 15, '2025-09-18 15:23:19'),
(55, 'Gone Girl', 'Gillian Flynn', 6.40, 65, 'A psychological thriller novel. The story is about a woman who disappears on her fifth wedding anniversary, leaving her husband as the prime suspect in her disappearance.', 7, '2025-09-18 15:23:19'),
(56, 'The Da Vinci Code', 'Dan Brown', 7.80, 80, 'A mystery thriller novel. The novel follows Harvard symbologist Robert Langdon as he investigates a murder in the Louvre Museum and uncovers a conspiracy to reveal a secret society.', 7, '2025-09-18 15:23:19'),
(57, 'The Stand', 'Stephen King', 10.80, 30, 'A post-apocalyptic dark fantasy novel. The story is about a plague that wipes out most of the world\'s population and the struggle between good and evil that follows.', 7, '2025-09-18 15:23:19'),
(59, 'The Shining', 'Stephen King', 8.40, 40, 'A horror novel that tells the story of a family who takes care of an isolated hotel during the winter. The hotel is haunted by a malevolent supernatural force that drives the father to madness.', 7, '2025-09-18 15:23:19'),
(81, 'The Hunger Games', 'Suzanne Collins', 6.00, 85, 'A dystopian novel set in a post-apocalyptic world. The story follows a teenage girl named Katniss Everdeen who is forced to participate in a televised fight to the death.', 10, '2025-09-18 15:23:19'),
(82, 'The Maze Runner', 'James Dashner', 5.60, 70, 'A dystopian novel. The story follows a group of teenagers who are trapped in a mysterious maze and must find a way to escape.', 10, '2025-09-18 15:23:19'),
(83, 'Divergent', 'Veronica Roth', 5.20, 5, 'A dystopian novel set in a futuristic Chicago. The society is divided into five factions, and a young girl named Tris Prior must choose her path and face the dangers of a world that is not what it seems.', 10, '2025-09-18 15:23:19'),
(84, 'The Fault in Our Stars', 'John Green', 4.40, 100, 'A romantic novel. The story is about two teenage cancer patients who fall in love and go on a journey to find meaning in their lives.', 1, '2025-09-18 15:23:19'),
(85, 'Paper Towns', 'John Green', 4.20, 95, 'A coming-of-age novel. The story follows a young man named Quentin Jacobsen who embarks on a journey to find his mysterious neighbor, Margo Roth Spiegelman, who has disappeared.', 1, '2025-09-18 15:23:19'),
(86, 'Looking for Alaska', 'John Green', 4.60, 90, 'A young adult novel. The story is about a teenage boy named Miles Halter who goes to a new boarding school and falls in love with a mysterious girl named Alaska Young.', 1, '2025-09-18 15:23:19'),
(87, 'The Kite Runner', 'Khaled Hosseini', 7.20, 55, 'A novel that tells the story of a young boy in Afghanistan and his friendship with his best friend. The novel explores themes of guilt, redemption, and friendship.', 1, '2025-09-18 15:23:19'),
(88, 'A Thousand Splendid Suns', 'Khaled Hosseini', 7.60, 45, 'A novel that tells the story of two women in Afghanistan and their struggles to survive in a war-torn country. The novel explores themes of love, family, and war.', 1, '2025-09-18 15:23:19'),
(91, 'The 4-Hour Workweek', 'Timothy Ferriss', 8.00, 50, 'A self-help book that provides a framework for escaping the 9-to-5 grind and living a life of freedom and adventure. It teaches readers how to automate their income, outsource their lives, and travel the world.', 3, '2025-09-18 15:23:19'),
(93, 'The Little Book of Hygge', 'Meik Wiking', 4.80, 90, 'A book that explores the Danish concept of hygge, which is a feeling of coziness and contentment. It provides tips on how to incorporate hygge into your life to find more happiness.', 2, '2025-09-18 15:23:19'),
(94, 'The Life-Changing Magic of Tidying Up', 'Marie Kondo', 3.80, 110, 'A book that provides a guide to decluttering and organizing your home. It promotes a minimalist lifestyle and encourages readers to only keep items that \"spark joy.\"', 2, '2025-09-18 15:23:19'),
(95, 'The 5 Love Languages', 'Gary Chapman', 3.40, 130, 'A book that explores the five different ways people express and receive love. It provides a framework for improving communication and strengthening relationships.', 2, '2025-09-18 15:23:19'),
(96, 'The 48 Laws of Power', 'Robert Greene', 10.00, 40, 'A controversial book that outlines 48 laws of power based on historical figures and events. The book is often used by business leaders and politicians to gain an advantage in their careers.', 3, '2025-09-18 15:23:19'),
(97, 'The Intelligent Investor', 'Benjamin Graham', 12.00, 35, 'A classic book on value investing. It provides a framework for long-term investing and teaches readers how to avoid common investing mistakes.', 3, '2025-09-18 15:23:19'),
(100, 'The Giving Tree', 'Shel Silverstein', 3.20, 100, 'A children\'s book that tells the story of a tree and a boy who have a lifelong friendship. The book explores themes of unconditional love and selflessness.', 15, '2025-09-18 15:23:19'),
(102, 'The Lightning Thief', 'Rick Riordan', 6.00, 75, 'A fantasy novel that tells the story of a young boy named Percy Jackson who discovers he is a demigod and must go on a quest to find the lightning bolt of Zeus.', 10, '2025-09-18 15:23:19'),
(108, 'The Haunting of Hill House', 'Shirley Jackson', 3.80, 0, 'A gothic horror novel that tells the story of a group of people who investigate a haunted house. The novel is a classic of the horror genre and has inspired many films and books.', 16, '2025-09-18 15:23:19'),
(113, 'On the Road', 'Jack Kerouac', 6.00, 80, 'A novel that tells the story of a group of friends who travel across the United States. The novel is a classic of the Beat Generation and explores themes of freedom, adventure, and self-discovery.', 1, '2025-09-18 15:23:19'),
(151, 'Doraemon Vol. 1', 'Fujiko F. Fujio', 2.00, 199, 'The first volume of the classic manga series about a robotic cat from the 22nd century who travels back in time to help a young boy named Nobita Nobi.', 9, '2025-09-18 15:23:19'),
(152, 'Dragon Ball Vol. 1', 'Akira Toriyama', 2.40, 150, 'The first volume of the iconic manga series that follows the adventures of Son Goku as he trains in martial arts and explores the world in search of the seven magical orbs known as the Dragon Balls.', 9, '2025-09-18 15:23:19'),
(153, 'One Piece Vol. 1', 'Eiichiro Oda', 2.60, 180, 'The first volume of the best-selling manga series about a young boy named Monkey D. Luffy who embarks on a journey to find the legendary treasure One Piece and become the King of the Pirates.', 9, '2025-09-18 15:23:19'),
(154, 'Naruto Vol. 1', 'Masashi Kishimoto', 2.20, 160, 'The first volume of the popular manga series that tells the story of a young ninja named Naruto Uzumaki who dreams of becoming the leader of his village, the Hokage.', 9, '2025-09-18 15:23:19'),
(155, 'Attack on Titan Vol. 1', 'Hajime Isayama', 3.00, 130, 'The first volume of the critically acclaimed manga series set in a world where humanity lives inside cities surrounded by enormous walls to protect themselves from gigantic humanoid creatures known as Titans.', 9, '2025-09-18 15:23:19'),
(156, 'Fullmetal Alchemist Vol. 1', 'Hiromu Arakawa', 2.80, 140, 'The first volume of the manga series about two brothers, Edward and Alphonse Elric, who embark on a journey to find the mythical Philosopher\'s Stone to restore their bodies after a failed alchemical attempt to resurrect their mother.', 9, '2025-09-18 15:23:19'),
(157, 'Death Note Vol. 1', 'Tsugumi Ohba', 2.80, 120, 'The first volume of the manga series that tells the story of a high school student named Light Yagami who finds a supernatural notebook that gives him the power to kill anyone whose name he writes in it.', 9, '2025-09-18 15:23:19'),
(158, 'Cooking for Dummies', 'Bryan Miller', 6.00, 50, 'A beginner\'s guide to cooking. It provides easy-to-follow recipes and tips on how to cook a variety of dishes, from simple meals to complex gourmet creations.', 12, '2025-09-18 15:23:19'),
(159, 'The Joy of Cooking', 'Irma S. Rombauer', 8.00, 35, 'A classic cookbook that has been in print for decades. It is a comprehensive guide to cooking, baking, and entertaining, with recipes for every occasion.', 12, '2025-09-18 15:23:19'),
(160, 'How to Cook Everything', 'Mark Bittman', 7.20, 45, 'A cookbook that provides a collection of simple, accessible recipes for home cooks. The book is a comprehensive guide to cooking, with tips on everything from meal planning to knife skills.', 12, '2025-09-18 15:23:19'),
(161, 'National Geographic Traveler', 'National Geographic', 10.00, 30, 'A travel guide that provides a comprehensive overview of a destination, with tips on where to go, what to see, and what to do. It also includes stunning photography and historical context.', 13, '2025-09-18 15:23:19'),
(162, 'Lonely Planet: The World', 'Lonely Planet', 14.00, 19, 'A comprehensive travel guide that covers every country in the world. It provides detailed information on destinations, attractions, and cultural experiences, with tips on how to travel on a budget.', 13, '2025-09-18 15:23:19'),
(163, 'The Road Less Traveled', 'M. Scott Peck', 6.40, 70, 'A non-fiction book that explores the concept of psychological and spiritual growth. The book provides a framework for living a more fulfilling life and achieving personal growth.', 2, '2025-09-18 15:23:19'),
(164, 'The Power of Now', 'Eckhart Tolle', 7.20, 60, 'A self-help book that explores the importance of living in the present moment. The book provides a guide to overcoming anxiety and finding inner peace.', 2, '2025-09-18 15:23:19'),
(165, 'The Four Agreements', 'Don Miguel Ruiz', 3.80, 100, 'A self-help book that provides a framework for personal freedom and happiness. The book outlines four agreements that can help readers overcome self-limiting beliefs and find a new sense of purpose.', 2, '2025-09-18 15:23:19'),
(166, 'The Secret', 'Rhonda Byrne', 4.40, 85, 'A self-help book that explores the concept of the law of attraction. The book provides a framework for achieving your goals and living a more fulfilling life by using the power of your thoughts.', 2, '2025-09-18 15:23:19'),
(167, 'The 5 AM Club', 'Robin Sharma', 5.60, 75, 'A self-help book that provides a framework for achieving success and happiness by waking up at 5 am. The book outlines a morning routine that can help readers improve their productivity and mindset.', 2, '2025-09-18 15:23:19'),
(168, 'The Art of War', 'Sun Tzu', 3.60, 100, 'A classic Chinese military treatise. The book is a timeless guide to strategy and tactics and has been applied to various fields, including business, politics, and sports.', 3, '2025-09-18 15:23:19'),
(169, 'The Wealth of Nations', 'Adam Smith', 11.20, 30, 'A classic book on economics. The book is a foundational work of modern economics and explores the principles of capitalism, including the division of labor, free markets, and the role of government.', 3, '2025-09-18 15:23:19'),
(170, 'Das Kapital', 'Karl Marx', 14.00, 25, 'A classic book on economics and political philosophy. The book is a foundational work of Marxist theory and explores the principles of capitalism, including the exploitation of labor, class struggle, and the role of government.', 6, '2025-09-18 15:23:19'),
(171, 'Moby-Dick', 'Herman Melville', 6.00, 45, 'A classic American novel that tells the story of a sea captain\'s obsessive pursuit of a giant white whale. The novel explores themes of obsession, revenge, and the human spirit.', 1, '2025-09-18 15:23:19'),
(172, 'One Hundred Years of Solitude', 'Gabriel Garcia Marquez', 7.60, 50, 'A classic novel that tells the story of the Buendía family over seven generations in the fictional town of Macondo. The novel is a masterpiece of magical realism and explores themes of love, loss, and the cyclical nature of history.', 1, '2025-09-18 15:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `book_images`
--

CREATE TABLE `book_images` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_cover` tinyint(1) NOT NULL DEFAULT 0,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_images`
--

INSERT INTO `book_images` (`id`, `book_id`, `image_path`, `is_cover`, `uploaded_at`) VALUES
(27, 22, 'cover_22.jpg', 1, '2025-09-18 15:23:19'),
(28, 23, 'cover_23.jpg', 1, '2025-09-18 15:23:19'),
(29, 24, 'cover_24.jpg', 1, '2025-09-18 15:23:19'),
(30, 25, 'cover_25.jpg', 1, '2025-09-18 15:23:19'),
(31, 26, 'cover_26.jpg', 1, '2025-09-18 15:23:19'),
(32, 27, 'cover_27.jpg', 1, '2025-09-18 15:23:19'),
(33, 28, 'cover_28.jpg', 1, '2025-09-18 15:23:19'),
(34, 29, 'cover_29.jpg', 1, '2025-09-18 15:23:19'),
(35, 30, 'cover_30.jpg', 1, '2025-09-18 15:23:19'),
(37, 32, 'cover_32.jpg', 1, '2025-09-18 15:23:19'),
(39, 34, 'cover_34.jpg', 1, '2025-09-18 15:23:19'),
(43, 38, 'cover_38.jpg', 1, '2025-09-18 15:23:19'),
(44, 39, 'cover_39.jpg', 1, '2025-09-18 15:23:19'),
(55, 50, 'cover_50.jpg', 1, '2025-09-18 15:23:19'),
(57, 52, 'cover_52.jpg', 1, '2025-09-18 15:23:19'),
(58, 53, 'cover_53.jpg', 1, '2025-09-18 15:23:19'),
(60, 55, 'cover_55.jpg', 1, '2025-09-18 15:23:19'),
(61, 56, 'cover_56.jpg', 1, '2025-09-18 15:23:19'),
(62, 57, 'cover_57.jpg', 1, '2025-09-18 15:23:19'),
(64, 59, 'cover_59.jpg', 1, '2025-09-18 15:23:19'),
(86, 81, 'cover_81.jpg', 1, '2025-09-18 15:23:19'),
(87, 82, 'cover_82.jpg', 1, '2025-09-18 15:23:19'),
(88, 83, 'cover_83.jpg', 1, '2025-09-18 15:23:19'),
(89, 84, 'cover_84.jpg', 1, '2025-09-18 15:23:19'),
(90, 85, 'cover_85.jpg', 1, '2025-09-18 15:23:19'),
(91, 86, 'cover_86.jpg', 1, '2025-09-18 15:23:19'),
(92, 87, 'cover_87.jpg', 1, '2025-09-18 15:23:19'),
(93, 88, 'cover_88.jpg', 1, '2025-09-18 15:23:19'),
(96, 91, 'cover_91.jpg', 1, '2025-09-18 15:23:19'),
(98, 93, 'cover_93.jpg', 1, '2025-09-18 15:23:19'),
(99, 94, 'cover_94.jpg', 1, '2025-09-18 15:23:19'),
(100, 95, 'cover_95.jpg', 1, '2025-09-18 15:23:19'),
(101, 96, 'cover_96.jpg', 1, '2025-09-18 15:23:19'),
(102, 97, 'cover_97.jpg', 1, '2025-09-18 15:23:19'),
(105, 100, 'cover_100.jpg', 1, '2025-09-18 15:23:19'),
(107, 102, 'cover_102.jpg', 1, '2025-09-18 15:23:19'),
(113, 108, 'cover_108.jpg', 1, '2025-09-18 15:23:19'),
(118, 113, 'cover_113.jpg', 1, '2025-09-18 15:23:19'),
(156, 151, 'cover_151.jpg', 1, '2025-09-18 15:23:19'),
(157, 152, 'cover_152.jpg', 1, '2025-09-18 15:23:19'),
(158, 153, 'cover_153.jpg', 1, '2025-09-18 15:23:19'),
(159, 154, 'cover_154.jpg', 1, '2025-09-18 15:23:19'),
(160, 155, 'cover_155.jpg', 1, '2025-09-18 15:23:19'),
(161, 156, 'cover_156.jpg', 1, '2025-09-18 15:23:19'),
(162, 157, 'cover_157.jpg', 1, '2025-09-18 15:23:19'),
(163, 158, 'cover_158.jpg', 1, '2025-09-18 15:23:19'),
(164, 159, 'cover_159.jpg', 1, '2025-09-18 15:23:19'),
(165, 160, 'cover_160.jpg', 1, '2025-09-18 15:23:19'),
(166, 161, 'cover_161.jpg', 1, '2025-09-18 15:23:19'),
(167, 162, 'cover_162.jpg', 1, '2025-09-18 15:23:19'),
(168, 163, 'cover_163.jpg', 1, '2025-09-18 15:23:19'),
(169, 164, 'cover_164.jpg', 1, '2025-09-18 15:23:19'),
(170, 165, 'cover_165.jpg', 1, '2025-09-18 15:23:19'),
(171, 166, 'cover_166.jpg', 1, '2025-09-18 15:23:19'),
(172, 167, 'cover_167.jpg', 1, '2025-09-18 15:23:19'),
(173, 168, 'cover_168.jpg', 1, '2025-09-18 15:23:19'),
(174, 169, 'cover_169.jpg', 1, '2025-09-18 15:23:19'),
(175, 170, 'cover_170.jpg', 1, '2025-09-18 15:23:19'),
(176, 171, 'cover_171.jpg', 1, '2025-09-18 15:23:19'),
(177, 172, 'cover_172.jpg', 1, '2025-09-18 15:23:19'),
(197, 22, 'cover_22.jpg', 1, '2025-09-18 15:23:19'),
(198, 23, 'cover_23.jpg', 1, '2025-09-18 15:23:19'),
(199, 24, 'cover_24.jpg', 1, '2025-09-18 15:23:19'),
(200, 25, 'cover_25.jpg', 1, '2025-09-18 15:23:19'),
(201, 26, 'cover_26.jpg', 1, '2025-09-18 15:23:19'),
(202, 27, 'cover_27.jpg', 1, '2025-09-18 15:23:19'),
(203, 28, 'cover_28.jpg', 1, '2025-09-18 15:23:19'),
(204, 29, 'cover_29.jpg', 1, '2025-09-18 15:23:19'),
(205, 30, 'cover_30.jpg', 1, '2025-09-18 15:23:19'),
(207, 32, 'cover_32.jpg', 1, '2025-09-18 15:23:19'),
(209, 34, 'cover_34.jpg', 1, '2025-09-18 15:23:19'),
(213, 38, 'cover_38.jpg', 1, '2025-09-18 15:23:19'),
(214, 39, 'cover_39.jpg', 1, '2025-09-18 15:23:19'),
(225, 50, 'cover_50.jpg', 1, '2025-09-18 15:23:19'),
(227, 52, 'cover_52.jpg', 1, '2025-09-18 15:23:19'),
(228, 53, 'cover_53.jpg', 1, '2025-09-18 15:23:19'),
(230, 55, 'cover_55.jpg', 1, '2025-09-18 15:23:19'),
(231, 56, 'cover_56.jpg', 1, '2025-09-18 15:23:19'),
(232, 57, 'cover_57.jpg', 1, '2025-09-18 15:23:19'),
(234, 59, 'cover_59.jpg', 1, '2025-09-18 15:23:19'),
(256, 81, 'cover_81.jpg', 1, '2025-09-18 15:23:19'),
(257, 82, 'cover_82.jpg', 1, '2025-09-18 15:23:19'),
(258, 83, 'cover_83.jpg', 1, '2025-09-18 15:23:19'),
(259, 84, 'cover_84.jpg', 1, '2025-09-18 15:23:19'),
(260, 85, 'cover_85.jpg', 1, '2025-09-18 15:23:19'),
(261, 86, 'cover_86.jpg', 1, '2025-09-18 15:23:19'),
(262, 87, 'cover_87.jpg', 1, '2025-09-18 15:23:19'),
(263, 88, 'cover_88.jpg', 1, '2025-09-18 15:23:19'),
(266, 91, 'cover_91.jpg', 1, '2025-09-18 15:23:19'),
(268, 93, 'cover_93.jpg', 1, '2025-09-18 15:23:19'),
(269, 94, 'cover_94.jpg', 1, '2025-09-18 15:23:19'),
(270, 95, 'cover_95.jpg', 1, '2025-09-18 15:23:19'),
(271, 96, 'cover_96.jpg', 1, '2025-09-18 15:23:19'),
(272, 97, 'cover_97.jpg', 1, '2025-09-18 15:23:19'),
(275, 100, 'cover_100.jpg', 1, '2025-09-18 15:23:19'),
(277, 102, 'cover_102.jpg', 1, '2025-09-18 15:23:19'),
(283, 108, 'cover_108.jpg', 1, '2025-09-18 15:23:19'),
(288, 113, 'cover_113.jpg', 1, '2025-09-18 15:23:19'),
(326, 151, 'cover_151.jpg', 1, '2025-09-18 15:23:19'),
(327, 152, 'cover_152.jpg', 1, '2025-09-18 15:23:19'),
(328, 153, 'cover_153.jpg', 1, '2025-09-18 15:23:19'),
(329, 154, 'cover_154.jpg', 1, '2025-09-18 15:23:19'),
(330, 155, 'cover_155.jpg', 1, '2025-09-18 15:23:19'),
(331, 156, 'cover_156.jpg', 1, '2025-09-18 15:23:19'),
(332, 157, 'cover_157.jpg', 1, '2025-09-18 15:23:19'),
(333, 158, 'cover_158.jpg', 1, '2025-09-18 15:23:19'),
(334, 159, 'cover_159.jpg', 1, '2025-09-18 15:23:19'),
(335, 160, 'cover_160.jpg', 1, '2025-09-18 15:23:19'),
(336, 161, 'cover_161.jpg', 1, '2025-09-18 15:23:19'),
(337, 162, 'cover_162.jpg', 1, '2025-09-18 15:23:19'),
(338, 163, 'cover_163.jpg', 1, '2025-09-18 15:23:19'),
(339, 164, 'cover_164.jpg', 1, '2025-09-18 15:23:19'),
(340, 165, 'cover_165.jpg', 1, '2025-09-18 15:23:19'),
(341, 166, 'cover_166.jpg', 1, '2025-09-18 15:23:19'),
(342, 167, 'cover_167.jpg', 1, '2025-09-18 15:23:19'),
(343, 168, 'cover_168.jpg', 1, '2025-09-18 15:23:19'),
(344, 169, 'cover_169.jpg', 1, '2025-09-18 15:23:19'),
(345, 170, 'cover_170.jpg', 1, '2025-09-18 15:23:19'),
(346, 171, 'cover_171.jpg', 1, '2025-09-18 15:23:19'),
(347, 172, 'cover_172.jpg', 1, '2025-09-18 15:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `book_id`, `quantity`, `created_at`) VALUES
(42, 10, 25, 2, '2025-09-21 18:07:29'),
(44, 9, 162, 1, '2025-09-22 08:57:21');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Classic Literature'),
(2, 'Personal development'),
(3, 'Economics & Business'),
(5, 'Science Fiction'),
(6, 'History & Politics'),
(7, 'Detective and Horror'),
(8, 'Art & Design'),
(9, 'Manga & Comics'),
(10, 'Fantasy'),
(11, 'Science'),
(12, 'Cooking'),
(13, 'Travel'),
(14, 'Poetry'),
(15, 'Children'),
(16, 'Horror');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','cancelled') NOT NULL DEFAULT 'pending',
  `customer_name` varchar(100) NOT NULL,
  `delivery_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `customer_name`, `delivery_address`, `phone_number`, `created_at`) VALUES
(9, 9, 247.00, 'shipped', 'Linh Nguyen', 'KHU 4-XA TU HIEP-HUYEN HA HOA-TINH PHU THO-VIET NAM', '0356849054', '2025-09-21 17:32:09'),
(10, 9, 7.00, 'shipped', 'Linh Nguyen', 'KHU 6-XA TU HIEP-HUYEN HA HOA-TINH PHU THO-VIET NAM', '0356849054', '2025-09-21 17:33:35'),
(11, 10, 7.84, 'shipped', 'Kien depzai', 'dg', '0356849054', '2025-09-22 06:00:33'),
(12, 9, 2.00, 'shipped', 'Linh Nguyen', 'KHU 4-XA TU HIEP-HUYEN HA HOA-TINH PHU THO-VIET NAM', '0356849054', '2025-09-22 06:18:09'),
(13, 9, 14.00, 'pending', 'Linh Nguyen', 'KHU 4-XA TU HIEP-HUYEN HA HOA-TINH PHU THO-VIET NAM', '0356849054', '2025-09-22 08:57:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `book_id`, `quantity`, `price`) VALUES
(17, 9, 108, 65, 3.80),
(18, 10, 26, 1, 7.00),
(19, 11, 25, 2, 3.92),
(20, 12, 151, 1, 2.00),
(21, 13, 162, 1, 14.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'Admin Bookstore', 'admin@bookstore.com', '$2y$10$EAC7E6cIqVGbPSk2Xg/y.u0g1Z.0.j6.J9.Cg.QpXf.Gk.J2H5L8i', '0987654321', '123 Admin Street, Hanoi', 'admin', '2025-09-08 12:53:23'),
(2, 'Nguyễn Văn An', 'an.nguyen@example.com', '$2y$10$gO6.g7e.h9f.Jk5.p6d.Qe.R7a.S8c.T9u.V0w.X1y.Z2A.B3c.D4e', '0123456789', '456 Đường Láng, Đống Đa, Hà Nội', 'customer', '2025-09-08 12:53:23'),
(3, 'Trần Thị Bích', 'bich.tran@example.com', '$2y$10$gO6.g7e.h9f.Jk5.p6d.Qe.R7a.S8c.T9u.V0w.X1y.Z2A.B3c.D4e', '0912345678', '789 Phố Huế, Hai Bà Trưng, Hà Nội', 'customer', '2025-09-08 12:53:23'),
(4, 'Nguyễn Đình Chính', 'nhockk2003@gmail.com', '$2y$10$rDtoUGCUsAf8pGwMrObbIe/wTHl1NyBF/SMmobhCd9HGGEnzB86wu', NULL, NULL, 'admin', '2025-09-08 13:21:05'),
(6, 'Chinh', 'nguyenchinhpt03@gmail.com', '$2y$10$B8PObKTQUdfEx0R5WO0GSOP5YGdSuPpRlwOKUeaEq6IyYDrqxgriG', NULL, NULL, 'admin', '2025-09-13 08:16:20'),
(7, 'hanh', 'hanhdk@gmail.com', '$2y$10$80/2BY5qvheWnjf6.ziKleY1X9m5rMacpvvjG.l1DQAIC/./RuNMi', NULL, NULL, 'customer', '2025-09-13 08:33:38'),
(8, 'chinh123', 'hanhdk1212@gmail.com', '$2y$10$lXpaDl47yBXhm0X2uYM0ZeU7XjzP94n51URgBrhsCBmTAc3sVHTSy', NULL, NULL, 'customer', '2025-09-15 15:01:39'),
(9, 'Linh Nguyen', 'gg112233@gmail.com', '$2y$10$Oxazfx3XR5MA0sCVDQ8b9.CQfIiNtUfbsGcEmj0ldC6ZD1hxZam6K', NULL, NULL, 'customer', '2025-09-21 17:31:44'),
(10, 'Kien depzai', 'vinh123@gmail.com', '$2y$10$60J20UvPFU8EVZ0Pr8cmju9SU3nL71M/VduOrJnsZeqpzwyou2cWa', '0356849123', 'nguyên xá minh khai nam tư liem', 'customer', '2025-09-21 18:06:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `book_images`
--
ALTER TABLE `book_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_book_unique` (`user_id`,`book_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `book_images`
--
ALTER TABLE `book_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=366;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `book_images`
--
ALTER TABLE `book_images`
  ADD CONSTRAINT `book_images_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
