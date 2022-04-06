FROM registry.nexdev.net:5050/docker/dotnet/core/sdk:5.0.102-ca-patch-buster-slim AS builder

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

WORKDIR "/src/SabaNews.Site"
RUN dotnet restore -v n

COPY ./SabaNews.Site .

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM registry.nexdev.net:5050/docker/dotnet/core/aspnet:5.0-buster-slim AS base

WORKDIR /app
COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.Site.dll"]
