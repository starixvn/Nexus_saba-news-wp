namespace SabaNews.Livescore.SyncData.Services.Interfaces
{
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Mqtt;

    public interface ISyncMqttRecivedDataService
    {
        Task ReceivedLiveMatchStatistic(MqttLiveMatch match);

        Task ReceivedLiveMatchTextLive(MqttTextLiveIncidents textLive);
    }
}