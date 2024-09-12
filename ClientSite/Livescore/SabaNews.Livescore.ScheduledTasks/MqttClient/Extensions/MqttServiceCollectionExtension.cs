namespace SabaNews.Livescore.ScheduledTasks.MqttClient.Extensions
{
    using System;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using Microsoft.Extensions.Hosting;
    using MQTTnet.Client.Options;
    using SabaNews.Livescore.ScheduledTasks.MqttClient.Configurations;
    using SabaNews.Livescore.ScheduledTasks.MqttClient.Services;

    public static class MqttServiceCollectionExtension
    {
        public static IServiceCollection AddMqttClientHostedService(this IServiceCollection services, IConfiguration configuration)
        {
            var mqttSettings = configuration.GetSection(MqttSettings.ConfigurationKey).Get<MqttSettings>();
            services.AddSingleton(mqttSettings);

            if (mqttSettings.Enabled)
            {
                services.AddMqttClientServiceWithConfig(aspOptionBuilder =>
                {
                    aspOptionBuilder.WithWebSocketServer(o =>
                    {
                        o.Uri = mqttSettings.WebSocketHost;
                        if (mqttSettings.UseProxy)
                        {
                            o.ProxyOptions = new MqttClientWebSocketProxyOptions
                            {
                                Address = mqttSettings.ProxyAddress,
                                BypassOnLocal = mqttSettings.ByPassLocal
                            };
                        }
                    });
                    aspOptionBuilder.WithCredentials(mqttSettings.Username);
                });
            }

            return services;
        }

        private static IServiceCollection AddMqttClientServiceWithConfig(this IServiceCollection services, Action<AspNetCoreMqttClientOptionBuilder> configure)
        {
            services.AddSingleton(serviceProvider =>
            {
                var optionBuilder = new AspNetCoreMqttClientOptionBuilder(serviceProvider);
                configure(optionBuilder);
                return optionBuilder.Build();
            });
            services.AddSingleton<MqttClientService>();
            services.AddSingleton<IHostedService>(serviceProvider =>
            {
                return serviceProvider.GetService<MqttClientService>();
            });

            return services;
        }
    }
}