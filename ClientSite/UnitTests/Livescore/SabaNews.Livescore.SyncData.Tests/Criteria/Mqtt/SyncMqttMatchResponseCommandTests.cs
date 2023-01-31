namespace SabaNews.Livescore.SyncData.Tests.Criteria.Mqtt
{
    using SabaNews.Livescore.SyncData.Criteria.Mqtt;
    using Xunit;

    public class SyncMqttMatchResponseCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new SyncMqttMatchResponseCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncMatch_ReceivedFromMqtt", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new SyncMqttMatchResponseCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new SyncMqttMatchResponseCommand { MatchJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}