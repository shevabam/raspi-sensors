-- 
-- param datas
-- 
INSERT INTO param(id, key, value, desc) VALUES
(1, "api_key", "aWIxlJTYgR64ByAI15jGI821GH9O4gIUuZdnxP6iSyWK4vGlh", "API key for a dialog with the Raspberry Pi and the Raspi-sensor's database"),
(2, "force_connect", "0", "Set to 0 if you want to allow homepage for everyone"),
(3, "cache_expires", "900", "Cache expiration in seconds, for the graph. Set to 1 if you don't want cache"),
(4, "graph_zoom_entries", "30", "Max number of entries for the timeline"),
(5, "graph_max_entries", "500", "Max number of entries loaded in the graph");