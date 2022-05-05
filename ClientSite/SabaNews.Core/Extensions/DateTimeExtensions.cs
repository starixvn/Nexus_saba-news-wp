namespace SabaNews.Core.Extensions
{
    using System;

    public static class DateTimeExtensions
    {
        public static DateTime ConvertToUTC(this DateTime dateTime, float timezone)
            => dateTime.AddHours(timezone > 0 ? -timezone : Math.Abs(timezone));

        public static DateTime ToStartOfDay(this DateTime date) => new(date.Year, date.Month, date.Day);

        public static DateTime ToEndOfDay(this DateTime date)
        {
            const byte Hour = 23;
            const byte Minute = 59;
            return new(date.Year, date.Month, date.Day, Hour, Minute, Minute);
        }
    }
}