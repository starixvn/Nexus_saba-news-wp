namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Extensions
{
    using System;
    using MQTTnet.Client.Options;

    internal class AspNetCoreMqttClientOptionBuilder : MqttClientOptionsBuilder
    {
        public IServiceProvider ServiceProvider { get; }

        public AspNetCoreMqttClientOptionBuilder(IServiceProvider serviceProvider)
        {
            ServiceProvider = serviceProvider;
        }
    }
}