FROM registry.nexdev.net:5050/docker/dotnet/core/sdk:5.0 AS builder

WORKDIR /src

COPY ["SabaNews.Site/SabaNews.Site.csproj", "SabaNews.Site/"]
COPY ["SabaNews.Models/SabaNews.Models.csproj", "SabaNews.Models/"]
COPY ["SabaNews.Services/SabaNews.Services.csproj", "SabaNews.Services/"]
COPY ["SabaNews.Criteria/SabaNews.Criteria.csproj", "SabaNews.Criteria/"]
COPY ["SabaNews.Core/SabaNews.Core.csproj", "SabaNews.Core/"]
COPY ["nuget.config", ""]

WORKDIR "/src/SabaNews.Site"
RUN dotnet restore -v diag

COPY . .

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM registry.nexdev.net:5050/docker/dotnet/core/aspnet:5.0-buster-slim AS base

WORKDIR /app
COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.Site.dll"]
