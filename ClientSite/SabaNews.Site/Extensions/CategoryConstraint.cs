namespace SabaNews.Site.Extensions
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Routing;
    public class CategoryConstraint : IRouteConstraint
    {
        public bool Match(HttpContext httpContext, IRouter route, string parameterName, RouteValueDictionary values,  RouteDirection routeDirection)
        {
            string slug = values[parameterName].ToString();
            return slug.Contains("category-");
        }
    }
}