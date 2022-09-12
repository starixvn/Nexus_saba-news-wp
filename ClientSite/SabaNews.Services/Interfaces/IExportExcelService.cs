namespace SabaNews.Services.Interfaces
{
    using SabaNews.Livescore.Models.League;
    using SabaNews.Models.ViewModel.User;
    using System.Collections.Generic;

    public interface IExportExcelService
    {
        byte[] ExportMembershipEvent(IEnumerable<MembershipEventModel> users);
        byte[] ExportMinigameWorldCup2022(IEnumerable<MiniGame> miniGames);
    }
}
