-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 11:02 PM
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
-- Database: `backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250323180456', '2025-03-23 18:05:09', 31);

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `days` int(11) NOT NULL,
  `person` int(11) NOT NULL,
  `info` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `kategorie_id` int(11) NOT NULL,
  `rating` decimal(3,1) DEFAULT NULL,
  `stars` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id`, `title`, `location`, `image`, `price`, `days`, `person`, `info`, `description`, `created_at`, `kategorie_id`, `rating`, `stars`) VALUES
(2, 'Hotel Condesa', 'Mallorca, Port d\'Alcúdia', '67fe5d45e3749.jpg', 910, 5, 2, 'DZ, Frühstück inkl. Flug', 'Nicht nur einen Traumblick auf das Mittelmeer erhalten Urlauber in diesem Hotel auf Mallorca. Das Haus liegt direkt am goldenen Sandstrand der Playa de Alcudia. Auch im All-Inclusive-Restaurant bleiben keine W&uuml;nsch offen: abwechslungsreiche internationale Spezialit&auml;ten lassen keine Langeweile auf dem Teller aufkommen und Themenbuffets runden das Angebot f&uuml;r Gro&szlig; und Klein ab. Abk&uuml;hlung finden G&auml;ste in den 3 Au&szlig;enpools, zudem l&auml;dt die Gartenanlage zu abendlichen Spazierg&auml;ngen ein. Auf dem hotelinternen Wasserspielplatz k&ouml;nnen sich kleine G&auml;ste nach Herzenslust austoben. Ein besonderes Augenmerk legt das Hotel auf Radfahrer: Leihr&auml;der, Fahrradwerkstatt und Verpflegungspakete bieten Aktiven einen sorgenfreien Ausflug in das Hinterland der Insel.', '2025-03-24 09:57:00', 1, 8.2, 4),
(9, 'Hotel Timor', 'Mallorca, Playa de Palma', '67fe5da791036.jpg', 888, 5, 3, 'DZ, Frühstück inkl. Flug', 'Auf der sonnenverw&ouml;hnten Baleareninsel Mallorca begr&uuml;&szlig;t dieses Hotel seine G&auml;ste nur einen Katzensprung vom Mittelmeer entfernt. In der gro&szlig;z&uuml;gigen Au&szlig;enanlage sorgt neben der weitl&auml;ufigen, von Palmen ges&auml;umten Poollandschaft auch eine Sonnenterrasse f&uuml;r Entspannung pur. Kleine G&auml;ste planschen ausgelassen in mehreren Kinderbecken, toben auf dem Spielplatz oder haben im hauseigenen Mini-Club Spa&szlig;. Erfrischende Drinks und Cocktails werden indes an der Pool- und Snackbar direkt am Wasser serviert und auch leckere H&auml;ppchen gibt es f&uuml;r Zwischendurch. Zu jeder Mahlzeit erleben Urlauber die kulinarischen Gen&uuml;sse der spanischen und internationalen K&uuml;che am reichhaltigen Buffet.', '2025-03-25 12:13:00', 1, 8.2, 4),
(10, 'Hotel Mariant Park', 'Mallorca, S\'Illot', '680210b3ef5ff.jpg', 924, 5, 2, 'Familienzimmer, All Inclusive inkl. Flug', 'Auf der sonnenverwöhnten Baleareninsel Mallorca begrüßt dieses Hotel seine Gäste nur einen Katzensprung vom Mittelmeer entfernt. In der großzügigen Außenanlage sorgt neben der weitläufigen, von Palmen gesäumten Poollandschaft auch eine Sonnenterrasse für Entspannung pur. Kleine Gäste planschen ausgelassen in mehreren Kinderbecken, toben auf dem Spielplatz oder haben im hauseigenen Mini-Club Spaß. Erfrischende Drinks und Cocktails werden indes an der Pool- und Snackbar direkt am Wasser serviert und auch leckere Häppchen gibt es für Zwischendurch. Zu jeder Mahlzeit erleben Urlauber die kulinarischen Genüsse der spanischen und internationalen Küche am reichhaltigen Buffet.', '2025-03-25 14:12:00', 1, 7.8, 4),
(11, 'Bella Playa Hotel & Spa', 'Mallorca, Cala Ratjada', '67fe5ec2e80b7.jpg', 1034, 5, 2, 'DZ, Frühstück inkl. Flug', 'Das moderne und stilvolle Hotel besticht durch seinen zentralen Standort in Playa de Palma auf Mallorca, das gleichzeitig nur einen Katzensprung vom gleichnamigen Sandstrand entfernt ist. Doch auch die Anlage selbst bietet Entspannung – dafür sorgen 2 erfrischende Pools sowie ein wohltuendes Spa. Dort erwarten Urlauber verschiedene Behandlungen, wie zum Beispiel Massagen, Sauna, Türkisches Bad oder Erlebnisduschen. Kleine Gäste planschen derweil im Kinderbecken. Wer sich aktiv betätigen möchte und Lust hat, die Insel kennenzulernen, kann ein Fahrrad leihen, um eine Erkundungstour zu starten. Anschließend lockt die Poolbar auf einen leckeren Cocktail, den Reisende zusammen mit dem Sonnenuntergang genießen.', '2025-03-25 14:50:00', 1, 8.7, 4),
(12, 'Hotel Riu Concordia', 'Mallorca, Playa de Palma', 'f75d7a0e7aa50d784a3bcf469e8ffd1d.jpg', 1096, 5, 2, 'DZ, Frühstück inkl. Flug', 'Von diesem fantastischen Hotel an der Türkischen Riviera gelangen die Gäste in wenigen Minuten zum hoteleigenen Strand. Hier können die Badeurlauber auf Liegen entspannt die Sonne genießen oder sich bei Wassersportarten und beim Beachvolleyball austoben. Die liebevoll gestaltete Anlage überzeugt mit einer exklusiven Poollandschaft, die mit verschiedenen Wasserrutschen auch kleine Wasserratten begeistert. Wer einmal so richtig abschalten möchte, ist im Spa- und Wellnessbereich des Resorts genau richtig: Hier finden Ruhesuchende neben Sauna, Hamam und Dampfbad auch professionelle Massagen. Abgesehen vom herausragenden Hauptrestaurant hält das Hotel zudem 2 A-la-carte-Restaurants für seine hungrigen und genussfreudigen Gäste bereit.', '2025-03-25 19:59:00', 1, 8.7, 4),
(13, 'Blau Punta Reina', 'Mallorca, Cala Mandia (Porto Cristo Novo)', '67fe5ffc261fd.jpg', 1098, 5, 2, 'DZ, HP Plus inkl. Flug', 'Malerisch auf einem Felsplateau gelegen, bietet dieses Resort einen einzigartigen Panoramablick auf das azurblaue Mittelmeer und die umliegende Klippenlandschaft. Nur fünf Kilometer trennen das familienfreundliche Haus an der Cala Mandia vom beliebten Hafenstädtchen Porto Cristo. Der für seine naturbelassenen Tropfsteinhöhlen bekannte Touristenort im Osten Mallorcas eignet sich hervorragend als Ziel einer Fahrradtour. Wer lieber am Strand entspannt, findet unweit des Hotels gleich zwei Badebuchten mit weißem, feinkörnigem Sandstrand. Ob Wassersport, Minigolf, Bogenschießen oder ein Aufenthalt im Spa-Bereich – diese Ferienanlage bietet Möglichkeiten, schöne Stunden unter der spanischen Sonne zu verbringen.', '2025-03-27 13:15:00', 1, 8.2, 4),
(14, 'Hipotels Mediterráneo', 'Mallorca, Sa Coma', '68021127db4b0.jpg', 1199, 5, 2, 'DZ, ohne Verpflegung inkl. Flug', 'Das Hostal Cas Bombu wird seit 1885 familiengeführt und befindet sich weniger als 100 m vom mallorquinischen Hafen Cala Rajada entfernt. Freuen Sie sich auf eine kostenfreie WLAN-Zone und kostenfreie Parkplätze in der Nähe. Das Cas Bombu bietet Holzbalken im Kolonialstil und Schwarz-Weiße-Fliesenböden. Die Zimmer verfügen über einen Ventilator und einen Schreibtisch und einige Zimmer bieten auch einen eigenen Balkon. Das eigene Bad ist mit einer Dusche ausgestattet. In der Cas Bombu Pension finden Sie auch eine Loungebar mit Sat-TV und eine Gartenterrasse. Im Spielebereich stehen Tischtennis und Billard zur Verfügung.Eine 24-Stunden-Rezeption und ein Tourenschalter gehören zu den weiteren Annehmlichkeiten. Zum Golfverein Capdepera fahren Sie 10 Minuten. Das Hotel bietet gegen Aufpreis einen Flughafentransfer und der Flughafen Palma liegt 80 km entfernt.', '2025-03-27 13:47:00', 1, 9.1, 4),
(15, 'Hipotels Gran Playa de Palma', 'Mallorca, Playa de Palma', '67fe61671f172.jpg', 1248, 5, 2, 'DZ, Frühstück inkl. Flug', 'Umgeben von reizvollen Gärten ist dieses attraktive Hotel exzellent direkt am wundervollen Sandstrand von Muro gelegen. Der Naturpark von SAlbufera mit seinem See ist nur wenige Schritte entfernt, die Orte Can Picafort und Alcúdia mit seiner römischen Architektur sind leicht zu erreichen. Die Inselhauptstadt Palma und der internationale Flughafenkönnen in einer Autostunde erreicht werden.', '2025-03-27 13:49:00', 1, 9.1, 4),
(16, 'Playa Esperanza Resort', 'Mallorca, Playa de Muro', '67fe61f0d5307.jpg', 1324, 5, 2, 'DZ, ohne Verpflegung inkl. Flug', 'Der attraktive Apartmentkomplex befindet sich in Ca\'n Picafort direkt im Touristenzentrum. Die öffentlichen Verkehrsmittel sind nur ca. 50 m vom Hotel entfernt. Weiterhin liegt der neue und beliebte Yachthafen ganz in der Nähe. In der näheren Umgebung erwarten Sie vielfältige Einkaufsmöglichkeiten (etwa 200 m), zahlreiche Restaurants (ca. 50 m), diverse Bars (ungefähr 50 m) und umfangreiche andere Unterhaltungsmöglichkeiten. Eine Diskothek befindet sich ebenfalls gleich in der Nähe (ca. 500 m entfernt). Der einladende Strand mit seinem türkisblauen Meer liegt ca. 500 m vom Hotel entfernt. Weiterhin befindet sich das Stadtzentrum in der Nähe (etwa 500 m entfernt). Eine Bahnstation liegt ca. 19 km und der Flughafen Palma de Mallorca etwa 63 km vom Hotel entfernt. Der moderne Apartmentkomplex verfügt auf 6 Etagen über insgesamt 30 Apartments. Die Anlage bietet seinen Gästen eine Empfangshalle mit Rezeption (24 Stunden besetzt), Hotelsafe, Wechselstube, Aufzug und Café. Im Haus stehen Ihnen eine Bar und ein TV-Raum zur Verfügung. Außerdem verfügt das Gebäude über einen Wäsche- und medizinischen Service. Die modern eingerichteten Studios und Apartments verfügen über einen kombinierten Wohn-/Schlafraum und sind mit einem Bad/WC, Dusche, Kochnische, Kühlschrank, Ventilator und Mietsafe ausgestattet. Weiterhin verfügen die geschmackvoll eingerichteten Studios und Apartments über einen Balkon oder eine Terrasse. Die Apartments sind zusätzlich mit einem separaten Schlafzimmer ausgestattet.', '2025-03-27 16:21:00', 1, 8.7, 4),
(17, 'allsun Hotel Eden Playa', 'Mallorca, Playa de Muro', '67fe626d881d5.jpg', 1788, 5, 2, 'Suite, All Inclusive inkl. Flug', 'Im Osten der Insel Mallorca liegt im Ort Cala Ratjada dieses Hotel. Das Erwachsenenhotel bietet aufgrund seiner zentralen Lage den perfekten Ausgangspunkt, um das Nachtleben mit zahlreichen Discos und Restaurants schnell zu erreichen. Ins Zentrum sind es nur 300 Meter. Gäste, die andere Orte besichtigen möchten, können den Bus nutzen, dessen Haltestelle etwa 200 Meter entfernt liegt. Einen Besuch sollten Urlauber auch dem Sandstrand Cala Son Moll abstatten. Wer noch mehr vom Mittelmeer sehen möchte, gelangt mit der Fähre innerhalb von 75 Minuten auf die Nachbarinsel Menorca.', '2025-03-27 17:03:00', 1, 8.9, 4),
(20, 'Dream Water World', 'Side & Alanya, Kumköy (Side)', 'f43fe83eda73fba822ee96c842fd75f9.jpg', 888, 5, 2, 'DZ, All Inclusive inkl. Flug', 'Das Hotel Dream Water World Hotel befindet sich in der Umgebung des Kumkoy, einem privaten Sand-/Kiesstrand, zu dem ein kostenloses Shuttle angeboten wird (ca. 800m). Am Strand sind Sonnenliegen und Sonnenschirme kostenlos verfügbar. Zum touristischen Zentrum mit Geschäften, Bars, Restaurants und einem Krankenhaus sind es ca. 5 km. Der Flughafen (AYT) ist ca. 61 km entfernt. Ein weiterer Flughafen (GZP) liegt in etwa 110 km Entfernung. Das Hotel verfügt über 350 Zimmer. Es bietet seinen Gästen eine Rezeption (Check In ab 14:00 Uhr, Check out bis 12:00 Uhr), eine Lobby mit Bar, einen Lift, eine Klimaanlage, einen Friseur, einen Shop sowie einen Parkplatz (ggf. gegen Gebühr). WLAN steht den Hotelgästen kostenlos zur Verfügung. Die Zimmerreinigung ist kostenlos. Zimmer-Service und Wäsche-/Bügel-Service sind gegen Gebühr. Zum Außenbereich des Hotels mit Wasserpark und Wasserrutsche gehören 2 Frischwasser-Pools mit einem Kinderbecken. Eine Bar am Pool bietet den Gästen erfrischende Getränke. Für bestimmte Einrichtungen oder Aktivitäten können zusätzliche Gebühren anfallen. Einige Dienstleistungen hängen von der Jahreszeit und den lokalen klimatischen Bedingungen ab. Die meisten Zimmer sind mit Laminat, Heizung (zentral gesteuert), Minibar (geg. Gebühr), Internet (kostenlos), Safe (geg. Gebühr) und Flatscreen-Sat-TV sowie zentral gesteuerter Klimaanlage ausgestattet. Familienzimmer können sowohl aus einem großen Raum als auch aus 2 Schlafzimmern (über eine Treppe oder Tür miteinander verbunden) bestehen. Servicesprachen: englisch, deutsch und türkisch.', '2025-04-15 15:47:00', 2, 8.0, 5),
(21, 'Seaden Sea World Resort & SPA', 'Side & Alanya, Kizilagac (Side)', '43dd8b9365fb8db7b845bbb968bb4e7e.jpg', 966, 5, 2, 'DZ, All Inclusive inkl. Flug', 'Diese weitläufige Anlage befindet sich direkt an der Küste der Türkischen Riviera. Der exklusive und familienfreundliche Gebäudekomplex legt seinen Schwerpunkt auf Animation und Wassersport. So reicht das reichhaltige Sportangebot vom gut ausgestatteten Fitnessstudio über Tennisplätze bis hin zu zahlreichen Wassersportarten, die jedoch gegen eine Gebühr angeboten werden. Zur komplementären Erholung wurde auf dem Gelände ein Spa-Center errichtet, das umfassende Wellness- und Massageprogramme anbietet. Zudem ist mit All Inclusive von 07.00 bis 24.00 Uhr bestens für das leibliche Wohl gesorgt. Kids ab 3 Jahre sind im Kids Club willkommen, während die Eltern gerne die Strand- und die Poolbar besuchen.', '2025-04-15 15:50:00', 2, 8.0, 5),
(22, 'Hane Family Resort', 'Side & Alanya, Evrenseki (Side)', '06540706b5b2f63dc02314d504a8bf29.jpg', 974, 5, 2, 'DZ, All Inclusive inkl. Flug', 'Dieses kinderfreundliche Clubhotel liegt im sonnenverwöhnten Ort Kumköy an der bei Urlaubern beliebten türkischen Südküste. Mit dem gepflegten Sandstrand in der Nähe eignet es sich gut für einen erholsamen Badeurlaub mit der ganzen Familie, ein breites Angebot vielfältiger Aktivitäten garantiert Spaß und Abwechslung für Groß und Klein. Die historische Stadt Side liegt nur wenige Kilometer entfernt und bietet neben attraktiven Einkaufs- und Unterhaltungsmöglichkeiten außerdem zahlreiche Sehenswürdigkeiten. Im Hotel selbst erwartete große wie kleine Gäste ein eigener großzügig angelegter Poolbereich. Die modernen Zimmer verfügen allesamt über einen Balkon und die gesamte Ferienanlage ist barrierefrei zugänglich.', '2025-04-15 15:53:00', 2, 8.9, 4),
(23, 'Dream World Palace', 'Side & Alanya, Colakli (Side)', 'd1f6fd11eff3a89c8a81fceecba8702a.jpg', 988, 5, 2, 'DZ, All Inclusive inkl. Flug', 'Das Hotel liegt in Colakli nur 350 Meter vom breiten, langen Sandstrand direkt am türkisblauen Mittelmeer der Türkei. Nach Side mit weiteren Unterhaltungs- und Einkaufsmöglichkeiten sind es ca. 12 Kilometer. Das moderne Resort erwartet seine Gäste mit einer großen Poollandschaft, umgeben von einer Sonnenterrasse mit Liegen und Sonnenschirmen. Für Spaß für die ganze Familie sorgt ein weiterer Außenpool mit Wasserrutschen.', '2025-04-15 15:55:00', 2, 8.4, 5),
(24, 'Diamond Premium Hotel & Spa', 'Side & Alanya, Titreyengöl (Side)', '8aad60eebd14141a103b3f229abfb727.jpg', 988, 5, 2, 'DZ, All Inclusive Plus inkl. Flug', 'Von diesem fantastischen Hotel an der Türkischen Riviera gelangen die Gäste in wenigen Minuten zum hoteleigenen Strand. Hier können die Badeurlauber auf Liegen entspannt die Sonne genießen oder sich bei Wassersportarten und beim Beachvolleyball austoben. Die liebevoll gestaltete Anlage überzeugt mit einer exklusiven Poollandschaft, die mit verschiedenen Wasserrutschen auch kleine Wasserratten begeistert. Wer einmal so richtig abschalten möchte, ist im Spa- und Wellnessbereich des Resorts genau richtig: Hier finden Ruhesuchende neben Sauna, Hamam und Dampfbad auch professionelle Massagen. Abgesehen vom herausragenden Hauptrestaurant hält das Hotel zudem 2 A-la-carte-Restaurants für seine hungrigen und genussfreudigen Gäste bereit.', '2025-04-15 15:57:00', 2, 8.4, 5),
(25, 'Vonresort Golden Beach', 'Side & Alanya, Colakli (Side)', '3cee88048e6dd5984c23c2bbda957b06.jpg', 1034, 5, 2, 'DZ, All Inclusive Plus inkl. Flug', 'Das weitläufige Areal dieses luxuriösen Hotels liegt nur wenige Kilometer vom Urlaubsort Side entfernt und wird einzig durch den feinsandigen Privatstrand von den azurblauen Wellen der türkischen Mittelmeerküste getrennt. Die komfortabel ausgestatteten Zimmer verfügen über Annehmlichkeiten wie eine Klimaanlage sowie einen eigenen Balkon oder Terrasse. Aktivurlauber wählen aus dem vielfältigen Sportangebot von Beachvolleyball bis Wassergymnastik oder nutzen den hoteleigenen Fitnessraum. Für Tiefenentspannung sorgt das traditionelle türkische Hammam oder eine wohltuende Massage im Spa. Abends klingt der Urlaubstag mit mediterranen und orientalischen Köstlichkeiten sowie abwechslungsreichen Liveshows aus.', '2025-04-15 16:17:00', 2, 8.6, 4),
(26, 'The Sense De Luxe Hotel', 'Side & Alanya, Kumköy (Side)', '172fb9660c8d4bc903ebb14945a41466.jpg', 1045, 5, 2, 'DZ, All Inclusive Plus inkl. Flug', 'Diese erstklassige und elegante Anlage befindet sich nicht weit entfernt vom Sandstrand (Badeschuhe werden aufgrund von vorgelagerter Felsplatte empfohlen) und dem Ortszentrum von Side an der Türkischen Rivera. Mehrere Pools mit Wasserrutschen und ein unterhaltsames Animationsprogramm für Groß und Klein machen das Hotel zur perfekten Urlaubsresidenz für Familien mit Kindern. Auch das kulinarische Angebot der 3 hoteleigenen Restaurants ist auf den anspruchsvollen Gaumen der Gäste abgestimmt und wird liebevoll und aufwendig angerichtet. Die Zimmer des Hotels lassen ebenfalls keinen Wunsch offen. Der Wellnessbereich und die vielfältige Auswahl an Sportmöglichkeiten runden das umfangreiche Angebot der Unterkunft ab.', '2025-04-15 16:19:00', 2, 8.4, 5),
(27, 'Side Stella Elite Resort & SPA', 'Side & Alanya, Kumköy (Side)', 'e972e2229b6b304878c5a8bc0f941865.png', 1110, 5, 2, 'DZ, All Inclusive Plus inkl. Flug', 'Die Unterkunft Side Stella Elite Resort & Spa - Adults Only hat ein Fitnesscenter, einen Garten, eine Terrasse und ein Restaurant in Side. Dieses Hotel mit 5 Sternen und kostenlosem WLAN bietet einen Zimmerservice und eine rund um die Uhr besetzte Rezeption. Dieses Hotel verfügt über einen Außenpool, einen Innenpool, einen Nachtclub und eine Gemeinschaftslounge.In der Unterkunft Side Stella Elite Resort & Spa - Adults Only ist jedes Zimmer ergänzt mit einer Klimaanlage, einem Schreibtisch, einem Flachbild-TV, einem eigenen Badezimmer, Bettwäsche, Handtüchern und einem Balkon mit Stadtblick. Alle Zimmer haben einen Safe.Gäste der Unterkunft Side Stella Elite Resort & Spa - Adults Only können ein als Buffet angebotenes Frühstück genießen.In der Unterkunft Side Stella Elite Resort & Spa - Adults Only können Gäste eine Sauna und ein türkisches Dampfbad nutzen. An der Unterkunft Side Stella Elite Resort & Spa - Adults Only können Sie Tischtennis und Darts spielen. Fahrradverleih und Autovermietung sind verfügbar.In der Nähe der Unterkunft Side Stella Elite Resort & Spa - Adults Only finden Sie die interessanten Orte Strand Kumköy Beach, Öffentlicher Strand Evrenseki und Öffentlicher Strand Side. Der nächstgelegene Flughafen ist der Flughafen Antalya, 64 km von der Unterkunft Side Stella Elite Resort & Spa - Adults Only entfernt. Die Unterkunft bietet einen kostenpflichtigen Flughafentransfer.', '2025-04-15 16:22:00', 2, 8.4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id`, `name`) VALUES
(1, 'Mallorca'),
(2, 'Side & Alanya');

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(1, 'chef', '[]', '$2y$13$rsjd65c/zqLb0W0lV5QS4ONAbxjuX6.hGfU8ITaS9vxNzqlmxo4IK'),
(2, 'admin', '[]', '$2y$13$OxBfJNchxoRM5LJqlrCRBOdE3ExNTL6AlKQAYIpadEbY8T..T52za'),
(4, 'tad', '[]', '$2y$13$7o/DqfCUUMrOGT9Ky7dJRuIvY94fO1YLS29St5SzDlOhaGYI5rLqy'),
(5, 'serah', '[]', '$2y$13$ijDkzDeILLP77I47snCk/.7l.IlznGUMzkkFSrmkCD8BpJAzVUIkO'),
(6, 'talan', '[]', '$2y$13$zZIjDE.To.lFXUrmi9IlwecFp1NK1N0auLoRTdvBvS/eSBkNqi/Da'),
(7, 'majid', '[]', '$2y$13$nVpoWP0LWGVmzjW3bCMtbeUY46DcYbRDuCp8iP/CoRiKZsG1LKlz.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3535ED9BAF991D3` (`kategorie_id`);

--
-- Indexes for table `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hotel`
--
ALTER TABLE `hotel`
  ADD CONSTRAINT `FK_3535ED9BAF991D3` FOREIGN KEY (`kategorie_id`) REFERENCES `kategorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
