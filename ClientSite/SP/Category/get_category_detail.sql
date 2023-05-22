/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_category_detail;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_category_detail`(
	slug varchar(255)
	/*
		Creator: Hank@20220720
        Revisions:
			- 20220720@Hank: init
            - 20230519@Hank: migrate rank math to yoast plugin
    */
)
BEGIN
	DECLARE count  INT;
	DECLARE cateId INT;
    DECLARE cateName VARCHAR(200);
    DECLARE cateParentName VARCHAR(200);
    DECLARE cateParentId INT; 
    
    SELECT t.term_id, wtt.parent INTO cateId, cateParentId 
    FROM wp_terms t INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND wtt.taxonomy = "category" WHERE t.slug = slug;
    
    IF(cateParentId != 0 AND cateParentId != 175) THEN
		SELECT IF(wtt.parent = 175, cateParentId, wtt.parent) INTO cateParentId 
		FROM wp_terms t INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND wtt.taxonomy = "category" WHERE t.term_id = cateParentId;
    END IF;
    
    SELECT t.term_id, t.name INTO cateParentId, cateParentName FROM wp_terms as t where t.term_id = cateParentId;
    
	SELECT  t.name as Title , t.term_id as CateId, t.slug as Slug, cateParentId, cateParentName
		, yi.title as SEOTitle, yi.description as description
        , yi.open_graph_title as OgTitle, yi.open_graph_description as OgDescription, yi.open_graph_image as OgImage
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wt ON  t.term_id = wt.term_id AND wt.taxonomy = "category"
    LEFT JOIN wp_yoast_indexable yi ON yi.object_id = t.term_id AND yi.object_type = "term" AND yi.object_sub_type = "category"
    WHERE t.slug = slug;
END

//
DELIMITER ;	