namespace SabaNews.Livescore.SyncData.Tests.Criteria.Mqtt
{
    using SabaNews.Livescore.SyncData.Criteria.Mqtt;
    using Xunit;

    public class SyncMqttMatchIncidentCommandTests
    {
        [Fact]
        public void GetSettingKey_Return_ExpectedStringValue()
        {
            var criteria = new SyncMqttMatchIncidentCommand();
            var actual = criteria.GetSettingKey();
            Assert.Equal("Livescore_SyncIncident_ReceivedFromMqtt", actual);
        }

        [Fact]
        public void IsValid_InValidParam_ReturnFalse()
        {
            var criteria = new SyncMqttMatchIncidentCommand();
            var actual = criteria.IsValid();
            Assert.False(actual);
        }

        [Fact]
        public void IsValid_ValidParam_ReturnTrue()
        {
            var criteria = new SyncMqttMatchIncidentCommand { IncindentJson = "Json" };
            var actual = criteria.IsValid();
            Assert.True(actual);
        }
    }
}