using AutoFixture;
using Microsoft.AspNetCore.Http;
using NSubstitute;
using SabaNews.Core.Models;
using SabaNews.Core.Services;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xunit;

namespace SabaNews.Core.UnitTests.Services
{
    public class SessionServiceTests
    {
        private ISession session;
        private readonly IFixture fixture = new Fixture();

        //[Fact]
        //public void WhenSetValue_CallExpectedFunction()
        //{
        //    // Arrange
        //    var service = GetService();
        //    var user = fixture.Create<UserSession>();
            
        //    //Act
        //    service.User = user;

        //    // Assert
        //    session.Received().Set(Arg.Any<string>(), Arg.Any<byte[]>());
        //}

        [Fact]
        public void WhenGetValue_CallExpectedFunction()
        {
            // Arrange
            var service = GetService();
            var user = fixture.Create<UserSession>();

            //Act
            var actual = service.User;

            // Assert
            session.Received().TryGetValue(Arg.Any<string>(),out Arg.Any<byte[]>());
        }

        [Fact]
        public void ClearAll_WhenCalled_CallExpectedFunction()
        {
            // Arrange
            var service = GetService();

            //Act
            service.ClearAll();

            // Assert
            session.Received().Clear();
        }

        #region Helpers
        private SessionService GetService()
        {
            session = Substitute.For<ISession>();
            
            var controller = new SessionService(session);

            return controller;
        }
       
        #endregion Helpers
    }
}
