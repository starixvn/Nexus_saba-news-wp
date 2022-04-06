namespace SabaNews.Livescore.Services
{
    using System;
    using System.Collections.Generic;
    using System.Net.Http;
    using System.Text;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Models;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Services.Interfaces;

    public class PushSignalService : IPushSignalService
    {
        private const string ContentType = "application/json";
        private readonly IHttpClientFactory httpClientFactory;
        private readonly PushSignalSettings signalRSettings;
        private readonly ILogServiceProvider logger;

        public PushSignalService(
            IHttpClientFactory httpClientFactory,
            ILogServiceProvider logger,
            PushSignalSettings signalRSettings
            )
        {
            this.httpClientFactory = httpClientFactory;
            this.signalRSettings = signalRSettings;
            this.logger = logger;
        }

        public async Task PushMatchListAsync(IEnumerable<Match> matches)
        {
            var data = new Dictionary<string, object>
            {
                { "matches", matches }
            };

            await PostAsync(signalRSettings.UpdateLiveSoccerUrl, data);
        }

        public async Task PushMatchStatisticAsync(long matchId, MathGeneralInfo details)
        {
            var data = new Dictionary<string, object>
            {
                { "statistic", details }
            };
            string url = string.Format("{0}/{1}", signalRSettings.UpdateLiveMatchStatisticUrl, matchId);

            await PostAsync(url, data);
        }

        public async Task PushMatchLineupAsync(long matchId, LineupDetail lineup)
        {
            var data = new Dictionary<string, object>
            {
                { "lineup", lineup }
            };
            string url = string.Format("{0}/{1}", signalRSettings.UpdateLiveMatchLineupUrl, matchId);

            await PostAsync(url, data);
        }

        public async Task PushMatchIncidentAsync(long matchId, IEnumerable<MatchIncidentEvent> incidents)
        {
            var data = new Dictionary<string, object>
            {
                { "incidents", incidents }
            };
            string url = string.Format("{0}/{1}", signalRSettings.UpdateLiveMatchIncidentUrl, matchId);

            await PostAsync(url, data);
        }

        private async Task PostAsync(string endPoint, IDictionary<string, object> data)
        {
            using (var request = CreateRequest(endPoint))
            {
                request.Content = new StringContent(JsonConvert.SerializeObject(data), Encoding.UTF8, ContentType);
                var client = httpClientFactory.CreateClient();

                using var response = await client.SendAsync(request);
                if (!response.IsSuccessStatusCode)
                {
                    await logger.WriteMessageSentryErrorAsync($"Send {request.RequestUri.AbsoluteUri} fail - Status Code: {response.StatusCode}");
                }
            }
        }

        private HttpRequestMessage CreateRequest(string endPointPath)
        {
            Uri url = new($"{signalRSettings.Endpoint}{endPointPath}");
            var request = new HttpRequestMessage(HttpMethod.Post, url);

            return request;
        }
    }
}