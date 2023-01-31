namespace SabaNews.Livescore.SyncData.Tests.Criteria.Season
{
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using Xunit;

    public class ImportSeasonPlayerHonorCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportSeasonPlayerHonorCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_SeasonPlayerHonor_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportSeasonPlayerHonorCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportSeasonPlayerHonorCommand { PlayerHonorListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}