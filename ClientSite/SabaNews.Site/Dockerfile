FROM hub.nexdev.net/base/dotnet/core/sdk:6.0-alpine AS builder

WORKDIR /src

COPY ["SabaNews.Models", "SabaNews.Models"]
COPY ["SabaNews.Services", "SabaNews.Services"]
COPY ["SabaNews.Criteria", "SabaNews.Criteria"]
COPY ["SabaNews.Core", "SabaNews.Core"]
COPY ["SabaNews.Resources", "SabaNews.Resources"]
COPY ["Livescore/SabaNews.Livescore.Core", "Livescore/SabaNews.Livescore.Core"]
COPY ["Livescore/SabaNews.Livescore.Models", "Livescore/SabaNews.Livescore.Models"]
COPY ["Livescore/SabaNews.Livescore.Repositories", "Livescore/SabaNews.Livescore.Repositories"]
COPY ["Livescore/SabaNews.Livescore.Services", "Livescore/SabaNews.Livescore.Services"]
COPY ["SabaNews.Site/SabaNews.Site.csproj", "SabaNews.Site/"]
COPY ["nuget.config", ""]

RUN dotnet restore "SabaNews.Site/SabaNews.Site.csproj"

COPY . .

WORKDIR "/src/SabaNews.Site"

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM hub.nexdev.net/base/dotnet/core/aspnet:6.0-final AS base

WORKDIR /app

COPY --from=builder /app .

RUN set -x \
 	&& apt-get update \
     && apt-get -y install nano \
     && apt-get -y install curl \
     && apt-get -y install telnet



ENTRYPOINT ["dotnet", "SabaNews.Site.dll"]
