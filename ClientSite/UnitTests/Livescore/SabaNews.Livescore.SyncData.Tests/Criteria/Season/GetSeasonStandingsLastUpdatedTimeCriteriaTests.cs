namespace SabaNews.Livescore.SyncData.Tests.Criteria.Season
{
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using Xunit;

    public class GetSeasonStandingsLastUpdatedTimeCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetSeasonStandingsLastUpdatedTimeCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_SeasonStandings_GetLastUpdatedTime", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GetSeasonStandingsLastUpdatedTimeCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}