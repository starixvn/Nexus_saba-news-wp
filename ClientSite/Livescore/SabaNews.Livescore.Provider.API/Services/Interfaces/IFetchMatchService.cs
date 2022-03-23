namespace SabaNews.Livescore.Provider.API.Services.Interfaces
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Matches;

    public interface IFetchMatchService
    {
        Task<MatchResponse> FetchMatchByIdAync(long matchId);

        Task<List<MatchResponse>> FetchMatchesByDateAsync(DateTime date);

        Task<List<MatchResponse>> FetchMatchesByDateRangeAsync(DateTime from, DateTime to);
    }
}