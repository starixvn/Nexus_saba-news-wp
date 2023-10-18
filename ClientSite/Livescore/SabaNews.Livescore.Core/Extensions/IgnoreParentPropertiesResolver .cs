namespace SabaNews.Livescore.Core.Extensions
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Reflection;
    using Newtonsoft.Json;
    using Newtonsoft.Json.Serialization;

    public class IgnoreParentPropertiesResolver : DefaultContractResolver
    {
        private readonly bool IgnoreBase;

        public IgnoreParentPropertiesResolver(bool ignoreBase)
        {
            IgnoreBase = ignoreBase;
        }

        protected override IList<JsonProperty> CreateProperties(Type type, MemberSerialization memberSerialization)
        {
            var allProps = base.CreateProperties(type, memberSerialization).ToList();
            if (!IgnoreBase)
            {
                return allProps;
            }

            // Choose the properties you want to serialize/deserialize
            var props = type.GetProperties(~BindingFlags.FlattenHierarchy).ToList();

            return allProps.Where(p => props.Exists(a => a.Name == p.PropertyName)).ToList();
        }
    }
}