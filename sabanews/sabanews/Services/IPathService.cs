namespace sabanews.Services
{
    public interface IPathService
    {
        string MapPath(string path);
        string MapPathContentRoot(string path);
        string MapFullPath(string path);
        string MapAbsolutePath(string rootPath, string path);
    }
}
