/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS post_get_by_jsonIds;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_get_by_jsonIds`(
	ids JSON,
    limitItem INT
    /*
		Creator: Hank@20230911
        Revisions: - 20230911@Hank: init
    */
)
BEGIN

	DROP TEMPORARY TABLE IF EXISTS tmpPostIds;
    CREATE TEMPORARY TABLE tmpPostIds (Id BIGINT);
    
	INSERT INTO tmpPostIds (Id)
    SELECT Id
    FROM 
		JSON_TABLE
        (
			ids,
            "$[*]"
            COLUMNS
            (
				Id BIGINT PATH "$"
            )
        ) AS t; 
	SELECT p.id as ID, p.post_title as PostTitle, p.post_content as PostContent
    , p.post_date as PostDate, p.post_date_gmt as PostDateGMT, p.post_name as Slug
    , pp.guid as FeatureImage, m1.meta_value as Metadata
    , p.comment_count as CommentCount, p.post_excerpt as PostExcerpt
	FROM wp_posts as p
    INNER JOIN tmpPostIds as tmp ON p.ID = tmp.Id
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE p.post_type = 'post' AND p.post_status = 'publish'
    ORDER BY p.post_date desc
    LIMIT limitItem;
    
    DROP TEMPORARY TABLE IF EXISTS tmpPostIds;
    

END
//
DELIMITER ;	