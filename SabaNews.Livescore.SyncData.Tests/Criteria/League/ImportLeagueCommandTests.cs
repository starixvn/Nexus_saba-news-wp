namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using SabaNews.Livescore.SyncData.Criteria.League;
    using Xunit;

    public class ImportLeagueCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportLeagueCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_League_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportLeagueCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportLeagueCommand { LeagueListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}