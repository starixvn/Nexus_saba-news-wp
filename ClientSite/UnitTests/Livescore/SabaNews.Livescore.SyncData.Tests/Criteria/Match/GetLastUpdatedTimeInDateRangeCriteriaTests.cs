namespace SabaNews.Livescore.SyncData.Tests.Criteria
{
    using System;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using Xunit;

    public class GetLastUpdatedTimeInDateRangeCriteriaTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetLastUpdatedTimeInDateRangeCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncMatch_GetLastUpdatedTimeByDateRange", actual);
        }

        [Fact]
        public void IsValid_ParamsAreInvalid_ReturnFalse()
        {
            var criteria = new GetLastUpdatedTimeInDateRangeCriteria
            {
                StartTime = null,
                EndTime = DateTime.Now
            };
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ParamsAreValid_ReturnTrue()
        {
            var criteria = new GetLastUpdatedTimeInDateRangeCriteria
            {
                StartTime = DateTime.Now,
                EndTime = DateTime.Now
            };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}