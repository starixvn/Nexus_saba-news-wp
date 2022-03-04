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
    using System.Net.Mail;
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
            var message = new MailMessage();
            message.From = new MailAddress(mailConfigs.MailFrom, mailConfigs.SenderName);
            message.To.Add(email.ReceiverEmail);
            message.Subject = email.Subject;
            message.Body = email.Body;
            message.IsBodyHtml = true;
            message.BodyEncoding = System.Text.Encoding.UTF8;
            message.SubjectEncoding = System.Text.Encoding.Default;


            using System.Net.Mail.SmtpClient smtp = new System.Net.Mail.SmtpClient(mailConfigs.SmtpServer, mailConfigs.SmtpPort);
            try
            {
                //if (mailConfigs.EnableSsl)
                //{
                    smtp.Credentials = new System.Net.NetworkCredential(mailConfigs.Username, mailConfigs.Password);
                //}
                smtp.Send(message);
                logger.LogInformation(string.Format("Receiver: {0}\n\tSubject: {1}", email.ReceiverEmail, email.Subject));
                result = new CommandResult { Code = 0, Message = "Gửi Email thành công" };
                message.Dispose();
            }
            catch (Exception e)
            {
                WriteLogError(email, e);
                result = new CommandResult { Code = 1, Message = e.Message };
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
