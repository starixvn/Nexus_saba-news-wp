namespace SabaNews.Services
{
    using System;
    using System.Collections;
    using System.Collections.Generic;
    using System.Globalization;
    using System.Linq;
    using System.Text;
    using Fanex.Logging;
    using SabaNews.Models;

    public interface ISerializerService
    {
        IEnumerable<ImageInfo> GetListImageFromMetadata(string featuredImage, string metadata);
    }

    public class SerializerService : ISerializerService
    {
        private ILogger logService;
        private int pos;
        private readonly Encoding stringEncoding;
        private readonly NumberFormatInfo nfi;
        private const short TwoCharacters = 2;
        private const short ThreeCharacters = 3;
        private const short FourCharacters = 4;
        private const short SixCharacters = 6;

        public SerializerService(ILogger logService)
        {
            nfi = new NumberFormatInfo
            {
                NumberGroupSeparator = "",
                NumberDecimalSeparator = "."
            };
            stringEncoding = new UTF8Encoding();
            this.logService = logService;
        }

        public IEnumerable<ImageInfo> GetListImageFromMetadata(string featuredImage, string metadata)
        {
            var results = new List<ImageInfo>();
            if (string.IsNullOrWhiteSpace(metadata))
            {
                return results;
            }

            try
            {
                pos = 0;
                var decryptedData = DeserializeString(metadata);

                var hashTable = (Hashtable)decryptedData;
                if (hashTable.Contains("sizes"))
                {
                    var sizes = hashTable["sizes"];
                    var sizeHash = (Hashtable)sizes;
                    var imageBaseUrl = string.Join("/", featuredImage.Split('/').SkipLast(1).ToArray());
                    results = MappingHashTableToListImgInfo(imageBaseUrl, sizeHash);
                }
                return results;
            }
            catch
            {
                return results;
            }
        }

        private static List<ImageInfo> MappingHashTableToListImgInfo(string imageBaseUrl, Hashtable sizeHash)
        {
            var images = new List<ImageInfo>();

            if (sizeHash != null)
            {
                foreach (Hashtable item in sizeHash.Values)
                {
                    if (IsValidKeys(item))
                    {
                        images.Add(new ImageInfo
                        {
                            Width = (int)item["width"],
                            Height = (int)item["height"],
                            ImageType = item.ContainsKey("mime-type") ? (string)item["mime-type"] : string.Empty,
                            ImageUrl = string.Format("{0}/{1}", imageBaseUrl, (string)item["file"])
                        });
                    }
                }
            }
            return images;
        }

        private static bool IsValidKeys(Hashtable item)
        {
            return item.ContainsKey("width") && item.ContainsKey("height") && item.ContainsKey("file");
        }

        private object DeserializeString(string str)
        {
            if (str == null || str.Length <= pos)
            {
                return new object();
            }

            int start;
            int end;
            int length;
            string stLen;
            switch (str[pos])
            {
                case 'N':
                    pos += TwoCharacters;
                    return null;

                case 'b':
                    char chBool;
                    chBool = str[pos + TwoCharacters];
                    pos += FourCharacters;
                    return chBool == '1';

                case 'i':
                    string stInt;
                    start = str.IndexOf(":", pos, StringComparison.OrdinalIgnoreCase) + 1;
                    end = str.IndexOf(";", start, StringComparison.OrdinalIgnoreCase);
                    stInt = str.Substring(start, end - start);
                    pos += ThreeCharacters + stInt.Length;
                    return int.Parse(stInt, nfi);

                case 'd':
                    string stDouble;
                    start = str.IndexOf(":", pos, StringComparison.OrdinalIgnoreCase) + 1;
                    end = str.IndexOf(";", start, StringComparison.OrdinalIgnoreCase);
                    stDouble = str.Substring(start, end - start);
                    pos += ThreeCharacters + stDouble.Length;
                    return double.Parse(stDouble, nfi);

                case 's':
                    start = str.IndexOf(":", pos, StringComparison.OrdinalIgnoreCase) + 1;
                    end = str.IndexOf(":", start, StringComparison.OrdinalIgnoreCase);
                    stLen = str[start..end];
                    int bytelen = int.Parse(stLen);
                    length = bytelen;

                    return HandleTypeString(str, stLen, end, length, bytelen);

                case 'a':
                    start = str.IndexOf(":", pos, StringComparison.OrdinalIgnoreCase) + 1;
                    end = str.IndexOf(":", start, StringComparison.OrdinalIgnoreCase);
                    stLen = str.Substring(start, end - start);
                    length = int.Parse(stLen);

                    return HandleArrayType(str, stLen, length);

                default:
                    return string.Empty;
            }
        }

        private string HandleTypeString(string str, string stLen, int end, int length, int bytelen)
        {
            if ((end + TwoCharacters + length) >= str.Length)
            {
                length = str.Length - TwoCharacters - end;
            }

            string stRet = str.Substring(end + TwoCharacters, length);
            while (stringEncoding.GetByteCount(stRet) > bytelen)
            {
                length--;
                stRet = str.Substring(end + TwoCharacters, length);
            }
            pos += SixCharacters + stLen.Length + length;
            stRet = stRet.Replace("\n", "\r\n");
            return stRet;
        }

        private object HandleArrayType(string str, string stLen, int length)
        {
            Hashtable htRet = new Hashtable(length);
            ArrayList alRet = new ArrayList(length);
            pos += FourCharacters + stLen.Length;
            for (int i = 0; i < length; i++)
            {
                object key = DeserializeString(str);
                object val = DeserializeString(str);

                if (alRet != null)
                {
                    if (key is int && (int)key == alRet.Count)
                    {
                        alRet.Add(val);
                    }
                    else
                    {
                        alRet = null;
                    }
                }
                htRet[key] = val;
            }
            pos++;

            if (pos < str.Length && str[pos] == ';')
            {
                pos++;
            }

            return alRet ?? (object)htRet;
        }
    }
}