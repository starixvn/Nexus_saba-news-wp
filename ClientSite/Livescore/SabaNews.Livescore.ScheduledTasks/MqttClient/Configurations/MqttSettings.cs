namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Configurations
{
    internal class MqttSettings
    {
        public const string ConfigurationKey = "MQTTSettings";
        public bool Enabled { get; set; }
        public bool UseProxy { get; set; }
        public string ProxyAddress { get; set; }
        public bool ByPassLocal { get; set; }
        public string Host { get; set; }
        public string WebSocketHost { get; set; }
        public int Port { get; set; }
        public string[] SubscribedTopics { get; set; }
        public MqttClientMode Mode { get; set; }
    }
}