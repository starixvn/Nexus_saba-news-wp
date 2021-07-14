CREATE TABLE `wp_postview_details` (
  `post_id` bigint(20) unsigned NOT NULL,
  `view_date` date NOT NULL,
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`view_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP PROCEDURE IF EXISTS post_update_viewcount;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_update_viewcount`( _post_id bigint(20) unsigned)
BEGIN
    INSERT INTO `wp_postview_details`
    (`post_id`,
    `view_date`,
    `view_count`)
    VALUES
    (_post_id,
    CURRENT_DATE(),
    0)
    ON DUPLICATE KEY UPDATE `view_count` = `view_count` + 1;
END


//
DELIMITER ;