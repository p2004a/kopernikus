CREATE TABLE users (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  group_id INT,
  login CHAR(20),
  pass CHAR(128),
  name CHAR(40),
  email CHAR(40)
);

CREATE TABLE deleted_users (
  user_id INT NOT NULL PRIMARY KEY,
  group_id INT,
  login CHAR(20),
  pass CHAR(128),
  name CHAR(40),
  email CHAR(40)
);

CREATE TABLE groups (
  group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name CHAR(50)
);

CREATE TABLE privileges (
  privilege_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name CHAR(50),
  description VARCHAR(500)
);

CREATE TABLE group_permissions (
  permission_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  group_id INT,
  privilege_id INT
);

INSERT INTO groups (name) VALUES ('Administrators');
INSERT INTO groups (name) VALUES ('Guests');
INSERT INTO users (group_id, login, pass, name) VALUES ((SELECT group_id FROM groups WHERE name = 'Administrators'), 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', 'Administrator');
INSERT INTO users (group_id, login, pass, name) VALUES ((SELECT group_id FROM groups WHERE name = 'Guests'), 'guest', 'b0e0ec7fa0a89577c9341c16cff870789221b310a02cc465f464789407f83f377a87a97d635cac2666147a8fb5fd27d56dea3d4ceba1fc7d02f422dda6794e3c', 'Gość');
INSERT INTO privileges (name, description) VALUES ('EditUsers', 'Dodawanie, usuwanie i edytowanie użytkowników');
INSERT INTO group_permissions (group_id, privilege_id) VALUES ((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditUsers'));

