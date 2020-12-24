using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace sabanews.Models
{
    public class SideBarViewModel
    {
        public IEnumerable<Post> Posts { get; set; }
        public int Days { get; set; }
        public int CurrentMonth { get; set; }
        public int CurrentYear { get; set; }
        public int CurrentDay { get; set; }
        public int FirstDayOfWeek { get; set; }
    }
}
