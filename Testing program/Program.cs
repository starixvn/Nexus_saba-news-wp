namespace Testing_program
{
    using System;

    internal class Program
    {
        private static void Main(string[] args)
        {
            try
            {
                var encodedString = "a:2:{i:0;s:12:\"Sample array\";i:1;a:2:{i:0;s:5:\"Apple\";i:1;s:6:\"Orange\";}}";
                var serializer = new Serializer();
                var data = serializer.Deserialize(encodedString);
            }
            catch (Exception ex)
            {
                throw;
            }

            Console.WriteLine("Hello World!");
        }
    }
}