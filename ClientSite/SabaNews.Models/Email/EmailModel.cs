using SabaNews.Core.Constants;
using System;

namespace SabaNews.Models.Email
{
    public class EmailModel
    {
        public string Id { get; set; }

        public string EmailTitle { get; set; }

        public string EmailContent { get; set; }

        public string EmailReturnLink { get; set; }

        public string EmailEmbeddedLink { get; set; }

        public int CreatedBy { get; set; }

        public DateTime SendDate { get; set; }

        public int EmailId { get; set; }

        public int TotalSent { get; set; }

        public int TotalClicked { get; set; }

        public string SendMailTime
        {
            get
            {
                var timeSpan = DateTime.UtcNow - SendDate;

                if (timeSpan <= TimeSpan.FromSeconds(Constants.NumberSecondsAMinute))
                {
                    return string.Format("{0} giây trước", timeSpan.Seconds);
                }
                if (timeSpan <= TimeSpan.FromMinutes(Constants.NumberMinutesAHour))
                {
                    return timeSpan.Minutes > 1 ? string.Format("{0} phút trước", timeSpan.Minutes) : "1 phút trước";
                }
                if (timeSpan <= TimeSpan.FromHours(Constants.NumberHoursADay))
                {
                    return timeSpan.Hours > 1 ? string.Format("{0} giờ trước", timeSpan.Hours) : "1 giờ trước";
                }

                return SendDate.ToString("H:mm dd/MM/yyyy");
            }
        }
    }
}
