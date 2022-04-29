namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Services
{
    using Microsoft.Extensions.Hosting;
    using MQTTnet.Client.Connecting;
    using MQTTnet.Client.Disconnecting;
    using MQTTnet.Client.Receiving;

    internal interface IMqttClientService : IHostedService,
                                          IMqttClientConnectedHandler,
                                          IMqttClientDisconnectedHandler,
                                          IMqttApplicationMessageReceivedHandler
    {
    }
}