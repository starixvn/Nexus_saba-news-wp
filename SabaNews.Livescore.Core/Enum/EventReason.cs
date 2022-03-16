namespace SabaNews.Livescore.Core.Enum
{
    using System.ComponentModel;

    public enum EventReason
    {
        [Description("Foul")]
        Foul = 1,

        [Description("PersonalFoul")]
        PersonalFoul = 2,

        [Description("Violation of opponent/injured substitution")]
        InjuredSubstitution = 3,

        [Description("Tactical foul/tactical substitution")]
        TacticalSubstitution = 4,

        [Description("Offensive Foul")]
        OffensiveFoul = 5,

        [Description("Off the Ball Foul")]
        OffTheBallFoul = 6,

        unknown = 0
    }
}