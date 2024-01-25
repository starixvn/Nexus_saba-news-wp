namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Services
{
    using System;
    using System.Collections.Generic;
    using System.Text;
    using System.Threading;
    using System.Threading.Tasks;
    using MQTTnet;
    using MQTTnet.Client;
    using MQTTnet.Client.Connecting;
    using MQTTnet.Client.Disconnecting;
    using MQTTnet.Client.Options;
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Extensions;
    using SabaNews.Livescore.Models.Mqtt;
    using SabaNews.Livescore.ScheduledTasks.MqttClient.Configurations;
    using SabaNews.Livescore.Services.Interfaces;

    public class MqttClientService : IMqttClientService
    {
        private readonly IMqttClient mqttClient;
        private readonly ILogServiceProvider logger;
        private readonly IMqttClientOptions options;
        private readonly ILiveMatchService liveMatchService;
        private readonly string[] subscribedTopics;

        public MqttClientService(ILogServiceProvider logger,
            IMqttClientOptions options,
            MqttSettings settings,
            ILiveMatchService liveMatchService)
        {
            this.options = options;
            this.logger = logger;
            this.liveMatchService = liveMatchService;
            subscribedTopics = settings.SubscribedTopics;
            mqttClient = new MqttFactory().CreateMqttClient();
            ConfigureMqttClient();
        }

        private void ConfigureMqttClient()
        {
            mqttClient.ConnectedHandler = this;
            mqttClient.DisconnectedHandler = this;
            mqttClient.ApplicationMessageReceivedHandler = this;
        }

        public async Task HandleConnectedAsync(MqttClientConnectedEventArgs eventArgs)
        {
            logger.WriteLogFileInfo("Connectd MQTT server");
            if (subscribedTopics.Length > 0)
            {
                var topics = new List<MqttTopicFilter>();
                foreach (var topic in subscribedTopics)
                {
                    topics.Add(
                        new MqttTopicFilter
                        {
                            Topic = topic,
                        }
                        );
                }
                await mqttClient.SubscribeAsync(topics.ToArray());
                logger.WriteLogFileInfo($"Subscibed topics: {string.Join(", ", subscribedTopics)}");
            }
        }

        public async Task HandleDisconnectedAsync(MqttClientDisconnectedEventArgs eventArgs)
        {
            await mqttClient.ReconnectAsync();

            if (eventArgs.Exception is not null)
            {
                // await logger.WriteExceptionSentryErrorAsync(eventArgs.Exception);
                logger.WriteLogFileInfo(eventArgs.Exception.Message);
            }
        }

        public async Task StartAsync(CancellationToken cancellationToken)
        {
            try
            {
                logger.WriteLogFileInfo("Starting connect MQTT");
                await mqttClient.ConnectAsync(options);

                if (!mqttClient.IsConnected)
                {
                    await mqttClient.ReconnectAsync();
                }
                logger.WriteLogFileInfo("Connected to MQTT");
            }
            catch (Exception ex)
            {
                await logger.WriteExceptionSentryErrorAsync(ex);
            }
        }

        public async Task StopAsync(CancellationToken cancellationToken)
        {
            if (cancellationToken.IsCancellationRequested)
            {
                var disconnectOption = new MqttClientDisconnectOptions
                {
                    ReasonCode = MqttClientDisconnectReason.NormalDisconnection,
                    ReasonString = "MQTT Disconnect"
                };
                await mqttClient.DisconnectAsync(disconnectOption, cancellationToken);
            }
            await mqttClient.DisconnectAsync();
            logger.WriteLogFileInfo("Disconnected MQTT");
        }

        public async Task HandleApplicationMessageReceivedAsync(MqttApplicationMessageReceivedEventArgs eventArgs)
        {
            var topic = eventArgs.ApplicationMessage.Topic;

            var json = Encoding.UTF8.GetString(eventArgs.ApplicationMessage.Payload);

            if (topic.StartsWith("/stats"))
            {
                var match = JsonConvert.DeserializeObject<MqttLiveMatch>(json);
                await HandleMatchDetailResponse(match);
            }
        }

        private async Task HandleMatchDetailResponse(MqttLiveMatch match)
        {
            var startTime = DateTimeHelper.UnixTimeStampToDateTime(match.StartTimeStamp);
            await liveMatchService.UpdateLiveMatchStartTimeAsync(match.MatchId, startTime, match.StartTimeStamp);
        }
    }
}