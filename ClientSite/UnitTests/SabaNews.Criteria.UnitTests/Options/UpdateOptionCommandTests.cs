using SabaNews.Criteria.Options;
using SabaNews.Criteria.Posts;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Criteria.UnitTests.Options
{
    public class UpdateOptionCommandTests
    {
        [Fact]
        public void GetSettingKey_WhenCalled_ReturnExpectedSettingKey()
        {
            // Arrange
            var criteria = new UpdateOptionCommand();
            var expected = "Options_UpdateKey";

            // Act
            var actual = criteria.GetSettingKey();

            // Assert
            Assert.Equal(expected, actual);
        }

        [Theory]
        [InlineData("", "")]
        [InlineData("", "value")]
        public void IsValid_WhenCalled_ReturnFalse(string key, string value)
        {
            // Arrange
            var criteria = new UpdateOptionCommand()
            {
                OptionKey = key,
                OptionValue = value
            };

            // Act
            var actual = criteria.IsValid();

            // Assert
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_WhenCalled_ReturnTrue()
        {
            // Arrange
            var criteria = new UpdateOptionCommand()
            {
                OptionKey = "key",
                OptionValue = "value"
            };

            // Act
            var actual = criteria.IsValid();

            // Assert
            Assert.True(actual);
        }
    }
}
