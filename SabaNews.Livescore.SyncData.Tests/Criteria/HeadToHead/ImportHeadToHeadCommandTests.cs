namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using SabaNews.Livescore.SyncData.Criteria.HeadToHead;
    using Xunit;

    public class ImportHeadToHeadCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportHeadToHeadCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncHeadToHead_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportHeadToHeadCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportHeadToHeadCommand { HeadToHeadJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}