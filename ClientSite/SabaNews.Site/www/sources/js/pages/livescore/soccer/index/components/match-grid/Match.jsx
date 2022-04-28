import React, { PureComponent, Fragment } from 'react';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import MatchStage from '@jsroot/components/MatchStage';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import Site from '@jsroot/common/site';
import Tooltip from 'react-bootstrap/Tooltip';
import LangHelper from '@jsroot/common/langHelper';
import { toast } from 'react-toastify';
toast.configure();

export default class Match extends PureComponent {
    constructor(props) {
        super(props);

        const match = this.props.match;
        const matchIds = CommonFunctions.getFavouriteMatches();
        const matchIndex = matchIds.findIndex(item => item === match.matchId);
        this.state = {
            isFavourite: matchIndex >= 0,
            isShowTooltip: true
        };
    }

    getTeamStatus = (match, isHomeTeam) => {
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        switch (match.status) {
            case MatchStatus.Finished:
                if (match[`${homeFieldName}PenaltyScore`]
                    && match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                    return <li>PEN</li>;
                } else if (match[`${homeFieldName}AggregateScore`]
                    && match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                    return <li>AGG</li>;
                } else {
                    return <Fragment />;
                }
                break;

            default:
                return <Fragment />;
                break;
        }
    };

    getTeamScore = (match, isHomeTeam) => {
        const fieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postponed:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return <span>&nbsp; - &nbsp;</span>;
                break;

            default:
                const totalScore = match[`${fieldName}ExtraScore`] ? match[`${fieldName}ExtraScore`] : match[`${fieldName}TotalScore`];
                const halfScore = match[`${fieldName}HalfScore`];
                return <div>
                    <span className="total-score">{totalScore}</span> <span className="half-score">({halfScore})</span></div>;
                break;
        }
    };

    getTeamName = (team, searchKey) => {
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const teamName = CommonFunctions.getTeamName(team);
        return searchKey.length > 0 ? teamName.replace(reg, "<span class='text-matched'>$1</span>") : teamName;
    };

    handleClick = event => {
        const className = event.target.className;
        if(className.includes('icon-livescore')) {
            return;
        }

        const matchId = this.props.match.matchId;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${matchId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    handleUpdateFavourite = () => {
        const matchId = this.props.match.matchId;
        const isFavourite = CommonFunctions.updateFavouriteMatch(matchId);
        let message = "";
        if(isFavourite) {
            const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/tran-dau-quan-tam/`);
            message = <div className="fav-match-add"><span>Thêm vào Trận đấu quan tâm</span> | <a href={url}>hiển thị</a></div>;
        } else {
            message = <div className="fav-match-remove"><span>Xóa khỏi Trận đấu quan tâm</span></div>;
        }
        const toastId = toast.dark(message, {
            toastId: 'fav',
            position: toast.POSITION.BOTTOM_CENTER,
            hideProgressBar: true,
            autoClose: 3000
        });
        toast.update(toastId, { render: () => message, autoClose: 3000 });
        this.setState({ isFavourite: isFavourite, isShowTooltip: false});
    }

    renderTooltip = () => {
        const { isFavourite } = this.state;
        return (
            <Tooltip className="tooltip-add-favourite-match" >
            { isFavourite ? LangHelper.getResource('RemoveFavouriteMatchTooltip') :  LangHelper.getResource('AddFavouriteMatchTooltip')}
            </Tooltip>
        )
    };

    onMouseOut = () => {
        const isShowTooltip = this.state.isShowTooltip;
        if(!isShowTooltip) {
            this.setState({isShowTooltip: true});
        }
    }

    render() {
        const { match, searchKey } = this.props;
        const isLiveMatch = match.status === MatchStatus.FirstHalf
            || match.status === MatchStatus.SecondHalf
            || match.status === MatchStatus.Intermission
            || match.status === MatchStatus.ExtraTime
            || match.status === MatchStatus.PenaltyShootout;
        const isIntermission = match.status === MatchStatus.Intermission;
        const { isFavourite, isShowTooltip } = this.state;

        return (
            <div className="group-match-item">
                <div className={`soccer-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                    onClick={this.handleClick}
                >
                    {
                        isShowTooltip ?
                        <OverlayTrigger
                            placement="bottom"
                            trigger={['hover', 'focus']}
                            overlay={this.renderTooltip()}
                        >
                            <i onClick={this.handleUpdateFavourite} onMouseOut={this.onMouseOut} className={`icon-livescore ${isFavourite ? "icon-live-start start-active" : "icon-favourite-outline"}`} />
                        </OverlayTrigger> :
                        <i onClick={this.handleUpdateFavourite} onMouseOut={this.onMouseOut} className={`icon-livescore ${isFavourite ? "icon-live-start start-active" : "icon-favourite-outline"}`} />
                    }
                    <MatchStage match={match} />
                    <div className="soccer-match-team">
                        <div className="group-team">
                            <div className="soccer-team home-team">
                                <TeamIcon className="soccer-team-logo" src={match.homeTeam.icon} />
                                <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: this.getTeamName(match.homeTeam, searchKey) }} />
                                <ul className="soccer-team-status">
                                    {this.getTeamStatus(match, true)}
                                </ul>
                                <div className="soccer-team-result">
                                    {this.getTeamScore(match, true)}
                                </div>
                            </div>
                            <div className="soccer-team away-team">
                                <TeamIcon className="soccer-team-logo" src={match.awayTeam.icon} />
                                <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: this.getTeamName(match.awayTeam, searchKey) }} />
                                <ul className="soccer-team-status">
                                    {this.getTeamStatus(match, false)}
                                </ul>
                                <div className="soccer-team-result">
                                    {this.getTeamScore(match, false)}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="soccer-match-star-icon">
                        <i className="icon-livescore icon-live-right" />
                    </div>
                </div>
            </div>
        );
    }
}