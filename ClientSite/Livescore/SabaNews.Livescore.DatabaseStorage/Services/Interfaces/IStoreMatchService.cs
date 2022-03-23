namespace SabaNews.Livescore.DatabaseStorage.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;

    public interface IStoreMatchService
    {
        Task<CommandResult> StoreMatchListAsync(string matchListJson);
    }
}