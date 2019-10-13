DROP DATABASE skynetpass;
CREATE DATABASE skynetpass;
use skynetpass;

CREATE TABLE app_authorization(
    app_authorization_id INT AUTO_INCREMENT NOT NULL,
    module varchar(64) NOT NULL,
    action varchar(64),
    description varchar(64),
    state BOOLEAN,
    CONSTRAINT pk_app_authorization PRIMARY KEY (app_authorization_id)
);

CREATE TABLE user_role(
    user_role_id INT AUTO_INCREMENT NOT NULL,
    updated_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    created_user_id INT,
    updated_user_id INT,

    name varchar(64) NOT NULL,
    CONSTRAINT pk_user_role PRIMARY KEY (user_role_id)
);

CREATE TABLE user_role_authorization(
    user_role_id INT NOT NULL,
    app_authorization_id INT NOT NULL,
    CONSTRAINT fk_user_role_authorization_user_role FOREIGN KEY (user_role_id) REFERENCES user_role (user_role_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_user_role_authorization_app_authorization FOREIGN KEY (app_authorization_id) REFERENCES app_authorization (app_authorization_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE TABLE user(
    user_id INT AUTO_INCREMENT NOT NULL,
    updated_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    created_user_id INT,
    updated_user_id INT,

    password varchar(64) NOT NULL,
    email varchar(64) NOT NULL,
    temp_key varchar(32) NOT NULL,
    avatar varchar(64) NOT NULL,
    user_name varchar(32) NOT NULL,
    state boolean default true,
    login_count SMALLINT,
    last_update_temp_key DATETIME,
    fa2_secret VARCHAR(64),

    user_role_id INT NOT NULL,

    CONSTRAINT pk_user PRIMARY KEY (user_id),
    CONSTRAINT uk_user UNIQUE INDEX (email,user_name),
    CONSTRAINT fk_user_user_role FOREIGN KEY (user_role_id) REFERENCES user_role (user_role_id)
    ON UPDATE RESTRICT ON DELETE RESTRICT
);


CREATE TABLE pass_folder(
    pass_folder_id INT AUTO_INCREMENT NOT NULL,
    updated_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    created_user_id INT,
    updated_user_id INT,

    name varchar(255) NOT NULL,
    description varchar(255),

    CONSTRAINT pk_pass_folder PRIMARY KEY (pass_folder_id)
);

CREATE TABLE pass_password(
    pass_password_id INT AUTO_INCREMENT NOT NULL,
    title VARCHAR(255),
    description text,
    user_name varchar(64),
    password varchar(64),
    web_site varchar(255),
    key_char char,

    last_update DATETIME,
    pass_folder_id INT NOT NULL,

    CONSTRAINT pk_pass_password PRIMARY KEY (pass_password_id),
    CONSTRAINT fk_pass_password_pass_customer FOREIGN KEY (pass_folder_id) REFERENCES pass_folder (pass_folder_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
);


CREATE TABLE pass_password_audit(
    pass_password_audit_id INT AUTO_INCREMENT NOT NULL,

    user_refer_id INT,
    table_action VARCHAR(16),
    description VARCHAR(64),
    create_at DATETIME,

    pass_password_id INT NOT NULL,
    CONSTRAINT pk_pass_password_audit PRIMARY KEY (pass_password_audit_id),
    CONSTRAINT fk_pass_password_audit_pass_password FOREIGN KEY (pass_password_id) REFERENCES pass_password (pass_password_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT fk_pass_password_audit_user FOREIGN KEY (user_refer_id) REFERENCES user (user_id)
        ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE TABLE user_session(
     user_session_id INT AUTO_INCREMENT NOT NULL,
     user_id INT NOT NULL,
     session_date DATETIME NOT NULL,
     description ENUM('in','out'),
     CONSTRAINT pk_user_session PRIMARY KEY (user_session_id),
     CONSTRAINT fk_user_session_user FOREIGN KEY (user_id) REFERENCES user (user_id)
         ON UPDATE RESTRICT ON DELETE RESTRICT
);

-- INSERT DATA
INSERT INTO user_role(name) VALUES ('Administrador'),('Personal'),('Invitado');
INSERT INTO user(user_name,password,user_role_id) VALUES ('admin', sha1('admin'),1);

INSERT INTO app_authorization(module, action, description, state) VALUES
    ('reporte','listar','listar reporte',true),

    ('usuario','listar','listar usuarios',true),
    ('usuario','crear','crear nuevo usuarios',true),
    ('usuario','eliminar','Eliminar un usuario',true),
    ('usuario','modificar','Acualizar los datos del usuario exepto la contraseña',true),
    ('usuario','actualizarContraseña','Solo se permite actualizar la contraseña',true),

    ('rol','listar','listar roles',true),
    ('rol','crear','crear nuevos rol',true),
    ('rol','eliminar','Eliminar un rol',true),
    ('rol','modificar','Acualizar los roles',true),

    ('cliente','listar','listar cliente',true),
    ('cliente','crear','crear nuevos cliente',true),
    ('cliente','eliminar','Eliminar un cliente',true),
    ('cliente','modificar','Acualizar los cliente',true),

    ('contraseña','listar','listar contraseñas',true),
    ('contraseña','crear','crear nuevos contraseña',true),
    ('contraseña','eliminar','Eliminar un contraseña',true),
    ('contraseña','modificar','Acualizar los contraseñas',true),

    ('escritorio','general','vista general',true),
    ('escritorio','estadistica','estadisticas de usuario',true);


INSERT INTO user_role_authorization(user_role_id, app_authorization_id) VALUES
    (1,1),
    (1,2),
    (1,3),
    (1,4),
    (1,5),
    (1,6),
    (1,7),
    (1,8),
    (1,9),
    (1,10),
    (1,11),
    (1,12),
    (1,13),
    (1,14),
    (1,15),
    (1,16),
    (1,17),
    (1,18),
    (1,19),
    (1,20);