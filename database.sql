CREATE TABLE users (
  user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
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
INSERT INTO users (group_id, login, pass, name) VALUES ((SELECT group_id FROM groups WHERE name = 'Administrators'), 'admin', SHA2('admin', 512), 'Administrator');
INSERT INTO privileges (name, description) VALUES ('EditUsers', 'Dodawanie, usuwanie i edytowanie użytkowników');
INSERT INTO group_permissions (group_id, privilege_id) VALUES ((SELECT group_id FROM groups WHERE name = 'Administrators'), (SELECT privilege_id FROM privileges WHERE name = 'EditUsers'));

