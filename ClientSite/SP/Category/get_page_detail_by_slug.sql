/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
DROP PROCEDURE IF EXISTS get_page_detail_by_slug;

DELIMITER //

CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_page_detail_by_slug`(
	Slug varchar(255)
	/*
		Creator: Hank@20220720
        Revisions:
			- 20220720@Hank: init
    */
)
BEGIN
	SELECT p.post_title as title, p.ID as CateId
    ,(SELECT meta_value FROM wp_postmeta pm1  WHERE  meta_key = "rank_math_title" AND pm1.post_id = p.ID) as SEOTitle
	,(SELECT meta_value FROM wp_postmeta pm2  WHERE  meta_key = "rank_math_description" AND pm2.post_id = p.ID) as Description
    ,(SELECT meta_value FROM wp_postmeta pm3  WHERE  meta_key = "rank_math_facebook_image" AND pm3.post_id = p.ID) as OgImage
	,(SELECT meta_value FROM wp_postmeta pm4  WHERE  meta_key = "rank_math_facebook_description" AND pm4.post_id = p.ID) as OgDescription
	,(SELECT meta_value FROM wp_postmeta pm5  WHERE  meta_key = "rank_math_facebook_title" AND pm5.post_id = p.ID) as OgTitle
	FROM wp_posts p 
	where p.post_name = Slug AND p.post_type = "page";
END
//
DELIMITER ;	