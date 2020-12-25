FROM registry.nexdev.net:5050/docker/dotnet/core/sdk:5.0 AS builder

WORKDIR /src

COPY ["SabaNews.Models", "SabaNews.Models"]
COPY ["SabaNews.Services", "SabaNews.Services"]
COPY ["SabaNews.Criteria", "SabaNews.Criteria"]
COPY ["SabaNews.Core", "SabaNews.Core"]
COPY ["SabaNews.Site/SabaNews.Site.csproj", "SabaNews.Site/"]
COPY ["nuget.config", ""]

WORKDIR "/src/SabaNews.Site"
RUN dotnet restore -v diag

COPY ./SabaNews.Site .

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM registry.nexdev.net:5050/docker/dotnet/core/aspnet:5.0-buster-slim AS base

WORKDIR /app
COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.Site.dll"]
