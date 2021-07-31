namespace SabaNews.Core.Services
{
    using System;
    using System.IO;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;
    using MailKit.Net.Smtp;
    using MailKit.Security;
    using Microsoft.Extensions.Logging;
    using Microsoft.Extensions.Options;
    using MimeKit;
    using SabaNews.Core.Models;

    public interface IBaseMailService
    {
        Task<CommandResult> SendMailAsync(MailModel email);

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

        public async Task<CommandResult> SendMailAsync(MailModel email)
        {
            if (email == null || !IsValidEmail(email.ReceiverEmail) || !mailConfigs.Enabled)
            {
                return new CommandResult { Code = 1, Message = "Email không hợp lệ"  };
            }

            var message = new MimeMessage();
            message.From.Add(new MailboxAddress(mailConfigs.SenderName, mailConfigs.Username));
            message.To.Add(new MailboxAddress(string.Empty, email.ReceiverEmail));
            message.Subject = email.Subject;
            message.Priority = mailConfigs.MailPriority;

            var builder = new BodyBuilder
            {
                HtmlBody = email.Body
            };
            message.Body = builder.ToMessageBody();
            CommandResult result;

            using var mailclient = new SmtpClient();
            try
            {
                mailclient.ServerCertificateValidationCallback += (sender, certificate, chain, errors) => true;
                mailclient.CheckCertificateRevocation = false;
                await mailclient.ConnectAsync(mailConfigs.SmtpServer, mailConfigs.SmtpPort, mailConfigs.EnableSsl);
                if (mailConfigs.EnableSsl)
                {
                    await mailclient.AuthenticateAsync(mailConfigs.Username, mailConfigs.Password);
                }

                await mailclient.SendAsync(message);
                logger.LogInformation(string.Format("Receiver: {0}\n\tSubject: {1}", email.ReceiverEmail, email.Subject));
                result = new CommandResult { Code = 0, Message = "Gửi Email thành công" };
            }
            catch (AuthenticationException ex)
            {
                WriteLogError(email, ex);
                result = new CommandResult { Code = 1, Message = ex.Message };
            }
            catch (SmtpCommandException ex)
            {
                WriteLogError(email, ex);
                result = new CommandResult { Code = 1, Message = ex.Message };
            }
            catch (SmtpProtocolException ex)
            {
                WriteLogError(email, ex);
                result = new CommandResult { Code = 1, Message = ex.Message };
            }
            catch (Exception ex)
            {
                WriteLogError(email, ex);
                result = new CommandResult { Code = 1, Message = ex.Message };
            }

            await mailclient.DisconnectAsync(true);
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
