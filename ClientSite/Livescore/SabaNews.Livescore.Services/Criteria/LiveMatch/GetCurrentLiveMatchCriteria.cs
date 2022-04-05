namespace SabaNews.Livescore.Services.Criteria.LiveMatch
{
    using Fanex.Data.Repository;

    internal class GetCurrentLiveMatchCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_LiveMatch_Get";

        public override bool IsValid() => true;
    }
}