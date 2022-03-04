namespace SabaNews.Core.Services
{
    using MailKit.Net.Smtp;
    using MailKit.Security;
    using Microsoft.Extensions.Configuration;
    using Microsoft.Extensions.Logging;
    using MimeKit;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using System;
    using System.IO;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;

    public interface IBaseMailService
    {
        Task<CommandResult> SendMailAsync(MailModel email);

        string ReadTextFile(string filePath);
    }

    public class BaseMailService : IBaseMailService
    {
        private readonly ILogger logger;
        private readonly IConfiguration configuration;

        public BaseMailService(
            ILogger<BaseMailService> logger,
            IConfiguration configuration)
        {
            this.logger = logger;
            this.configuration = configuration;
        }

        public async Task<CommandResult> SendMailAsync(MailModel email)
        {
            MailConfigs mailConfigs;
            if (email.IsSendEDM)
            {
                mailConfigs = configuration.GetSection("MailConfigsForBroadcast").Get<MailConfigs>();
            }
            else
            {
                mailConfigs = configuration.GetSection("MailConfigsForTransactional").Get<MailConfigs>();
            }
            
            if (email == null || !IsValidEmail(email.ReceiverEmail) || !mailConfigs.Enabled)
            {
                return new CommandResult { Message = Message.InvalidEmail };
            }

            CommandResult result;
            var message = new MimeMessage();
            message.From.Add(new MailboxAddress(mailConfigs.SenderName, mailConfigs.MailFrom));
            message.To.Add(new MailboxAddress(string.Empty, email.ReceiverEmail));
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
                mailclient.Connect(mailConfigs.SmtpServer, mailConfigs.SmtpPort, SecureSocketOptions.StartTls);
                mailclient.Authenticate(mailConfigs.Username, mailConfigs.Password);
                await mailclient.SendAsync(message);
                mailclient.Disconnect(true);
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
