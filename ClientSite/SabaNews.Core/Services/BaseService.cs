namespace SabaNews.Services
{
    using Fanex.Data.Repository;
    using SabaNews.Core.Models;
    using SabaNews.Core.Repositories;
    using SabaNews.Core.Services;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class BaseService : IBaseService
    {
        private readonly IDynamicRepository dynamicRepository;
        private readonly IBaseRepository baseRepository;
        private readonly ILogServiceProvider logServiceProvider;

        public BaseService(
            IDynamicRepository dynamicRepository,
            IBaseRepository baseRepository,
            ILogServiceProvider logServiceProvider)
        {
            this.dynamicRepository = dynamicRepository;
            this.baseRepository = baseRepository;
            this.logServiceProvider = logServiceProvider;
        }

        public async Task<IEnumerable<T>> GetDataAsync<T>(ICriteria criteria)
        {
            if (criteria != null && criteria.IsValid())
            {
                var watch = System.Diagnostics.Stopwatch.StartNew();
                var result =  await dynamicRepository.FetchAsync<T>(criteria);
                watch.Stop();
                logServiceProvider.WriteLogFileTrackingSP(criteria.GetSettingKey(), watch.ElapsedMilliseconds.ToString() + "ms");
                return result;
            }

            return new List<T>();
        }

        public async Task<T> GetDataEntityAsync<T>(ICriteria criteria)
        {
            if (criteria != null && criteria.IsValid())
            {
                var watch = System.Diagnostics.Stopwatch.StartNew();
                var result = await dynamicRepository.GetAsync<T>(criteria);
                watch.Stop();
                logServiceProvider.WriteLogFileTrackingSP(criteria.GetSettingKey(), watch.ElapsedMilliseconds.ToString() + "ms");
                return result;
            }

            return default;
        }

        public async Task<ValueTuple<IEnumerable<TFirst>, IEnumerable<TSecond>>> FetchMultipleAsync<TFirst, TSecond>(ICriteria criteria)
        {
            if (criteria != null && criteria.IsValid())
            {
                var watch = System.Diagnostics.Stopwatch.StartNew();
                var result = await dynamicRepository.FetchMultipleAsync<TFirst, TSecond>(criteria);
                watch.Stop();
                logServiceProvider.WriteLogFileTrackingSP(criteria.GetSettingKey(), watch.ElapsedMilliseconds.ToString() + "ms");
                return result;
            }

            return new ValueTuple<IEnumerable<TFirst>, IEnumerable<TSecond>>(new List<TFirst>(), new List<TSecond>());
        }

        public async Task<(IEnumerable<TFirst>, IEnumerable<TSecond>, IEnumerable<TThird>)> FetchMultipleAsync<TFirst, TSecond, TThird>(ICriteria criteria)
        {
            if (criteria != null && criteria.IsValid())
            {
                var watch = System.Diagnostics.Stopwatch.StartNew();
                var result = await dynamicRepository.FetchMultipleAsync<TFirst, TSecond, TThird>(criteria);
                watch.Stop();
                logServiceProvider.WriteLogFileTrackingSP(criteria.GetSettingKey(), watch.ElapsedMilliseconds.ToString() + "ms");
                return result;
            }

            return new ValueTuple<IEnumerable<TFirst>, IEnumerable<TSecond>, IEnumerable<TThird>>(new List<TFirst>(), new List<TSecond>(), new List<TThird>());
        }

        public async Task<CommandResult> ExecuteNonQueryAsync(INonQueryCommand command)
        {
            var commandResult = new CommandResult();
            if (command != null && command.IsValid())
            {
                var watch = System.Diagnostics.Stopwatch.StartNew();
                commandResult = await baseRepository.ExecuteNonQueryAsync(command);
                watch.Stop();
                logServiceProvider.WriteLogFileTrackingSP(command.GetSettingKey(), watch.ElapsedMilliseconds.ToString() + "ms");
            }

            return commandResult;
        }
    }
}