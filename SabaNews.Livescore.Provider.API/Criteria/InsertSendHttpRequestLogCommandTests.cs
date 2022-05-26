namespace SabaNews.Livescore.Provider.API.Tests.Criteria
{
    using SabaNews.Livescore.Provider.API.Criteria;
    using Xunit;

    public class InsertSendHttpRequestLogCommandTests
    {
        [Fact]
        public void GetSettingKey_ReturnCorrectSettingKey()
        {
            // Arrange
            var criteria = new InsertSendHttpRequestLogCommand();
            var expected = "Livescore_HttpRequestLog_Insert";

            // Act
            var actual = criteria.GetSettingKey();

            // Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public void NullRequireParam_IsValid_ReturnFalse()
        {
            var criteria = new InsertSendHttpRequestLogCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void HasValueRequireParam_IsValid_ReturnFalse()
        {
            var criteria = new InsertSendHttpRequestLogCommand { RequestUrl = "ABC" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}