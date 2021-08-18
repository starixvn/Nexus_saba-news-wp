namespace SabaNews.Services.Interfaces
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Criteria;
    using SabaNews.Models;

    public interface IPostService
    {
        Task<IEnumerable<Post>> GetPostsBySlugAsync(string slug, int start, int end);

        Task<PostTemplate> GetPostsByCateSlugAsync(PostParam postParam);

        Task<IEnumerable<Post>> GetPostsByCateSlugNotContainChidrenAsync(GetPostsByCateSlugNotContainChildrenCriteria criteria);

        Task<PostTemplate> GetRecentPostsAsync(PostParam postParam);

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

        IList<string> GetBlackListIPs();

        Task<IEnumerable<NewsSitemapModel>> GetNewsSitemapDataAsync();

        Task<IEnumerable<Post>> GetRSSDataAsync();

        Task<CommandResult> UpdateVideoView(int postId);

        Task<CommandResult> UpdateViewAsync(int postId, bool isVideo);
    }
}