/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_posts_by_slug;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_posts_by_slug`(
	_categorySlug varchar(200),
    _start INT,
    _end INT
    /*
		Creator: Hank@20210720
        Revisions:
			- 20210720@Hank: init
            - 20220727@Hank: get sub cate posts
    */
)
BEGIN
	DECLARE category INT;
	DECLARE cate_name VARCHAR(200);
    SELECT t.term_id, t.name INTO category, cate_name FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND taxonomy = "category" WHERE slug = _categorySlug;
    
     DROP TEMPORARY TABLE IF EXISTS tmp_Cate;
    CREATE TEMPORARY TABLE tmp_Cate (term_id BIGINT, slug VARCHAR(255), term_taxonomy_id BIGINT);
    
    INSERT INTO tmp_Cate (term_id, slug, term_taxonomy_id)
    SELECT t.term_id, t.slug, wtt.term_taxonomy_id
    FROM wp_terms t
    INNER JOIN wp_term_taxonomy as wtt ON wtt.term_id = t.term_id AND wtt.taxonomy = "category" 
    WHERE wtt.term_id = category OR wtt.parent = category;
    
    INSERT INTO tmp_Cate (term_id, slug, term_taxonomy_id)
    SELECT t.term_id, t.slug, wtt.term_taxonomy_id
    FROM wp_terms t
    INNER JOIN wp_term_taxonomy as wtt ON wtt.term_id = t.term_id AND wtt.taxonomy = "category" 
    WHERE wtt.parent IN (SELECT wtt1.term_id FROM wp_term_taxonomy as wtt1 WHERE wtt1.parent = category);
   
	SELECT p.id as ID, p.post_title as PostTitle, p.post_content as PostContent
    , p.post_date as PostDate, p.post_date_gmt as PostDateGMT, p.post_name as Slug
    , pp.guid as FeatureImage, m1.meta_value as Metadata, cate_name as Categories
    , p.comment_count as CommentCount, p.post_excerpt as PostExcerpt
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE p.post_type = 'post' AND p.post_status = 'publish'
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
					INNER JOIN tmp_Cate as c ON c.term_taxonomy_id = tr.term_taxonomy_id)
	ORDER BY p.post_date DESC
	limit _start, _end;
END
//
DELIMITER ;	