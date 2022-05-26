namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using SabaNews.Livescore.SyncData.Criteria.League;
    using Xunit;

    public class GetMaxLeagueIdCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetMaxLeagueIdCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_League_GetMaxId", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GetMaxLeagueIdCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}