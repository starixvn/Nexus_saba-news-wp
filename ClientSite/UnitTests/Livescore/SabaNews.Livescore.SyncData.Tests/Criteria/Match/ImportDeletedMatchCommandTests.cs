namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class ImportDeletedMatchCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportDeletedMatchCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncMatch_SyncDeteledMatch", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportDeletedMatchCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportDeletedMatchCommand { DeletedMatchJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}