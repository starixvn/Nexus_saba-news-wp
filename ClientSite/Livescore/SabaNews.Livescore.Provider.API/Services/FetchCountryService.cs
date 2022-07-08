
namespace SabaNews.Livescore.Provider.API.Services
{
    using SabaNews.Livescore.Provider.API.Models.Country;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class FetchCountryService : IFetchCountryService
    {
        private readonly IHttpClientService httpClient;

        public FetchCountryService(IHttpClientService httpClient)
        {
            this.httpClient = httpClient;
        }

        public async Task<List<CountryListResponse>> FetchCountryListAsync()
        {
            var param = new Dictionary<string, object>();
            return await httpClient.ExecutePostAsync<List<CountryListResponse>>(ApiPath.GetCountryListApiPath, param);
        }

        private static class ApiPath
        {
            internal const string GetCountryListApiPath = "/general/country";
        }
    }
}
