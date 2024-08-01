/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/
UPDATE sabasports.wp_posts 
SET 
    post_content = REPLACE(post_content,
        'https://www.saba.vn',
        'https://www.sabavn.com')
WHERE
    post_content like '%https://www.saba.vn%';
    
UPDATE sabasports.wp_posts 
SET 
    post_content = REPLACE(post_content,
        'https://saba.vn',
        'https://www.sabavn.com')
WHERE
    post_content like '%https://saba.vn%';
	


UPDATE sabasports.wp_email 
SET 
    EmbeddedLink = REPLACE(EmbeddedLink,
        'https://saba.vn',
        'https://www.sabavn.com')
WHERE
    EmbeddedLink like '%https://saba.vn%';
	

UPDATE sabasports.wp_email 
SET 
    EmailContent = REPLACE(EmailContent,
        'https://saba.vn',
        'https://www.sabavn.com')
WHERE
    EmailContent like '%https://saba.vn%';
	

UPDATE sabasports.wp_email_detail 
SET 
    EmailContent = REPLACE(EmailContent,
        'https://saba.vn',
        'https://www.sabavn.com')
WHERE
    EmailContent like '%https://saba.vn%';
	
	
UPDATE sabasports.wp_options 
SET 
    option_value = REPLACE(option_value,
        'https://saba.vn',
        'https://www.sabavn.com')
WHERE
    option_value like '%https://saba.vn%';
	
	

UPDATE sabasports.wp_team
SET 
    IconVN = REPLACE(IconVN,
        'https://saba.vn',
        'https://www.sabavn.com')
WHERE
    IconVN like '%https://saba.vn%';
	