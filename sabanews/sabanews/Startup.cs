namespace sabanews
{
    using Fanex.Data;
    using Fanex.Data.MySql;
    using Fanex.Data.Repository;
    using Fanex.Logging;
    using Fanex.Logging.Extensions;
    using Fanex.Logging.Extensions.AspNetCore;
    using Fanex.Logging.Extensions.Database;
    using Fanex.Logging.Sentry;
    using Microsoft.AspNetCore.Builder;
    using Microsoft.AspNetCore.Hosting;
    using Microsoft.AspNetCore.Http;
    using Microsoft.AspNetCore.HttpOverrides;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.DependencyInjection;
    using Microsoft.Extensions.FileProviders;
    using Microsoft.Extensions.Hosting;
    using sabanews.Middlewares;
    using sabanews.Services;
    using Sentry;
    using System.Collections.Generic;
    using System.IO;

    public class Startup
    {
        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;
        }

        public IConfiguration Configuration { get; }

        public void ConfigureServices(IServiceCollection services)
        {
            DbSettingProviderManager
               .StartNewSession()
               .WithMySql(Path.Combine(Directory.GetCurrentDirectory(), "App_Data"))
               .Run();

            LogManager
                .SetDefaultLogCategory("chatbot-for-licensee-service")
                .Use(new List<ILogEngine>
                {
                    new SentryLogging(new SentryEngineOptions
                    {
                        Dsn = new Dsn("https://f38b31b0602f4ca7b3c8b3c6975c179f@sentry.nexdev.net/55"),
                        FrontEndDsn = new Dsn("https://f38b31b0602f4ca7b3c8b3c6975c179f@sentry.nexdev.net/55")
                    })
                });

            ExceptionHandler
                .UseConfig(new LoggingConfiguration())
                .UseGetDbExceptionInfo(DatabaseInfoGetters.GetDbExceptionInfo);

            services.Configure<ForwardedHeadersOptions>(options =>
            {
                options.ForwardedHeaders =
                    ForwardedHeaders.XForwardedFor | ForwardedHeaders.XForwardedProto;
            });

            services.Configure<CookiePolicyOptions>(options =>
            {
                options.CheckConsentNeeded = context => true;
                options.MinimumSameSitePolicy = SameSiteMode.None;
            });

            services.AddResponseCompression();
            
            services.AddControllersWithViews();
            services.AddTransient<IPostService, PostService>();
            services.AddTransient<IBaseService, BaseService>();
            services.AddTransient<IDynamicRepository, DynamicRepository>();
            services.AddTransient<IPathService, PathService>();
            services.AddSingleton<ILogger, Logger>();
        }

        public void Configure(IApplicationBuilder app, IWebHostEnvironment env)
        {
            if (env.IsDevelopment())
            {
                app.UseMiddleware<ErrorHandler>();
                //app.UseDeveloperExceptionPage();
            }
            else
            {
                app.UseForwardedHeaders();
                app.UseMiddleware<ErrorHandler>();
                app.UseHsts();
            }

            app.UseHttpsRedirection();
            app.UseResponseCompression();
            app.UseStaticFiles(new StaticFileOptions
            {
                FileProvider = new PhysicalFileProvider(
                Path.Combine(env.ContentRootPath, "wordpress")),
                RequestPath = "/wordpress"
            });
            app.UseStaticFiles();
            app.UseRouting();

            app.UseEndpoints(endpoints =>
            {
                endpoints.MapControllerRoute(
                    name: "default",
                    pattern: "{controller=Home}/{action=Index}/{id?}");
            });

            
        }
    }
}
