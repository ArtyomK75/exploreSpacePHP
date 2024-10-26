CREATE TABLE user_roles (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            ROLE VARCHAR(20) NOT NULL
);

CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL UNIQUE,
                       password_hash char(60) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       role_id INT NOT NULL,
                       created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                       FOREIGN KEY (role_id)  REFERENCES user_roles (Id)
);

CREATE TABLE questions (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           title varchar(1000) NOT NULL,
                           answer1 varchar(1000) NOT NULL,
                           answer2 varchar(1000) NOT NULL,
                           answer3 varchar(1000) NOT NULL,
                           answer4 varchar(1000) NOT NULL,
                           correctAnswer TINYINT NOT NULL
);

CREATE TABLE answers (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         user_id INT NOT NULL,
                         question_id INT NOT NULL,
                         correct_answer TINYINT NOT NULL,
                         selected_answer TINYINT NOT NULL,
                         created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (question_id)  REFERENCES questions (Id),
                         FOREIGN KEY (user_id)  REFERENCES users (Id)
);

CREATE TABLE pictureOfDay (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           day TINYINT NOT NULL,
                           title varchar(1000) NOT NULL,
                           path varchar(500) NOT NULL
);

INSERT INTO pictureOfDay
VALUES (1, 1, 'IC 5146: The Cocoon Nebula', 'Cocoon_Ventura_960.jpg'),
       (2, 2, 'Supermoon Beyond the Temple of Poseidon', 'SupermoonPoseidon_Maragos_960.jpg'),
       (3, 3, 'Fermi''s 12-year All-Sky Gamma-ray Map', '12YearMap_Fermi_1080.jpg'),
       (4, 4, 'The Dark Tower in Scorpius', 'DarkTowerCDK700-Selby1024.jpg'),
       (5, 5, 'Supernova Remnant CTA 1', 'CTA1_15_75_Lelu1024.jpg'),
       (6, 3, 'South Pacific Shadowset', 'FijiMoonsetWangJin1060.jpg'),
       (7, 7, 'Fresh Tiger Stripes on Saturn''s Enceladus', 'EnceladusStripes_Cassini_960.jpg'),
       (8, 8, 'Moon Eclipses Saturn', 'MoonEclipsesSaturn_Sanz_960.jpg'),
       (9, 9, 'Tulip Nebula and Black Hole Cygnus X-1', 'Tulip_Shastry_1080.jpg'),
       (10, 10, 'Star Factory Messier 17', 'M17SwanMaxant_1024.jpg'),
       (11, 11, 'Southern Moonscape', 'lorand_fenyes_hold_0016_Moretus_hegyvidek1024c.jpg'),
       (12, 12, 'IFN and the NGC 7771 Group', 'NGC7769_70_71_Mandel_1024.jpg'),
       (13, 13, 'The Moon Dressed Like Saturn', 'SaturnMoon_Sojuel_960.jpg'),
       (14, 14, 'A Triangular Prominence Hovers Over the Sun', 'SunTriangle_Vanoni_960.jpg'),
       (15, 15, 'Quarter Moon and Sister Stars', 'MoonPleiades_Dyer_960.jpg'),
       (16, 16, 'NGC 6995: The Bat Nebula', 'Bat_Taivalnaa_960.jpg'),
       (17, 17, 'NGC 247 and Friends', 'NGC247-Hag-Ben1024.jpg'),
       (18, 18, 'Ringed Ice Giant Neptune', 'NeptuneTriton_webb1059.png'),
       (19, 19, 'Small Moon Deimos', 'PIA11826_c.jpg'),
       (20, 20, 'M31: The Andromeda Galaxy', 'M31_HstSubaruGendler_960.jpg'),
       (21, 21, 'Mars: Moon, Craters, and Volcanos', 'MarsPan_ExpressLuck_1080.jpg'),
       (22, 22, 'Horsehead and Orion Nebulas', 'OrionOrange_Grelin_1080.jpg'),
       (23, 23, 'A Night Sky over the Tatra Mountains', 'NightTatra_Rosadzinski_960.jpg'),
       (24, 24, 'Young Star Cluster NGC 1333', 'NGC1333Webb1024.jpg'),
       (25, 25, 'Aurora Australis and the International Space Station', 'iss071e564695_1024.jpg'),
       (26, 26, 'The Moona Lisa', 'Moonalisa_Example1024.jpg'),
       (27, 27, 'Find the Man in the Moon', 'ManInMoon_Caxete_1080.jpg'),
       (28, 28, 'Mercury''s Vivaldi Crater from BepiColombo', 'MercuryCaloris_BepiColombo_960.jpg'),
       (29, 29, 'Melotte 15 in the Heart Nebula', 'HeartMelotte_McInnis_960.jpg'),
       (30, 30, 'Meteors and Aurora over Germany', 'AuroraPerseids_Anders_1080.jpg'),
       (31, 31, 'Late Night Vallentuna', 'PerseidM45Aurora_Heden1024.jpg');


INSERT INTO user_roles
VALUES (1, 'User'),
       (2, 'Admin');
