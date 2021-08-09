namespace SabaNews.Models.Email
{
    using System;
    using SabaNews.Core.Constants;

    public class EmailDetailModel
    {
        public int EmailId { get; set; }

        public int UserId { get; set; }

        public string TokenId { get; set; }

        public string EmailAddress { get; set; }

        public string EmailTitle { get; set; }

        public string EmailContent { get; set; }

        public DateTime SendTime { get; set; }

        public int IsUserClicked { get; set; }

        public int Status { get; set; }

        public string SubscribeToken { get; set; }

        public string SendMailTime
        {
            get
            {
                var timeSpan = DateTime.UtcNow - SendTime;

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

                return SendTime.ToString("H:mm dd/MM/yyyy");
            }
        }
    }
}
