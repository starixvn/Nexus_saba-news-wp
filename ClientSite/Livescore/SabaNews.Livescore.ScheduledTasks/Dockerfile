FROM registry.nexdev.net:5050/docker/dotnet/core/sdk:5.0.102-ca-patch-buster-slim AS builder

WORKDIR /src

COPY ["nuget.config", "."]
COPY ["Livescore/SabaNews.Livescore.ScheduledTasks/SabaNews.Livescore.ScheduledTasks.csproj", "Livescore/SabaNews.Livescore.ScheduledTasks/"]
COPY ["Livescore/SabaNews.Livescore.Provider.API/SabaNews.Livescore.Provider.API.csproj", "Livescore/SabaNews.Livescore.Provider.API/"]
COPY ["Livescore/SabaNews.Livescore.Models/SabaNews.Livescore.Models.csproj", "Livescore/SabaNews.Livescore.Models/"]
COPY ["Livescore/SabaNews.Livescore.Core/SabaNews.Livescore.Core.csproj", "Livescore/SabaNews.Livescore.Core/"]
COPY ["SabaNews.Core/SabaNews.Core.csproj", "SabaNews.Core/"]
COPY ["Livescore/SabaNews.Livescore.Services/SabaNews.Livescore.Services.csproj", "Livescore/SabaNews.Livescore.Services/"]
COPY ["Livescore/SabaNews.Livescore.Repositories/SabaNews.Livescore.Repositories.csproj", "Livescore/SabaNews.Livescore.Repositories/"]
COPY ["Livescore/SabaNews.Livescore.SyncData/SabaNews.Livescore.SyncData.csproj", "Livescore/SabaNews.Livescore.SyncData/"]
RUN dotnet restore "Livescore/SabaNews.Livescore.ScheduledTasks/SabaNews.Livescore.ScheduledTasks.csproj"

COPY . .

WORKDIR "/src/Livescore/SabaNews.Livescore.ScheduledTasks"

RUN dotnet publish -r linux-x64 --self-contained false -o /app

FROM registry.nexdev.net:5050/docker/dotnet/core/aspnet:5.0-buster-slim AS base

WORKDIR /app
COPY --from=builder /app .

ENTRYPOINT ["dotnet", "SabaNews.Livescore.ScheduledTasks.dll"]