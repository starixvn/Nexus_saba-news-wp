namespace SabaNews.Services.UnitTests
{
    using AutoFixture;
    using Microsoft.Extensions.Configuration;
    using NSubstitute;
    using SabaNews.Core.Models;
    using SabaNews.Criteria;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using System;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Threading.Tasks;
    using Xunit;

    public class PostServiceTests
    {
        private IBaseService baseService;
        private ISerializerService serializerService;
        private IConfiguration configuration;
        private readonly IFixture fixture = new Fixture();

        [Fact]
        public async Task GetPostsBySlugAsyncGetPostsByCateSlugAsync_WhenCalled_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var slug = fixture.Create<string>();
            var start = fixture.Create<int>();
            var end = fixture.Create<int>();
            var criteria = fixture.Create<GetPostsBySlugCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetPostsBySlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPostsBySlugAsync(slug, start, end);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(slug, criteria._CategorySlug);
            Assert.Equal(start, criteria._Start);
            Assert.Equal(end, criteria._End);
        }

        [Fact]
        public async Task GetPostsBySlugAsync_WhenCalled_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<GetPostsByCateSlugCriteria>();
            var criteria = fixture.Create<GetPostsByCateSlugCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetPostsByCateSlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPostsByCateSlugAsync(param);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(param, criteria);
        }

        [Fact]
        public async Task GetPostsByCateSlugNotContainChidrenAsync_WhenCalled_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<GetPostsByCateSlugNotContainChildrenCriteria>();
            var criteria = fixture.Create<GetPostsByCateSlugNotContainChildrenCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetPostsByCateSlugNotContainChildrenCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPostsByCateSlugNotContainChidrenAsync(param);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(param, criteria);
        }

        [Fact]
        public async Task GetRecentPostsAsync_WhenHavePostID_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<GetRecentPostsParam>();
            var criteria = fixture.Create<GetRecentPostsCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetRecentPostsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetRecentPostsAsync(param);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetRecentPostsAsync_WhenCallInSinglePage_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = new GetRecentPostsParam { PostID = 4, Start = 0, End = 3 };
            var criteria = fixture.Create<GetRecentPostsCriteria>();
            var expected = GetTestPost();
            baseService.GetDataAsync<Post>(Arg.Do<GetRecentPostsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetRecentPostsAsync(param);

            //Assert
            Assert.Equal(expected.First(), actual.First());
        }
        

        [Fact]
        public async Task GetRecentPostsAsync_WhenDontHavePostID_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<GetRecentPostsParam>();
            param.PostID = 0;
            var criteria = fixture.Create<GetRecentPostsCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetRecentPostsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetRecentPostsAsync(param);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetTopPostsByCateSlugAsync_WhenHavePostID_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<PostParam>();
            var criteria = fixture.Create<GetTopPostsByCateSlugCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetTopPostsByCateSlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetTopPostsByCateSlugAsync(param);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetTopPostsByCateSlugAsync_WhenDontHavePostID_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<PostParam>();
            param.PostID = 0;
            var criteria = fixture.Create<GetTopPostsByCateSlugCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetTopPostsByCateSlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetTopPostsByCateSlugAsync(param);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetPostDetailBySlugAsync_WhenDontHavePostID_ReturnExpectedPost()
        {
            //Arrange
            var service = GetPostService();
            var slug = fixture.Create<string>();
            var postType = fixture.Create<string>();
            var criteria = fixture.Create<GetPostDetailBySlugCriteria>();
            var expected = fixture.Create<Post>();
            baseService.GetDataEntityAsync<Post>(Arg.Do<GetPostDetailBySlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPostDetailBySlugAsync(slug, postType);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task SearchPostAsync_WhenKeywordIsNotNull_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<SearchPostsCriteria>();
            var criteria = fixture.Create<SearchPostsCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<SearchPostsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.SearchPostAsync(param);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task SearchPostAsync_WhenKeywordIsNull_ReturnNull()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<SearchPostsCriteria>();
            param._Keyword = null;
            var criteria = fixture.Create<SearchPostsCriteria>();
            IEnumerable<Post> expected = null;
            baseService.GetDataAsync<Post>(Arg.Do<SearchPostsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.SearchPostAsync(param);

            //Assert
            Assert.Null(actual);
        }

        [Fact]
        public async Task GetPostByTagAsync_WhenKeywordIsNull_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var param = fixture.Create<GetPostsByTagCriteria>();
            var criteria = fixture.Create<GetPostsByTagCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetPostsByTagCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPostByTagAsync(param);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetSavedPostsByUserID_WhenCalled_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var Ids = fixture.Create<string>();
            var criteria = fixture.Create<GetPostsByIDsCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetPostsByIDsCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetSavedPostsByUserID(Ids);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetCateDetailAsync_WhenCalled_ReturnExpectedCategory()
        {
            //Arrange
            var service = GetPostService();
            var slug = fixture.Create<string>();
            var criteria = fixture.Create<GetCateDetailCriteria>();
            var expected = fixture.Create<Category>();
            baseService.GetDataEntityAsync<Category>(Arg.Do<GetCateDetailCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetCateDetailAsync(slug);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetPageDetailBySlugAsync_WhenCalled_ReturnExpectedCategory()
        {
            //Arrange
            var service = GetPostService();
            var slug = fixture.Create<string>();
            var criteria = fixture.Create<GetPageDetailBySlugCriteria>();
            var expected = fixture.Create<Category>();
            baseService.GetDataEntityAsync<Category>(Arg.Do<GetPageDetailBySlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPageDetailBySlugAsync(slug);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetPageDetailBySlugAsync_WhenCalled_ReturnNull()
        {
            //Arrange
            var service = GetPostService();
            var slug = fixture.Create<string>();
            var criteria = fixture.Create<GetPageDetailBySlugCriteria>();
            Category expected = null;
            baseService.GetDataEntityAsync<Category>(Arg.Do<GetPageDetailBySlugCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetPageDetailBySlugAsync(slug);

            //Assert
            Assert.Null(actual);
        }

        [Fact]
        public async Task CheckTypeCategoryAsync_WhenCalled_ReturnExpectedBoolean()
        {
            //Arrange
            var service = GetPostService();
            var parentSlug = fixture.Create<string>();
            var id = fixture.Create<int>();
            var criteria = fixture.Create<CheckTypeCategoryCriteria>();
            var expected = fixture.Create<Category>();
            baseService.GetDataEntityAsync<Category>(Arg.Do<CheckTypeCategoryCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.CheckTypeCategoryAsync(parentSlug, id);

            //Assert
            Assert.Equal(expected != null, actual);
        }

        [Fact]
        public async Task GetMenuAsync_WhenCalled_ReturnExpectedMenus()
        {
            //Arrange
            var service = GetPostService();
            var menuName = fixture.Create<string>();
            var criteria = fixture.Create<GetMenuCriteria>();
            var expected = fixture.Create<IEnumerable<Menu>>();
            baseService.GetDataAsync<Menu>(Arg.Do<GetMenuCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetMenuAsync(menuName);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetSitemapDataAsync_WhenCalled_ReturnExpectedSitemap()
        {
            //Arrange
            var service = GetPostService();
            var criteria = fixture.Create<GetSitemapDataCriteria>();
            var expected = fixture.Create<IEnumerable<SitemapModel>>();
            baseService.GetDataAsync<SitemapModel>(Arg.Do<GetSitemapDataCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetSitemapDataAsync();

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public void GetBlackListIPs_WhenCalled_ReturnExpectedData()
        {
            //Arrange
            var service = GetPostService();
            var blackList = new List<string>();
            var blackListIPs = "";
            configuration.GetValue<string>("BlackListIPs").Returns(blackListIPs);
            if (!string.IsNullOrWhiteSpace(blackListIPs))
            {
                var blackListArr = blackListIPs.Split(",");
                if (blackListArr != null && blackListArr.Any())
                {
                    blackList = blackListArr.Select(t => t.Trim()).ToList();
                }
            }
            //Act
            var actual =  service.GetBlackListIPs();

            //Assert
            Assert.Equal(blackList, actual);
        }

        [Fact]
        public async Task GetNewsSitemapDataAsync_WhenCalled_ReturnExpectedNewsSitemapModel()
        {
            //Arrange
            var service = GetPostService();
            var criteria = fixture.Create<GetNewsSitemapCriteria>();
            var expected = fixture.Create<IEnumerable<NewsSitemapModel>>();
            baseService.GetDataAsync<NewsSitemapModel>(Arg.Do<GetNewsSitemapCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetNewsSitemapDataAsync();

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task GetRSSDataAsync_WhenCalled_ReturnExpectedPosts()
        {
            //Arrange
            var service = GetPostService();
            var criteria = fixture.Create<GetRSSCriteria>();
            var expected = fixture.Create<IEnumerable<Post>>();
            baseService.GetDataAsync<Post>(Arg.Do<GetRSSCriteria>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.GetRSSDataAsync();

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task UpdateVideoView_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetPostService();
            var postId = fixture.Create<int>();
            var criteria = fixture.Create<UpdateVideoViewCommand>();
            var expected = fixture.Create<CommandResult>();
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateVideoViewCommand>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.UpdateVideoView(postId);

            //Assert
            Assert.Equal(expected, actual);
        }

        [Fact]
        public async Task UpdateViewAsync_WhenCalled_ReturnExpectedCommandResult()
        {
            //Arrange
            var service = GetPostService();
            var postId = fixture.Create<int>();
            var isVideo = fixture.Create<bool>();
            var criteria = fixture.Create<UpdateViewCommand>();
            var expected = fixture.Create<CommandResult>();
            baseService.ExecuteNonQueryAsync(Arg.Do<UpdateViewCommand>(x => criteria = x)).Returns(expected);

            //Act
            var actual = await service.UpdateViewAsync(postId, isVideo);

            //Assert
            Assert.Equal(expected, actual);
            Assert.Equal(postId, criteria.PostId);
            Assert.Equal(isVideo, criteria.IsVideoPost);
        }

        [Fact]
        public void PostFunction_WhenCalled_ReturnExpectedData()
        {
            //Arrange
            var expectedInSecondPost = fixture.Create<Post>();
            var expectedInMunitePost = fixture.Create<Post>();
            var expectedInHourPost = fixture.Create<Post>();
            var Images = new List<ImageInfo>();
            Images.Add(new ImageInfo { ImageType = "jpg", Width = 100, Height = 100, ImageUrl = "url" });
            Images.Add(new ImageInfo { ImageType = "jpg", Width = 200, Height = 200, ImageUrl = "url" });
            expectedInSecondPost.Images = Images;
            expectedInSecondPost.PostDateGMT = DateTime.UtcNow.AddSeconds(-10);
            expectedInMunitePost.PostDateGMT = DateTime.UtcNow.AddMinutes(-10);
            expectedInHourPost.PostDateGMT = DateTime.UtcNow.AddHours(-1);
            var postDateFormat = expectedInSecondPost.PostTimeFormatSingle;
            var imageSize100 = expectedInSecondPost.GetImageBySize(100);
            var imageSize50 = expectedInSecondPost.GetImageBySize(50);
            var imageSizeInValid = expectedInSecondPost.GetImageBySize(-1);
            var imageSize = expectedInMunitePost.GetImageBySize(100);
            //Act & Assert
            Assert.Equal("10 giây trước", expectedInSecondPost.PostTime);
            Assert.Equal("10 phút trước", expectedInMunitePost.PostTime);
            Assert.Equal("1 giờ trước", expectedInHourPost.PostTime);
            Assert.Equal(expectedInSecondPost.PostDate.ToString("dddd, dd/MM/yyyy, HH:mm", new CultureInfo("vi")), postDateFormat);
            Assert.Equal("url", imageSize100);
            Assert.Equal(expectedInSecondPost.FeatureImage, imageSize50);
            Assert.Equal(expectedInSecondPost.FeatureImage, imageSizeInValid);
            Assert.Equal(expectedInMunitePost.FeatureImage, imageSize);
        }

        private static IEnumerable<Post> GetTestPost()
        {
            List<Post> expected = new List<Post>();
            expected.Add(new Post { ID = 1, PostExcerpt = "", PostContent = "content", FeatureImage = "", Metadata = "" });
            expected.Add(new Post { ID = 2, PostExcerpt = "", PostContent = "content \n content", FeatureImage = "", Metadata = "" });
            expected.Add(new Post { ID = 3, PostExcerpt = "", PostContent = "content \n content", FeatureImage = "", Metadata = "" });
            return (IEnumerable<Post>)expected;
        }

        private static IEnumerable<Post> GetExpectedPost()
        {
            List<Post> expected = new List<Post>();
            expected.Add(new Post { ID = 1, PostExcerpt = "", PostContent = "content", FeatureImage = "", Metadata = "" });
            expected.Add(new Post { ID = 2, PostExcerpt = "", PostContent = "content \n content", FeatureImage = "", Metadata = "" });
            return (IEnumerable<Post>)expected;
        }

        #region Helpers
        private PostService GetPostService()
        {
            baseService = Substitute.For<IBaseService>();
            serializerService = Substitute.For<ISerializerService>();
            configuration = Substitute.For<IConfiguration>();
            var service = new PostService(baseService, serializerService, configuration);

            return service;
        }
        #endregion Helpers
    }
}
