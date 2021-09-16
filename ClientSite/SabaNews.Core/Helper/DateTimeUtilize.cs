namespace SabaNews.Core.Helper
{
    using System;
    using SabaNews.Core.Constants;

    public class DateTimeUtilize
    {
        private readonly TimeSpan _timeSpan;

        public DateTimeUtilize()
        {
            _timeSpan = new TimeSpan(Constants.ServerTimeZone, 0, 0);
        }

        public static string ChangeTimeZoneToClient(DateTime time)
        {
            var timeSpan = new DateTimeUtilize();
            return time.Add(timeSpan._timeSpan).ToString("dd MMM yyyy HH:mm:ssss");
        }
    }
}
