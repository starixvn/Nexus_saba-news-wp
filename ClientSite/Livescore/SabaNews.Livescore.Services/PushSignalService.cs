namespace SabaNews.Livescore.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.SignalR.Client;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Models;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Interfaces;

    public class PushSignalService : IPushSignalService
    {
        private readonly PushSignalSettings signalRSettings;
        private HubConnection hubConnection;
        private readonly ILogServiceProvider logger;

        public PushSignalService(
            ILogServiceProvider logger,
            PushSignalSettings signalRSettings
            )
        {
            this.signalRSettings = signalRSettings;
            this.logger = logger;
            hubConnection = new HubConnectionBuilder()
                           .WithUrl(new Uri(signalRSettings.Endpoint))
                           .WithAutomaticReconnect()
                           .Build();
        }

        public async Task PushMatchListAsync(IEnumerable<Match> matches)
        {
            await hubConnection.StartAsync();
            await hubConnection.InvokeAsync(signalRSettings.UpdateLiveMatchMethod, matches);
        }

        public async Task PushMatchStatisticAsync(long matchId, Statistic statistic)
        {
            await hubConnection.InvokeAsync(signalRSettings.UpdateLiveMatchMethod, matchId, statistic);
        }

        public async Task PushMatchLineupAsync(long matchId, LineupDetail lineup)
        {
            await hubConnection.InvokeAsync(signalRSettings.UpdateLineupMethod, matchId, lineup);
        }

        public async Task PushMatchIncidentAsync(long matchId, IEnumerable<MatchIncident> incidents)
        {
            await hubConnection.InvokeAsync(signalRSettings.UpdateIncidentMethod, matchId, incidents);
        }
    }
}