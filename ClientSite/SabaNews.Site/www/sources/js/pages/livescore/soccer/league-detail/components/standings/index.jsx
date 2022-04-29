import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../../actions';
import Grid from './Grid';
import Promotion from './Promotion';
import CommonFunctions from '@jsroot/common/common-functions';

export class SeasonStandings extends PureComponent {
    constructor(props) {
        super(props);

        this.MobileMaxWidth = 580;

        this.state = {
            isMobile: window.innerWidth <= this.MobileMaxWidth ? true : false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);

        this.props.getSeasonStandings({
            leagueId: window.getMatchByStageParams.leagueId,
            seasonId: window.getMatchByStageParams.seasonId
        });
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= this.MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > this.MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
    }

    groupStandingsByGroupName = seasonStandings => {
        const totalItems = seasonStandings.length;
        const standings = [];
        for (let i = 0; i < totalItems; i++) {
            let temp = seasonStandings[i];
            const key = temp.groupName;
            const item = {
                key,
                childs: [temp]
            };
            if (standings.length === 0) {
                standings.push(item);
            } else {
                const index = standings.findIndex(x => x.key === key);
                if (index === - 1) {
                    standings.push(item);
                } else {
                    standings[index].childs.push(temp);
                }
            }
        }

        return standings;
    };

    groupStandingsByStage = seasonStandings => {
        let totalItems = seasonStandings.length;
        const standings = [];
        for (let i = 0; i < totalItems; i++) {
            let temp = { ...seasonStandings[i] };
            temp.promotion = JSON.parse(temp.promotion);
            temp.promotion = temp.promotion && temp.promotion.Color ? temp.promotion : null;
            const item = {
                stageId: temp.stageId,
                stageNameEN: temp.stageNameEN,
                stageNameVN: temp.stageNameVN,
                orderIndex: temp.orderIndex,
                childs: [temp]
            };
            if (standings.length === 0) {
                standings.push(item);
            } else {
                const index = standings.findIndex(x => x.stageId === temp.stageId);
                if (index === - 1) {
                    standings.push(item);
                } else {
                    standings[index].childs.push(temp);
                }
            }
        }

        totalItems = standings.length;
        for (let i = 0; i < totalItems; i++) {
            standings[i].childs = this.groupStandingsByGroupName(standings[i].childs);
        }

        return standings;
    };

    getPromotionStandings = seasonStandings => {
        const totalItems = seasonStandings.length;
        const promotions = [];
        for (let i = 0; i < totalItems; i++) {
            const promotion = JSON.parse(seasonStandings[i].promotion);
            if (promotion && promotion.Color && promotions.findIndex(item => item.Id === promotion.Id) === -1) {
                promotions.push(promotion);
            }
        }

        return promotions;
    };

    getGroupName = groupName => `Bảng ${groupName.charAt(0)}`;

    render() {
        const seasonStandings = this.groupStandingsByStage(this.props.seasonStandings);
        const promotions = this.getPromotionStandings(this.props.seasonStandings);
        const totalItems = seasonStandings.length;
        const isMobile = this.state.isMobile;

        return (
            <div className="season-standings">
                {
                    seasonStandings.map(group => {
                        return <div key={group.stageId}>
                            {totalItems > 1 && <div className="group-stage">{CommonFunctions.getStageName(group)}</div>}
                            <Grid standings={group.childs} isMobile={isMobile} />
                        </div>;
                    })
                }
                {
                    promotions.length > 0 && <Promotion promotions={promotions} />
                }
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { seasonStandings } = state;
    return { seasonStandings };
};

export default connect(mapStateToProps, mapDispatchToProps)(SeasonStandings);