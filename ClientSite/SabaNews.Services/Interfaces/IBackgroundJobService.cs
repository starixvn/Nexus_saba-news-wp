namespace SabaNews.Services.Interfaces
{
    public interface IBackgroundJobService
    {
        void TriggerUpdateCaching();
    }
}