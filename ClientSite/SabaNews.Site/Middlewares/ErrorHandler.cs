namespace sabanews.Middlewares
{
    using Microsoft.AspNetCore.Http;
    using SabaNews.Core.Services;
    using System;
    using System.Threading.Tasks;

    public class ErrorHandler
    {
        private readonly RequestDelegate next;
        private readonly ILogServiceProvider logService;

        public ErrorHandler(RequestDelegate next, ILogServiceProvider logService)
        {
            this.next = next;
            this.logService = logService;
        }

        public async Task InvokeAsync(HttpContext context)
        {
            try
            {
                WriteAccessLog(context);
                await next(context);
            }
            catch (Exception ex)
            {
                await logService.WriteExceptionSentryErrorAsync(ex);
            }
        }

        private void WriteAccessLog(HttpContext context)
        {
            try
            {
                var remoteIP = context.Request.Headers["x-forwarded-for"].ToString();
                var logContent = $"- { DateTime.UtcNow }: , x-forwarded-for: {remoteIP}"
                + $", Request-Path: {context.Request.Path}"
                + $", User-Agent: {context.Request.Headers["User-Agent"]}";
                if(!string.IsNullOrWhiteSpace(remoteIP))
                {
                    logService.WriteLogFileInfo(logContent);
                }
            }
            catch
            {
                
            }
        }
    }
}