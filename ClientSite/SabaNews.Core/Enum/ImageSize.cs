namespace SabaNews.Core.Enum
{
    using System.ComponentModel;

    public enum ImageSize
    {
        [Description("150x150")]
        Small = 1,

        [Description("300x300")]
        Medium = 2,

        [Description("1024x1024")]
        Large = 3
    }
}