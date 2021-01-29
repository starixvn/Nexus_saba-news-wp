namespace SabaNews.Services
{
    using Microsoft.AspNetCore.Http;
    using Newtonsoft.Json;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Criteria;
    using SabaNews.Models.Comment;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class CommentService : ICommentService
    {
        private readonly IBaseService baseService;
        public const int CurrentUser = 1;
        public CommentService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<CommentTemplate> GetCommentsAsync(int postID, int start, int end)
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
                Comments = UpdateLikeComment(comments)
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
            var comments = await baseService.GetDataAsync<Comment>(criteria);
            return UpdateLikeComment(comments);
        }

        public async Task<Comment> GetLikeDataAsync(long commentID)
        {
            var criteria = new CmtGetLikeDataCriteria
            {
                CommentID = commentID
            };
            return await baseService.GetDataEntityAsync<Comment>(criteria);
        }
        public async Task<long> CreateComment(CreateComment model)
        {
            var command = new CmtCreateCommand
            {
                CommentPostID = model.CommentPostID,
                CommentAuthor = model.CommentAuthor,
                CommentAuthorEmail = model.CommentAuthorEmail,
                CommentDate = model.CommentDate,
                CommentDateGMT = model.CommentDateGMT,
                CommentContent = model.CommentContent,
                CommentAgent = model.CommentAgent,
                CommentParent = model.CommentParent,
                CommentAuthorIP = model.CommentAuthorIP,
                UserID = model.UserID
            };

            var result = await baseService.ExecuteNonQueryAsync(command);
            return result.IsSuccessful ? (int)command.CommentID : 0;
        }

        public async Task<CommandResult> UpdateLikeData(long commentID, string ipClient)
        {
            var comment = await GetLikeDataAsync(commentID);
            var users = "";
            var ips = "";
            var likeCount = 0;
            if (comment.CommentLikeCount == 0 || string.IsNullOrEmpty(comment.CommentLikeUsers))
            {

                var arrUsers = new List<int> { CurrentUser };
                var arrIPs = new List<string> { ipClient };
                users = JsonConvert.SerializeObject(arrUsers);
                ips = JsonConvert.SerializeObject(arrIPs);
                likeCount = 1;
            }
            else
            {
                List<int> arrUsers = JsonConvert.DeserializeObject<List<int>>(comment.CommentLikeUsers);
                List<string> arrIPs = JsonConvert.DeserializeObject<List<string>>(comment.CommentLikeIPs);
                arrUsers.Add(CurrentUser);
                arrIPs.Add(ipClient);
                users = JsonConvert.SerializeObject(arrUsers);
                ips = JsonConvert.SerializeObject(arrIPs);
                likeCount = (int)(comment.CommentLikeCount + 1);
            }
            var criteria = new CmtUpdateLikeCommand
            {
                CommentID = commentID,
                CommentLikeCount = likeCount,
                CommentLikeIPs = ips,
                CommentLikeUsers = users
            };
            return await baseService.ExecuteNonQueryAsync(criteria);
        }

        private static IEnumerable<Comment> UpdateLikeComment(IEnumerable<Comment> comments)
        {
            foreach (var comment in comments)
            {
                if (!string.IsNullOrEmpty(comment.CommentLikeUsers))
                {
                    var arrUsers = JsonConvert.DeserializeObject<List<int>>(comment.CommentLikeUsers);
                    var HasUser = arrUsers.FindIndex(item => item == CurrentUser);
                    if (HasUser != -1)
                    {
                        comment.isLiked = true;
                    }
                }
            }
            return comments;
        }



    }
}
