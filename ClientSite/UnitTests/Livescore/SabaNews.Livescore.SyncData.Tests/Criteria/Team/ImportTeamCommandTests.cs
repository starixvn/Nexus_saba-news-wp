namespace SabaNews.Livescore.SyncData.Tests.Criteria.Team
{
    using SabaNews.Livescore.SyncData.Criteria.Team;
    using Xunit;

    public class ImportTeamCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportTeamCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_Team_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportTeamCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportTeamCommand { TeamListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}