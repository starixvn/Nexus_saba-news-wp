namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;


    public class UpdateIncidentUpdatedTimeCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new UpdateIncidentUpdatedTimeCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Match_Incident_UpdateTimeSync", actual);
        }

        [Fact]
        public void IsValid_ParamsIsValid_ReturnTrue()
        {
            var criteria = new UpdateIncidentUpdatedTimeCommand();

            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}
