namespace SabaNews.Core.Services
{
    using System;
    using System.IO;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;
    using Fanex.MailDeliver.MailClient;
    using Fanex.MailDeliver.MailClient.API;
    using Fanex.MailDeliver.Model;
    using MailKit.Net.Smtp;
    using MailKit.Security;
    using Microsoft.Extensions.Logging;
    using Microsoft.Extensions.Options;
    using MimeKit;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;

    public interface IBaseMailService
    {
        CommandResult SendMail(MailModel email);

        string ReadTextFile(string filePath);
    }

    public class BaseMailService : IBaseMailService
    {
        private readonly MailConfigs mailConfigs;
        private readonly ILogger logger;

        public BaseMailService(
            IOptionsSnapshot<MailConfigs> mailConfigs,
            ILogger<BaseMailService> logger)
        {
            this.mailConfigs = mailConfigs.Value;
            this.logger = logger;
        }

        public CommandResult SendMail(MailModel email)
        {
            if (email == null || !IsValidEmail(email.ReceiverEmail) || !mailConfigs.Enabled)
            {
                return new CommandResult { Message = Message.InvalidEmail  };
            }
            CommandResult result;
            try
            {
                AppSettings.AppMailId = mailConfigs.AppMailId;
                AppSettings.AppMailAddress = mailConfigs.AppMailAddress;
                var mailItem = new MailRecord
                {
                    MailTo = email.ReceiverEmail,
                    Title = mailConfigs.SenderName,
                    Subject = email.Subject,
                    Body = email.Body
                };

                using var client = new MailDeliver();
                client.SendMail(mailItem);
                result = new CommandResult { Code = Constants.SuccessCode, Message = Message.SendEmailSuccessfully };
            }
            catch (Exception ex)
            {
                WriteLogError(email, ex);
                result = new CommandResult { Message = ex.Message };
            }

            return result;
        }

        public string ReadTextFile(string filePath)
        {
            using var sr = new StreamReader(filePath);
            return sr.ReadToEnd();
        }

        private static bool IsValidEmail(string email)
        {
            if (string.IsNullOrWhiteSpace(email))
            {
                return false;
            }

            var regex = new Regex(@"\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*");
            return regex.IsMatch(email);
        }

        private void WriteLogError(MailModel email, Exception ex)
        {
            logger.LogError(ex, string.Format("Receiver: {0}\n\tSubject: {1}\n\tErrorMessage: {2}", email.ReceiverEmail, email.Subject, ex.Message));
        }
    }
}
