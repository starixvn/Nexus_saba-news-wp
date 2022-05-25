import soccerMatchSlice, {
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches,
    setLiveMatches
} from '../soccerMatchSlice';

describe('soccerMatchSlice', () => {
    describe('reducers', () => {
        describe('setSearchKey', () => {
            it('should update state correctly', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        searchKey: ''
                    }, {
                        type: setSearchKey.type,
                        payload: 'searchkey'
                    })
                ).toEqual({
                    today: '2022-05-25',
                    searchKey: 'searchkey'
                });
            });
        });

        describe('setSelectedDate', () => {
            it('should update state correctly', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        selectedDate: ''
                    }, {
                        type: setSelectedDate.type,
                        payload: '2022-05-25'
                    })
                ).toEqual({
                    today: '2022-05-25',
                    selectedDate: '2022-05-25'
                });
            });
        });

        describe('setDateRanges', () => {
            it('should update state correctly', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        dateRanges: []
                    }, {
                        type: setDateRanges.type,
                        payload: ['2022-05-24', '2022-05-25']
                    })
                ).toEqual({
                    today: '2022-05-25',
                    dateRanges: ['2022-05-24', '2022-05-25']
                });
            });
        });

        describe('setMajorLeagues', () => {
            it('should update state correctly', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        majorLeagues: []
                    }, {
                        type: setMajorLeagues.type,
                        payload: [
                            {
                                leagueId: 1,
                                leagueNameEN: 'Champions League',
                                leagueNameVN: 'Champions League'
                            },
                            {
                                leagueId: 2,
                                leagueNameEN: 'Premier League',
                                leagueNameVN: 'Ngoại hạng Anh'
                            }
                        ]
                    })
                ).toEqual({
                    today: '2022-05-25',
                    majorLeagues: [
                        {
                            leagueId: 1,
                            leagueNameEN: 'Champions League',
                            leagueNameVN: 'Champions League'
                        },
                        {
                            leagueId: 2,
                            leagueNameEN: 'Premier League',
                            leagueNameVN: 'Ngoại hạng Anh'
                        }
                    ]
                });
            });
        });

        describe('setMatches', () => {
            it('should update state correctly', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        matches: []
                    }, {
                        type: setMatches.type,
                        payload: [
                            {
                                matchId: 1,
                                league: {
                                    leagueId: 1,
                                    leagueNameEN: 'Premier League',
                                    leagueNameVN: 'Ngoại hạng Anh',
                                },
                                homeTeam: {
                                    teamId: 1,
                                    teamNameEN: 'Arsenal',
                                },
                                awayTeam: {
                                    teamId: 2,
                                    teamNameEN: 'Everton',
                                }
                            }
                        ]
                    })
                ).toEqual({
                    today: '2022-05-25',
                    matches: [
                        {
                            matchId: 1,
                            league: {
                                leagueId: 1,
                                leagueNameEN: 'Premier League',
                                leagueNameVN: 'Ngoại hạng Anh',
                            },
                            homeTeam: {
                                teamId: 1,
                                teamNameEN: 'Arsenal',
                            },
                            awayTeam: {
                                teamId: 2,
                                teamNameEN: 'Everton',
                            }
                        }
                    ]
                });
            });
        });

        describe('setLiveMatches', () => {
            afterEach(() => {
                window.isLivePage = false;
            });

            it('should update state correctly when isLivePage = true', () => {
                window.isLivePage = true;

                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        matches: []
                    }, {
                        type: setLiveMatches.type,
                        payload: [
                            {
                                matchId: 1,
                                league: {
                                    leagueId: 1,
                                    leagueNameEN: 'Premier League',
                                    leagueNameVN: 'Ngoại hạng Anh',
                                },
                                homeTeam: {
                                    teamId: 1,
                                    teamNameEN: 'Arsenal',
                                },
                                awayTeam: {
                                    teamId: 2,
                                    teamNameEN: 'Everton',
                                }
                            }
                        ]
                    })
                ).toEqual({
                    today: '2022-05-25',
                    totalLiveMatches: 1,
                    matches: [
                        {
                            matchId: 1,
                            league: {
                                leagueId: 1,
                                leagueNameEN: 'Premier League',
                                leagueNameVN: 'Ngoại hạng Anh',
                            },
                            homeTeam: {
                                teamId: 1,
                                teamNameEN: 'Arsenal',
                            },
                            awayTeam: {
                                teamId: 2,
                                teamNameEN: 'Everton',
                            }
                        }
                    ]
                });
            });

            it('do nothing when isLivePage = false and today != selectedDate', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        selectedDate: '2022-05-24',
                        matches: []
                    }, {
                        type: setLiveMatches.type,
                        payload: [
                            {
                                matchId: 1,
                                league: {
                                    leagueId: 1,
                                    leagueNameEN: 'Premier League',
                                    leagueNameVN: 'Ngoại hạng Anh',
                                }
                            }
                        ]
                    })
                ).toEqual({
                    today: '2022-05-25',
                    selectedDate: '2022-05-24',
                    totalLiveMatches: 1,
                    matches: []
                });
            });

            it('do nothing when isLivePage = false and matches = null', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        selectedDate: '2022-05-25',
                        matches: null
                    }, {
                        type: setLiveMatches.type,
                        payload: [
                            {
                                matchId: 1,
                                league: {
                                    leagueId: 1,
                                    leagueNameEN: 'Premier League',
                                    leagueNameVN: 'Ngoại hạng Anh',
                                }
                            }
                        ]
                    })
                ).toEqual({
                    today: '2022-05-25',
                    selectedDate: '2022-05-25',
                    totalLiveMatches: 1,
                    matches: null
                });
            });

            it('should update state correctly when isLivePage = false, today === selectedDate, matches != null', () => {
                expect(
                    soccerMatchSlice({
                        today: '2022-05-25',
                        selectedDate: '2022-05-25',
                        matches: [
                            {
                                matchId: 1,
                                league: {
                                    leagueId: 1,
                                    leagueNameEN: 'Premier League',
                                    leagueNameVN: 'Ngoại hạng Anh',
                                }
                            },
                            {
                                matchId: 2,
                                league: {
                                    leagueId: 2,
                                    leagueNameEN: 'La Liga'
                                }
                            }
                        ]
                    }, {
                        type: setLiveMatches.type,
                        payload: [
                            {
                                matchId: 1,
                                league: {
                                    leagueId: 1,
                                    leagueNameEN: 'Premier League',
                                    leagueNameVN: 'Ngoại hạng Anh',
                                },
                                homeTeam: {
                                    teamId: 1,
                                    teamNameEN: 'Arsenal',
                                },
                                awayTeam: {
                                    teamId: 2,
                                    teamNameEN: 'Everton',
                                }
                            }
                        ]
                    })
                ).toEqual({
                    today: '2022-05-25',
                    selectedDate: '2022-05-25',
                    totalLiveMatches: 1,
                    matches: [
                        {
                            matchId: 1,
                            league: {
                                leagueId: 1,
                                leagueNameEN: 'Premier League',
                                leagueNameVN: 'Ngoại hạng Anh',
                            },
                            homeTeam: {
                                teamId: 1,
                                teamNameEN: 'Arsenal',
                            },
                            awayTeam: {
                                teamId: 2,
                                teamNameEN: 'Everton',
                            }
                        },
                        {
                            matchId: 2,
                            league: {
                                leagueId: 2,
                                leagueNameEN: 'La Liga'
                            }
                        }
                    ]
                });
            });
        });
    });
});