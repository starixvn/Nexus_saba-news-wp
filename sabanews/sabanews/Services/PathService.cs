namespace sabanews.Services
{
    using Microsoft.AspNetCore.Hosting;
    using System.IO;

    public class PathService : IPathService
    {
        private readonly IWebHostEnvironment webHostEnvironment;

        public PathService(IWebHostEnvironment webHostEnvironment)
        {
            this.webHostEnvironment = webHostEnvironment;
        }

        public string MapPath(string path)
        {
            string webRootPath = webHostEnvironment.WebRootPath;

            return Path.Combine(webRootPath, path);
        }

        public string MapPathContentRoot(string path)
        {
            string contentRootPath = webHostEnvironment.ContentRootPath;

            return Path.Combine(contentRootPath, path);
        }

        public string MapFullPath(string path)
        {
            string contentRootPath = webHostEnvironment.ContentRootPath;

            return Path.GetFullPath(contentRootPath + path);
        }

        public string MapAbsolutePath(string rootPath, string path)
        {
            return Path.GetFullPath(rootPath + path);
        }
    }
}