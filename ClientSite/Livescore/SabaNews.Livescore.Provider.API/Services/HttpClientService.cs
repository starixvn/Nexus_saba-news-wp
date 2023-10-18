namespace SabaNews.Livescore.Provider.API.Services
{
    using System;
    using System.Collections.Generic;
    using System.Net.Http;
    using System.Text;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Newtonsoft.Json;
    using Newtonsoft.Json.Linq;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Constants;
    using SabaNews.Livescore.Provider.API.Criteria;
    using SabaNews.Livescore.Provider.API.Models;

    public class HttpClientService : IHttpClientService
    {
        private const string ContentType = "application/json";
        private readonly IHttpClientFactory clientFactory;
        private readonly ICacheWrapperService cacheWrapperService;
        private readonly ILogServiceProvider logService;
        private readonly IBaseService baseService;
        private readonly ApiConfiguration config;

        public HttpClientService(
            IHttpClientFactory clientFactory,
            ICacheWrapperService cacheWrapperService,
            ILogServiceProvider logService,
            IBaseService baseService,
            ApiConfiguration config)
        {
            this.clientFactory = clientFactory;
            this.cacheWrapperService = cacheWrapperService;
            this.logService = logService;
            this.baseService = baseService;
            this.config = config;
        }

        public async Task<T> ExecuteGetAsync<T>(string endPoint, IDictionary<string, object> data = null) where T : new()
                 => await SendHttpClientAsync<T>(endPoint, HttpMethod.Get, data);

        public async Task<T> ExecutePostAsync<T>(string endPoint, IDictionary<string, object> data) where T : new()
         => await SendHttpClientAsync<T>(endPoint, HttpMethod.Post, data);

        private async Task<T> SendHttpClientAsync<T>(string endPoint, HttpMethod method, IDictionary<string, object> data, bool isAuthentication = false)
            where T : new()
        {
            ValidateEndPoint(endPoint);

            using var request = CreateRequest(endPoint, method);
            StringContent content = await InitStringContent(data, isAuthentication);
            request.Content = content;
            var client = clientFactory.CreateClient(HttpClientName.DefaultHttpClientWithProxy);

            using var response = await client.SendAsync(request);

            if (response.IsSuccessStatusCode)
            {
                var jsonResult = await response.Content.ReadAsStringAsync();
                if (config.IsWriteLogRequest)
                {
                    await AddLogSendRequestonDB($"{config.Endpoint}{endPoint}", JsonConvert.SerializeObject(content), config.IsWriteResponse ? jsonResult : "{}");
                }
                var message = $"Response -- API: {endPoint} - Params: {JsonConvert.SerializeObject(data)} - Code: {response.StatusCode},{response.IsSuccessStatusCode}";
                var result = HandleDataResponse<T>(jsonResult, message);

                return result;
            }

            var err = await response.Content.ReadAsStringAsync();
            await logService.WriteMessageSentryErrorAsync($"API: {endPoint} - Params: {JsonConvert.SerializeObject(data)} - Message: {err}");
            return default;
        }

        private async Task AddLogSendRequestonDB(string url, string param, string response)
        {
            try
            {
                var command = new InsertSendHttpRequestLogCommand
                {
                    RequestUrl = url,
                    Param = param,
                    Response = response
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
            }
        }

        private async Task<StringContent> InitStringContent(IDictionary<string, object> data, bool isAuthentication)
        {
            data ??= new Dictionary<string, object>();

            if (!isAuthentication)
            {
                string token = await GetTokenAsync();
                data.Add("token", token);
            }

            var content = new StringContent(JsonConvert.SerializeObject(data), Encoding.UTF8, ContentType);
            return content;
        }

        private HttpRequestMessage CreateRequest(string endPointPath, HttpMethod method)
        {
            Uri url = new($"{config.Endpoint}{endPointPath}");
            var request = new HttpRequestMessage(method, url);

            return request;
        }

        private static void ValidateEndPoint(string endPoint)
        {
            if (string.IsNullOrWhiteSpace(endPoint))
            {
                throw new ArgumentNullException(nameof(endPoint));
            }
        }

        private T HandleDataResponse<T>(string json, string message)
        {
            if (string.IsNullOrWhiteSpace(json))
            {
                return default;
            }

            JObject jsonObject = JObject.Parse(json);
            if (IsSuccess(jsonObject))
            {
                var jToken = jsonObject["data"];
                var result = jToken.ToObject<T>();
                return result;
            }

            logService.WriteMessageSentryErrorAsync($"{message} - {json}");
            return default;
        }

        private static bool IsSuccess(JObject jObject) => jObject.ContainsKey("code") && (int)jObject["code"] == 0;

        private async Task<string> GetTokenAsync()
        {
            var tokenModel = await cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey);
            tokenModel ??= await RefreshTokenAsync();

            return tokenModel?.Token;
        }

        public async Task<AuthToken> RefreshTokenAsync()
        {
            var tokenModel = await AuthenticateAsync();

            if (tokenModel is not null)
            {
                await cacheWrapperService.SetAsync(CachingKeys.APITokenKey, tokenModel,
                new CacheItemOptions
                {
                    AbsoluteExpiration = tokenModel.TokenExpired
                });
            }

            return tokenModel;
        }

        private async Task<AuthToken> AuthenticateAsync()
        {
            var authData = new Dictionary<string, object>
            {
                {"project", config.Project},
                {"secret", config.Secret}
            };
            return await SendHttpClientAsync<AuthToken>(config.LoginPath, HttpMethod.Post, authData, true);
        }
    }
}