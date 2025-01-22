namespace SabaNews.Site.Extensions
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.Routing;

    public class CategoryConstraint : IRouteConstraint
    {
        public bool Match(HttpContext httpContext, IRouter route, string routeKey, RouteValueDictionary values,  RouteDirection routeDirection)
        {
            if (values.TryGetValue(routeKey, out var value) && value is string slug)
            {
                return !slug.EndsWith(".html");
            }

            return false;
        }
    }
}