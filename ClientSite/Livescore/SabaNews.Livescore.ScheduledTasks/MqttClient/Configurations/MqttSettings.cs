namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Configurations
{
    public class MqttSettings
    {
        public const string ConfigurationKey = "MQTTSettings";
        public bool Enabled { get; set; }
        public bool UseProxy { get; set; }
        public string ProxyAddress { get; set; }
        public bool ByPassLocal { get; set; }
        public string WebSocketHost { get; set; }
        public string Username { get; set; }
        public string[] SubscribedTopics { get; set; }
    }
}