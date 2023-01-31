namespace SabaNews.Livescore.SyncData.Tests.Criteria.Team
{
    using SabaNews.Livescore.SyncData.Criteria.Team;
    using Xunit;

    public class GetTeamLastUpdatedTimeCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetTeamLastUpdatedTimeCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_Team_GetLastUpdatedTime", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GetTeamLastUpdatedTimeCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}