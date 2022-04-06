namespace SabaNews.Livescore.Services.Interfaces
{
    using System.Threading.Tasks;

    public interface ILiveMatchService
    {
        public Task InsertLiveMatchToTemporaryTable(long[] matchIds);
    }
}