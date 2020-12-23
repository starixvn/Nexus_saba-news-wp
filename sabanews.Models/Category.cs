using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace sabanews.Models
{
    public class Category
    {
        public int CatId { get; set; }
        public string Name { get; set; }
        public int ParentId { get; set; }
        public string Slug { get; set; }
        public string Title { get; set; }
        public string Description { get; set; }
    }
}
