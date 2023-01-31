namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class ImportIncidentCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportIncidentCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Match_Incident_Sync", actual);
        }

        [Theory]
        [InlineData(0, "")]
        [InlineData(1, "")]
        [InlineData(0, "abc")]
        public void IsValid_InValidParam_ReturnFalse(long matchId, string incidentJson)
        {
            var criteria = new ImportIncidentCommand { MatchId = matchId, IncidentJson = incidentJson };
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportIncidentCommand { IncidentJson = "Json", MatchId = 1 };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}