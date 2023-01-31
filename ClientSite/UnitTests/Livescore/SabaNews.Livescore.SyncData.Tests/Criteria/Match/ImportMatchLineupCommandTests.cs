namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class ImportMatchLineupCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportMatchLineupCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Match_Lineup_Sync", actual);
        }

        [Theory]
        [InlineData("", "")]
        [InlineData("Test", "")]
        [InlineData("", "Test")]
        public void IsValid_InValidParam_ReturnFalse(string lineUp, string player)
        {
            var criteria = new ImportMatchLineupCommand { MatchLineupJson = lineUp, LineupPlayerJson = player };
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportMatchLineupCommand { MatchLineupJson = "Json", LineupPlayerJson = "json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}