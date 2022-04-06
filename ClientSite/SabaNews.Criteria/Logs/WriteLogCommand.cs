namespace SabaNews.Criteria.Logs
{
    using System;

    public class WriteLogCommand : BaseNonQueryCommand
    {
        public int ActorId { get; set; }

        public int ActionId { get; set; }

        public string Data { get; set; }

        public string OldData { get; set; }

        public DateTime LogDate { get; set; } = DateTime.UtcNow;

        public override string GetSettingKey() => "log_writelog";

        public override bool IsValid() => ActionId > 0;
    }
}
