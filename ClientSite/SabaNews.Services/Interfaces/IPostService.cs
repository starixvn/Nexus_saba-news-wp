namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models;
    using SabaNews.Models.Posts;

    public interface IPostService
    {
        Task<IEnumerable<Post>> GetPostsBySlugAsync(string slug, int start, int end);

        Task<IEnumerable<Post>> GetPostsByCateSlugAsync(GetPostsByCateSlugCriteria criteria);

        Task<IEnumerable<Post>> GetPostsByCateSlugNotContainChidrenAsync(GetPostsByCateSlugNotContainChildrenCriteria criteria);

        Task<IEnumerable<Post>> GetRecentPostsAsync(GetRecentPostsParam postParam);

        Task<IEnumerable<Post>> GetTopPostsByCateSlugAsync(PostParam postParam);

        Task<Post> GetPostDetailBySlugAsync(string slug, string postType);

        Task<IEnumerable<Post>> SearchPostAsync(SearchPostsCriteria criteria);

        Task<IEnumerable<Post>> GetPostByTagAsync(GetPostsByTagCriteria criteria);

        Task<IEnumerable<Post>> GetSavedPostsByUserID(string IDs);

        Task<IEnumerable<Menu>> GetMenuAsync(string MenuName);

        Task<Category> GetCateDetailAsync(string slug);

        Task<Category> GetPageDetailBySlugAsync(string slug);

        Task<Boolean> CheckTypeCategoryAsync(string parentSlug, int ID);

        Task<IEnumerable<SitemapModel>> GetSitemapDataAsync();

        Task<IEnumerable<NewsSitemapModel>> GetNewsSitemapDataAsync();

        Task<IEnumerable<Post>> GetRSSDataAsync();

        Task<CommandResult> UpdateVideoView(int postId);

        Task<CommandResult> UpdateViewAsync(int postId, bool isVideo);

        Task<IEnumerable<Post>> GetPostsByCateSlugs(GetPostsByCateSlugsCriteria criteria);
    }
}