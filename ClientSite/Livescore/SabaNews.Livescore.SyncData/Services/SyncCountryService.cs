namespace SabaNews.Livescore.SyncData.Services
{
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Country;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Country;
    using SabaNews.Livescore.SyncData.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class SyncCountryService : ISyncCountryService
    {
        private readonly IBaseService baseService;
        private readonly IFetchCountryService fetchCountryService;

        public SyncCountryService(
            IBaseService baseService,
            IFetchCountryService fetchCountryService)
        {
            this.baseService = baseService;
            this.fetchCountryService = fetchCountryService;
        }

        public async Task SyncCountryListAsync()
        {
            List<CountryListResponse> countries = await fetchCountryService.FetchCountryListAsync();
            if (countries.Any())
            {
                var json = JsonConvert.SerializeObject(countries);
                var command = new ImportCountryCommand
                {
                    CountryListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }
    }
}
