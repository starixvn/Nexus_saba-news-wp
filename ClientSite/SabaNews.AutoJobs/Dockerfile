FROM hub.nexdev.net/base/dotnet/core/sdk:5.0.102-ca-patch-buster-slim AS builder

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

FROM hub.nexdev.net/base/dotnet/core/aspnet:5.0-buster-slim AS base

WORKDIR /app
COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.AutoJobs.dll"]