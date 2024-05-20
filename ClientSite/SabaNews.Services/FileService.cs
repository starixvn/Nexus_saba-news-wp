namespace SabaNews.Services
{
    using Microsoft.AspNetCore.Http;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Services.Interfaces;
    using System;
    using System.IO;
    using System.Net.Http.Headers;
    using System.Threading.Tasks;

    public class FileService : IFileService
    {
        private readonly IPathService pathService;
        private readonly string baseUrl;

        public FileService(
            IPathService pathService,
            IConfiguration configuration)
        {
            this.pathService = pathService;
            baseUrl = configuration.GetValue<string>("BaseUrl");
        }


        public async Task<UploadResult> UploadFileAsync(IFormFile file, string dirFolder)
        {
            UploadResult result;
            try
            {
                var Dir = pathService.MapAbsoluteRootPath(dirFolder);
                if (!Directory.Exists(Dir))
                {
                    Directory.CreateDirectory(Dir);
                }
                string filename = ContentDispositionHeaderValue.Parse(file.ContentDisposition).FileName.Trim('"');
                filename = pathService.EnsureCorrectFilename(filename);
                filename = DateTimeUtilize.Now().Millisecond + filename;
                var filePath = pathService.MapAbsoluteRootPath("/" + dirFolder + filename);
                if (File.Exists(filePath))
                {
                    File.Delete(filePath);
                }
                using (FileStream output = File.Create(filePath))
                    await file.CopyToAsync(output);

                string linkPhoto = dirFolder + filename;
                string url = string.Concat(baseUrl, linkPhoto);
                result = new UploadResult { Code = Constants.SuccessCode, AbsoluteUrl = url, Url = linkPhoto };

            }
            catch (Exception e)
            {
                result = new UploadResult { Message = e.Message };
            }
            return result;
        }

        public UploadResult DeleteFile(string url, string dirFolder)
        {
            UploadResult result;
            try
            {
                var pathList = url.Split('/');
                var filename = pathList[^1];
                var filePath = pathService.MapAbsoluteRootPath("/" + dirFolder + filename);
                if (File.Exists(filePath))
                {
                    File.Delete(filePath);
                }
                result = new UploadResult { Code = Constants.SuccessCode };
            }
            catch (Exception e)
            {
                result = new UploadResult { Message = e.Message };
            }
            return result;
        }
    }
}
