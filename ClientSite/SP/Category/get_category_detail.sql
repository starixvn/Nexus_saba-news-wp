/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_category_detail;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_category_detail`(
	slug varchar(255)
	/*
		Creator: Hank@20220720
        Revisions:
			- 20220720@Hank: init
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
		,(SELECT meta_value FROM wp_termmeta tm1  WHERE  meta_key = "rank_math_description" AND tm1.term_id = t.term_id) as description
        ,(SELECT meta_value FROM wp_termmeta tm2  WHERE  meta_key = "rank_math_facebook_image" AND tm2.term_id = t.term_id) as OgImage
        ,(SELECT meta_value FROM wp_termmeta tm3  WHERE  meta_key = "rank_math_facebook_description" AND tm3.term_id = t.term_id) as OgDescription
        ,(SELECT meta_value FROM wp_termmeta tm4  WHERE  meta_key = "rank_math_facebook_title" AND tm4.term_id = t.term_id) as OgTitle
        ,(SELECT meta_value FROM wp_termmeta tm5  WHERE  meta_key = "rank_math_title" AND tm5.term_id = t.term_id) as SEOTitle
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wt ON  t.term_id = wt.term_id AND wt.taxonomy = "category"
    WHERE t.slug = slug;
END

//
DELIMITER ;	