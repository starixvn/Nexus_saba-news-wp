DROP PROCEDURE IF EXISTS Livescore_LiveMatch_GetMatchDetails;
DELIMITER //

CREATE DEFINER = 'root'@'%' PROCEDURE Livescore_LiveMatch_GetMatchDetails(
    /*
		Creator: Oliver@20220406
        Revisions: - 20220406@Oliver: Get match details
        Params: 
    */
)
SQL SECURITY DEFINER
BEGIN
    
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_LiveMatch_GetMatchDetails TO 'sabasports'@'%';