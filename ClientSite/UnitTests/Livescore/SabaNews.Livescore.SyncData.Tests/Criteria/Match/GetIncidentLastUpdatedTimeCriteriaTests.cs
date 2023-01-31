using SabaNews.Livescore.SyncData.Criteria.Matches;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Livescore.SyncData.Tests.Criteria.Match
{
    public class GetIncidentLastUpdatedTimeCriteriaTests
    {

        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new GetIncidentLastUpdatedTimeCriteria();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Match_Incident_GetLastUpdatedTime", actual);
        }

        [Fact]
        public void IsValid_ParamsIsValid_ReturnTrue()
        {
            var criteria = new GetIncidentLastUpdatedTimeCriteria();
            
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}
