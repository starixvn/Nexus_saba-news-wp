namespace SabaNews.Livescore.Services.Criteria.Team
{
    using Fanex.Data.Repository;

    public class GetTeamPlayerByIdCriteria : CriteriaBase
    {
        public long TeamId { get; set; }


        public override string GetSettingKey() => "Livescore_PlayerCustom_GetByTeamId";

        public override bool IsValid() => TeamId > 0;
    }
}