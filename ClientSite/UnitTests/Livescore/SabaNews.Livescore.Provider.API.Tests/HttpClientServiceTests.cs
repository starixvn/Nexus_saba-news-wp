namespace SabaNews.Livescore.Provider.API
{
    using System;
    using System.Collections.Generic;
    using System.Net;
    using System.Net.Http;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using NSubstitute;
    using NSubstitute.ExceptionExtensions;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Constants;
    using SabaNews.Livescore.Provider.API.Criteria;
    using SabaNews.Livescore.Provider.API.Models;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.Provider.API.Tests;
    using Xunit;

    public class HttpClientServiceTests
    {
        private IHttpClientFactory clientFactory;
        private ICacheWrapperService cacheWrapperService;
        private ILogServiceProvider logService;
        private IBaseService baseService;
        private ApiConfiguration config;

        [Fact]
        public void ExecuteGetAsync_InvalidEndPoint_ThrowsArgumentNullException()
        {
            var service = GetService();
            // Act & Assert
            Assert.ThrowsAsync<ArgumentNullException>(() => service.ExecuteGetAsync<object>(string.Empty));
        }

        [Fact]
        public async Task SendHttpClientAsync_ValidEndPoint_CallsHttpClientExecuteAsync()
        {
            var service = GetService();
            // Arrange
            var endPoint = "http://endpoint";

            var response = new HttpResponseMessage(HttpStatusCode.OK)
            {
                Content = new StringContent(string.Empty)
            };
            cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey).Returns(new AuthToken());
            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));

            // Act
            await service.ExecuteGetAsync<object>(endPoint);

            // Assert
            clientFactory.Received().CreateClient(Arg.Is(HttpClientName.DefaultHttpClientWithProxy));
        }

        [Fact]
        public async Task ExecutePostAsync_EnableWriteLog_CallsWriteLogAsync()
        {
            var service = GetService(true);
            // Arrange
            var endPoint = "http://endpoint";

            var response = new HttpResponseMessage(HttpStatusCode.OK)
            {
                Content = new StringContent(string.Empty)
            };
            cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey).Returns(new AuthToken());
            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));

            // Act
            await service.ExecutePostAsync<object>(endPoint, new Dictionary<string, object>());

            // Assert
            clientFactory.Received().CreateClient(Arg.Is(HttpClientName.DefaultHttpClientWithProxy));
            await baseService.Received().ExecuteNonQueryAsync(Arg.Any<InsertSendHttpRequestLogCommand>());
        }

        [Fact]
        public async Task ExecutePostAsync_WriteLongError_CallsWriteLogSentryAsync()
        {
            var service = GetService(true);
            // Arrange
            var endPoint = "http://endpoint";

            var response = new HttpResponseMessage(HttpStatusCode.OK)
            {
                Content = new StringContent(string.Empty)
            };
            cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey).Returns(new AuthToken());
            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));
            var exception = new Exception("Exeption");
            baseService.ExecuteNonQueryAsync(Arg.Any<InsertSendHttpRequestLogCommand>()).Throws(exception);
            // Act
            await service.ExecutePostAsync<object>(endPoint, new Dictionary<string, object>());

            // Assert
            clientFactory.Received().CreateClient(Arg.Is(HttpClientName.DefaultHttpClientWithProxy));
            await logService.Received().WriteExceptionSentryErrorAsync(exception);
        }

        [Fact]
        public async Task ExecutePostAsync_GetNotSuccessCode_WriteLogSentryMessage()
        {
            var service = GetService();
            // Arrange
            var endPoint = "http://endpoint";

            var obj = new
            {
                Test = 1,
                Name = "ABC"
            };
            var stringContent = FakeJson<object>(false, obj);
            var response = new HttpResponseMessage(HttpStatusCode.OK)
            {
                Content = new StringContent(stringContent)
            };
            cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey).Returns(new AuthToken());
            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));
            var exception = new Exception("Exeption");
            baseService.ExecuteNonQueryAsync(Arg.Any<InsertSendHttpRequestLogCommand>()).Throws(exception);
            // Act
            await service.ExecutePostAsync<object>(endPoint, new Dictionary<string, object>());

            // Assert
            clientFactory.Received().CreateClient(Arg.Is(HttpClientName.DefaultHttpClientWithProxy));
            await logService.Received().WriteMessageSentryErrorAsync(Arg.Any<string>());
        }

        [Fact]
        public async Task ExecutePostAsync_GetSuccessCode_ReturnExpectedResult()
        {
            var service = GetService();
            // Arrange
            var endPoint = "http://endpoint";

            var obj = new
            {
                Test = 1,
                Name = "ABC"
            };
            var stringContent = FakeJson<object>(true, obj);
            var response = new HttpResponseMessage(HttpStatusCode.OK)
            {
                Content = new StringContent(stringContent)
            };
            cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey).Returns(new AuthToken());
            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));
            // Act
            var result = await service.ExecutePostAsync<object>(endPoint, new Dictionary<string, object>());

            // Assert
            clientFactory.Received().CreateClient(Arg.Is(HttpClientName.DefaultHttpClientWithProxy));
            await logService.DidNotReceive().WriteMessageSentryErrorAsync(Arg.Is(stringContent));
            Assert.Equal(JsonConvert.SerializeObject(obj), JsonConvert.SerializeObject(result));
        }

        [Fact]
        public async Task CallAPI_WhenReturnStatusError_WriteLogSentry()
        {
            var service = GetService();
            // Arrange
            var endPoint = "http://endpoint";
            var param = new Dictionary<string, object>();
            param.Add("token", null);
            var fakeJson = FakeJson(false, new { test = "" });
            var response = new HttpResponseMessage(HttpStatusCode.Forbidden)
            {
                Content = new StringContent(fakeJson)
            };
            cacheWrapperService.GetAsync<AuthToken>(CachingKeys.APITokenKey).Returns(new AuthToken());

            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));

            // Act
            await service.ExecutePostAsync<object>(endPoint, new Dictionary<string, object>());
            await logService.Received().WriteMessageSentryErrorAsync($"API: {endPoint} - Params: {JsonConvert.SerializeObject(param)} - Message: {fakeJson}");
            // Assert
            clientFactory.Received().CreateClient(Arg.Is(HttpClientName.DefaultHttpClientWithProxy));
        }

        [Fact]
        public async Task RefreshTokenAsync_ReturnAuthenticateModel_AndSetCache()
        {
            var service = GetService();
            var fakeJson = FakeJson(false, new AuthToken { Token = "321321321" });
            var response = new HttpResponseMessage(HttpStatusCode.Forbidden)
            {
                Content = new StringContent(fakeJson)
            };

            clientFactory.CreateClient(Arg.Any<string>()).Returns(new HttpClient(new MockHttpMessageHandler(response)));
            await service.RefreshTokenAsync();
        }

        private string FakeJson<T>(bool isSuccess = true, T outputObj = default)
        {
            var obj = new
            {
                code = isSuccess ? 0 : 1,
                message = string.Empty,
                data = outputObj
            };
            return JsonConvert.SerializeObject(obj);
        }

        private HttpClientService GetService(bool writeLogConfig = false)
        {
            clientFactory = Substitute.For<IHttpClientFactory>();
            cacheWrapperService = Substitute.For<ICacheWrapperService>();
            logService = Substitute.For<ILogServiceProvider>();
            baseService = Substitute.For<IBaseService>();
            config = Substitute.For<ApiConfiguration>();
            config.IsWriteLogRequest = writeLogConfig;
            config.Endpoint = "http://test.com";
            config.LoginPath = "/login";
            config.Project = "project";
            config.Secret = "1234";
            return new HttpClientService(clientFactory, cacheWrapperService, logService, baseService, config);
        }
    }
}