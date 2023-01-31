namespace SabaNews.Livescore.SyncData.Tests.Criteria.Player
{
    using SabaNews.Livescore.SyncData.Criteria.Player;
    using Xunit;

    public class GetPlayerLastUpdatedTimeCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetPlayerLastUpdatedTimeCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_Player_GetLastUpdatedTime", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GetPlayerLastUpdatedTimeCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}