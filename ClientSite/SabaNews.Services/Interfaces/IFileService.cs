namespace SabaNews.Services.Interfaces
{
    using Microsoft.AspNetCore.Http;
    using SabaNews.Core.Models;
    using System.Threading.Tasks;

    public interface IFileService
    {
        Task<CommandResult> UploadFileAsync(IFormFile file, string dirFolder);
    }
}
