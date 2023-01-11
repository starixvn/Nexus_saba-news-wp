import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Grid from './Grid';
import SkeletonGrid from './SkeletonGrid';
import SignalR from './SignalR';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';
import {  Width1024 } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isMobile: window.innerWidth <= Width1024 ? true : false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
        this.props.getLeagues();
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= Width1024) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > Width1024) {
                this.setState({ isMobile: false });
            }
        }
    };

    checkIsFinished = league => {
        const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD HH:mm:ss');
        const endTime = moment(DateTimeService.convertFromUTC(league.season.endTime)).format('YYYY-MM-DD HH:mm:ss');
        return moment(today).isSameOrAfter(endTime);
    };

    handleClickLeague = (event, league) => {
        const leagueName = Site.toSlug(CommonFunctions.getLeagueName(league));
        const today = DateTimeService.formatDateTime(DateTimeService.getCurrentClientDateTime(), 'YYYY-MM-DD');
        const seasonId = league.season ? league.season.seasonId : 0;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${seasonId}/${today}/${league.leagueId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    handleClickFixture = event => {
        const url = 'lich-thi-dau/bong-da';
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    getMatches = () => {
        const { matches } = this.props;
        if (!matches || matches.length === 0) {
            return {matches: null};
        }
        const tempMatches = matches;
        const isFinished = false;
        return {matches: tempMatches, isFinished: isFinished};
    };

    render() {
        const { leagues } = this.props;
        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        {
                            leagues === null && <SkeletonGrid />
                        }
                        {
                            leagues !== null &&
                            leagues.map((item, key) => {
                                return <Grid key={key} league={item.league} matches={item.matches} />
                            })
                        }
                    </div>
                </div>
            </div>
        );
    }

}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { leagues, league } = state;
    return { leagues, league };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);