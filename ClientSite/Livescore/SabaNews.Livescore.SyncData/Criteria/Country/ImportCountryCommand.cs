using SabaNews.Core.Models;

namespace SabaNews.Livescore.SyncData.Criteria.Country
{
    public class ImportCountryCommand : BaseNonQueryCommand
    {
        public string CountryListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_Country_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(CountryListJson);
    }
}
