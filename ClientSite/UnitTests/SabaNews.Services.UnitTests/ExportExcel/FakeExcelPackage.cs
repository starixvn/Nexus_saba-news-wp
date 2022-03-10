namespace SabaNews.Services.UnitTests.ExportExcel
{
    using OfficeOpenXml;
    using System.IO;

    public class FakeExcelPackage
    {
        public FakeExcelPackage()
        {
            if (!Directory.Exists("ExcelFile"))
            {
                Directory.CreateDirectory("ExcelFile");
            }
        }

        public ExcelPackage ExcelPackage => new ExcelPackage(new FileInfo("ExcelFile\\Worksheet.xlsx"));

        public ExcelWorksheets Worksheets => ExcelPackage.Workbook.Worksheets;
    }
}
