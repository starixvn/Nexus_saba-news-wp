namespace SabaNews.Core.Helper
{
    using System;
    using System.Globalization;
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

        public static string ChangeTimeZoneToClient(DateTimeOffset time, string format)
        {
            var timeSpan = new DateTimeUtilize();
            return time.Add(timeSpan._timeSpan).ToString(format);
        }

        public static string FormatDateTime(DateTimeOffset time, string Format)
        {
            var timeSpan = new DateTimeUtilize();
            return time.Add(timeSpan._timeSpan).ToString(Format, new CultureInfo("vi"));
        }

        public static DateTimeOffset TransformDatetimeToGMT(DateTimeOffset time)
        {
            var timeSpan = new DateTimeUtilize();
            return time.Subtract(timeSpan._timeSpan);
        }

        public static DateTime ConvertToClientDatetime(DateTime time)
        {
            var timeSpan = new DateTimeUtilize();
            return time.Add(timeSpan._timeSpan);
        }

        public static DateTimeOffset DateTime(int year, int month, int day, int hour = 0, int minute = 0, int second = 0)
        {
            return new DateTimeOffset(year, month, day, hour, minute, second, TimeSpan.FromHours(Constants.ServerTimeZone));
        }

        public static DateTimeOffset ToStartOfDays( DateTimeOffset date) => new DateTimeOffset(date.Year, date.Month, date.Day, 0, 0, 0, TimeSpan.FromHours(Constants.ServerTimeZone));

        public static DateTimeOffset ToEndOfDay(DateTimeOffset date)
        {
            const byte Hour = 23;
            const byte Minute = 59;
            return new(date.Year, date.Month, date.Day, Hour, Minute, Minute, TimeSpan.FromHours(Constants.ServerTimeZone));
        }

        public static DateTimeOffset UtcNow() => DateTimeOffset.UtcNow;

        public static DateTimeOffset Now() => DateTimeOffset.Now;

    }
}
