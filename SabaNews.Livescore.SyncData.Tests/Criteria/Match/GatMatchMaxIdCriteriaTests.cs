namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class GatMatchMaxIdCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GatMatchMaxIdCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livesocre_Match_GetMaxMatchId", actual);
        }

        [Fact]
        public void IsValid_Always_ReturnTrue()
        {
            var criteria = new GatMatchMaxIdCriteria();
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}