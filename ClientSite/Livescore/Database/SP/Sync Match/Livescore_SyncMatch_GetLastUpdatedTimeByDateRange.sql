DROP PROCEDURE IF EXISTS Livescore_SyncMatch_GetLastUpdatedTimeByDateRange;
DELIMITER //

CREATE DEFINER = 'sabasports'@'%' PROCEDURE Livescore_SyncMatch_GetLastUpdatedTimeByDateRange(
    startTime DATETIME
    ,endTime DATETIME
    /*
		Creator: Oliver@20220509
        Param: - 
        Revisions: - 20220509@Oliver: init
    */
)
SQL SECURITY DEFINER
BEGIN
    SELECT MAX(m.UpdatedAt) 
    FROM sabasports.livescore_match m 
    WHERE m.StartTime BETWEEN startTime AND endTime;
END;

//
DELIMITER ;
GRANT EXECUTE ON PROCEDURE Livescore_SyncMatch_GetLastUpdatedTimeByDateRange TO 'sabasports'@'%';