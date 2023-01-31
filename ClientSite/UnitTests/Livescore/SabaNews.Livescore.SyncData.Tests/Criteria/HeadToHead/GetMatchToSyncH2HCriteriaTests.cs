namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using System;
    using SabaNews.Livescore.SyncData.Criteria.HeadToHead;
    using Xunit;

    public class GetMatchToSyncH2HCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetMatchToSyncH2HCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncHeadToHead_GetMatchToSync", actual);
        }

        [Fact]
        public void IsValid_ParamsAreInvalid_ReturnFalse()
        {
            var criteria = new GetMatchToSyncH2HCriteria
            {
                FromDate = null,
                ToDate = DateTime.Now
            };
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ParamsAreValid_ReturnTrue()
        {
            var criteria = new GetMatchToSyncH2HCriteria
            {
                FromDate = DateTime.Now,
                ToDate = DateTime.Now
            };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}