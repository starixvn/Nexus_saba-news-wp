namespace SabaNews.Models
{
    using SabaNews.Core.Constants;
    using System;

    public class Comment
    {
        public int CommentID { get; set; }
        public int CommentPostID { get; set; }
        public string CommentAuthor { get; set; }
        public string CommentAuthorEmail { get; set; }
        public string CommentAuthorIP { get; set; }
        public DateTime CommentDate { get; set; }
        public DateTime CommentDateGMT { get; set; }
        public string CommentContent { get; set; }
        public int UserID { get; set; }
        public int CommentNumberChildren { get; set; }
        public string CommentTime
        {
            get
            {
                var timeSpan = DateTime.UtcNow - CommentDateGMT;

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

                return CommentDate.ToString("H:mm dd/MM/yyyy");
            }
        }
    }
}
