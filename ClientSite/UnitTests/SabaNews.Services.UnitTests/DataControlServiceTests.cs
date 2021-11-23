using AutoFixture;
using Fanex.Caching;
using Microsoft.Extensions.Configuration;
using NSubstitute;
using SabaNews.Core.Constants;
using SabaNews.Core.Services;
using SabaNews.Models.ViewModel.Posts;
using SabaNews.Services.Interfaces;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Services.UnitTests
{
    public class DataControlServiceTests
    {

        private IFetchDataService fetchDataService;
        private ICacheService cacheService;
        private IPostService postService;
        private ILogServiceProvider logServiceProvider;
        private IConfiguration configuration;



        #region Helpers
        private DataControlService GetDataControlService()
        {
            
            configuration = Substitute.For<IConfiguration>();
            fetchDataService = Substitute.For<IFetchDataService>();
            cacheService = Substitute.For<ICacheService>();
            postService = Substitute.For<IPostService>();
            logServiceProvider = Substitute.For<ILogServiceProvider>();
            var service = new DataControlService(fetchDataService, configuration, cacheService, postService, logServiceProvider);

            return service;
        }

        #endregion Helpers
    }
}
