namespace SabaNews.Livescore.Services.Criteria.League
{
    using Fanex.Data.Repository;
    
    class GetAllTeam : CriteriaBase
    {        
        public long LeagueId { get; set; }
        public override string GetSettingKey() => "Livescore_Team_GetAll";
        public override bool IsValid() => true;
    }
}
