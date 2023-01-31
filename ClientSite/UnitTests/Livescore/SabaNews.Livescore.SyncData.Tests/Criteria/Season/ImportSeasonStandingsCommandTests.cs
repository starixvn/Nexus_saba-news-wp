namespace SabaNews.Livescore.SyncData.Tests.Criteria.Season
{
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using Xunit;

    public class ImportSeasonStandingsCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportSeasonStandingsCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_SeasonStandings_Import", actual);
        }

        [Theory]
        [InlineData(0, "")]
        [InlineData(1000, "")]
        [InlineData(0, "Test")]
        public void IsValid_InValidParam_ReturnFalse(long seasonId, string standingJson)
        {
            var criteria = new ImportSeasonStandingsCommand { SeasonId = seasonId, SeasonStandingsJson = standingJson };
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportSeasonStandingsCommand { SeasonId = 1000, SeasonStandingsJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}