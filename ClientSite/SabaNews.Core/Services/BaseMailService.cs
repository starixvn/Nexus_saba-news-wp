namespace SabaNews.Core.Services
{
    using MailKit.Net.Smtp;
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
        private readonly ILogServiceProvider logService;
        private readonly IConfiguration configuration;
        private const string MailConfigsForBroadcast = "MailConfigsForBroadcast";
        private const string MailConfigsForTransactional = "MailConfigsForTransactional";

        public BaseMailService(
            ILogger<BaseMailService> logger,
            IConfiguration configuration,
            ILogServiceProvider logService)
        {
            this.logger = logger;
            this.configuration = configuration;
            this.logService = logService;
        }

        public async Task<CommandResult> SendMailAsync(MailModel email)
        {
            var mailConfigsKey = email.IsSendEDM ? MailConfigsForBroadcast : MailConfigsForTransactional;
            MailConfigs mailConfigs = configuration.GetSection(mailConfigsKey).Get<MailConfigs>();
            
            if (email == null || !IsValidEmail(email.ReceiverEmail) || !mailConfigs.Enabled)
            {
                return new CommandResult { Message = Message.InvalidEmail };
            }

            CommandResult result;
            var message = new MimeMessage();
            message.From.Add(new MailboxAddress(mailConfigs.SenderName, mailConfigs.MailFrom));
            message.To.Add(new MailboxAddress(string.Empty, email.ReceiverEmail));
            message.Subject = email.Subject;
            var builder = new BodyBuilder
            {
                HtmlBody = email.Body
            };
            message.Body = builder.ToMessageBody();

            using var mailclient = new SmtpClient();
            try
            {
                mailclient.ServerCertificateValidationCallback += (_, _, _, _) => true;
                mailclient.CheckCertificateRevocation = false;
                mailclient.Connect(mailConfigs.SmtpServer, mailConfigs.SmtpPort, mailConfigs.EnableSsl);
                if (mailConfigs.IsAuthenticate)
                {
                    mailclient.Authenticate(mailConfigs.Username, mailConfigs.Password);
                }
                await mailclient.SendAsync(message);
                mailclient.Disconnect(true);
                result = new CommandResult { Code = Constants.SuccessCode, Message = Message.SendEmailSuccessfully };
                logService.WriteLogFileInfo($"Receiver: {email.ReceiverEmail} - Subject: {email.Subject} - Message: {Message.SendEmailSuccessfully}"  );
            }

            catch (Exception ex)
            {
                logService.WriteLogFileInfo($"Receiver: {email.ReceiverEmail} - Subject: {email.Subject} - Message: {ex.Message}");
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
    }
}
