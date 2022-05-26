namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class ImportMatchStatisticCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportMatchStatisticCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncMatch_StatisticImport", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportMatchStatisticCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportMatchStatisticCommand { MatchScoreJson = "Json", MatchDetailJson = "abc" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}