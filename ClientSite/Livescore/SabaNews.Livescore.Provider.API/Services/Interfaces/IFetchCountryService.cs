namespace SabaNews.Livescore.Provider.API.Services
{
    using SabaNews.Livescore.Provider.API.Models.Country;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IFetchCountryService
    {
        Task<List<CountryListResponse>> FetchCountryListAsync();
    }
}
