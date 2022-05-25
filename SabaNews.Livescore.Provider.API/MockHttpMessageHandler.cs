namespace SabaNews.Livescore.Provider.API.Tests
{
    using System.Net.Http;
    using System.Threading;
    using System.Threading.Tasks;

    internal class MockHttpMessageHandler : DelegatingHandler
    {
        private readonly HttpResponseMessage responseMsg;

        public MockHttpMessageHandler(HttpResponseMessage responseMsg)
        {
            this.responseMsg = responseMsg;
        }

        protected override Task<HttpResponseMessage> SendAsync(HttpRequestMessage request, CancellationToken cancellationToken)
        {
            return Task.FromResult(responseMsg);
        }
    }
}