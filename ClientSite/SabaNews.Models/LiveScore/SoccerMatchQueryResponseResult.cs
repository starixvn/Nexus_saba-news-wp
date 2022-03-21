namespace SabaNews.Models.LiveScore
{
    using System.Net;

    public class SoccerMatchQueryResponseResult
    {
        public HttpStatusCode Code { get; set; }
        public SoccerMatchQueryResponse Result { get; set; }
        public int ReplyTime { get; set; }
    }
}