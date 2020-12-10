namespace sabanews.Services
{
    using Microsoft.AspNetCore.Hosting;
    using System.IO;

    public class PathService : IPathService
    {
        private readonly IHostingEnvironment _HostEnvironment;

        public PathService(IHostingEnvironment HostEnvironment)
        {
            this._HostEnvironment = HostEnvironment;
        }

        public string MapPath(string path)
        {
            string webRootPath = _HostEnvironment.WebRootPath;

            return Path.Combine(webRootPath, path);
        }

        public string MapPathContentRoot(string path)
        {
            string contentRootPath = _HostEnvironment.ContentRootPath;

            return Path.Combine(contentRootPath, path);
        }

        public string MapFullPath(string path)
        {
            string contentRootPath = _HostEnvironment.ContentRootPath;

            return Path.GetFullPath(contentRootPath + path);
        }

        public string MapAbsolutePath(string rootPath, string path)
        {
            return Path.GetFullPath(rootPath + path);
        }
    }
}
