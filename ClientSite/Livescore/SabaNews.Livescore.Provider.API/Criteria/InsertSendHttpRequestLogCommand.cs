namespace SabaNews.Livescore.Provider.API.Criteria
{
    using SabaNews.Core.Models;

    internal class InsertSendHttpRequestLogCommand : BaseNonQueryCommand
    {
        public string RequestUrl { get; set; }
        public string Param { get; set; }
        public string Response { get; set; }

        public override string GetSettingKey() => "Livescore_HttpRequestLog_Insert";

        public override bool IsValid()
        => !string.IsNullOrWhiteSpace(RequestUrl);
    }
}