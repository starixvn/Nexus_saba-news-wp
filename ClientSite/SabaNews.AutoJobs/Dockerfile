FROM hub.nexdev.net/base/dotnet/core/sdk:6.0-alpine AS builder

WORKDIR /src

COPY ["nuget.config", "."]
COPY ["SabaNews.AutoJobs/SabaNews.AutoJobs.csproj", "SabaNews.AutoJobs/"]
COPY ["SabaNews.Services/SabaNews.Services.csproj", "SabaNews.Services/"]
COPY ["SabaNews.Models/SabaNews.Models.csproj", "SabaNews.Models/"]
COPY ["SabaNews.Core/SabaNews.Core.csproj", "SabaNews.Core/"]
COPY ["SabaNews.Criteria/SabaNews.Criteria.csproj", "SabaNews.Criteria/"]

RUN dotnet restore "SabaNews.AutoJobs/SabaNews.AutoJobs.csproj"

COPY . .

WORKDIR "/src/SabaNews.AutoJobs"

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM hub.nexdev.net/base/dotnet/core/aspnet:6.0-alpine AS base

WORKDIR /app

COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.AutoJobs.dll"]