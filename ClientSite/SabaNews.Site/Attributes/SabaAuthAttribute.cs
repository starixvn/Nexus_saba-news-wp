namespace SabaNews.Site.Attributes
{
    using System;
    using System.Net;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.Mvc.Filters;
    using SabaNews.Core.Services;

    [AttributeUsage(AttributeTargets.Method, AllowMultiple = false)]
    public class SabaAuthAttribute : Attribute, IAuthorizationFilter
    {
        private readonly ISessionService sessionService;

        public string Role { get; set; }

        public SabaAuthAttribute(ISessionService sessionService)
        {
            this.sessionService = sessionService;
        }

        public void OnAuthorization(AuthorizationFilterContext context)
        {
            if (sessionService.User is null || sessionService.User.Id == 0)
            {
                UnauthorizedResponse(context);
                return;
            }

            if (!string.IsNullOrWhiteSpace(Role) && IsValidRole())
            {
                UnauthorizedResponse(context);
            }
        }

        private bool IsValidRole()
            => sessionService.User.Metadata.Contains(Role);

        private static void UnauthorizedResponse(AuthorizationFilterContext context)
        {
            context.HttpContext.Response.StatusCode = (int)HttpStatusCode.Unauthorized;
            context.Result = new RedirectResult("/");
        }
    }
}