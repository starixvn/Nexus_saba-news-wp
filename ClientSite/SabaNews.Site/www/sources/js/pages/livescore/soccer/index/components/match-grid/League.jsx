import React, { PureComponent } from 'react';
import Match from './Match';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../../actions';
import { Width1024 } from '@jsroot/common/constants';

export class League extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            isShowCountrySeparate: window.innerWidth <= Width1024 ? true : false
        };
    }

    onUpdateFavLeague = () => {
        this.props.setFavLeagues(CommonFunctions.getFavLeagues());
    };

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isShowCountrySeparate = this.state.isShowCountrySeparate;
        if (!isShowCountrySeparate && window.innerWidth <= Width1024) {
            this.setState({ isShowCountrySeparate: true });
        } else {
            if (isShowCountrySeparate && window.innerWidth > Width1024) {
                this.setState({ isShowCountrySeparate: false });
            }
        }
    };

    handleClick = event => {
        const { league, selectedDate } = this.props;
        const className = event.target.className;
        if(className.includes('icon-fav-league')) {
            return;
        }
        const seasonId = league.season ? league.season.seasonId : 0;
        const leagueNameParam = Site.toSlug(CommonFunctions.getLeagueName(league));
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueNameParam}/${seasonId}/${selectedDate}/${league.leagueId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };
    render() {
        const { league, searchKey, flagToggleFavLeagueIcon } =  this.props;
        const { isShowCountrySeparate } = this.state;
        const separate = isShowCountrySeparate ? '' : ': ';
        const leagueName = CommonFunctions.getLeagueNameWithStage(league);
        const countryName = league.countryVN ? league.countryVN + separate : '';
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const displayValue = searchKey.length > 0 ? leagueName.replace(reg, "<span class='text-matched'>$1</span>") : leagueName;
        return (
            <div className="soccer-match-grid-item">
                <div className="soccer-match-grid-item-header" onClick={this.handleClick}>
                    <div className="soccer-match-grid-item-league">
                        <FavouriteLeagueIcon league = {league} onUpdateFavLeague={this.onUpdateFavLeague} flagToggle={flagToggleFavLeagueIcon}/>
                        <div className="livescore-logo-team"><TeamIcon src={league.countryIconVN} /></div>
                        <div className="livescore-name-team">
                            <span className="country-name">{countryName}</span>
                            <span className="round-name" dangerouslySetInnerHTML={{ __html: displayValue }}></span>
                        </div>
                    </div>
                    <span className="icon-livescore icon-live-right" />
                </div>
                <div className="soccer-match-grid-item-body">
                    {
                        league.matches.map(match =>
                            <Match key={match.matchId} searchKey={searchKey} match={match} />
                        )
                    }
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { selectedDate, searchKey, flagToggleFavLeagueIcon } = state;
    return { selectedDate, searchKey, flagToggleFavLeagueIcon };
};

export default connect(mapStateToProps, mapDispatchToProps)(League);