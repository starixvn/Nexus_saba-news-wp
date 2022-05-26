namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class ImportMatchCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportMatchCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Match_Sync", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportMatchCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportMatchCommand { MatchListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}