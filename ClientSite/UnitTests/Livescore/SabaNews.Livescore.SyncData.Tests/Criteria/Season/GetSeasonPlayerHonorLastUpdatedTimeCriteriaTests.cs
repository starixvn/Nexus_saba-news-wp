namespace SabaNews.Livescore.SyncData.Tests.Criteria.Season
{
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using Xunit;

    public class GetSeasonPlayerHonorLastUpdatedTimeCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetSeasonPlayerHonorLastUpdatedTimeCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_SeasonPlayerHonor_GetLastUpdatedTime", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GetSeasonPlayerHonorLastUpdatedTimeCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}