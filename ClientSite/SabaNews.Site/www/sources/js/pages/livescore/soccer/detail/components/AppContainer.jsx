import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SignalR from './SignalR';
import Details from './Details';
import { ActionCreators } from '../actions';
import MatchGeneralInfo from './MatchGeneralInfo';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import CommonFunctions from '@jsroot/common/common-functions';

export class AppContainer extends Component {
    componentDidMount() {
        this.props.getMatchDetail();
        this.props.getMajorLeagues();
    }

    render() {
        const { match, majorLeagues } = this.props;
        if (!match || !match.generalInfo) {
            return null;
        }

        const homeTeamName = CommonFunctions.getTeamName(match.generalInfo.homeTeam);
        const awayTeamName = CommonFunctions.getTeamName(match.generalInfo.awayTeam);
        document.title = `${homeTeamName} vs ${awayTeamName}`;

        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <MatchGeneralInfo match={match} />
                        <Details />
                    </div>
                    <div className="livescore-soccer-extension">
                        <FavoriteMatches />
                        <FavoriteLeagues leagues={majorLeagues} />
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, majorLeagues } = state;
    return { match, majorLeagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);