using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace sabanews.Models
{
    public class SingleViewModel
    {
        public Post SinglePost { get; set; }
        public IEnumerable<Post> RelatedPosts { get; set; }
        public IEnumerable<Post> RandomRelatedPosts { get; set; }
    }
}
