namespace SabaNews.Livescore.SyncData.Services.Interfaces
{
    using System.Threading.Tasks;

    public interface ISyncCountryService
    {
        Task SyncCountryListAsync();
    }
}
