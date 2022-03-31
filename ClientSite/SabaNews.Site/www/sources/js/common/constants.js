export const MatchStatus = {
    NotStarted: 0,
    FirstHalf: 10,
    Section1: 11,
    EndOfTheFirstQuater: 12,
    Section2: 13,
    EndOfTheSecondQuater: 14,
    Intermission: 20,
    SecondHalf: 30,
    Section3: 31,
    EndOfTheThirdQuater: 32,
    Section4: 33,
    ExtraTime: 40,
    PenaltyShootout: 41,
    Finished: 50,
    Delay: 60,
    Interrupt: 61,
    CutInHalf: 62,
    Postpone: 63,
    Cancel: 64,
    TBD: 99
};

export const IncidentPosition = {
    'Neutral': 0,
    'Home': 1,
    'Away': 2
}

export const VARResultCode = {
    'Unknown': 0,
    'GoalValid': 1,
    'GoalInValid': 2,
    'PenaltyValid': 3,
    'PenaltyCancelled': 4,
    'RedCardValid': 5,
    'RedCardCanlled': 6,
    'PlayPenaltyVerification': 7,
    'PlayPenaltyChange': 8,
    'MaintainOriginalJudgment': 9,
    'SendPenaltyChange': 10
}
