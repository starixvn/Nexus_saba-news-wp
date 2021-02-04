namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IPostService
    {
        Task<IEnumerable<Post>> GetPostsBySlugAsync(string slug, int start, int end);

        Task<PostTemplate> GetPostsByCateSlugAsync(string cateSlug, int start, int end);

        Task<PostTemplate> GetRecentPostsAsync(string exceptCategorySlug, int start, int end, int postID = 0);

        Task<IEnumerable<Post>> GetTopPostsByCateSlugAsync(string cateSlug, int start, int end);

        Task<Post> GetPostDetailBySlugAsync(string slug, string postType);

        Task<IEnumerable<Post>> GetRelatedPostAsync(int ID);

        Task<IEnumerable<Post>> GetRandomReplatedPostAsync(string categories, int ID);

        Task<PostTemplate> SearchPostAsync(string keyword, int start, int end);

        Task<PostTemplate> GetPostByTagAsync(string tag, int start, int end);

        Task<PostTemplate> GetPostByDateAsync(string date, int start, int end);

        Task<IEnumerable<Menu>> GetMenuAsync(string MenuName);

        Task<Category> GetCateDetailAsync(string slug);

        Task<Category> GetPageDetailBySlugAsync(string slug);

        Task<Boolean> CheckTypeCategoryAsync(string parentSlug, int ID);
    }
}