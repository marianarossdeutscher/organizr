DROP TABLE IF EXISTS task_user;
DROP TABLE IF EXISTS task;
DROP TABLE IF EXISTS users;
DROP TYPE IF EXISTS task_status;

CREATE TYPE task_status AS ENUM ('To do', 'In progress', 'Completed');

CREATE TABLE users (
  userid         SERIAL        PRIMARY KEY,
  username       VARCHAR(100)  NOT NULL,
  email          VARCHAR(255)  NOT NULL UNIQUE,
  password_hash  VARCHAR(255)  NOT NULL
);

CREATE TABLE task (
  taskid         SERIAL          PRIMARY KEY,
  userid         INTEGER         NOT NULL,
  title          VARCHAR(255)    NOT NULL,
  description    TEXT,
  register_date  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  end_date       TIMESTAMP,
  priority       INTEGER,
  status         task_status     NOT NULL DEFAULT 'To do',

  CONSTRAINT fk_user_owner
    FOREIGN KEY(userid) REFERENCES users(userid)
      ON DELETE CASCADE
);

CREATE TABLE task_user (
  taskuserid  SERIAL  PRIMARY KEY,
  taskid      INTEGER NOT NULL,
  userid      INTEGER NOT NULL,

  CONSTRAINT fk_task
    FOREIGN KEY(taskid) REFERENCES task(taskid)
      ON DELETE CASCADE,
  CONSTRAINT fk_user
    FOREIGN KEY(userid) REFERENCES users(userid)
      ON DELETE CASCADE,

  CONSTRAINT uq_task_user UNIQUE(taskid, userid)
);

CREATE INDEX idx_task_register_date ON task(register_date);
CREATE INDEX idx_tu_taskid          ON task_user(taskid);
CREATE INDEX idx_tu_userid          ON task_user(userid);
CREATE INDEX idx_task_userid        ON task(userid);