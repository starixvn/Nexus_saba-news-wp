namespace SabaNews.Livescore.Core.Enum
{
    public enum MatchStatus
    {
        NotStarted = 0,
        FirstHalf = 10,
        Section1,
        EndOfTheFirstQuater,
        Section2,
        EndOfTheSecondQuater,
        Midfield = 20,
        SecondHalf = 30,
        Section3,
        EndOfTheThirdQuater,
        Section4,
        Overtime = 40,
        PenaltyShootout,
        Finished = 50,
        Postponed = 60,
        Interrupt,
        CutInHalf,
        Postponement,
        Cancel,
        TBD = 99
    }
}