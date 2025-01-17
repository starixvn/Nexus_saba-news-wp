namespace SabaNews.Core.Middlewares
{
    using Microsoft.AspNetCore.Http;
    using SabaNews.Core.Constants;
    using System.Threading.Tasks;

    public class RedirectOldUrl
    {
        private readonly RequestDelegate next;

        public RedirectOldUrl(RequestDelegate next)
        {
            this.next = next;
        }

        public async Task InvokeAsync(HttpContext context)
        {
            var path = context.Request.Path;
            if (CategoryKey.deprecatedPaths.Contains(path.Value))
            {
                context.Response.StatusCode = StatusCodes.Status410Gone;
                await context.Response.WriteAsync("This resource is no longer available.");
                return;
            }
            if (CategoryKey.LinkRedirectList.TryGetValue(path, out var newUrl))
            {
                context.Response.Redirect(newUrl, permanent: true); // 301 Redirect
            }
            else
            {
                await next(context);
            }
        }
    }
}
