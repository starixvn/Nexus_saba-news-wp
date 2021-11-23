namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using Newtonsoft.Json;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Comment;
    using SabaNews.Models.Comment;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Xunit;

    public class CommentServiceTests
    {
        private IBaseService baseService;
        private ISessionService sessionService;

        [Fact]
        public async Task GetCommentsAsync_WhenCalled_ReturnExpectedComments()
        {
            //Arrange
            var service = GetCommentService();
            var fixture = new Fixture();
            var model = fixture.Create<CmtGetParentAndNumberChildrenCriteria>();
            List<Comment> expected = GetExpectedComment();
            var criteria = new CmtGetParentAndNumberChildrenCriteria();
            baseService.GetDataAsync<Comment>(Arg.Do<CmtGetParentAndNumberChildrenCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await service.GetCommentsAsync(model);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(model.PostID, criteria.PostID);
            Assert.Equal(model.FilterType, criteria.FilterType);
        }

        [Fact]
        public async Task GetCommentChildrenAsync_WhenCalled_ReturnExpectedComments()
        {
            //Arrange
            var service = GetCommentService();
            var fixture = new Fixture();
            var postId = fixture.Create<int>();
            var commentId = fixture.Create<int>();
            List<Comment> expected = GetExpectedComment();
            var criteria = new CmtGetChildrenCriteria();
            baseService.GetDataAsync<Comment>(Arg.Do<CmtGetChildrenCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await service.GetCommentChildrenAsync(postId, commentId);
            
            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(postId, criteria.PostID);
            Assert.Equal(commentId, criteria.CmtID);
        }

        [Fact]
        public async Task GetLikeDataAsync_WhenCalled_ReturnExpectedComment()
        {
            //Arrange
            var service = GetCommentService();
            var fixture = new Fixture();
            var commentID = fixture.Create<long>();
            var expected = fixture.Create<Comment>();
            var criteria = new CmtGetLikeDataCriteria();
            baseService.GetDataEntityAsync<Comment>(Arg.Do<CmtGetLikeDataCriteria>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await service.GetLikeDataAsync(commentID);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(commentID, criteria.CommentID);
        }


        [Fact]
        public async Task CreateCommentAsync_WhenCalled_ReturnExpectedCommentID()
        {
            //Arrange
            var service = GetCommentService();
            var fixture = new Fixture();
            var model = fixture.Create<CreateComment>();
            var expected = fixture.Create<CommandResult>();
            var criteria = new CmtCreateCommand();
            baseService.ExecuteNonQueryAsync(Arg.Do<CmtCreateCommand>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await service.CreateCommentAsync(model);

            //Assert
            Assert.Equal(expected.IsSuccessful ? criteria.CommentID : 0, actual);
            Assert.Equal(model.CommentPostID, criteria.CommentPostID);
            Assert.Equal(model.CommentAuthor, criteria.CommentAuthor);
            Assert.Equal(model.CommentAuthorEmail, criteria.CommentAuthorEmail);
            Assert.Equal(model.CommentDate, criteria.CommentDate);
            Assert.Equal(model.CommentDateGMT, criteria.CommentDateGMT);
            Assert.Equal(model.CommentContent, criteria.CommentContent);
            Assert.Equal(model.CommentAgent, criteria.CommentAgent);
            Assert.Equal(model.CommentParent, criteria.CommentParent);
            Assert.Equal(model.CommentAuthorIP, criteria.CommentAuthorIP);
            Assert.Equal(model.UserID, criteria.UserID);
        }

        [Fact]
        public async Task UpdateLikeDataAsync_WhenNotLiked_ReturnExpectedCommendResult()
        {
            //Arrange
            var service = GetCommentService();
            var fixture = new Fixture();
            var commentId = fixture.Create<long>();
            var clientIp = fixture.Create<string>(); ;
            var type = fixture.Create<string>();
            var comment = new Comment { CommentID = 1, CommentLikeCount = 0, CommentLikeIPs = "[1,2]", CommentLikeUsers = "[1]" };
            var expected = fixture.Create<CommandResult>();
            var criteria = new CmtUpdateLikeCommand();
            service.GetLikeDataAsync(commentId).Returns(comment);
            baseService.ExecuteNonQueryAsync(Arg.Do<CmtUpdateLikeCommand>(x => criteria = x)).Returns(expected);
            //Act
            var actual = await service.UpdateLikeDataAsync(commentId, clientIp, type);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(commentId, criteria.CommentID);
            Assert.Equal(comment.CommentLikeCount + 1, criteria.CommentLikeCount);
            Assert.Equal(JsonConvert.SerializeObject(new List<string> { clientIp }), criteria.CommentLikeIPs);
            Assert.Equal(JsonConvert.SerializeObject(new List<int> { sessionService.User.Id }), criteria.CommentLikeUsers);

        }

        [Fact]
        public async Task UpdateLikeDataAsync_WhenLikedAlready_ReturnExpectedCommendResult()
        {
            //Arrange
            var service = GetCommentService();
            var fixture = new Fixture();
            var commentId = fixture.Create<long>();
            var clientIp = fixture.Create<string>(); ;
            var type = "like";
            var comment = new Comment { CommentID = 1, CommentLikeCount = 2, CommentLikeIPs = "[1,2]", CommentLikeUsers = "[2]" };
            List<int> arrUsers = JsonConvert.DeserializeObject<List<int>>(comment.CommentLikeUsers);
            List<string> arrIPs = JsonConvert.DeserializeObject<List<string>>(comment.CommentLikeIPs);
            arrUsers.Add(sessionService.User.Id);
            arrIPs.Add(clientIp);
            var expected = fixture.Create<CommandResult>();
            var criteria = new CmtUpdateLikeCommand();
            service.GetLikeDataAsync(commentId).Returns(comment);
            baseService.ExecuteNonQueryAsync(Arg.Do<CmtUpdateLikeCommand>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.UpdateLikeDataAsync(commentId, clientIp, type);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(commentId, criteria.CommentID);
        }

        private static List<Comment> GetExpectedComment()
        {
            List<Comment> expected = new List<Comment>();
            expected.Add(new Comment { CommentID = 1, CommentLikeUsers = "[1]", isLiked = false });
            expected.Add(new Comment { CommentID = 2, CommentLikeUsers = "[2]", isLiked = false });
            expected.Add(new Comment { CommentID = 3, CommentLikeUsers = "[3]", isLiked = false });
            return expected;

        }



        #region Helpers
        private CommentService GetCommentService()
        {
            baseService = Substitute.For<IBaseService>();
            sessionService = Substitute.For<ISessionService>();
            sessionService.User = new UserSession { Id = 1 };
            var service = new CommentService(baseService, sessionService);

            return service;
        }

        #endregion Helpers
    }
}
