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
        public const string SMTPServer = "smtp-broadcasts.postmarkapp.com";//"smtppro.zoho.com";
        public const int Port = 587;
        public const string Username = "PM-B-broadcast-pLXq-G2SplfOokpARLWq1";//"info@saba.vn";
        public const string Password = "Hn8hAYM8QbXtPJb6DTv78BrQplnA5rrkTFIe";//"xj1cPe4fS4s4";
        public const bool IsEnableSSL = false;
        public const string MailFrom = "notifications@mailer.saba.vn";
        public const string ReceiverEmail = "sabasports5@gmail.com";

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
            var message = new MimeMessage();
            message.From.Add(new MailboxAddress("SABA Sports", MailFrom));
            message.To.Add(new MailboxAddress(string.Empty, ReceiverEmail));
            message.Subject = email.Subject;
            //message.Headers.Add("X-PM-Message-Stream", "broadcast");
            var builder = new BodyBuilder
            {
                HtmlBody = email.Body
            };
            message.Body = builder.ToMessageBody();

            using var mailclient = new SmtpClient();
            try
            {
                mailclient.ServerCertificateValidationCallback += (sender, certificate, chain, errors) => true;
                mailclient.CheckCertificateRevocation = false;
                mailclient.Connect(SMTPServer, Port, IsEnableSSL);
                mailclient.Authenticate(Username, Password);

                mailclient.Send(message);
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
