namespace SabaNews.Livescore.ScheduledTasks.Constants
{
    public static class JobName
    {
        public const string SyncAllMatchByUpdatedTime = "SyncAllMatchByUpdatedTime";
        public const string SyncMatchByUpdatedTimeInPast = "SyncMatchByUpdatedTimeInPast";
        public const string SyncMatchByUpdatedTimeFromCurrentToFuture = "SyncMatchByUpdatedTimeFromCurrentToFuture";
        public const string SyncLeagueListByLastUpdatedTime = "SyncLeagueListByLastUpdatedTime";
        public const string SyncLeagueListGreaterThanLeagueId = "SyncLeagueListGreaterThanLeagueId";
        public const string SyncTeamListByLastUpdatedTime = "SyncTeamListByLastUpdatedTime";
        public const string SyncTeamListGreaterThanTeamId = "SyncTeamListGreaterThanTeamId";
        public const string SyncPlayerListByLastUpdatedTime = "SyncPlayerListByLastUpdatedTime";
        public const string SyncSeasonListByLastUpdatedTime = "SyncSeasonListByLastUpdatedTime";
        public const string SyncStageListByLastUpdatedTime = "SyncStageListByLastUpdatedTime";
        public const string SyncSeasonStandingsByLastUpdatedTime = "SyncSeasonStandingsByLastUpdatedTime";
        public const string SyncLiveMatchAndPushSignalR = "SyncLiveMatchAndPushSignalR";
        public const string SyncSeasonTeamHonorByLastUpdatedTime = "SyncSeasonTeamHonorByLastUpdatedTime";
        public const string SyncSeasonPlayerHonorByLastUpdatedTime = "SyncSeasonPlayerHonorByLastUpdatedTime";
        public const string SyncHeadToHeadFromCurrentToFuture = "SyncHeadToHeadFromCurrentToFuture";
        public const string SyncAllMatchByMaxId = "SyncAllMatchByMaxId";
        public const string SyncDeletedMatchesInTwoWeek = "SyncDeletedMatchesInTwoWeek";
        public const string SyncCountryList = "SyncCountryList";
        public const string SyncIncidentByUpdatedTime = "SyncIncidentByUpdatedTime";
    }
}