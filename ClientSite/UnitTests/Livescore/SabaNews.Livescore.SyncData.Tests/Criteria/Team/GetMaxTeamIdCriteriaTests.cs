namespace SabaNews.Livescore.SyncData.Tests.Criteria.Team
{
    using SabaNews.Livescore.SyncData.Criteria.Team;
    using Xunit;

    public class GetMaxTeamIdCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetMaxTeamIdCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_Team_GetMaxId", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GetMaxTeamIdCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}