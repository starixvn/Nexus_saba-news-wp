namespace SabaNews.Models.LiveScore
{
    using System.Net;

    public class LiveMatchQueryResponseResult
    {
        public HttpStatusCode Code { get; set; }
        public LiveMatchQueryResponse Result { get; set; }
        public int ReplyTime { get; set; }
    }
}