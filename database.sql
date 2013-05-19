DROP TABLE IF EXISTS users, deleted_users, groups, privileges, group_permissions, news, subpages, view_interestig, nameday;

CREATE TABLE users (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  group_id INT,
  login CHAR(20),
  pass CHAR(128),
  name CHAR(40),
  email CHAR(40),
  fbid CHAR(20)
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE deleted_users (
  user_id INT NOT NULL PRIMARY KEY,
  group_id INT,
  login CHAR(20),
  pass CHAR(128),
  name CHAR(40),
  email CHAR(40),
  fbid CHAR(20)
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE groups (
  group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name CHAR(50)
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE privileges (
  privilege_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name CHAR(50),
  description VARCHAR(500)
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE group_permissions (
  permission_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  group_id INT,
  privilege_id INT
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE news (
  news_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  sign CHAR(40),
  state ENUM('show', 'hide', 'not_accepted'),
  date DATE,
  title CHAR(200),
  short_text TEXT,
  text MEDIUMTEXT,
  image CHAR(200)
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE subpages (
  subpage_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name CHAR(20),
  title CHAR(200),
  text LONGTEXT
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE view_interestig (
  view_interesting_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  url CHAR(200),
  title CHAR(200),
  target CHAR(10),
  position INT NOT NULL,
  visible BOOLEAN
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

CREATE TABLE logs (
  log_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  message TEXT,
  time DATETIME
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

INSERT INTO groups (name) VALUES 
('Administrators'),
('Guests');

INSERT INTO users (group_id, login, pass, name) VALUES 
((SELECT group_id FROM groups WHERE name = 'Administrators'), 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'Administrator'),
((SELECT group_id FROM groups WHERE name = 'Guests'), 'guest', 'b0e0ec7fa0a89577c9341c16cff870789221b310a02cc465f464789407f83f377a87a97d635cac2666147a8fb5fd27d56dea3d4ceba1fc7d02f422dda6794e3c', 'Gość');

INSERT INTO privileges (name, description) VALUES 
('EditChanges', 'Zmienianie i ustalanie zmian na następny dzień'),
('EditUsers', 'Dodawanie, usuwanie i edytowanie użytkowników'),
('EditMenu', 'Edytowanie menu'),
('EditSubpages', 'Dodawanie, usuwanie i edytowanie podstron'),
('EditViewInteresting', 'Dodawanie, usuwanie i edytowanie elementów w zobacz na stronie głównej'),
('EditNews', 'Dodawanie, usuwanie i edytowanie (ale tylko własnych) aktualności'),
('EditNewsAll', 'Dodawanie, usuwanie i edytowanie wszystkich aktualności'),
('ViewLogs', 'Oglądanie logów strony'),
('FacebookAdmin', 'Moderowanie komentarzy i dostęp do statystyk strony');

INSERT INTO group_permissions (group_id, privilege_id) VALUES 
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditChanges')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditUsers')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditMenu')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditSubpages')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditViewInteresting')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditNews')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditNewsAll')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'ViewLogs')),
((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'FacebookAdmin'));

CREATE TABLE `nameday` (
  `id` int(10) NOT NULL PRIMARY KEY,
  `month` int(2),
  `day` int(2),
  `nameday` varchar(256)
) CHARACTER SET utf8 COLLATE utf8_polish_ci;

INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(1, 1, 1, 'Mieczysław|Mieszko|Maria|Mieczysława|Masław|Odys|Odyseusz|Wilhelm|Piotr'),
(2, 1, 2, 'Abel|Bazyli|Narcyz|Aspazja|Grzegorz|Sylwester|Sylwestra|Izydor|Makary|Odylon|Telesfor|Jakubina|Hortulana|Telesfora|Achacy|Achacjusz'),
(3, 1, 3, 'Arleta|Anter|Daniel|Danuta|Enoch|Meliton|Teonas|Genowefa|Teona|Piotr'),
(4, 1, 4, 'Aniela|Angelika|Benedykta|Dobromir|Eugeniusz|Krystiana|Grzegorz|Fereol|Tytus|Rygobert|Suligost'),
(5, 1, 5, 'Amata|Emilian|Emiliusz|Edward|Szymon|Piotr'),
(6, 1, 6, 'Andrzej|Jędrzej|Baltazar|Epifania|Fotyn|Fotyna|Kacper|Melchior|Norman|Miłowit|Miłwit'),
(7, 1, 7, 'Chociesław|Izydor|Julian|Lucjan|Kryspin|Rajmund|Walenty|Rajnold'),
(8, 1, 8, 'Mścisław|Seweryn|Teofil|Mroczysław'),
(9, 1, 9, 'Adrian|Antoni|Julian|Marcelin|Marcjanna|Przemir|Piotr'),
(10, 1, 10, 'Agaton|Danuta|Dobrosław|Jan|Kolumba|Nikanor|Paweł|Wilhelm|Anna|Leonia|Piotr'),
(11, 1, 11, 'Feliks|Hilary|Honorata|Hortensja|Hortensjusz|Hygin|Matylda|Odon|Krzesimir'),
(12, 1, 12, 'Antoni|Arkadiusz|Wiktorian|Bonet|Arkady|Benedykt|Bonitus|Czesława|Bonita|Ernest|Tacjan|Tacjana|Wiktoriana|Wiktorianna|Tygriusz|Piotr'),
(13, 1, 13, 'Bogumił|Bogusław|Gotfryd|Leoncjusz|Melania|Weronika'),
(14, 1, 14, 'Feliks|Hilary|Amadea|Krystiana|Nina|Otto|Rajner|Saba|Mściwuj|Piotr'),
(15, 1, 15, 'Aleksander|Dąbrówka|Dobrawa|Domosław|Izydor|Makary|Maur|Paweł|Ida|Micheasz|Piotr|Habakuk'),
(16, 1, 16, 'Marceli|Waldemar|Waleriusz|Treweriusz|Włodzimierz|Piotr'),
(17, 1, 17, 'Antoni|Jan|Alba|Sulpicjusz|Rościsław|Przemił'),
(18, 1, 18, 'Beatrycze|Bogumił|Krystyna|Liberata|Lubart|Małgorzata|Zuzanna|Wenerand'),
(19, 1, 19, 'Andrzej|Bernard|Erwin|Erwina|Eufemia|Henryk|Kanut|Mariusz|Marta|Matylda|Sara|Wulstan|Adalryk|Alderyk'),
(20, 1, 20, 'Dobiegniew|Fabian|Dobrożyźń|Sebastian'),
(21, 1, 21, 'Agnieszka|Patrokles|Epifani|Jarosław|Jarosława|Krystiana|Józefa|Awit|Awita'),
(22, 1, 22, 'Uriel|Anastazy|Dorian|Gaudencjusz|Gaudenty|Dobromysł|Wincenty|Jutrogost'),
(23, 1, 23, 'Emerencja|Ildefons|Jan|Klemens|Maria|Rajmund|Rajmunda'),
(24, 1, 24, 'Chwalibog|Franciszek|Milena|Wera|Ksenia|Rafał|Tymoteusz'),
(25, 1, 25, 'Miłosz|Paweł|Artemia|Albert|Miłobor'),
(26, 1, 26, 'Paula|Skarbimir|Leon|Tymoteusz|Tytus|Leona|Wanda|Żeligniew|Ksenofont|Małostryj'),
(27, 1, 27, 'Aniela|Rozalia|Jan Chryzostom|Jerzy|Julian|Przybysław|Adalruna|Alruna|Angelika|Teodoryk'),
(28, 1, 28, 'Augustyn|Manfred|Manfreda|Flawian|Ildefons|Julian|Karol|Piotr|Roger|Tomasz|Waleriusz|Walery'),
(29, 1, 29, 'Franciszek Salezy|Sulpicjusz|Ismena|Gildas|Konstancja|Waleriusz|Zdzisław'),
(30, 1, 30, 'Adelajda|Cyntia|Dobiegniew|Feliks|Gerard|Gerarda|Hiacynta|Maciej|Marcin|Martyna|Sebastian|Teofil|Adalgunda|Adelgunda|Piotr'),
(31, 1, 31, 'Cyrus|Euzebiusz|Jan|Ksawery|Ludwik|Marceli|Marcelin|Marcelina'),
(32, 2, 1, 'Brygida|Winand|Winanda|Seweryn|Żegota|Zybert|Zybracht|Zybart|Zygbert|Piotr'),
(33, 2, 2, 'Joanna|Katarzyna|Kornel|Maria|Ermentruda|Marcin|Miłosław|Miłosława|Mirosław|Marian|Teodoryk|Korneli|Korneliusz|Piotr'),
(34, 2, 3, 'Błażej|Oskar|Ignacy'),
(35, 2, 4, 'Joanna|Weronika|Częstogoj'),
(36, 2, 5, 'Saba|Agata|Lubodrog|Awit|Awita|Adelajda|Albwin|Dobiemir|Elpin|Indracht|Izydor|Jakub|Modest|Przybygniew|Strzeżysława|Rodomił'),
(37, 2, 6, 'Dorota|Leon|Paweł|Leona|Amanda|Zdziewit'),
(38, 2, 7, 'Ryszard|Teodor|Partenia|Parteniusz|Rozalia|Romeusz'),
(39, 2, 8, 'Elfryda|Hieronim|Ampeliusz|Gabriela|Sebastian|Izajasz|Gniewomir|Polikarp|Juwencja|Juwencjusz|Zyta|Piotr'),
(40, 2, 9, 'Apolonia|Cyryl|Marian|Sulisława'),
(41, 2, 10, 'Jacek|Scholastyka|Trojan'),
(42, 2, 11, 'Dezydery|Lucjan|Olgierd|Cedmon|Sekundyn|Bertrada'),
(43, 2, 12, 'Modest|Eulalia|Damian|Ampeliusz|Melecjusz|Norma|Ewa'),
(44, 2, 13, 'Arleta|Grzegorz|Katarzyna|Jordana|Toligniew'),
(45, 2, 14, 'Cyryl|Dobiesława|Metody|Auksencja|Auksencjusz|Auksenty|Adolf|Walenty|Piotr|Liliana'),
(46, 2, 15, 'Jowita|Faustyn|Zygfryda|Jordana|Zygfryd|Klaudia|Klaudiusz|Georgina'),
(47, 2, 16, 'Danuta|Eliasz|Julianna|Izajasz|Daniel|Irena|Samuel'),
(48, 2, 17, 'Aleksy|Donat|Zbigniew|Sylwin|Sylwina|Julian'),
(49, 2, 18, 'Konstancja|Szymon|Konstantyna|Klaudiusz|Gertruda'),
(50, 2, 19, 'Arnold|Konrad'),
(51, 2, 20, 'Leon|Ludomira|Leona|Aulus'),
(52, 2, 21, 'Eleonora|Piotr|Gumbert'),
(53, 2, 22, 'Marta|Małgorzata|Marwald|Konkordia|Wrocisław|Marold|Chociebąd'),
(54, 2, 23, 'Damian|Romana|Izabela|Polikarp|Montan'),
(55, 2, 24, 'Józefa|Bogurad|Sergiusz|Maciej|Marek|Wieledrog|Ermegarda|Irmegarda|Montan'),
(56, 2, 25, 'Cezary|Wiktor|Lubart|Antonina|Adam'),
(57, 2, 26, 'Aleksander|Dionizy|Mirosław|Klaudian|Gerlinda'),
(58, 2, 27, 'Gabriel|Jana|Anastazja|Auksencja|Baldomer|Auksencjusz|Auksenty|Baldomera|Honoryna|Bazyli|Anna'),
(59, 2, 28, 'Ludomir|Roman|Falibog|Gaja|Makary|Oswald|Kaja|August|Gajusz'),
(60, 2, 29, 'Ariusz|Roman|Dobrosiodł|August|Lutosław|Teofil'),
(61, 3, 1, 'Feliks|Herakles|Józef|Eudoksja|Leon|Nicefor|Leona|Antoni|Joanna|Radosław|Budzisław|Albina');
INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(62, 3, 2, 'Absalon|Paweł|Januaria|Agnieszka|Symplicjusz|Michał|Helena|Franciszek|Henryk|Krzysztof|Prosper'),
(63, 3, 3, 'Wirzchosława|Gerwina|Asteriusz|Samuel|Gerwin|Kinga|Maryna|Kunegunda|Tycjan|Piotr'),
(64, 3, 4, 'Adrian|Leonard|Lucjusz|Gerarda|Eugeniusz|Kazimierz|Gerard|Arkadiusz|Jakubina|Piotr'),
(65, 3, 5, 'Adrian|Fryderyk|Pakosław|Jan|Oliwia|Marek|Euzebiusz|'),
(66, 3, 6, 'Eugenia|Róża|Jordana|Koleta|Frydolin|Wiktor|Jordan|Będzimysł'),
(67, 3, 7, 'Paweł|Bazyli|Tomasz|Nadmir|Felicyta|German|Eubul'),
(68, 3, 8, 'Miłogost|Wincenty|Stefan|Filemon|Jan|Julian|Beata|Herenia'),
(69, 3, 9, 'Mścisława|Dominik|Tarazjusz|Prudencjusz|Franciszka|Katarzyna|Przemyślibor'),
(70, 3, 10, 'Makary|Porfirion|Marceli|Cyprian|Aleksander|Zwnisława|Gajus|Gaja|Piotr'),
(71, 3, 11, 'Benedykt|Konstantyn|Prokop|Sofroniusz|Trofim|Kandyd|Drogosława|Konstanty'),
(72, 3, 12, 'Bernard|Józefina|Grzegorz|Blizbor|Piotr'),
(73, 3, 13, 'Roderyk|Bożena|Marek|Kasjan|Ernest|Trzebiesław|Krystyna'),
(74, 3, 14, 'Fawila|Michał|Afrodyzy|Pamela|Bożeciecha|Matylda|Leona|Afrodyzjusz|Afrodyzja|Leon|Jakub|Piotr|Ewa'),
(75, 3, 15, 'Klemens|Ludwika|Longina|Longin|Gościmir|Krzysztof|Placyd'),
(76, 3, 16, 'Hilary|Abraham|Cyriak|Hiacynt|Izabela|Artemia|Natalis|Herbert|Miłostryj'),
(77, 3, 17, 'Agrykola|Ambroży|Cieszysław|Gertruda|Józef|Patrycjusz|Patrycy|Patryk|Paweł|Regina|Witburga|Zbigniew|Zbygniew|Zbygniewa'),
(78, 3, 18, 'Narcyz|Boguchwał|Anzelm|Cyryl|Marta|Edward|Salwator|Aleksander|Trofim|Celestyna'),
(79, 3, 19, 'Bogdan|Jan|Józef|Leoncjusz|Marceli|Marek'),
(80, 3, 20, 'Klemens|Klaudia|Bogusław|Ambroży|Eufemia|Cyriaka|Wincenty|Patrycjusz|Anatol|Aleksander|Fotyna|Józefa|Ermegarda|Irmegarda|Kutbert|Wolfram'),
(81, 3, 21, 'Benedykt|Marzanna|Klemencja|Mikołaj|Filemon|Ludomira'),
(82, 3, 22, 'Paweł|Bogusław|August|Kazimierz|Katarzyna|Godzisław|Baldwin|Baldwina|Bazyli'),
(83, 3, 23, 'Feliks|Pelagia|Zbysław|Eberhard|Wiktoriana|Wiktorianna|Wiktorian|Konrad|Piotra|Pelagiusz|Katarzyna|Oktawian'),
(84, 3, 24, 'Sewer|Sofroniusz|Marek|Aldmir|Oldmir|Szymon|Dzirżysława|Gabriel|Bertrada|Ademar'),
(85, 3, 25, 'Mariola|Dyzma|Nikodem|Maria|Lutomysł|Ireneusz'),
(86, 3, 26, 'Feliks|Emanuel|Emanuela|Teodor|Larysa|Nicefor|Manuela|Tworzymir|Bazyli|Montan'),
(87, 3, 27, 'Benedykt|Ernest|Lidia|Jan'),
(88, 3, 28, 'Aleksander|Doroteusz|Guntram|Renata|Ingbert|Joanna|Kastor|Malachiasz|Malkolm|Pryskus|Rogat'),
(89, 3, 29, 'Wiktoryn|Marek|Eustazy|Czcirad|Cyryl'),
(90, 3, 30, 'Leonard|Dobromir|Amadea|Kwiryna|Kwiryn|Mamertyn|Aniela|Litobor|Częstobor|Jan|Mamertyna|Piotr'),
(91, 3, 31, 'Gwido|Beniamin|Kornelia|Dobromira|Balbina|Amos|Achacjusz|Achacy'),
(92, 4, 1, 'Hugo|Zbigniew|Grażyna|Meliton|Teodora|Katarzyna|Tolisław|Jakubina'),
(93, 4, 2, 'Laurencja|Franciszek|Urban|Miłobąd'),
(94, 4, 3, 'Sykstus|Ryszard|Antoni|Pankracy|Cieszygor|Jakub'),
(95, 4, 4, 'Benedykt|Ambroży|Zdzimir|Izydor'),
(96, 4, 5, 'Irena|Wincenty|Borzywoj|Tristan'),
(97, 4, 6, 'Zachariasz|Wilhelm|Diogenes|Zachary|Katarzyna|Ireneusz|Piotra|Platonida|Celestyna'),
(98, 4, 7, 'Donat|Herman|Przecław|Epifaniusz|Hegezyp|Przedsław|Niestanka'),
(99, 4, 8, 'Sieciesława|Cezaryna|Radosław|August|Apolinary|Cezary|January'),
(100, 4, 9, 'Marceli|Maja|Dobrosława|Wadim|Heliodor'),
(101, 4, 10, 'Grodzisław|Makary|Pompejusz|Marek|Apoloniusz|Daniel|Michał|Antoni|Ezechiel|Małgorzata|Henryk'),
(102, 4, 11, 'Filip|Herman|Leon|Jaromir|Gemma|Leona|Hildebrand|Hildebranda|Adolf'),
(103, 4, 12, 'Zenon|Saba|Andrzej|Wiktor|Juliusz'),
(104, 4, 13, 'Justyn|Marcjusz|Marcin|Hermenegilda|Przemysł|Przemysław|Małgorzata|Jan|Ida'),
(105, 4, 14, 'Walerian|Tyburcja|Tyburcy|Maria|Tyburcjusz|Julianna|Myślimir|Trofim|Ardalion|Krzysztof|Piotr'),
(106, 4, 15, 'Wiktoryn|Olimpia|Modest|Abel|Wszegniew|Anastazja|Tytus|Potencjana|Potencjanna|Sylwester|Sylwestra|Piotr'),
(107, 4, 16, 'Benedykt|Bernadeta|Charyzjusz|Leonid|Erwin|Patrycy|Lambert|Urban|Julia|Ksenia'),
(108, 4, 17, 'Klara|Józef|Robert|Radociech|Anicet|Stefan|Izydora|Aniceta|Jakub|Innocenty'),
(109, 4, 18, 'Gościsław|Bogusław|Apoloniusz|Flawiusz|Bogusława|Alicja|Gosława|Barbara'),
(110, 4, 19, 'Konrada|Leontyna|Werner|Leona|Włodzimierz|Irydion|Cieszyrad|Leon|Pafnucy'),
(111, 4, 20, 'Amalia|Sekundyn|Teodor|Marcelin|Berenika|Agnieszka|Nawoj|Szymon|Sulpicjusz|Czesław|Ursycyn'),
(112, 4, 21, 'Feliks|Addar|Anzelm|Drogomił|Apollo|Apollina|Bartosz|Konrad'),
(113, 4, 22, 'Łukasz|Wanesa|Gajusz|Teodor|Gaja|Soter|Leonid|Strzeżymir|Leon|Leona|Wirginiusz'),
(114, 4, 23, 'Adalbert|Wojciech|Gerard|Helena|Jerzy|Gabriela|Gerarda|Lena'),
(115, 4, 24, 'Fidelis|Erwin|Horacy|Egbert|Grzegorz|Horacjusz|Aleksja|Aleksy|Aleksander|Saba'),
(116, 4, 25, 'Kaliksta|Ewodia|Markusław|Jarosław|Ewodiusz|Marek|Piotr'),
(117, 4, 26, 'Marcelin|Klarencjusz|Spycimir|Klet|Marzena|Artemon'),
(118, 4, 27, 'Felicja|Anastazy|Marcin|Zyta|Piotr|Teofil|Andrzej|Kanizjusz|Bożebor'),
(119, 4, 28, 'Paweł|Maria|Waleria|Marek|Przybycześć|Afrodyzy|Afrodyzjusz|Afrodyzja|Arystarch|Andrea|Witalis|Piotr');
INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(120, 4, 29, 'Hugo|Bogusław|Robert|Piotr|Ermentruda|Paulin|Ryta|Augustyn|Roberta|Angelina|Wiktor|Katarzyna'),
(121, 4, 30, 'Eutropiusz|Bartłomiej|Chwalisława|Lilla|Katarzyna|Afrodyzy|Afrodyzjusz|Afrodyzja|Jakub|Marian|Andrea|Piotr'),
(122, 5, 1, 'Jeremiasz|Berta|Briok|Jeremi|Józef|Aniela|Floryna|Lubomir|Jakub|Tamara|Maja'),
(123, 5, 2, 'Zoe|Borys|Zygmunta|Walter|Gwalbert|Walbert|Waldebert|Witomir|Atanazy|Walenty|Zygmunt|Anatol'),
(124, 5, 3, 'Juwenalis|Maria|Alodia|Antonina|Aleksander|Leonia|Tymoteusz|Piotr'),
(125, 5, 4, 'Florian|Michał|Paulin|Monika|Grzegorz|January|Antonina'),
(126, 5, 5, 'Pius|Teodor|Irena|Zdziebor|Penelopa|Waldemar|Sabrina'),
(127, 5, 6, 'Judyta|Ewodia|Teodor|Gościwit|Benedykta|Ewodiusz|Jurand|Jan|Jakub|Dawid|Miłodrog|Małgorzata|Domagniew|Placyd'),
(128, 5, 7, 'Benedykt|Florian|Gizela|August|Ludomiła|Domicela|Domicjan|Domicjana|Flawia|Róża|Sykstus|Piotr'),
(129, 5, 8, 'Heladiusz|Dezyderia|Amat|Michał|Stanisław|Ida|Achacjusz|Achacy'),
(130, 5, 9, 'Karolina|Hiob|Grzegorz|Mikołaj|Beat'),
(131, 5, 10, 'Gordian|Chociesław|Gordiana|Symeon|Chocsław|Antonin|Izydor|Częstomir|Samuel|Wiktoryna|Jan|Sylwester|Sylwestra'),
(132, 5, 11, 'Benedykt|Filip|Mamerta|Adalbert|Leon|Gwalbert|Walbert|Waldebert|Stella|Franciszek|Mamert|Lutogniew|Ignacy|Zuzanna|Tadea|Tadeusz'),
(133, 5, 12, 'Wszemił|Dominik|Epifani|Nawoja|Domicjan|Domicjana|Joanna|Pankracy|Jazon|Jan|Domicela|Flawia|Janina|German'),
(134, 5, 13, 'Robert|Gerard|Serwacy|Aaron|Magdalena|Andrzej|Natalis|Gloria|Ciechosław|Gerarda|Dobiesława|Agnieszka'),
(135, 5, 14, 'Jeremiasz|Koryna|Bonifacy|Maciej|Wiktor|Dobiesław|Ampeliusz|Fenenna'),
(136, 5, 15, 'Strzeżysław|Robert|Cecyliusz|Atanazy|Zofia|Dionizja|Czcibora|Nadzieja|Izydor|Jan|Kasjusz|Florencjusz|Florenty|Piotr'),
(137, 5, 16, 'Germeriusz|Trzebiemysł|Fidol|Wiktoriana|Wiktorianna|Jan Nepomucen|Ubald|Wiktorian|Andrzej|Szymon|Honorat|Jędrzej|Jan'),
(138, 5, 17, 'Weronika|Paschalis|Herakliusz|Sławomir|Bruno|Wiktor|Montan'),
(139, 5, 18, 'Feliks|Wenancjusz|Myślibor|Eryk|Liberiusz|Klaudia|Aleksander|Aleksandra|Teodot'),
(140, 5, 19, 'Celestyn|Potencjana|Potencjanna|Kryspin|Bernarda|Piotr|Iwo|Pękosław|Mikołaj|Augustyn|Pudencjana|Pudencjanna'),
(141, 5, 20, 'Teodor|Anastazy|Wiktoria|Asteriusz|Bernardyn|Saturnina|Iwo|Bronimir|Rymwid|Józefa'),
(142, 5, 21, 'Tymoteusz|Ryksa|Walenty|Wiktor|Przedsława|Jan|Pudens|Krzysztof|Piotr|Teobald'),
(143, 5, 22, 'Wisława|Emil|Helena|Ryta|Julia|Jan|Krzesisława|Wiesław|Wiesława'),
(144, 5, 23, 'Dezydery|Iwona|Symeon|Michał|Emilia|Dezyderiusz|Budziwoj|Eufrozyna|Jan'),
(145, 5, 24, 'Maria|Wincenty|Milena|Joanna|Zuzanna|Natan|Tomira|Orion|Jan|Cieszysława'),
(146, 5, 25, 'Magdalena|Beda|Leon|Imisława|Grzegorz|Urban|Leona|Wenerand'),
(147, 5, 26, 'Angelika|Filip|Adalwina|Alwina|Adalwin|Alwin|Emil|Ewelina|Paulina|Więcemił|Ariusz'),
(148, 5, 27, 'Lucjan|Magdalena|Oliwer|Radowit|Izydor|Juliusz|Jan'),
(149, 5, 28, 'Heladiusz|Wilhelm|Emil|Priam|Wiktor|Ignacy|German|Augustyn|Jaromir'),
(150, 5, 29, 'Maksymina|Teodor|Bogusława|Maksymin|Ermentruda|Urszula|Rajmunda|Magdalena|Piotr'),
(151, 5, 30, 'Feliks|Ferdynand|Jan|Andronik|Sulimir|Suligniewa|Bazyli'),
(152, 5, 31, 'Feliks|Teodor|Petronela|Petroniusz|Petronia|Kancjusz|Kancjan|Kancjanela|Noe'),
(153, 6, 1, 'Bernard|Nikodem|Alfons|Symeon|Fortunat|Magdalena|Eunika|Konrad|Felin|Felina|Ischyrion|Juwencja|Juwencjusz|Justyn|Teobald|Pamela'),
(154, 6, 2, 'Marzanna|Marcelin|Efrem|Maria|Trofima|Erazm|Racisław|Nicefor|Piotr|Eugeniusz|Florianna|Floriana|Mikołaj|Marianna|Fotyn|Materna|Domna'),
(155, 6, 3, 'Tamara|Konstantyn|Owidiusz|Leszek|Paula|Cecyliusz|Ferdynand|Klotylda|Owidia|Kewin|Klotylda'),
(156, 6, 4, 'Dacjan|Karol|Braturad|Niepełka|Karp|Franciszek|Saturnina|Gostmił|Aleksander|Skarbisław'),
(157, 6, 5, 'Walter|Waleria|Dobrociech|Bonifacy|Nikanor|Igor|Hildebrand|Hildebranda'),
(158, 6, 6, 'Norberta|Dominika|Benignus|Gerarda|Symeon|Więcerad|Laurenty|Gerard|Klaudiusz|Norbert|Paulina|Kandyda'),
(159, 6, 7, 'Lukrecja|Paweł|Anna|Robert|Jarosław|Antoni|Hadriana|Wisław|Ciechomir|Wiesława|Piotr'),
(160, 6, 8, 'Wyszesław|Wilhelm|Medard|Maksymin|Seweryn|Herakliusz|Medarda|Maksymina|Dobrociech'),
(161, 6, 9, 'Anna|Felicjan|Pelagia|Sylwester|Sylwestra'),
(162, 6, 10, 'Bogumiła|Mauryn|Bogumił|Apollo|Edgar|Wiktorian|Wiktoriana|Wiktorianna|Ingolf|Małgorzata'),
(163, 6, 11, 'Feliks|Teodozja|Anastazy|Radomił|Barnaba|Witomysł'),
(164, 6, 12, 'Wyszemir|Onufry|Władysława|Jarogniewa|Narcyz|Antonina|Leon|Jan|Jarogniew|Leona|Celestyna|Władysław|Tadea|Placyd|Piotr|Ewa'),
(165, 6, 13, 'Herman|Armand|Chociemir|Lucjan|Gerard|Antoni|Tobiasz|Gerarda|Lubowid'),
(166, 6, 14, 'Walerian|Justyn|Eliza|Ninogniew'),
(167, 6, 15, 'Bernard|Abraham|Edburga|Witold|Witosław|Jolanta|Wit|Leonida|Lotar|Angelina|Wisława'),
(168, 6, 16, 'Benon|Cyryk|Ludgarda|Aneta|Budzimir|Justyna|Jan|Aureusz|Benona|Aubert|Alina'),
(169, 6, 17, 'Izaura|Drogomysł|Radomił|Hipacy|Franciszek|Adrianna|Gundolf|Marcjan|Rajner|Adolf|Waleriana|Awit|Awita|Piotr|Montan');
INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(170, 6, 18, 'Elżbieta|Paula|Marek|Efrem|Emil|Gerwazy|Drohobysz|Amanda|Miłobor'),
(171, 6, 19, 'Michalina|Borzysław|Gerwazy|Odo|Protazy|Julianna|Eurydyka'),
(172, 6, 20, 'Bożena|Bogna|Rafał|Edburga|Gemma|Florentyna|Franciszek|Hektor'),
(173, 6, 21, 'Teodor|Domamir|Demetria|Lutfryd|Alojza|Alojzy|Marcja|Rudolf|Rudolfina|Alicja|Rudolfa|Chloe'),
(174, 6, 22, 'Agenor|Flawiusz|Achacy|Innocenta|Paulina|Achacjusz|Innocenty|Tomasz|Alban'),
(175, 6, 23, 'Józef|Wanda|Agrypina|Zenon|Albin|Anna'),
(176, 6, 24, 'Emilia|Wilhelm|Danuta|Jan'),
(177, 6, 25, 'Prosper|Fiebrosław|Wilhelm|Antyd|Eulogiusz|Tolisława|Febronia|Febron|Dorota'),
(178, 6, 26, 'Jeremiasz|Edburga|Paweł|Zdziwoj|Jan|Dawid|Maksencjusz|Maksanty'),
(179, 6, 27, 'Włodzisław|Cyryl|Bożydar'),
(180, 6, 28, 'Paweł|Ekhard|Józef|Zbrosław|Leon|Serena|Leona|Ireneusz|Plutarch|Ekard|Jakert|Heron'),
(181, 6, 29, 'Piotr|Paweł|Dawid|Dalebor Benedykta|Kasjusz'),
(182, 6, 30, 'Marcjalis|Bazyli|Władysława|Ciechosława|Leon|Emilia|Leona|Lucyna|Trofim|Władysław|Ermentruda|Teobald'),
(183, 7, 1, 'Bogusław|Halina|Estera|Namir|Karolina|Marcin|Teodoryk|Domicjan|Domicjana|Klarysa|Ekhard|Jakert'),
(184, 7, 2, 'Otto|Urban|Martynian|Bożydar|Juda|Niegosława|Switun|Juwenalis'),
(185, 7, 3, 'Heliodor|Jacek|Miłosław|Otto|Otton|Teodot|Tomasz|Leon|Anatol|Leona'),
(186, 7, 4, 'Elżbieta|Aurelian|Aggeusz|Alfred|Teodor|Józef|Odon|Odo|Malwina|Wielisław|Innocenty|Julian|Berta|Ozeasz|Piotr'),
(187, 7, 5, 'Zoe|Marta|Wilhelm|Trofima|Antoni|Przybywoj|Bartłomiej|Jakub|Filomena|Karolina'),
(188, 7, 6, 'Niegosław|Dominika|Nazaria|Dominik|Ignacja|Gotard|Agrypina|Chociebor|Zuzanna'),
(189, 7, 7, 'Benedykt|Pompejusz|Sędzisława|German|Antoni|Cyryl|Wilibald|Metody|Estera'),
(190, 7, 8, 'Adrian|Kilian|Kiliana|Prokop|Eugeniusz|Odeta|Edgar|Chwalimir|Adolf|Adolfa|Piotr|Teobald'),
(191, 7, 9, 'Lukrecja|Zenona|Sylwia|Anatola|Adolfina|Anatolia|Weronika|Ludwika|Patrycjusz|Róża|Zenon|Florianna|Floriana|Hieronim|Mikołaj|Lucja|Teodoryk|Antoni|Kornel|Korneli|Korneliusz|Barbara'),
(192, 7, 10, 'Filip|Aniela|Samson|Zacheusz|Witołd|Amalberga|Sylwan|Witalis|January|Aleksander|Rufina|Rzędziwoj|Engelbert|Askaniusz'),
(193, 7, 11, 'Benedykt|Pius|Pelagia|Siepraw|Olga|Wyszesława|Kalina|Placyd|Sawin|Cyrus|Zybert|Zybracht|Zybart|Zygbert'),
(194, 7, 12, 'Feliks|Paweł|Leon|Epifania|Jan Gwalbert|Leona|Weronika|Euzebiusz|Andrzej|Henryk|Natan'),
(195, 7, 13, 'Radomiła|Joel|Ernest|Eugeniusz|Małgorzata|Trofima|Sara|Jakub|Ezdrasz'),
(196, 7, 14, 'Marcelin|Ulryk|Kamil|Tuskana|Franciszek|Dobrogost|Kosma|Marceli|Izabela|Damian|Angelina'),
(197, 7, 15, 'Lubomysł|Daniel|Włodzimierz|Roksana|Egon|Ignacy|Henryk|Niecisław|Dawid|Anna'),
(198, 7, 16, 'Benedykt|Faust|Stefan|Eustacjusz|Eustachy|Andrzej|Dzierżysław|Carmen|Ermegarda|Irmegarda'),
(199, 7, 17, 'Januaria|Donata|Marcelina|Dzierżykraj|Konstancja|Aneta|Marceli|Andrzej|Aleksja|Karolina|Leon|Aleksy|Leona|Jadwiga|Bogdan'),
(200, 7, 18, 'Robert|Erwin|Uniesław|Matern|Arnold|Wespazjan|Teodozja|Szymon|Arnolf|Arnulf|Karolina'),
(201, 7, 19, 'Arseniusz|Marcin|Wincenty|Lutobor|Rufin|Zdziesuł|Piotr'),
(202, 7, 20, 'Paweł|Eliasz|Sewera|Hieronim|Małgorzata|Leon|Heliasz|Czesław|Leona'),
(203, 7, 21, 'Benedykt|Prokop|Daniel|Ignacy|Andrzej|Wiktor|Stojsław|Julia|Prakseda|Klaudiusz|Arbogast'),
(204, 7, 22, 'Wawrzyniec|Bolesława|Magdalena|Pankracy|Laurencjusz|Albin|Naczęsława|Benona'),
(205, 7, 23, 'Bogna|Apolinary|Apolinaria'),
(206, 7, 24, 'Olga|Antoni|Kinga|Wojciecha|Kunegunda|Krystyna|Zyglinda'),
(207, 7, 25, 'Walentyna|Nieznamir|Alfons|Olimpia|Rudolf|Rudolfina|Jakub|Sławosław|Krzysztof|Rudolfa|Dariusz'),
(208, 7, 26, 'Anna|Mirosława|Grażyna|Krystiana|Bartłomieja|Hanna|Sancja|Joachim'),
(209, 7, 27, 'Aureli|Pantaleon|Antuza|Natalia|Bertold|Laurenty|Wszebor|Julia|Lilla'),
(210, 7, 28, 'Alfonsa|Samson|Pantaleon|Marcela|Wiktor|Innocenty|Tymona|Tymon|Achacjusz|Achacy'),
(211, 7, 29, 'Konstantyn|Lucyliusz|Olaf|Maria|Faustyn|Serafina|Marta|Urban|Beatrycze|Lucyla|Cierpisław|Prosper'),
(212, 7, 30, 'Ubysław|Ubysława|Ludomiła|Julita|Maryna|Abdon|Julia|Piotr'),
(213, 7, 31, 'Alfonsa|Justyn|German|Helena|Demokryt|Ignacy|Ernesta|Emilian|Beat|Ludomir|Adam|Lubomir'),
(214, 8, 1, 'Alfons|Justyn|Nadzieja|Piotr|Brodzisław|Rudolf|Rudolfina|Konrad|Rudolfa'),
(215, 8, 2, 'Gustaw|Karina|Maria|Stefan|Świętosław|Teodota'),
(216, 8, 3, 'Nikodem|Nikodema|Szczepan|Lidia|Piotr'),
(217, 8, 4, 'Alfred|Mironieg|Perpetua|Maria|Arystarch|Protazy|Dominika|Andrzej'),
(218, 8, 5, 'Nonna|Cyriak|Abel|Maria|Memiusz|Oswalda|Oswald|Karolina'),
(219, 8, 6, 'Wincenty|Felicysym|Stefan|Jakub|January|Just|Namir|Nasław|Oktawian'),
(220, 8, 7, 'Licyniusz|Kajetan|Klaudia|Dobiemir|Dorota|Sykstus|Andromeda|Albert|Doryda|Licynia|Konrad|Donata'),
(221, 8, 8, 'Niezamysł|Cyriak|Sylwiusz|Cyryl|Emilian|Niegosław'),
(222, 8, 9, 'Klarysa|Miłorad|Domicjan|Domicjana|Dominika|Roland|Roman|Jan|Romuald'),
(223, 8, 10, 'Bernard|Amadea|Wawrzyniec|Wierzchosław|Asteria|Hugona|Bożydar|Wirzchosław|Filomena|Prochor');
INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(224, 8, 11, 'Lukrecja|Herman|Włodzimierz|Tyburcja|Zuzanna|Ligia|Aleksander'),
(225, 8, 12, 'Łukasz|Filip|Mateusz|Anita|Julia|Robert|Lech'),
(226, 8, 13, 'Radomiła|Hipolit|Sekundyn|Hipolita|Kasjan|Adriana|Helena|Wojbor|Jan|Diana|Radegunda'),
(227, 8, 14, 'Alfred|Euzebiusz|Atanazja|Kalikst|Dobrowoj|Maksymilian|Dobrowoja|Machabeusz|Ursycyn|Majnard'),
(228, 8, 15, 'Napoleon|Maria|Stefan|Arnolf|Arnulf|Trzebiemir|Armida'),
(229, 8, 16, 'Ambroży|Emil|Roch|Domarad|Eleuteria|Stefan|Piotra|Saba|Arsacjusz'),
(230, 8, 17, 'Bertram|Jacek|Miron|Zawisza|Anita|Jaczewoj|Liberat|Eliza|Joanna|Anastazja|Julianna|Angelika|Serwiusz|Żaneta'),
(231, 8, 18, 'Klara|Ilona|Tacjana|Tworzysława|Helena|Agapit|Bogusława|Bronisław'),
(232, 8, 19, 'Bolesław|Ludwik|Emilia|Sykstus|Juliusz|Jan|Sebald |Julian'),
(233, 8, 20, 'Bernard|Samuel|Sobiesław|Sabin|Sara|Jan|Samuela'),
(234, 8, 21, 'Bernard|Franciszek|Joanna|Męcimir|Adolf|Filipina|Emilian|Baldwin|Baldwina'),
(235, 8, 22, 'Fabrycjan|Tymoteusz|Teonas|Fabrycy|Dalegor|Hipolit|Namysław|Maria|Zygfryd|Zygfryda|Cezary|Agatonik|Teona'),
(236, 8, 23, 'Filip|Róża|Walerian|Benicjusz|Sulirad|Zacheusz|Laurenty|Apolinary|Piotra|Klaudiusz|Leoncja|Lubomira'),
(237, 8, 24, 'Michalina|Halina|Anita|Cieszymir|Bartłomiej|Joanna|Jerzy|Malina|Bartosz'),
(238, 8, 25, 'Sieciesław|Kalasanty|Ludwik|Luiza|Michał|Elwira|Grzegorz|Arediusz|Gaudencjusz|Teodoryk|Genezjusz'),
(239, 8, 26, 'Wirzchosława|Maria|Maksym|Dobroniega|Joanna|Wiktorian|Konstanty|Ireneusz|Wiktoriana|Wiktorianna'),
(240, 8, 27, 'Teodor|Józef|Gebhard|Rufus|Monika|Amadea|Małgorzata|Przybymir|Cezary'),
(241, 8, 28, 'Alfons|Hermes|Joachima|Stronisław|Sobiesław|Adelinda|Aleksy|Augustyn|Aleksander'),
(242, 8, 29, 'Mederyka|Sabina|Flora|Racibor|Mederyk|Jan'),
(243, 8, 30, 'Gaudencja|Miron|Tekla|Częstowoj|Adaukt|Rebeka|Piotr'),
(244, 8, 31, 'Rajmund|Bogdan|Albertyna|Amat|Paulina|Teodot'),
(245, 9, 1, 'Idzi|Bronisław|Bronisława|Sykstus|August|Miłodziad|Melecjusz|Anna'),
(246, 9, 2, 'Absalon|Dziesław|Wilhelm|Stefan|Bogdan|Eliza|Tobiasz|Dionizy|Henryk|Julian|Czesław'),
(247, 9, 3, 'Manswet|Eufemia|Erazma|Wincenty|Gerarda|Antoni|Bazylisa|Szymona|Mojmir|Bartłomiej|Zenon|Szymon|Dorota|Joachim|Gerard|Izabela|Natalis|Jan|Bronisław|Grzegorz|Przecław|Przedsław'),
(248, 9, 4, 'Rozalia|Liliana|Rościgniew|Daniela|Hermiona|Ida|Ermegarda|Irmegarda|Scypion|Kandyda'),
(249, 9, 5, 'Herakles|Wawrzyniec|Herkulan|Dorota|Fereol|Stronisława|Herkules|Justyna'),
(250, 9, 6, 'Eugenia|Michał|Zachariasz|Magnus|German|Eugeniusz|Zachary|Albin|Aleksja|Uniewit|Gundolf|Ewa'),
(251, 9, 7, 'Sozont|Melchior|Ryszard|Domasława|Regina|Dobrobąd|Teodoryk|Gratus'),
(252, 9, 8, 'Radosława|Maria|Adrianna|Radosław|Nestor'),
(253, 9, 9, 'Gorgoniusz|Aureliusz|Otmar|Edeltrauda|Piotr|Audomar|Dionizy|Ożanna'),
(254, 9, 10, 'Mścibor|Klemens|Leon|Poliana|Polianna|Pulcheria|Aldona|Mikołaj|Leona|Łukasz|Nimfodora|Kandyda|Piotr'),
(255, 9, 11, 'Feliks|Jacek|Naczęsław|Dagna|Prot|Jan|Ademar|Piotr'),
(256, 9, 12, 'Gwidon|Maria|Sylwin|Amadeusz|Cyrus|Maja|Sylwina'),
(257, 9, 13, 'Eugenia|Filip|Amat|Aureliusz|Morzysław|Aleksander|Litoriusz'),
(258, 9, 14, 'Bernard|Siemomysł|Szymon|Matern|Piotr'),
(259, 9, 15, 'Budzigniew|Maria|Albin|Ekhard|Kamil'),
(260, 9, 16, 'Eugenia|Edyta|Jakub|Kornel|Eufemia|Edda|Sędzisław|Antym|Franciszek|Kamila|Sebastiana|Wiktor|Cyprian|Korneli|Korneliusz'),
(261, 9, 17, 'Ariadna|Szczęsny|Zygmunta|Justyna|Justyn|Narcyz|Hildegarda|Lamberta|Franciszek|Szczęsna|Dezyderiusz|Drogosław|Lambert|Teodora|Piotr'),
(262, 9, 18, 'Irena|Józef|Fereol|Zachariasz|Dobrowit|Stefania|Zachary|Ryszarda|Tytus|Baltazar|Sobierad'),
(263, 9, 19, 'Więcemir|Teodor|Wilhelmina|Marta|Alfons|Trofim|Konstancja|Festus|January|Zuzanna|Arnolf|Arnulf'),
(264, 9, 20, 'Klemens|Mieczysława|Irena|Faustyna|Oleg|Eustachiusz|Eustachy|Fausta|Dionizy|Miłostryj|Barbara|Perpetua|Agnieszka'),
(265, 9, 21, 'Bernardyna|Hipolit|Jonasz|Marek|Laurenty|Mateusz|Ifigenia|Bożeciech|Melecjusz'),
(266, 9, 22, 'Daria|Maurycy|Tomasz|Ignacy|Joachim|Prosimir|Józefa'),
(267, 9, 23, 'Bogusław|Liwiusz|Tekla|Boguchwała|Libert|Zachariasz|Zachary|Krzysztof|Elżbieta|Piotr'),
(268, 9, 24, 'Teodor|Gerard|Maria|Uniegost|Tomir|Gerarda'),
(269, 9, 25, 'Aureli|Aurelia|Aurelian|Herkulan|Wincenty|Rufus|Franciszek|Włodzisław|Eufrozyna|Władysław|Kleofas|Ermenfryda|Irmfryda|Ermenfryd|Irmfryd'),
(270, 9, 26, 'Euzebiusz|Majnard|Cyprian|Kacper|Kasper|Damian'),
(271, 9, 27, 'Przedbor|Urban|Gaja|Amadeusz|Gajusz|Mirela|Adolf|Adolfa|Zybart|Zybert|Zybracht|Zygbert'),
(272, 9, 28, 'Więcesław|Wawrzyniec|Salomon|Wacława|Marek|Sylwin|Wacław|Laurencjusz|Jan|Alodiusz|Sylwina|Tymon'),
(273, 9, 29, 'Cyriak|Dadzbog|Dadzboga|Lutwin|Dariusz|Franciszek|Fraternus|Gabriel|Gajana|Grimbald|Michalina|Michał|Mikołaj|Rafał|Rypsyma|Teodota|Ludwin'),
(274, 9, 30, 'Wiara|Felicja|Grzegorz|Zofia|Hieronim|Imisław|Wiktor|Honoriusz|Znamir'),
(275, 10, 1, 'Cieszysław|Benigna|Danuta|Jan|Małobąd|Remigiusz|Igor'),
(276, 10, 2, 'Leodegar|Stanimir|Teofil|Nasiębor|Ursycyn');
INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(277, 10, 3, 'Teresa|Gerard|Ewalda|Gerarda|Ewald|Kandyd|Eustachy|Sierosław|Częstobrona|Ermegarda|Irmegarda|Romana|Augustyna|Kandyda|Cyprian'),
(278, 10, 4, 'Dalwin|Franciszek|Konrad|Konrada|Dalewin'),
(279, 10, 5, 'Justyn|Faust|Galla|Charytyna|Placyd|Konstancjusz|Apolinary|Flawiana|Igor'),
(280, 10, 6, 'Fryderyka|Alberta|Brunon|Roman|Bronisław|Baldwin|Baldwina|Askaniusz|Artur'),
(281, 10, 7, 'Amalia|Tekla|Marek|August|Maria|Rościsława|Stefan|Justyna|Mireli'),
(282, 10, 8, 'Laurencja|Marcin|Ginter|Ewodia|Guncerz|Gunter|Demetriusz|Pelagia|Symeon|Ewodiusz|Marcjusz|Brygida|Artemon|Gratus'),
(283, 10, 9, 'Przedpełk|Ludwik|Arnold|Guncerz|Ginter|Gunter|Dionizjusz|Bogdan|Atanazja|Bożydar|Sara|Sybilla|Dionizy|Jan|Aaron|Piotr'),
(284, 10, 10, 'Lutomir|Kalistrat|Samuel|Przemysław|Paulin|Franciszek|Tomiła|Tomił|Adalryk|Alderyk|Kasjusz|Eulampiusz|Eulampia'),
(285, 10, 11, 'Burchard|Germanik|Maria|Dobromiła|Aldona|Brunon|Marian|Emil|Emilian|Placyda|Placyd'),
(286, 10, 12, 'Witolda|Wilfryd|Serafin|Cyriak|Edwin|Marcin|Witold|Salwin|Grzymisław|Eustachy|Maksymiliana|Maksymilian'),
(287, 10, 13, 'Daniel|Geraldyna|Wacław|Wacława|Maurycy|Gerald|Teofil|Mikołaj|Edward|Siemisław|Florencjusz|Florenty'),
(288, 10, 14, 'Bernard|Dominik|Alan|Gaja|Kalikst|Fortunata|Gajusz'),
(289, 10, 15, 'Teresa|Sewer|Tekla|Gościsława|Brunon|Jadwiga|Teodoryk'),
(290, 10, 16, 'Aurelia|Radzisław|Ambroży|Gerard|Gaweł|Emil|Gerarda|Florentyna|Grzegorz|Dionizy|Jadwiga'),
(291, 10, 17, 'Małgorzata|Lucyna|Laurentyna|Augustyna|Rudolf|Wiktor|Rudolfina|Seweryna|Sulisława|Ignacy|Marian|Rudolfa|Zuzanna|Heron|Andrzej'),
(292, 10, 18, 'Asklepiades|Bratomił|Julian|Just|Łukasz|Miłobrat|Piotr|Remigia|Remigiusz|Siemowit'),
(293, 10, 19, 'Paweł|Pelagia|Ziemowit|Kleopatra|Ferdynand|Siemowit|Skarbimir'),
(294, 10, 20, 'Budzisława|Irena|Wendelin|Apollo|Witalis|Aurora'),
(295, 10, 21, 'Klementyna|Elżbieta|Hilary|Bernard|Dobromił|Pelagia|Celina|Urszula|Nunilona|Samuel|Wszebora|Piotr'),
(296, 10, 22, 'Filip|Sewer|Kordula|Marek|Kordian|Abercjusz|Alodia'),
(297, 10, 23, 'Seweryn|Giedymin|Małogost|Marlena|Ignacy|Roman|Jan|Domicjusz|Gracjan|Gracjana|Gracjanna|Klotylda'),
(298, 10, 24, 'Filip|Salomon|Marcin|Pamfilia|Boleczest|Marek|Antoni|Walentyna|Rafał'),
(299, 10, 25, 'Daria|Chryzant|Tarazjusz|German|Teodozjusz|Maur|Sambor|Kryspin|Tadea|Cyryn|Cyryna'),
(300, 10, 26, 'Ludomiła|Ewaryst|Lutosław|Lucyna|Leonarda|Amanda'),
(301, 10, 27, 'Frumencjusz|Sabina|Iwona|Wincenty|Siestrzemił|Manfred|Manfreda'),
(302, 10, 28, 'Tadeusz|Wszeciech|Szymon|Juda|Wielimir|Domabor'),
(303, 10, 29, 'Teodor|Narcyz|Longin|Lubgost|Euzebia|Franciszek|Wioleta|Ida|Ermelinda|Piotr'),
(304, 10, 30, 'Zenobia|Przemysław|Gerarda|German|Klaudiusz|Edmund'),
(305, 10, 31, 'Augusta|Alfons|Wolfgang|Antoni|Urban|Godzimir|Lucyla|Saturnin|Narcyz|Lucyliusz'),
(306, 11, 1, 'Konradyn|Seweryn|Andrzej|Warcisław|Wiktoryna|Konradyna|Nikola'),
(307, 11, 2, 'Wiktoryn|Eudoksjusz|Ambroży|Stomir|Małgorzata|Tobiasz|Bogdana|Teodot|Wojsław|Wojsława'),
(308, 11, 3, 'Sylwia|Huberta|Chwalisław|German|Hubert|Marcin|Bogumił|Cezary'),
(309, 11, 4, 'Karolina|Dżesika|Emeryk|Karol Boromeusz|Olgierd|Modesta|Witalis|Mojżesz|Emeryka|Mściwoj|Mszczujwoj|Perpetua'),
(310, 11, 5, 'Florian|Trofima|Marek|Sławomir|Blandyna|Blandyn|Dalemir'),
(311, 11, 6, 'Feliks|Leonard|Trzebowit|Daniela|Teobald'),
(312, 11, 7, 'Achilles|Karyna|Amaranta|Melchior|Przemił|Antoni|Florentyn|Engelbert|Gizbert|Żelibrat|Ingarda|Florencjusz|Florenty'),
(313, 11, 8, 'Sędziwoj|Sewerian|Adrian|Sewer|Wiktoryn|Godfryd|Wiktoria|Seweryn|Marcin|Klaudiusz|Wiktor|Hadrian'),
(314, 11, 9, 'Genowefa|Teodor|Bogodar|Teodora|Ursyn|Nestor'),
(315, 11, 10, 'Probus|Nimfa|Stefan|Andrzej|Leon|Leona|Ludomir|Uniebog'),
(316, 11, 11, 'Jozafat|Teodor|Marcin|Spycisław|Bartłomiej|Maciej|Anastazja|Prot'),
(317, 11, 12, 'Jozafat|Marcin|Witold|Jonasz|Renata|Cibor|Czcibor|Renat|Arsacjusz'),
(318, 11, 13, 'Brykcjusz|Walentyn|Eugeniusz|Stanisław|German|Liwia|Augustyna|Mikołaj|Jan|Arkadiusz'),
(319, 11, 14, 'Elżbieta|Wszerad|Józef|Antyd|Serapion|Klementyn|Judyta|Hipacy|Laurenty|Kosma|Lewin|Damian|Agryppa|Teodot|Maria|Paweł|Montan'),
(320, 11, 15, 'Alfons|Idalia|Leopold|Leopoldyna|Roger|Albert|Przybygniew|Artur'),
(321, 11, 16, 'Ariel|Otmar|Aureliusz|Audomar|Niedamir|Dionizy|Gertruda|Edmund|Patrokles|Agnieszka'),
(322, 11, 17, 'Jozafat|Hugo|Karolina|Palmira|Zbysław|Floryn|Zacheusz|Grzegorz|Sulibor|Dionizy'),
(323, 11, 18, 'Leonard|Aniela|Otto|Otton|Galezy|Odo|Tomasz|Cieszymysł|Filipina|Karolina|Roman|Gabriela|Józefa'),
(324, 11, 19, 'Elżbieta|Paweł|Seweryn|Mironiega|Salomea|Kryspin|Małowid|Barbara'),
(325, 11, 20, 'Feliks|Maksencja|Ampeliusz|Hieronim|Sędzimir|Edmund|Anatol|Oktawia|Oktawiusz|Sylwester|Sylwestra|Fortunata'),
(326, 11, 21, 'Janusz|Twardosław|Maria|Elwira|Albert|Rufus|Regina|Konrad|Wiesław|Heliodor'),
(327, 11, 22, 'Cecylia|Marek|Maur|Wszemiła'),
(328, 11, 23, 'Klemens|Adela|Przedwoj|Erast|Orestes|Felicyta|Fotyna');
INSERT INTO `nameday` (`id`, `month`, `day`, `nameday`) VALUES
(329, 11, 24, 'Dobrosław|Pęcisław|Gerard|Emilia|Flora|Franciszek|Protazy|Jan|Biruta|Jaśmina|Felicjana|Felicjanna|Agnieszka'),
(330, 11, 25, 'Erazm|Katarzyna|Tęgomir|Piotr'),
(331, 11, 26, 'Leonard|Sylwester|Dobiemiest|Delfin|Lechosława|Lechosław|Jan|Konrad|Sylwestra|Piotr'),
(332, 11, 27, 'Wirgiliusz|Jozafat|Zygfryd|Stojgniew|Gustaw|Dominik|Damazy|Zygfryda|Oda|Walery|Maksymilian|Sekundyn|Achacjusz|Achacy'),
(333, 11, 28, 'Grzegorz|Lesław|Zdzisław|Gościrad|Lesława|Jakub|Rufin|Ginter|Guncerz|Gunter|Kwieta|Berta'),
(334, 11, 29, 'Klementyna|Walter|Błażej|Bolemysł|Przemysł|Fryderyk|Saturnin|Paramon'),
(335, 11, 30, 'Zbysława|Andrzej|Justyna|Konstancjusz|Maura|Tadea|Kutbert'),
(336, 12, 1, 'Eligia|Natalia|Platon|Edmund|Eligiusz|Blanka|Sobiesława|Gosława'),
(337, 12, 2, 'Bibiana|Bibianna|Aurelia|Wiktoryn|Zbylut|Ludwina|Balbina|Sulisław|Paulina|Sylweriusz|Budzisława|Budzisław'),
(338, 12, 3, 'Uniemir|Kasjan|Lucjusz|Franciszek|Kryspin|Ksawery|Atalia|Gerlinda|Biryn|Sofoniasz'),
(339, 12, 4, 'Klemens|Chrystian|Barbara|Krystian|Hieronim|Melecjusz'),
(340, 12, 5, 'Anastazy|Kryspina|Sabina|Kryspin|Gerald|Pęcisława|Krystyna|Saba'),
(341, 12, 6, 'Angelika|Heliodor|Agata|Dionizja|Mikołaj|Emilian|Leoncja|Piotr'),
(342, 12, 7, 'Ambroży|Marcin|Ninomysł|Agaton|Marcisław|Józefa|Polikarp|Zdziemił|Sabin'),
(343, 12, 8, 'Maria|Narcyza|Apollo|Boguwola'),
(344, 12, 9, 'Wielisława|Leokadia|Waleria|Joachim|Delfina|Wiesław|Piotr'),
(345, 12, 10, 'Polidor|Brajan Daniel|Judyta|Maria|Andrzej|Julia|Switun|Unierad|Unirad'),
(346, 12, 11, 'Stefan|Damazy|Waldemar|Wojmir|Wilburga'),
(347, 12, 12, 'Adelajda|Dagmara|Edburga|Suliwuj|Paramon|Spirydion|Aleksander|Ada|Przybysława|Maksencjusz|Joanna'),
(348, 12, 13, 'Bernarda|Otylia|Edburga|Jodok|Róża|Włodzisława|Auksencja|Auksencjusz|Auksenty|Aubert'),
(349, 12, 14, 'Pompejusz|Sławobor|Alfred|Nahum|Arseniusz|Noemi|Izydor|Heron'),
(350, 12, 15, 'Cecylia|Walerian|Mścigniew|Wolimir|Drogosława'),
(351, 12, 16, 'Alina|Adelajda|Zdzisława|Ananiasz|Albina|Dyter|Deder|Tyter'),
(352, 12, 17, 'Florian|Łazarz|Łukasz|Olimpia'),
(353, 12, 18, 'Bogusław|Wszemir|Arkadia|Wilibald|Auksencja|Winebald|Winibald|Winibalda|Wunibald|Auksencjusz|Auksenty'),
(354, 12, 19, 'Tymoteusz|Nemezjusz|Abraham|Dariusz|Beniamin|Urban|Mścigniew|Bogumiła'),
(355, 12, 20, 'Bogumiła|Dominik|Amon|Liberat|Dagmara|Teofil|Ursycyn'),
(356, 12, 21, 'Tomisław|Balbin|Tomasz|Honorat|Festus|Piotr'),
(357, 12, 22, 'Drogomir|Ksawera|Honorata|Zenon|Franciszka|Gryzelda|Flawian|Beata|Ischyrion'),
(358, 12, 23, 'Wiktoria|Anatola|Sławomir|Sławomira|Dagobert|Anatolia'),
(359, 12, 24, 'Adela|Grzymisława|Druzjanna|Ewa|Godzisława|Irmina|Druzjan|Adamina|Grzegorz|Ewelina|Hermina|Zenobiusz|Tarsylia|Tarsylla|Adam'),
(360, 12, 25, 'Eugenia|Mateusz|Piotr|Siemisław|Domna'),
(361, 12, 26, 'Wrociwoj|Szczepan|Dionizy'),
(362, 12, 27, 'Żaneta|Fabia|Fabiola|Jan|Cezary|Krystyna'),
(363, 12, 28, 'Teofila|Antoni|Ema|Teonas|Dobrowieść|Teona|Godzisław'),
(364, 12, 29, 'Trofim|Jonatan|Gerarda|Dominik|Marcin|Tomasz|Gosław|Dawid|Gerard|Jakert|Tadea'),
(365, 12, 30, 'Sewer|Anizja|Egwin|Eugeniusz|Rajner|Dionizy|Uniedrog|Irmina'),
(366, 12, 31, 'Sylwester|Donata|Mariusz|Melania|Saturnina|Sebastian|Tworzysław|Sylwestra');

