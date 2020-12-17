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
    using System;
    using System.Collections.Generic;
    using System.IO;
    using System.Linq;

    public class Startup
    {
        public Startup(IConfiguration configuration)
        {
            Configuration = configuration;
        }

        public IConfiguration Configuration { get; }

        public void ConfigureServices(IServiceCollection services)
        {
            var connections = Configuration.GetSection("ConnectionStrings")
                         .GetChildren()
                         .AsEnumerable()
                         .Where(x => !string.IsNullOrWhiteSpace(x.GetValue<string>("Name")))
                         .ToDictionary(x => x.GetValue<string>("Name"),
                             y => new ConnectionConfiguration(y.GetValue<string>("Name"), y.GetValue<string>("ConnectionString"), y.GetValue<string>("ProviderName")));

            string evn = Environment.GetEnvironmentVariable("ASPNETCORE_ENVIRONMENT");
            string resourcePath = !string.IsNullOrWhiteSpace(evn) ? $"~/App_Data/{evn}" : "~/App_Data";

            DbSettingProviderManager
               .StartNewSession()
               .Use(connections)
               .WithMySql()
               .UseDefaultDbSettingProvider(resourcePath: resourcePath)
               .Run();

            LogManager
                .SetDefaultLogCategory(Configuration.GetSection("LogCategory").Value)
                .Use(new List<ILogEngine>
                {
                    new SentryLogging(new SentryEngineOptions
                    {
                        Dsn = new Dsn(Configuration.GetSection("SentryEngineOptions:Dsn").Value),
                        FrontEndDsn = new Dsn(Configuration.GetSection("SentryEngineOptions:FrontEndDsn").Value)
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
            services.AddSingleton(Configuration);
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
                app.UseForwardedHeaders();
                app.UseDeveloperExceptionPage();
            }
            else
            {
                app.UseMiddleware<ErrorHandler>();
                app.UseForwardedHeaders();
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