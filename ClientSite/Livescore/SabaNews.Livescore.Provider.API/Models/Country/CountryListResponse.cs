namespace SabaNews.Livescore.Provider.API.Models.Country
{
    public class CountryListResponse
    {
        public long CountryId { get; set; }

        public long? CategoryId { get; set; }

        public string CountryName { get; set; }

        public string CountryNameZHT { get; set; }

        public string CountryNameEN { get; set; }

        public string CountryFullName { get; set; }

        public string CountryFullNameZHT { get; set; }

        public string CountryFullNameEN { get; set; }

        public string CountryLogo { get; set; }
    }
}
