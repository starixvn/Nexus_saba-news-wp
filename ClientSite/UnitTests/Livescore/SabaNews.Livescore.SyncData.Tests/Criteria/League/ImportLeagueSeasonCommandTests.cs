namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using SabaNews.Livescore.SyncData.Criteria.League;
    using Xunit;

    public class ImportLeagueSeasonCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportLeagueSeasonCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_LeagueSeason_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportLeagueSeasonCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportLeagueSeasonCommand { LeagueListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}