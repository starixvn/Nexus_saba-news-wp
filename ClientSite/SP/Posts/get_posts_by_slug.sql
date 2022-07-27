/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_posts_by_slug;

DELIMITER //


CREATE DEFINER=`root`@`%` PROCEDURE `get_posts_by_slug`(
	_categorySlug varchar(200),
    _start INT,
    _end INT
)
BEGIN
	DECLARE count  INT;
	DECLARE _category INT;
	DECLARE cate_name VARCHAR(200);
    SELECT t.term_id, t.name INTO _category, cate_name FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND taxonomy = "category" WHERE slug = _categorySlug;
   
	SELECT p.id, p.post_title, p.post_content, p.post_date, p.post_date_gmt, p.post_name, 
    pp.guid as feature_image, m1.meta_value as metadata, cate_name, p.comment_count, p.post_excerpt
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE p.post_type = 'post' AND p.post_status = 'publish'
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
					INNER JOIN wp_term_taxonomy as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id AND tt.taxonomy = "category"
					WHERE tt.term_id = _category OR tt.parent = _category)
	ORDER BY p.post_date DESC
	limit _start, _end;
END

//
DELIMITER ;	