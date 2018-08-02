
-- 
-- Fixtures : group
-- 
INSERT INTO sensor_group(name) VALUES('Garden');


-- 
-- Fixtures : sensor
-- 
INSERT INTO sensor(id, group_id, name, device, color, enabled) VALUES
(1, 1, 'Wood greenhouse', '28-039104d6fbcf', '#ff9c00', 1),
(2, 1, 'Vertical greenhouse', '28-011081a4fabc', '#63C955', 1),
(3, 1, 'Outside', '28-0741280c5dea', '#69c7e2', 1);