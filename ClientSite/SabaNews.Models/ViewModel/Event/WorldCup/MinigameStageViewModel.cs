using SabaNews.Core.Models;
using SabaNews.Models.Posts;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Models.ViewModel.Event.WorldCup
{
    public class MinigameStageViewModel
    {
        public HeaderViewModel Header { get; set; }
        public byte Stage { get; set; }
        public UserSession User { get; set; }
        public bool IsLogin { get; set; }
    }
}
