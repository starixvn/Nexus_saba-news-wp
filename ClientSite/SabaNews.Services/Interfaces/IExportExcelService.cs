namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models.ViewModel.User;
    using System.Collections.Generic;

    public interface IExportExcelService
    {
        byte[] ExportMembershipEvent(IEnumerable<MembershipEventModel> users);
    }
}
