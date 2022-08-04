/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isFunction="0" isNested="0"></info>*/
CREATE DEFINER=`sabasports`@`%` PROCEDURE `get_sitemap`(
	/*
		Creator: Hank@20210720
        Revisions:
			- 20210720@Hank: init
    */
)
get_sitemap:BEGIN

    SELECT p.ID
          ,p.post_name As Slug
          ,p.post_type AS PostType
          ,p.post_modified_gmt AS ModifiedDate
	FROM wp_posts p 
	WHERE p.post_type IN ('post', 'page') AND post_name <> ''
    AND p.post_status = 'publish'
    AND p.post_modified_gmt > '2020-12-31'
    

    UNION

    SELECT  t.term_id
           ,t.slug As Slug
           ,wtt.taxonomy
           ,NULL
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy wtt ON t.term_id = wtt.term_id AND taxonomy = "category" 
    WHERE wtt.count > 0
    
    ORDER BY PostType DESC
    ,ModifiedDate DESC;
END