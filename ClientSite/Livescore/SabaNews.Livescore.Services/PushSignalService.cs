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

        public async Task PushToMatchListAsync(IEnumerable<Match> matches)
        {
            var data = new Dictionary<string, object>
            {
                { "matches", matches }
            };

            await PostAsync(signalRSettings.UpdateLiveSoccerUrl, data);
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