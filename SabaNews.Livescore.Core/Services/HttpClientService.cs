namespace SabaNews.Livescore.Core.Services
{
    using System;
    using System.Collections.Generic;
    using System.Net.Http;
    using System.Text;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using Newtonsoft.Json.Linq;
    using SabaNews.Livescore.Core.Constants;
    using SabaNews.Livescore.Core.Models;
    using SabaNews.Livescore.Core.Services.Interfaces;

    public class HttpClientService : IHttpClientService
    {
        private const string ContentType = "application/json";
        private readonly IHttpClientFactory clientFactory;
        private readonly ApiConfiguration config;

        public HttpClientService(IHttpClientFactory clientFactory, ApiConfiguration config)
        {
            this.clientFactory = clientFactory;
            this.config = config;
        }

        public async Task<T> ExecuteGetAsync<T>(string endPoint, IDictionary<string, object> data = null) where T : new()
                 => await SendHttpClientAsync<T>(endPoint, HttpMethod.Get, data);

        public async Task<T> ExecutePostAsync<T>(string endPoint, IDictionary<string, object> data) where T : new()
         => await SendHttpClientAsync<T>(endPoint, HttpMethod.Post, data);

        private async Task<T> SendHttpClientAsync<T>(string endPoint, HttpMethod method, IDictionary<string, object> data)
            where T : new()
        {
            ValidateEndPoint(endPoint);

            using var request = CreateRequest(endPoint, method);
            if (data != null)
            {
                var content = new StringContent(JsonConvert.SerializeObject(data), Encoding.UTF8, ContentType);
                request.Content = content;
            }
            var client = clientFactory.CreateClient(HttpClientName.DefaultHttpClientWithProxy);

            using var response = await client.SendAsync(request);
            if (response.IsSuccessStatusCode)
            {
                var jsonResult = await response.Content.ReadAsStringAsync();

                var result = HandleDataResponse<T>(jsonResult);

                return result;
            }

            return default;
        }

        private HttpRequestMessage CreateRequest(string endPointPath, HttpMethod method)
        {
            Uri url = new($"{config.Endpoint}{endPointPath}");
            var request = new HttpRequestMessage(method, url);
            request.Headers.Accept.Clear();

            request.Headers.Add("Key", config.ApiKey);

            return request;
        }

        private static void ValidateEndPoint(string endPoint)
        {
            if (string.IsNullOrWhiteSpace(endPoint))
            {
                throw new ArgumentNullException(nameof(endPoint));
            }
        }

        private static T HandleDataResponse<T>(string json)
        {
            if (string.IsNullOrWhiteSpace(json))
            {
                return default;
            }
            JObject jsonObject = JObject.Parse(json);
            var value = jsonObject.Value<dynamic>("Result");
            return jsonObject.Value<T>("Result");
        }
    }
}