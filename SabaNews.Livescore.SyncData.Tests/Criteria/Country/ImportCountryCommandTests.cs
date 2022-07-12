using SabaNews.Livescore.SyncData.Criteria.Country;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Livescore.SyncData.Tests.Criteria.Country
{
    public class ImportCountryCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new ImportCountryCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_Sync_Country_Import", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new ImportCountryCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new ImportCountryCommand { CountryListJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}
