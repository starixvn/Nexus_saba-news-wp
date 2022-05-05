import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SignalR from './SignalR';
import Details from './Details';
import { ActionCreators } from '../actions';
import MatchGeneralInfo from './MatchGeneralInfo';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';

export class AppContainer extends Component {
    componentDidMount() {
        this.props.getMatchDetail();
        this.props.getMajorLeagues();
    }

    render() {
        if (!this.props.match || !this.props.match.generalInfo) {
            return null;
        }
        return (
            <>
                <div className="container livescore-container">
                    <SignalR />
                    <div className="livescore-soccer">
                        <div className="livescore-soccer-main">
                            <MatchGeneralInfo match={this.props.match} />
                            <Details />
                        </div>
                        <div className="livescore-soccer-extension">
                            <FavoriteMatches />
                            <FavoriteLeagues leagues={this.props.majorLeagues} />
                        </div>
                    </div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, majorLeagues } = state;
    return { match, majorLeagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);