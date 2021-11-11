FROM registry.nexdev.net:5050/docker/dotnet/core/sdk:5.0.102-ca-patch-buster-slim AS builder

WORKDIR /src

COPY ["nuget.config", "."]
COPY ["SabaNews.WindowsService/SabaNews.WindowsService.csproj", "SabaNews.WindowsService/"]
COPY ["SabaNews.Services/SabaNews.Services.csproj", "SabaNews.Services/"]
COPY ["SabaNews.Models/SabaNews.Models.csproj", "SabaNews.Models/"]
COPY ["SabaNews.Core/SabaNews.Core.csproj", "SabaNews.Core/"]
COPY ["SabaNews.Criteria/SabaNews.Criteria.csproj", "SabaNews.Criteria/"]

RUN dotnet restore "SabaNews.WindowsService/SabaNews.WindowsService.csproj"

COPY . .

WORKDIR "/src/SabaNews.WindowsService"

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM registry.nexdev.net:5050/docker/dotnet/core/aspnet:5.0-buster-slim AS base

WORKDIR /app

COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.WindowsService.dll"]