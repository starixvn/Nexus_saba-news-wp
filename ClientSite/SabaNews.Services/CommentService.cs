namespace SabaNews.Services
{
    using SabaNews.Criteria;
    using SabaNews.Models;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class CommentService : ICommentService
    {
        private readonly IBaseService baseService;

        public CommentService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<CommentTemplate> GetCommentsAsync(int postID, int start,int end)
        {
            var criteria = new CmtGetParentAndNumberChildrenCriteria
            {
                PostID = postID,
                Start = start,
                End = end
            };
            var comments = await baseService.GetDataAsync<Comment>(criteria);
            var commentTemplate = new CommentTemplate
            {
                TotalItems = criteria.TotalItems,
                TotalParentCmt = criteria.TotalParentCmt,
                Comments = comments
            };
            return commentTemplate;
        }

        public async Task<IEnumerable<Comment>> GetCommentChildrenAsync(int postID, int commentID)
        {
            var criteria = new CmtGetChildrenCriteria
            {
                PostID = postID,
                CmtID = commentID
            };
            return await baseService.GetDataAsync<Comment>(criteria);
        }


    }
}
