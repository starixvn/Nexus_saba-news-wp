namespace sabanews.Services
{
    using MySqlConnector;
    using sabanews.Models;
    using System;
    using System.Collections.Generic;
    using System.Data;
    using System.Linq;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;

    public interface IPostService
    {
        Task<IEnumerable<Post>> GetAsync(PostParam param);
        Task<IEnumerable<Menu>> GetMenuAsync(string MenuName);
        Task<Post> GetPostDetail(int ID);
        Task<IEnumerable<Post>> GetRelatedPost(int ID, string tag);
        Task<IEnumerable<Post>> GetRandomReplatedPost(string categories, int ID);
        Task<IEnumerable<Post>> SearchPost(string keyword, int start, int end);
        Task<IEnumerable<Post>> GetPostByTag(string tag, int start, int end);
        Task<IEnumerable<Post>> GetPostByDate(string date, int start, int end);
    }

    public class PostService : IPostService
    {
        private readonly ConnectDb Db;

        public PostService(ConnectDb Db)
        {
            this.Db = Db;
        }

        public async Task<IEnumerable<Post>> GetAsync(PostParam param)
        {
            List<Post> posts = new List<Post>();
            
            using (var command = new MySqlCommand("CALL wordpress.get_posts(@category,@start,@end);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@category", param.Category);
                command.Parameters.AddWithValue("@start", param.Start);
                command.Parameters.AddWithValue("@end", param.End);
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var excerpt = Regex.Replace(reader["post_content"].ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                        var post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString(),
                            PostExcerpt = (excerpt.Length > 200) ? excerpt.Substring(0, 200) : excerpt,
                            PostContent = reader["post_content"].ToString(),
                            PostDate = ToRelativeDate(DateTime.Parse(reader["post_date"].ToString())),
                            FeatureImage = reader["feature_image"].ToString(),
                            Categories = reader["cate_name"].ToString()
                        };
                        posts.Add(post);
                    }
                }
            }
            return posts;
        }

        public async Task<Post> GetPostDetail(int ID)
        {
            var post = new Post();
            using (var command = new MySqlCommand("CALL wordpress.get_post_detail(@ID);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@ID", ID);
               
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var excerpt = Regex.Replace(reader["post_content"].ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                        post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString(),
                            PostExcerpt = (excerpt.Length > 150) ? excerpt.Substring(0, 150) : excerpt,
                            PostContent = reader["post_content"].ToString(),
                            PostDate = ToRelativeDate(DateTime.Parse(reader["post_date"].ToString())),
                            FeatureImage = reader["feature_image"].ToString(),
                            Tags = reader["tags"].ToString(),
                            Categories = reader["categories"].ToString()
                        };
                    }
                }
            }
            return post;
        }

        public async Task<IEnumerable<Post>> GetRelatedPost(int ID, string tag)
        {
            List<Post> posts = new List<Post>();
            using (var command = new MySqlCommand("CALL wordpress.get_related_posts(@ID,0,4);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@ID", ID);
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString()
                        };
                        posts.Add(post);
                    }
                }
            }
            return posts;
        }

        public async Task<IEnumerable<Post>> GetRandomReplatedPost(string categories, int ID)
        {
            List<Post> posts = new List<Post>();

            using (var command = new MySqlCommand("CALL wordpress.get_random_post_categories(@Categories, @ID);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@Categories", categories);
                command.Parameters.AddWithValue("@ID", ID);
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var excerpt = Regex.Replace(reader["post_content"].ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                        var post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString(),
                            PostExcerpt = (excerpt.Length > 150) ? excerpt.Substring(0, 150) : excerpt,
                            PostContent = reader["post_content"].ToString(),
                            PostDate = ToRelativeDate(DateTime.Parse(reader["post_date"].ToString())),
                            FeatureImage = reader["feature_image"].ToString()
                        };
                        posts.Add(post);
                    }
                }
            }
            return posts;
        }

        public async Task<IEnumerable<Post>> SearchPost(string keyword, int start, int end)
        {
            List<Post> posts = new List<Post>();

            using (var command = new MySqlCommand("CALL wordpress.search_posts(@keyword,@start,@end);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@keyword", keyword);
                command.Parameters.AddWithValue("@start", start);
                command.Parameters.AddWithValue("@end", end);
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var excerpt = Regex.Replace(reader["post_content"].ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                        var post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString(),
                            PostExcerpt = (excerpt.Length > 150) ? excerpt.Substring(0, 150) : excerpt,
                            PostContent = reader["post_content"].ToString(),
                            PostDate = ToRelativeDate(DateTime.Parse(reader["post_date"].ToString())),
                            FeatureImage = reader["feature_image"].ToString()
                        };
                        posts.Add(post);
                    }
                }
            }
            return posts;
        }

        public async Task<IEnumerable<Post>> GetPostByTag(string tag, int start, int end)
        {
            List<Post> posts = new List<Post>();
            using (var command = new MySqlCommand("CALL wordpress.get_posts_by_tag(@tag,@start,@end);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@tag", tag.Trim(' '));
                command.Parameters.AddWithValue("@start", start);
                command.Parameters.AddWithValue("@end", end);
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var excerpt = Regex.Replace(reader["post_content"].ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                        var post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString(),
                            PostExcerpt = (excerpt.Length > 150) ? excerpt.Substring(0, 150) : excerpt,
                            PostContent = reader["post_content"].ToString(),
                            PostDate = ToRelativeDate(DateTime.Parse(reader["post_date"].ToString())),
                            FeatureImage = reader["feature_image"].ToString()
                        };
                        posts.Add(post);
                    }
                }
            }
            return posts;
        }

        public async Task<IEnumerable<Post>> GetPostByDate(string date, int start, int end)
        {
            List<Post> posts = new List<Post>();
            using (var command = new MySqlCommand("CALL wordpress.get_posts_by_date(@date,@start,@end);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@date", date);
                command.Parameters.AddWithValue("@start", start);
                command.Parameters.AddWithValue("@end", end);
                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var excerpt = Regex.Replace(reader["post_content"].ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                        var post = new Post
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            PostTitle = reader["post_title"].ToString(),
                            PostExcerpt = (excerpt.Length > 150) ? excerpt.Substring(0, 150) : excerpt,
                            PostContent = reader["post_content"].ToString(),
                            PostDate = ToRelativeDate(DateTime.Parse(reader["post_date"].ToString())),
                            FeatureImage = reader["feature_image"].ToString()
                        };
                        posts.Add(post);
                    }
                }
            }
            return posts;
        }


        public async Task<IEnumerable<Menu>> GetMenuAsync(string MenuName)
        {
            List<Menu> menus = new List<Menu>();

            using (var command = new MySqlCommand("CALL wordpress.get_menu(@menu_name);", Db.Connection))
            {
                if (Db.Connection.State == ConnectionState.Closed) await Db.Connection.OpenAsync();
                command.Parameters.AddWithValue("@menu_name", MenuName);

                using (var reader = command.ExecuteReader())
                {
                    while (reader.Read())
                    {
                        var test = reader["ID"];
                        var menu = new Menu
                        {
                            ID = Convert.ToInt32(reader["ID"]),
                            Name = reader["post_title"].ToString(),
                            CatId = Convert.ToInt32(reader["term_id"]),
                            ParentId = Convert.ToInt32(reader["menu_parent"]),
                            Slug = reader["slug"].ToString()
                        };
                        menus.Add(menu);
                    }
                }
            }
            return menus;
        }

        public static string ToRelativeDate(DateTime dateTime)
        {
            var timeSpan = DateTime.Now - dateTime;

            if (timeSpan <= TimeSpan.FromSeconds(60))
                return string.Format("{0} giây trước", timeSpan.Seconds);

            if (timeSpan <= TimeSpan.FromMinutes(60))
                return timeSpan.Minutes > 1 ? String.Format("{0} phút trước", timeSpan.Minutes) : "about a minute ago";

            if (timeSpan <= TimeSpan.FromHours(24))
                return timeSpan.Hours > 1 ? String.Format("{0} giờ trước", timeSpan.Hours) : "about an hour ago";

            return dateTime.ToString("H:mm dd/MM/yyyy");
        }
    }
}
