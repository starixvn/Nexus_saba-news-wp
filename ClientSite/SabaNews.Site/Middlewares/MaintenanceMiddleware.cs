namespace SabaNews.Site.Middlewares
{
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Http;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;

    public class MaintenanceMiddleware
    {
        private readonly RequestDelegate next;
        private readonly UMSettings umSettings;
        private readonly ILogServiceProvider logServiceProvider;

        public MaintenanceMiddleware(RequestDelegate next, UMSettings umSettings, ILogServiceProvider logServiceProvider)
        {
            this.next = next;
            this.umSettings = umSettings;
            this.logServiceProvider = logServiceProvider;
        }

        public async Task Invoke(HttpContext context)
        {
            if (umSettings.Enabled && !IsBackdoorIPs(context))
            {
                context.Response.Redirect("/maintenance");
                return;
            }
            await next.Invoke(context);
        }

        private bool IsBackdoorIPs(HttpContext context)
        {
            var currentIP = context.Connection.RemoteIpAddress?.ToString();
            logServiceProvider.WriteLogFileInfo($"IP: {currentIP}");
            return umSettings.BackdoorIPs.Any() && umSettings.BackdoorIPs.Any(ip => ip == currentIP);
        }
    }
}