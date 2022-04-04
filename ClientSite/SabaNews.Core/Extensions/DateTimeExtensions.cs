namespace SabaNews.Core.Extensions
{
    using System;

    public static class DateTimeExtensions
    {
        public static DateTime ConvertToUTC(this DateTime dateTime)
        {
            var tzInfo = TimeZoneInfo.FindSystemTimeZoneById("SE Asia Standard Time");
            return TimeZoneInfo.ConvertTimeToUtc(DateTime.SpecifyKind(dateTime, DateTimeKind.Unspecified), tzInfo);
        }

        public static DateTime ConvertFromUTC(this DateTime dateTime)
        {
            var tzInfo = TimeZoneInfo.FindSystemTimeZoneById("SE Asia Standard Time");
            return TimeZoneInfo.ConvertTimeFromUtc(DateTime.SpecifyKind(dateTime, DateTimeKind.Utc), tzInfo);
        }

        public static DateTime ToStartOfDay(this DateTime date) => new DateTime(date.Year, date.Month, date.Day);

        public static DateTime ToEndOfDay(this DateTime date) => new DateTime(date.Year, date.Month, date.Day, 23, 59, 59);
    }
}