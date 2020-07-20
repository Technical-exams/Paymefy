BEGIN TRANSACTION;
DROP TABLE IF EXISTS `elevator_stats`;
CREATE TABLE IF NOT EXISTS `elevator_stats` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`time`	INTEGER NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`elevator`	TEXT NOT NULL,
	`stopped_at` INTEGER NOT NULL,
	`accum_moves`	INTEGER NOT NULL DEFAULT 0,
	`last_move`	INTEGER NOT NULL DEFAULT 0
);

DROP VIEW IF EXISTS `elevator_stats_summary`;
CREATE VIEW IF NOT EXISTS `elevator_stats_summary` (`time`, `elevator`, `stopped_at`, `accum_moves`, `last_move`)
AS
SELECT `a`.`time`, `a`.`elevator`, `a`.`stopped_at`, `a`.`accum_moves`, `a`.`last_move`
FROM `elevator_stats` as `a`
JOIN (SELECT MAX(accum_moves) as `accum_moves`, `time`, `elevator`
FROM `elevator_stats`
GROUP by `time`, `elevator`) as `b`
ON `a`.`time`= `b`.`time`
AND `a`.`elevator` = `b`.`elevator`  
WHERE `a`.`accum_moves` = `b`.`accum_moves`
ORDER BY `a`.`time`, `a`.`elevator`

COMMIT;
