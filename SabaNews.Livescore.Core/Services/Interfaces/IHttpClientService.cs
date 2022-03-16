namespace SabaNews.Livescore.Core.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IHttpClientService
    {
        Task<T> ExecuteGetAsync<T>(string endPoint, IDictionary<string, object> data = null) where T : new();

        Task<T> ExecutePostAsync<T>(string endPoint, IDictionary<string, object> data) where T : new();
    }
}