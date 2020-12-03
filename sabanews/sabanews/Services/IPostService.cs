namespace sabanews.Services
{
    using sabanews.Models;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IPostService
    {
        Task<IEnumerable<Post>> GetPostsAsync(int cateId, int start, int end);
        Task<IEnumerable<Post>> GetPostsBySlugAsync(string slug, int start, int end);
        Task<Post> GetPostDetailAsync(int ID);
        Task<IEnumerable<Post>> GetRelatedPostAsync(int ID);
        Task<IEnumerable<Post>> GetRandomReplatedPostAsync(string categories, int ID);
        Task<IEnumerable<Post>> SearchPostAsync(string keyword, int start, int end);
        Task<IEnumerable<Post>> GetPostByTagAsync(string tag, int start, int end);
        Task<IEnumerable<Post>> GetPostByDateAsync(string date, int start, int end);
        Task<IEnumerable<Menu>> GetMenuAsync(string MenuName);
        Task<Category> GetCateDetailAsync(string slug);
    }
}
