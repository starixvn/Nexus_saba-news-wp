namespace SabaNews.Services.Interfaces
{
    using SabaNews.Models;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface ICommentService
    {
        Task<CommentTemplate> GetCommentsAsync(int postID, int start, int end);
        Task<IEnumerable<Comment>> GetCommentChildrenAsync(int postID, int commentID);
    }
}
