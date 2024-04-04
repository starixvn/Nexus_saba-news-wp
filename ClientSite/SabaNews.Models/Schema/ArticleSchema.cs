using System.Text.Json.Serialization;
using Newtonsoft.Json;

namespace SabaNews.Models.Schema
{
    public class ArticleSchema
    {
        [JsonProperty("@context")]
        public string Context { get; set; }
        [JsonProperty("@type")]
        public string @Type { get; set; }
        public string Headline { get; set; }
        public string Description { get; set; }
        public string DatePublished {get; set;}
        public string DateModified {get; set;}
    }
}
