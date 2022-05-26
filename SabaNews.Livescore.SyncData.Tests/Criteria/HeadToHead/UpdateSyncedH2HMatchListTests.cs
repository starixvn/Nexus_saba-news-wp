namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using SabaNews.Livescore.SyncData.Criteria.HeadToHead;
    using Xunit;

    public class UpdateSyncedH2HMatchListTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new UpdateSyncedH2HMatchList();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncHeadToHead_UpdateSyncedMatch", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new UpdateSyncedH2HMatchList();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new UpdateSyncedH2HMatchList { MatchId = 1000 };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}