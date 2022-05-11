namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Mqtt;
    using SabaNews.Livescore.SyncData.Criteria.Mqtt;
    using SabaNews.Livescore.SyncData.Services.Interfaces;

    public class SyncMqttRecivedDataService : ISyncMqttRecivedDataService
    {
        private readonly IBaseService baseService;

        public async Task ReceivedLiveMatchTextLive(MqttTextLiveIncidents textLive)
        {
            var command = new SyncMqttMatchIncidentCommand
            {
                IncindentJson = JsonConvert.SerializeObject(textLive.Incidents)
            };

            await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task ReceivedLiveMatchStatistic(MqttLiveMatch match)
        {
            var command = new SyncMqttMatchResponseCommand
            {
                MatchJson = JsonConvert.SerializeObject(match)
            };

            await baseService.ExecuteNonQueryAsync(command);
        }
    }
}