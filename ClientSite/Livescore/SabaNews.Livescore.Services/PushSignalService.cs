namespace SabaNews.Livescore.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Http.Connections;
    using Microsoft.AspNetCore.SignalR.Client;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Models;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Interfaces;

    public class PushSignalService : IPushSignalService
    {
        private readonly PushSignalSettings signalRSettings;
        private readonly HubConnection hubConnection;
        private readonly ILogServiceProvider logger;

        public PushSignalService(
            ILogServiceProvider logger,
            PushSignalSettings signalRSettings)
        {
            this.signalRSettings = signalRSettings;
            this.logger = logger;
            hubConnection = new HubConnectionBuilder()
                           .WithUrl(new Uri(signalRSettings.Endpoint),
                                options =>
                                {
                                    options.Transports = HttpTransportType.WebSockets;
                                    options.SkipNegotiation = true;
                                })
                           .WithAutomaticReconnect()
                           .Build();
        }

        public async Task PushMatchListAsync(IEnumerable<Match> matches)
        {
            await ReInitConnect();
            await hubConnection.InvokeAsync(signalRSettings.UpdateLiveMatchMethod, matches).ContinueWith(task =>
            {
                if (task.IsFaulted)
                {
                    logger.WriteExceptionSentryErrorAsync(task.Exception);
                }
            });
        }

        public async Task PushMatchDetailcAsync(long matchId, MatchDetail detail)
        {
            await ReInitConnect();

            await hubConnection.InvokeAsync(signalRSettings.UpdateLiveMatchDetail, matchId, detail).ContinueWith(task =>
            {
                if (task.IsFaulted)
                {
                    logger.WriteExceptionSentryErrorAsync(task.Exception);
                }
            });
        }

        private async Task ReInitConnect()
        {
            if (hubConnection.State == HubConnectionState.Disconnected)
            {
                await hubConnection.StartAsync();
            }
        }
    }
}