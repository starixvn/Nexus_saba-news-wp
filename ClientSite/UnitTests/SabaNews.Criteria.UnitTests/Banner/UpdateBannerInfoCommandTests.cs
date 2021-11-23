using SabaNews.Criteria.Banner;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Criteria.UnitTests.Banner
{
    public class UpdateBannerInfoCommandTests
    {
        [Fact]
        public void GetSettingKey_WhenCalled_ReturnExpectedSettingKey()
        {
            // Arrange
            var criteria = new UpdateBannerInfoCommand();
            var expected = "banner_update";

            // Act
            var actual = criteria.GetSettingKey();

            // Assert
            Assert.Equal(expected, actual);
        }

       


        [Fact]
        public void IsValid_WhenCalled_ReturnTrue()
        {
            // Arrange
            var criteria = new UpdateBannerInfoCommand()
            {
                NaviagationUrl = "url",
                DesktopUrl = "url",
                MobileUrl = "url",
                ActionId = 1,
                ActorId = 1,
                UpdatedDate = DateTime.Now
            };

            // Act
            var actual = criteria.IsValid();

            // Assert
            Assert.True(actual);
        }
    }
}
