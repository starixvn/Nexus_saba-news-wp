CREATE TABLE `wp_postview_details` (
  `post_id` bigint(20) unsigned NOT NULL,
  `view_date` date NOT NULL,
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`,`view_date`)
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
	DECLARE TotalPost INT DEFAULT 0; 
    DECLARE TotalVideo INT DEFAULT 0;
    DECLARE TotalPostView INT DEFAULT 0;
    DECLARE TotalVideoView INT DEFAULT 0;
	
	DROP TEMPORARY TABLE IF EXISTS tmp_PostViews;
	CREATE TEMPORARY TABLE tmp_PostViews(PostId BIGINT UNSIGNED, ViewCount INT, ViewDate DATE, VideoView INT, IsVideo BIT);
    INSERT INTO tmp_PostViews(PostId, ViewCount, ViewDate, VideoView, IsVideo)
    SELECT pvd.post_id 
           ,pvd.view_count
           ,pvd.view_date
           ,pvd.video_view
           ,pvd.is_video_post
    FROM wp_postview_details pvd
    INNER JOIN wp_posts p ON p.ID = pvd.post_id AND p.post_status = 'publish' AND p.post_type = 'post'
    WHERE pvd.view_date BETWEEN fromDate AND toDate;
    
    DROP TEMPORARY TABLE IF EXISTS tmp_PostRootCategory;
    CREATE TEMPORARY TABLE tmp_PostRootCategory(PostId BIGINT UNSIGNED, RootCategoryId INT);
    INSERT INTO tmp_PostRootCategory(PostId, RootCategoryId)
    SELECT DISTINCT p.ID
                    ,get_root_category(tt.term_id)
    FROM wp_posts p
    INNER JOIN wp_term_relationships tr ON p.ID = tr.object_id
    INNER JOIN wp_term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id and tt.taxonomy = 'category'
    WHERE p.post_status = 'publish' AND p.post_type = 'post'
    AND p.post_date BETWEEN fromDate  AND toDate;

	SELECT COUNT(DISTINCT tmp.PostId) 
    FROM tmp_PostRootCategory tmp 
    INNER JOIN wp_terms t ON tmp.RootCategoryId = t.term_id 
    WHERE t.name <> 'video' INTO TotalPost;

    SELECT COUNT(DISTINCT tmp.PostId) 
    FROM tmp_PostRootCategory tmp 
    INNER JOIN wp_terms t ON tmp.RootCategoryId = t.term_id 
    WHERE t.name = 'video' INTO TotalVideo;

    SELECT SUM(tmp.ViewCount) 
    FROM tmp_PostViews tmp 
    WHERE tmp.IsVideo = 0 INTO TotalPostView;

	SELECT SUM(tmp.VideoView) 
    FROM tmp_PostViews tmp WHERE tmp.IsVideo = 1 INTO TotalVideoView;
    
    SELECT IFNULL(TotalPost, 0) As TotalPost
            ,IFNULL(TotalVideo, 0) As TotalVideo
            ,IFNULL(TotalPostView, 0) As TotalPostView
            ,IFNULL(TotalVideoView, 0) As TotalVideoView;
    
    DROP TEMPORARY TABLE IF EXISTS tmp_PostViews;
    DROP TEMPORARY TABLE IF EXISTS tmp_PostRootCategory;

END
//
DELIMITER ;	