namespace SabaNews.Hubs
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.SignalR;
    using SabaNews.Models.LiveScore;

    public class LiveScoreHub : Hub<ILiveScoreClient>
    {
        public async Task UpdateLiveSoccer(IList<SoccerMatch> soccerMatches)
        {
            await Clients.All.UpdateLiveSoccer(soccerMatches);
        }

        public async Task UpdateLiveSoccerCommentary(long matchId, IList<SoccerMatchCommentary> commentaries)
        {
            await Clients.Group(matchId.ToString()).UpdateLiveSoccerCommentary(commentaries);
        }

        public async Task AddNewSoccerGroup(long matchId)
        {
            await Groups.AddToGroupAsync(Context.ConnectionId, matchId.ToString());
        }

        public async Task RemoveFromSoccerGroup(long matchId)
        {
            await Groups.RemoveFromGroupAsync(Context.ConnectionId, matchId.ToString());
        }
    }
}