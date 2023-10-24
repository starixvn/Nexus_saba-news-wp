namespace SabaNews.Models.Comment
{
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using System;

    public class Comment
    {
        public long CommentID { get; set; }
        public long CommentPostID { get; set; }
        public string CommentAuthor { get; set; }
        public string CommentAuthorEmail { get; set; }
        public string CommentAuthorIP { get; set; }
        public DateTimeOffset CommentDate { get; set; }
        public DateTimeOffset CommentDateGMT { get; set; }
        public string CommentContent { get; set; }
        public long CommentParent { get; set; }
        public long UserID { get; set; }
        public long CommentLikeCount { get; set; }
        public string CommentLikeIPs { get; set; }
        public string CommentLikeUsers { get; set; }
        public long CommentNumberChildren { get; set; }
        public string Photo { get; set; }
        public bool isLiked { get; set; }
        public string CommentTime
        {
            get
            {
                var timeSpan = DateTimeUtilize.UtcNow().DateTime - CommentDateGMT.DateTime;

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
