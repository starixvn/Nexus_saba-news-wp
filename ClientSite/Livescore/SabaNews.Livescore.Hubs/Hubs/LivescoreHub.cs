namespace SabaNews.Livescore.Hubs
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.SignalR;

    public class LivescoreHub : Hub<ILivescoreClient>
    {
        public async Task UpdateLiveSoccer()
        {
            await Clients.All.UpdateLiveSoccer();
        }

        public async Task UpdateLiveSoccerCommentary(long matchId)
        {
            await Clients.Group(matchId.ToString()).UpdateLiveSoccerCommentary();
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