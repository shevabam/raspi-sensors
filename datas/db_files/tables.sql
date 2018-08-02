-- 
-- Table : param
-- 
CREATE TABLE IF NOT EXISTS param (
    id    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    key   VARCHAR(255) NOT NULL,
    value TEXT,
    desc  TEXT
);

-- 
-- Table : sensor_group
-- 
CREATE TABLE IF NOT EXISTS sensor_group (
    id          INTEGER         PRIMARY KEY AUTOINCREMENT,
    name        VARCHAR(200)    NOT NULL
);

-- 
-- Table : sensor
-- 
CREATE TABLE IF NOT EXISTS sensor (
    id          INTEGER         PRIMARY KEY AUTOINCREMENT,
    group_id    INTEGER,
    name        VARCHAR(200)    NOT NULL,
    device      VARCHAR(150)    NOT NULL,
    color       VARCHAR(7),
    enabled     INTEGER         NOT NULL DEFAULT 1,
    FOREIGN KEY(group_id) REFERENCES sensor_group(id)
);

-- 
-- Table : temperature
-- 
CREATE TABLE IF NOT EXISTS temperature (
    id          INTEGER         PRIMARY KEY AUTOINCREMENT,
    sensor_id   INTEGER,
    value       DECIMAL         NOT NULL,
    date        DATETIME        NOT NULL,
    created_at  DATETIME        NOT NULL,
    FOREIGN KEY(sensor_id) REFERENCES sensor(id)
);

