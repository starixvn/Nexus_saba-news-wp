import React, { Component } from 'react';

export default class MatchSummary extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="summary-match">
                <div className="section-title">
                    <div>Hiệp 1</div><div>0 - 0</div>
                </div>
                <div className="participant-row home-participant">
                    <div className="incident">
                        <div className="time-box">29'</div>
                        <div className="incident-icon">
                            <svg className="yellow-card-icon">
                                <title>Thẻ vàng</title>
                            </svg>
                        </div>
                        <a className="player-name"><div>Bennett J.</div></a>
                    </div>
                </div>
                <div className="participant-row away-participant">
                    <div className="incident">
                        <div className="stime-box">31'</div>
                        <div className="incident-icon">
                            <svg className="yellow-card-icon"><title>Thẻ vàng</title></svg>
                        </div>
                        <a className="player-name"><div>McKinstry S.</div></a>
                    </div>
                </div>
                <div className="section-title">
                    <div>Hiệp 2</div><div>1 - 1</div>
                </div>
                <div className="participant-row away-participant">
                    <div className="incident">
                        <div className="time-box">46'</div>
                        <div className="incident-icon-sub">
                            <svg className="arrow-up-icon"><title>Dự bị - Vào</title></svg>
                        </div>
                        <a className="player-name">Fernandez M.</a>
                        <div className="incident-sub-out incident-side-away">
                            <div className="incident-icon-sub-out">
                                <svg className="arrow-down-icon"><title>Dự bị - Ra</title></svg>
                            </div>
                            <a className="sub-down player-name">Bate L.</a>
                        </div>
                    </div>
                </div>
                <div className="participant-row away-participant">
                    <div className="incident">
                        <div className="time-box">61'</div>
                        <div className="incident-icon">
                            <svg className="football-goal-icon" xmlns="http://www.w3.org/2000/svg">
                                <title>Phạt đền</title>
                                <path d="M4.678.549A7.5,7.5,0,0,0,2.2,12.8a7.494,7.494,0,0,0,10.607,0A7.494,7.494,0,0,0,12.8,2.2,7.51,7.51,0,0,0,4.678.549Zm-2.8,10.109a5.148,5.148,0,0,1-.989-.8,7.071,7.071,0,0,1-.228-3.94,6.3,6.3,0,0,1,.48-.8c.056-.079.107-.16.167-.237L3,5.89v.042a13.038,13.038,0,0,0,.272,2.7s0,.006,0,.01L1.959,10.705C1.93,10.691,1.9,10.675,1.875,10.657Zm5.032,3.479a6.444,6.444,0,0,0,.9.377A7.034,7.034,0,0,1,4,13.592L6.84,14.1C6.862,14.112,6.884,14.125,6.907,14.137Zm-.01-.512-3.348-.6A10.455,10.455,0,0,1,2.5,11.511c-.077-.137-.163-.27-.228-.412L3.584,9.043l.1.025a21.761,21.761,0,0,0,2.5.5l.125.017L7.875,12.3q-.409.575-.831,1.142C7,13.5,6.948,13.565,6.9,13.625Zm5.57-1.158a6.977,6.977,0,0,1-.846.721,6.428,6.428,0,0,0-.253-1.153l1.677-3.129a7.835,7.835,0,0,0,1.321-.745.591.591,0,0,0,.134-.128A6.989,6.989,0,0,1,12.467,12.467Zm-.074-4.058c.062.119.124.239.186.361l-1.62,3.022-.028.005a14.776,14.776,0,0,1-2.649.255l-1.617-2.8q.377-.7.748-1.405c.168-.318.333-.639.5-.959l3.346-.344A18.677,18.677,0,0,1,12.393,8.409Zm-.679-3.584c-.159.419-.329.834-.5,1.25l-3.259.335c-.039-.051-.079-.1-.122-.15q-.577-.654-1.159-1.3l.011-.012-.469-.468.907-2.732a1.273,1.273,0,0,1,.189-.037c.493-.05.987-.107,1.482-.136a7.207,7.207,0,0,1,.915,0L11.957,4.1C11.879,4.341,11.8,4.586,11.714,4.825Zm.753-2.292a6.993,6.993,0,0,1,1.438,2.082,7.13,7.13,0,0,0-1.34-.72c-.073-.03-.149-.057-.225-.083l-2.21-2.491A2.922,2.922,0,0,0,10.157,1,6.991,6.991,0,0,1,12.467,2.533ZM5.251.867c.009.006.013.012.018.013a5.617,5.617,0,0,1,1.4.736L5.819,4.169l-.013,0c-.173.072-.35.136-.521.214a14.945,14.945,0,0,0-2.036,1.1L1.576,4.5a.506.506,0,0,1,.029-.153c.112-.3.223-.6.35-.894a6.7,6.7,0,0,1,.331-.657c.08-.088.162-.175.247-.26A6.983,6.983,0,0,1,5.266.841Z" transform="translate(0 0)"></path>
                            </svg>
                        </div>
                        <a className="player-name"><div>Greenwood S.</div></a>
                        <div className="sub-incident">(Phạt đền)</div>
                    </div>
                </div>
                <div className="participant-row away-participant">
                    <div className="incident">
                        <div className="time-box">62'</div>
                        <div className="incident-icon-sub">
                            <svg className="arrow-up-icon"><title>Dự bị - Vào</title></svg>
                        </div>
                        <a className="player-name">Moore K.</a>
                        <div className="incident-sub-out incident-side-away">
                            <div className="incident-icon-sub-out">
                                <svg className="arrow-down-icon"><title>Dự bị - Ra</title></svg>
                            </div>
                            <a className="sub-down player-name">Allen C.</a>
                        </div>
                    </div>
                </div>
                <div className="participant-row away-participant">
                    <div className="incident">
                        <div className="time-box">68'</div>
                        <div className="incident-icon-sub">
                            <svg className="arrow-up-icon"><title>Dự bị - Vào</title></svg>
                        </div>
                        <a className="player-name">Jenkins J.</a>
                        <div className="incident-sub-out incident-side-away">
                            <div className="incident-icon-sub-out">
                                <svg className="arrow-down-icon"><title>Dự bị - Ra</title></svg>
                            </div>
                            <a className="sub-down player-name">Chilokoa-Mullen J.</a>
                        </div>
                    </div>
                </div>
                <div className="participant-row away-participant">
                    <div className="incident">
                        <div className="time-box">72'</div>
                        <div className="incident-icon">
                            <svg className="yellow-card-icon"><title>Thẻ vàng</title></svg>
                        </div>
                        <a className="player-name"><div>Fernandez M.</div></a>
                    </div>
                </div>
                <div className="participant-row home-participant">
                    <div className="incident">
                        <div className="time-box">74'</div>
                        <div className="incident-icon-sub">
                            <svg className="arrow-up-icon"><title>Dự bị - Vào</title></svg>
                        </div>
                        <a className="player-name">Williams J.</a>
                        <div className="incident-sub-out">
                            <div className="incident-icon-sub-out">
                                <svg className="arrow-down-icon"><title>Dự bị - Ra</title></svg>
                            </div>
                            <a className="sub-down player-name">Santiago Y.</a>
                        </div>
                    </div>
                </div>
                <div className="participant-row home-participant">
                    <div className="incident">
                        <div className="time-box">79'</div>
                        <div className="incident-icon-sub">
                            <svg className="arrow-up-icon"><title>Dự bị - Vào</title></svg>
                        </div>
                        <a className="player-name">Davies J.</a>
                        <div className="incident-sub-out ">
                            <div className="incident-icon-sub-out">
                                <svg className="arrow-down-icon "><title>Dự bị - Ra</title></svg>
                            </div>
                            <a className="sub-down player-name">Robson M.</a>
                        </div>
                    </div>
                </div>
                <div className="participant-row home-participant">
                    <div className="incident">
                        <div className="time-box">86'</div>
                        <div className="incident-icon-sub">
                            <svg className="arrow-up-icon"><title>Dự bị - Vào</title></svg>
                        </div>
                        <a className="player-name">Cassanova D. J.</a>
                        <div className="incident-sub-out ">
                            <div className="incident-icon-sub-out">
                                <svg className="arrow-down-icon "><title>Dự bị - Ra</title></svg>
                            </div>
                            <a className="sub-down player-name">Lusala D.</a>
                        </div>
                    </div>
                </div>
                <div className="participant-row home-participant">
                    <div className="incident">
                        <div className="time-box">87'</div>
                        <div className="incident-icon">
                            <svg className="football-goal-icon" xmlns="http://www.w3.org/2000/svg">
                                <title>Bàn thắng</title>
                                <path d="M4.678.549A7.5,7.5,0,0,0,2.2,12.8a7.494,7.494,0,0,0,10.607,0A7.494,7.494,0,0,0,12.8,2.2,7.51,7.51,0,0,0,4.678.549Zm-2.8,10.109a5.148,5.148,0,0,1-.989-.8,7.071,7.071,0,0,1-.228-3.94,6.3,6.3,0,0,1,.48-.8c.056-.079.107-.16.167-.237L3,5.89v.042a13.038,13.038,0,0,0,.272,2.7s0,.006,0,.01L1.959,10.705C1.93,10.691,1.9,10.675,1.875,10.657Zm5.032,3.479a6.444,6.444,0,0,0,.9.377A7.034,7.034,0,0,1,4,13.592L6.84,14.1C6.862,14.112,6.884,14.125,6.907,14.137Zm-.01-.512-3.348-.6A10.455,10.455,0,0,1,2.5,11.511c-.077-.137-.163-.27-.228-.412L3.584,9.043l.1.025a21.761,21.761,0,0,0,2.5.5l.125.017L7.875,12.3q-.409.575-.831,1.142C7,13.5,6.948,13.565,6.9,13.625Zm5.57-1.158a6.977,6.977,0,0,1-.846.721,6.428,6.428,0,0,0-.253-1.153l1.677-3.129a7.835,7.835,0,0,0,1.321-.745.591.591,0,0,0,.134-.128A6.989,6.989,0,0,1,12.467,12.467Zm-.074-4.058c.062.119.124.239.186.361l-1.62,3.022-.028.005a14.776,14.776,0,0,1-2.649.255l-1.617-2.8q.377-.7.748-1.405c.168-.318.333-.639.5-.959l3.346-.344A18.677,18.677,0,0,1,12.393,8.409Zm-.679-3.584c-.159.419-.329.834-.5,1.25l-3.259.335c-.039-.051-.079-.1-.122-.15q-.577-.654-1.159-1.3l.011-.012-.469-.468.907-2.732a1.273,1.273,0,0,1,.189-.037c.493-.05.987-.107,1.482-.136a7.207,7.207,0,0,1,.915,0L11.957,4.1C11.879,4.341,11.8,4.586,11.714,4.825Zm.753-2.292a6.993,6.993,0,0,1,1.438,2.082,7.13,7.13,0,0,0-1.34-.72c-.073-.03-.149-.057-.225-.083l-2.21-2.491A2.922,2.922,0,0,0,10.157,1,6.991,6.991,0,0,1,12.467,2.533ZM5.251.867c.009.006.013.012.018.013a5.617,5.617,0,0,1,1.4.736L5.819,4.169l-.013,0c-.173.072-.35.136-.521.214a14.945,14.945,0,0,0-2.036,1.1L1.576,4.5a.506.506,0,0,1,.029-.153c.112-.3.223-.6.35-.894a6.7,6.7,0,0,1,.331-.657c.08-.088.162-.175.247-.26A6.983,6.983,0,0,1,5.266.841Z" transform="translate(0 0)"></path>
                            </svg>
                        </div>
                        <a className="player-name"><div>Davies J.</div></a>
                        <div className="assist">(<a className="">Mundle R.</a>)</div>
                    </div>
                </div>
            </div>
        );
    }
}