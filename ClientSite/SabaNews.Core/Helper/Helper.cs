namespace SabaNews.Core.Helper
{
    using Newtonsoft.Json;
    using System.Collections.Generic;
    using System.Linq;

    public static class Helper
    {
        public static  T CheckOriginValue<T>(T origin, T current)
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
    }
}
