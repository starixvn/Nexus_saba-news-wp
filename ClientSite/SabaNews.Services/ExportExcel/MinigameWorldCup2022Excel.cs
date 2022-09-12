namespace SabaNews.Services.ExportExcel
{
    using OfficeOpenXml;
    using OfficeOpenXml.Style;
    using SabaNews.Core.Helper;
    using SabaNews.Livescore.Models.League;
    using System.Collections.Generic;

    public class MinigameWorldCup2022Excel : ExcelBase
    {
        private const byte RowStartHeader = 2;
        private const byte TotalColumnsHeader = 6;
        private const byte ColIndexLuckynumber = 5;
        private const byte ColIndexCount = 1;
        private const byte ColEmail = 3;
        private const byte ColNumber = 1;
        private const byte ColTeam = 4;
        private const byte ColJoinDate = 6;
        private const byte DefaultColWidth = 25;
        private const byte SmallColWidth = 10;
        private const byte EmailColWidth = 35;

        private readonly IEnumerable<MiniGame> miniGames;

        public MinigameWorldCup2022Excel(string templatePath, IEnumerable<MiniGame> miniGames)
            : base(templatePath, 0, "MiniGame WorldCup 2022")
        {
            this.miniGames = miniGames;
        }

        protected internal override void RenderTitle(ExcelWorksheet templateWorksheet, ExcelWorksheet worksheet)
        {
            templateWorksheet.Cells[1, 1, RowStartHeader - 1, TotalColumnsHeader]
                .Copy(worksheet.Cells[1, 1, RowStartHeader - 1, TotalColumnsHeader]);
        }

        protected internal override void RenderBody(ExcelWorksheet templateWorksheet, ExcelWorksheet worksheet)
        {
            var rowIndex = 1;
            var index = 0;
            foreach (var user in miniGames)
            {
                rowIndex++;
                index++;
                RenderRow(worksheet, user, index, rowIndex);
            }
        }

        protected internal virtual void RenderRow(ExcelWorksheet worksheet, MiniGame user, int index, int rowIndex)
        {
            var colIndex = 1;
            string teamName = !string.IsNullOrEmpty(user.TeamNameEN) ? user.TeamNameEN : user.TeamFullNameEN;
            teamName = !string.IsNullOrEmpty(user.TeamNameVN) ? user.TeamNameVN : teamName;
            worksheet.Cells[rowIndex, colIndex++].Value = index;
            worksheet.Cells[rowIndex, colIndex++].Value = user.Name;
            worksheet.Cells[rowIndex, colIndex++].Value = user.Email;
            worksheet.Cells[rowIndex, colIndex++].Value = teamName;
            worksheet.Cells[rowIndex, colIndex++].Value = user.LuckyNumber;
            worksheet.Cells[rowIndex, colIndex].Value = DateTimeUtilize.FormatDateTime(user.JoinDate, "dd/MM/yyyy HH:mm:ssss");
            worksheet.Cells[rowIndex, ColIndexCount].Style.HorizontalAlignment = ExcelHorizontalAlignment.Center;
            worksheet.Cells[rowIndex, ColIndexLuckynumber].Style.HorizontalAlignment = ExcelHorizontalAlignment.Center;
            worksheet.Cells[rowIndex, ColTeam].Style.HorizontalAlignment = ExcelHorizontalAlignment.Center;
            worksheet.Cells[rowIndex, ColJoinDate].Style.HorizontalAlignment = ExcelHorizontalAlignment.Center;
        }

        protected internal override void ProcessUI(ExcelWorksheet worksheet)
        {
            worksheet.DefaultColWidth = DefaultColWidth;
            worksheet.Column(ColNumber).Width = SmallColWidth;
            worksheet.Column(ColEmail).Width = EmailColWidth;
        }
    }
}
