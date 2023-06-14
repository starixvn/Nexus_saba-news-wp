namespace SabaNews.Core.Helper
{
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text.RegularExpressions;

    public static class Helper
    {
        public static T CheckOriginValue<T>(T origin, T current)
        {
            if (!origin.Equals(current))
            {
                return current;
            }
            return default;
        }

        public static List<T> ParseJsonToList<T>(string data)
        {
            var result = new List<T>();
            if (data == null)
            {
                return result;
            }
            result = JsonConvert.DeserializeObject<List<T>>(data);
            return result;
        }

        public static (string, long) GetCategoryInfo(string slug)
        {
            var list = slug.Split('-');
            var lastword = list[list.Length - 1];
            var listSLug = list.Take(list.Length - 1);
            var id = lastword.Substring(1);
            var cateSlug = string.Join("-", listSLug);
            return (cateSlug, long.Parse(id));
        }

        public static string ReplaceStringFromVarSeo(string str)
        {
            const string dateFormat = "dd/MM/yyyy";
            var result = Regex.Replace(str, WordpressVariable.SepVar, WordpressVariable.Separate, RegexOptions.IgnoreCase);
            result = Regex.Replace(result, WordpressVariable.SiteNameVar, WordpressVariable.SiteName, RegexOptions.IgnoreCase);
            result = Regex.Replace(result, WordpressVariable.PageVar, WordpressVariable.PageValue, RegexOptions.IgnoreCase);
            result = Regex.Replace(result, WordpressVariable.CurrentDateVar, DateTimeUtilize.FormatDateTime(DateTime.UtcNow, dateFormat), RegexOptions.IgnoreCase);

            return result;
        }
    }
}
