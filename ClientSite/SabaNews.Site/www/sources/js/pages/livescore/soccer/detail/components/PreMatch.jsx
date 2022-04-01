import React, { Component } from 'react';
import Site from '@jsroot/common/site';
import moment from 'moment';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
export class LiveMatch extends Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        if (!this.props.soccerMatch) {
            return null;
        }
        const soccerMatch = this.props.soccerMatch;
        const startTime = moment(soccerMatch.generalInfo.startTime).format("HH:mm");
        return (
            <>
                <div className="duel-participant">
                    <div className="duel-participant-start-time">{soccerMatch.generalInfo.status === 0 ? startTime : Site.MappingStatusMatchText(soccerMatch.generalInfo.status)}</div>
                    <div className="duel-participant-home">
                        <div className="participant-image">
                            <a>
                                <img width="58" height="58" alt={soccerMatch.generalInfo.homeTeamNameEN} loading="lazy"
                                    src={soccerMatch.generalInfo.homeTeamIcon} />
                            </a>
                        </div>
                        <div className="participant-name-wrapper">
                            <div className="participant-name">{soccerMatch.generalInfo.homeTeamNameEN}</div>
                        </div>
                    </div>
                    <div className="duel-participant-score">
                        <div className="detail-score-match-info live">
                            <div className="detail-score-wrapper live">
                                <span>VS</span>
                            </div>
                        </div>
                    </div>
                    <div className="duel-participant-away">
                        <div className="participant-image">
                            <a>
                                <img width="58" height="58" alt={soccerMatch.generalInfo.awayTeamNameEN} loading="lazy"
                                    src={soccerMatch.generalInfo.awayTeamIcon} />
                            </a>
                        </div>
                        <div className="participant-name-wrapper">
                            <div className="participant-name">{soccerMatch.generalInfo.awayTeamNameEN}</div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { soccerMatch } = state;
    return {
        soccerMatch
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(LiveMatch);