namespace SabaNews.Livescore.Services.Criteria.LiveMatch
{
    using Fanex.Data.Repository;

    internal class GetNotExistedMatchCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Livematch_GetNotExistedMatch";

        public override bool IsValid() => true;
    }
}