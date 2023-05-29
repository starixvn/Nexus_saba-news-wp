namespace SabaNews.Site.Extensions
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Routing;
    public class CategoryConstraint : IRouteConstraint
    {
        public bool Match(HttpContext httpContext, IRouter route, string parameterName, RouteValueDictionary values,  RouteDirection routeDirection)
        {
            string slug = values[parameterName].ToString();
            if(slug.Contains("-c"))
            {
                var list = slug.Split('-');
                var lastword = list[list.Length - 1];
                var test = lastword.Substring(1);
                if(!string.IsNullOrEmpty(lastword) && lastword.StartsWith('c') && int.TryParse(lastword.Substring(1), out _))
                {
                    return true;
                }
            };
            return false;
        }
    }
}