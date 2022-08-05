/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS post_get_by_cate_slugs;

DELIMITER //


CREATE DEFINER=`sabasports`@`%` PROCEDURE `post_get_by_cate_slugs`(
	slug1 VARCHAR(255),
    slug2 VARCHAR(255),
    slug3 VARCHAR(255),
    start INT,
    end INT,
    OUT slug VARCHAR(255),
    OUT TotalItems int  
	
	/*
		Creator: Hank@20220720
        Revisions:
			- 20220720@Hank: init
            - 202200805@Hank: Fix TotalItem
    */
)
GetSlug:BEGIN
	DECLARE count  INT;
	DECLARE category INT;
    DECLARE cate_name VARCHAR(200);
    DECLARE cate_parent_name VARCHAR(200);
    DECLARE cate_parent INT;
    
    SET slug = get_cate_slug(slug1, slug2, slug3);
    
    IF (slug is null) THEN
		LEAVE GetSlug;
    END IF;
    
	DROP TEMPORARY TABLE IF EXISTS tmp_PostIds;
    CREATE TEMPORARY TABLE tmp_PostIds (PostId BIGINT);
    
    DROP TEMPORARY TABLE IF EXISTS tmp_Cate;
    CREATE TEMPORARY TABLE tmp_Cate (term_id BIGINT, slug VARCHAR(255), term_taxonomy_id BIGINT);
    
	SELECT t.term_id, t.name, wtt.parent INTO category, cate_name, cate_parent 
    FROM wp_terms t INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND wtt.taxonomy = "category" WHERE t.slug = slug;
    
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
    
    INSERT INTO tmp_PostIds (PostId)
    SELECT p.ID
	FROM wp_posts as p
	WHERE p.post_type = 'post' AND p.post_status = 'publish'
	AND p.ID IN (SELECT tr.object_id from wp_term_relationships as tr 
					INNER JOIN tmp_Cate as c ON c.term_taxonomy_id = tr.term_taxonomy_id);
	
    INSERT INTO tmp_PostIds (PostId)
    SELECT tr.object_id
	FROM wp_terms t
	INNER JOIN wp_term_taxonomy tt ON tt.term_id = t.term_id AND taxonomy = 'post_tag'
	INNER JOIN wp_term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
	WHERE t.slug IN (SELECT c.slug from tmp_Cate c);
	
    SELECT COUNT(DISTINCT(PostId)) INTO TotalItems FROM tmp_PostIds;
                    
    SELECT p.id as ID, p.post_title as PostTitle, p.post_content as PostContent, p.post_excerpt as PostExcerpt
    , p.post_date as PostDate, p.post_date_gmt as PostDateGMT, p.post_name as Slug
    , pp.guid as FeatureImage, m1.meta_value as Metadata, p.comment_count as CommentCount
    , cate_parent_name as CategoryParent, cate_name as Categories
	FROM wp_posts as p
	LEFT JOIN wp_postmeta as m ON m.post_id = p.ID and m.meta_key = "_thumbnail_id"
	LEFT JOIN wp_postmeta as m1 ON m1.post_id = m.meta_value AND m1.meta_key = "_wp_attachment_metadata"
	LEFT JOIN wp_posts as pp ON pp.ID = m.meta_value AND pp.post_type="attachment" 
	WHERE p.post_type = 'post' AND p.post_status = 'publish'
	AND EXISTS (SELECT tmp.PostId FROM tmp_PostIds tmp WHERE tmp.PostId = p.ID)
	ORDER BY p.post_date DESC
    LIMIT start, end;
	
    
END

//
DELIMITER ;	