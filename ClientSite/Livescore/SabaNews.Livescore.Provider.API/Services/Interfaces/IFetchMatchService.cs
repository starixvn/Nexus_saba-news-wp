namespace SabaNews.Livescore.Provider.API.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Models.Matches.Params;

    public interface IFetchMatchService
    {
        Task<List<MatchResponse>> FetchMatchListAsync(FetchMatchParams parameters);
    }
}