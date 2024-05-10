/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/
UPDATE sabasports.wp_posts 
SET 
    post_content = REPLACE(post_content,
        'vncdn.sabasports.com',
        'cdn.sabavn.com')
WHERE
    post_content like '%vncdn.sabasports.com%';
    

UPDATE sabasports.wp_posts 
SET 
    guid = REPLACE(guid,
        'vncdn.sabasports.com',
        'cdn.sabavn.com')
WHERE
    guid like '%vncdn.sabasports.com%' AND post_type = "attachment";
	

UPDATE sabasports.wp_postmeta
SET 
    meta_value = REPLACE(meta_value,
        'vncdn.sabasports.com',
        'cdn.sabavn.com')
WHERE
    meta_value like '%vncdn.sabasports.com%';