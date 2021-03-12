namespace SabaNews.Services.Interfaces
{
    using System.Threading.Tasks;

    public interface IBackgroundJobService
    {
        void TriggerUpdateCaching();

        Task InitSitemapXml();
    }
}