namespace SabaNews.Core.Helper
{
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
    }
}
