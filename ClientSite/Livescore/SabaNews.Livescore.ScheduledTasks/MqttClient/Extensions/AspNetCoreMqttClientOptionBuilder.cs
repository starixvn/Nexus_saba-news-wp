namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Extensions
{
    using System;
    using MQTTnet.Client.Options;

    public class AspNetCoreMqttClientOptionBuilder : MqttClientOptionsBuilder
    {
        public IServiceProvider ServiceProvider { get; }

        public AspNetCoreMqttClientOptionBuilder(IServiceProvider serviceProvider)
        {
            ServiceProvider = serviceProvider;
        }
    }
}