namespace SabaNews.Services.ExportExcel
{
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;

    public class ExportExcelService : IExportExcelService
    {
        private readonly IPathService pathService;
        public ExportExcelService(IPathService pathService)
        {
            this.pathService = pathService;
        }
        public byte[] ExportMembershipEvent(IEnumerable<MembershipEventModel> users)
        {
            const string ExcelTemplatePath = "ExcelTemplates/Event.xlsx";
            var templatePath = pathService.MapPathContentRoot(ExcelTemplatePath);
            var excel = new MembershipEventExcel(templatePath, users);
            return excel.Export();
        }
    }
}
