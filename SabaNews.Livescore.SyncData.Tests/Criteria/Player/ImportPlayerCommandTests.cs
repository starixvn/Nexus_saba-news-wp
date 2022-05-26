namespace SabaNews.Livescore.SyncData.Tests.Criteria.Player
{
    using SabaNews.Livescore.SyncData.Criteria.Player;
    using Xunit;

    public class ImportPlayerCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportPlayerCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_Player_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportPlayerCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportPlayerCommand { PlayerListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}