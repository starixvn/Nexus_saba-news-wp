namespace SabaNews.Livescore.Provider.API.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Player;

    public interface IFetchPlayerService
    {
        Task<IDictionary<string, List<PlayerListResponse>>> FetchPlayerListAsync(FetchPlayerListParams playerParams);
    }
}