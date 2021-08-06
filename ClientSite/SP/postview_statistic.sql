CREATE TABLE `wp_postview_details` (
  `post_id` bigint(20) unsigned NOT NULL,
  `view_date` date NOT NULL,
  `is_video_post` bit(1) NOT NULL DEFAULT b'0',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `video_view` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`view_date`,`is_video_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP PROCEDURE IF EXISTS post_update_viewcount;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_update_viewcount`( postId bigint(20) unsigned, isVideoPost bit, errCode int, errMsg nvarchar(100))
BEGIN
    INSERT INTO `wp_postview_details`
    (`post_id`,
    `is_video_post`,
    `view_date`,
    `view_count`)
    VALUES
    (postId,
    isVideoPost,
    CURRENT_DATE(),
    1)
    ON DUPLICATE KEY UPDATE `view_count` = `view_count` + 1;
END
//
DELIMITER ;

DROP PROCEDURE IF EXISTS post_update_video_view;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_update_video_view`( postId bigint(20) unsigned, errCode int, errMsg nvarchar(100))
BEGIN
    UPDATE `wp_postview_details` v
    SET
    `video_view` = `video_view` + 1
    WHERE `post_id` = postId AND `view_date` = CURRENT_DATE() AND `is_video_post` = 1;   
END
//
DELIMITER ;

DROP FUNCTION IF EXISTS get_root_category;
SET GLOBAL log_bin_trust_function_creators = 1;
DELIMITER //
CREATE DEFINER=`sabasports`@`%` FUNCTION `get_root_category`(categoryId int)
RETURNS INT
BEGIN

	DECLARE RootCategoryId INT;
    
    SELECT tmp_cate_tree.cate_id INTO RootCategoryId
    FROM (
	SELECT tmp_t.term_id as cate_id, @pv:=tmp_t.parent as parent_id
		FROM (SELECT * FROM wp_term_taxonomy) tmp_t
		INNER JOIN
		(SELECT @pv:=categoryId)tmp
		WHERE tmp_t.term_id = @pv) tmp_cate_tree
	WHERE tmp_cate_tree.parent_id = 0;	
    
    RETURN RootCategoryId;
END; 
//
DELIMITER ;

DROP PROCEDURE IF EXISTS post_view_statistic_get;
DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_view_statistic_get`(
	fromDate DATE,
    toDate DATE
)
BEGIN
    DROP TEMPORARY TABLE IF EXISTS tmpPosts;
    CREATE TEMPORARY TABLE tmpPosts(PostId BIGINT UNSIGNED, PostDate DATE, RootCategoryId INT);
    INSERT INTO tmpPosts(PostId, PostDate, RootCategoryId)
    SELECT DISTINCT p.ID ,p.post_date ,get_root_category(tt.term_id)
    FROM wp_posts p
    INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id
    INNER JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id and tt.taxonomy = 'category'
    WHERE p.post_status = 'publish' AND p.post_type = 'post'
    AND p.post_date BETWEEN fromDate  AND toDate;
    
    SELECT p.PostDate as PostDate
          ,COUNT(DISTINCT IF (t.Name <> 'video', p.PostId, NULL)) AS PostCount
          ,SUM(CASE WHEN pd.is_video_post <> 1 THEN pd.view_count ELSE 0 END) AS PostView
          ,COUNT(DISTINCT IF (t.Name = 'video', p.PostId, NULL)) AS VideoCount
          ,SUM(CASE WHEN pd.is_video_post = 1 THEN pd.view_count ELSE 0 END) AS VideoView
    FROM tmpPosts p 
    INNER JOIN wp_terms t ON p.RootCategoryId = t.term_id
    LEFT JOIN wp_postview_details pd ON p.PostId = pd.post_id
    GROUP BY p.PostDate
    ORDER BY p.PostDate;
    
    DROP TEMPORARY TABLE IF EXISTS tmpPosts;
END
//
DELIMITER ;