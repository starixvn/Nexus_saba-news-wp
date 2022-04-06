namespace SabaNews.Site.Hubs
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.SignalR;
    using SabaNews.Livescore.Models.Lineup;
    using SabaNews.Livescore.Models.Match;

    public class LivescoreHub : Hub<ILivescoreClient>
    {
        public async Task UpdateListLiveMatch(IList<Match> matches)
        {
            await Clients.All.UpdateLiveMatches(matches);
        }

        public async Task UpdateLiveMatchDetail(long matchId, Statistic statistic, LineupDetail lineup, IEnumerable<MatchIncident> incidents)
        {
            await Clients.Group(matchId.ToString()).UpdateLiveMatchStatistic(statistic, lineup, incidents);
        }

        public async Task JoinSoccerGroup(long matchId)
        {
            await Groups.AddToGroupAsync(Context.ConnectionId, matchId.ToString());
        }

        public async Task LeaveSoccerGroup(long matchId)
        {
            await Groups.RemoveFromGroupAsync(Context.ConnectionId, matchId.ToString());
        }
    }
}