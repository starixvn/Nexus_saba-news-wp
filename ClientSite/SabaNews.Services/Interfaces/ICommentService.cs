namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Models.Comment;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface ICommentService
    {
        Task<CommentTemplate> GetCommentsAsync(int postID, string filterType, int start, int end);
        Task<IEnumerable<Comment>> GetCommentChildrenAsync(int postID, int commentID);
        Task<Comment> GetLikeDataAsync(long commentID);
        Task<long> CreateCommentAsync(CreateComment model);
        Task<CommandResult> UpdateLikeDataAsync(long commentID, string ipClient, string type);
    }
}
