namespace SabaNews.Site.Extensions
{
    using Microsoft.AspNetCore.Razor.TagHelpers;

    [HtmlTargetElement("custom-image", TagStructure = TagStructure.WithoutEndTag)]
    public class ImageTagHelper : TagHelper
    {
        public override void Process(TagHelperContext context, TagHelperOutput output)
        {
        }
    }
}