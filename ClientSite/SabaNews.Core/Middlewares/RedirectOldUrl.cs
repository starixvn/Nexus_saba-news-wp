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
            if (path.Value.EndsWith("-sabavn"))
            {
                context.Response.Redirect(path.Value[..^7], permanent: true);
                return;
            }
            if (CategoryKey.LinkRedirectList.TryGetValue(path, out var newUrl))
            {
                context.Response.Redirect(newUrl, permanent: true); 
                return;
            }
            await next(context);
        }
    }
}
