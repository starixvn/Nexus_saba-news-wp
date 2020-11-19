namespace sabanews.Services
{
    using MySqlConnector;
    using System;

    public class ConnectDb : IDisposable
    {
        public MySqlConnection Connection { get; }

        public ConnectDb(string connectionString)
        {
            Connection = new MySqlConnection(connectionString);
        }

        public void Dispose() => Connection.Dispose();
    }
   
}
